<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT'] . '/');
}
include ROOT_PATH . "assets/db/db_connect.php";
include "calendar/projectAddModalCalendar.php";
global $conn;

// Eğer session_start() başka bir yerde başlatıldıysa $_SESSION'a erişebilmeliyiz
$user_id = $_SESSION['userID'];

$today = new DateTime();
$current_month = intval($today->format('m'));
$current_year = intval($today->format('Y'));
$today_date = $today->format('Y-m-d');

$month = isset($_GET['month']) ? intval($_GET['month']) : $current_month;
$year = isset($_GET['year']) ? intval($_GET['year']) : $current_year;

// Türkçe ay isimlerini döndüren fonksiyon
function getTurkishMonth($month) {
    $months = [
        1 => 'Ocak', 2 => 'Şubat', 3 => 'Mart', 4 => 'Nisan',
        5 => 'Mayıs', 6 => 'Haziran', 7 => 'Temmuz', 8 => 'Ağustos',
        9 => 'Eylül', 10 => 'Ekim', 11 => 'Kasım', 12 => 'Aralık'
    ];
    return $months[$month];
}

// Ayın günlerini döndüren fonksiyon
function getMonth($month, $year) {
    $num_days = date('t', strtotime($year . '-' . $month . '-01'));  // Bu ayın gün sayısı
    $num_days_last_month = date('j', strtotime('last day of previous month', strtotime($year . '-' . $month . '-01'))); // Önceki ayın gün sayısı
    $days = array();
    $first_day_of_week = date('N', strtotime($year . '-' . $month . '-01')); // Haftanın ilk günü (1 = Pazartesi)

    // Önceki ayın son günlerini ekle
    for ($i = $first_day_of_week - 1; $i > 0; $i--) {
        $days[] = array('day' => $num_days_last_month - $i + 1, 'month' => $month - 1, 'year' => $year, 'current' => false);
    }

    // Bu ayın günlerini ekle
    for ($i = 1; $i <= $num_days; $i++) {
        $days[] = array('day' => $i, 'month' => $month, 'year' => $year, 'current' => true);
    }

    // Sonraki ayın ilk günlerini ekle
    $days_left = 42 - count($days); // Toplam 42 hücre için kalan günler (6 satır x 7 sütun)
    for ($i = 1; $i <= $days_left; $i++) {
        $days[] = array('day' => $i, 'month' => $month + 1, 'year' => $year, 'current' => false);
    }

    return $days;
}

// Event Sorgusu
$sql_events = "SELECT e.id, e.title, e.dateStart, e.dateEnd, e.dateType, e.status
               FROM events e 
               JOIN events_assignees ea ON e.id = ea.event_id 
               WHERE ea.user_id = ? AND e.status=0 ORDER BY e.dateStart ASC";

$sql_projects = "SELECT p.id, p.title, p.dateDeadline, p.client, p.status
                 FROM projects p 
                 JOIN projects_assignees pa ON p.id = pa.project_id 
                 WHERE pa.user_id = ? AND p.active = '1' AND p.status <> 'Tamamlandı'";

// Sorguları hazırlama
$stmt_events = $conn->prepare($sql_events);
$stmt_projects = $conn->prepare($sql_projects);

if (!$stmt_events || !$stmt_projects) {
    die("Sorgu hatası: " . $conn->error);
}

$stmt_events->bind_param('i', $user_id);
$stmt_projects->bind_param('i', $user_id);

// Sorguları çalıştırma ve sonuçları çekme
$stmt_events->execute();
$result_events = $stmt_events->get_result();
$stmt_projects->execute();
$result_projects = $stmt_projects->get_result();

// Event ve Projelerin sonuçlarını işleme
$calendar_days = getMonth($month, $year);
$events = [];

// Event verilerinin işlenmesi
if ($result_events->num_rows > 0) {
    while ($row = $result_events->fetch_assoc()) {
        $start_date = date('Y-m-d H:i:s', strtotime($row['dateStart'])); // Saat bilgisiyle birlikte
        $end_date = date('Y-m-d H:i:s', strtotime($row['dateEnd']));
        $current_date = $start_date;
        while (strtotime($current_date) <= strtotime($end_date)) {
            $events[] = [
                'id' => $row['id'],
                'title' => $row['title'],
                'type' => 'event',
                'date' => $current_date // Tarih ve saat bilgisi
            ];
            $current_date = date('Y-m-d H:i:s', strtotime($current_date . ' +1 day'));
        }
    }
}

// Project verilerinin işlenmesi
if ($result_projects->num_rows > 0) {
    while ($row = $result_projects->fetch_assoc()) {
        $deadline_date = date('Y-m-d H:i:s', strtotime($row['dateDeadline'])); // Saat bilgisiyle birlikte
        $events[] = [
            'id' => $row['id'],
            'title' => $row['title'],
            'client' => $row['client'],
            'type' => 'project',
            'date' => $deadline_date // Tarih ve saat bilgisi
        ];
    }
}

// $events dizisini tarih ve saate göre sıralama
usort($events, function ($a, $b) {
    return strtotime($a['date']) - strtotime($b['date']);
});

?>

<!-- HTML Kodu Başlıyor -->
<div class="animate__animated p-6" :class="[$store.app.animation]">
    <div class="panel">
        <div style="display:flex;gap:15px;flex-wrap: nowrap;align-items: baseline;justify-content: space-between;">
            <a href="javascript:void(0);" id="prevMonth">Önceki Ay</a>
            <h1 id="calendarTitle" class="text-lg font-semibold mb-6" style="text-align: center;">
                <?php echo getTurkishMonth($month) . ' ' . $year; ?>
            </h1>
            <a href="javascript:void(0);" id="nextMonth">Sonraki Ay</a>
        </div>
        <div class="mb-6 grid gap-6 sm:grid-cols-1 lg:grid-cols-6">
            <div style="display:flex;gap:15px;">
                <!-- Müşteri Filtre -->
                <select id="filterClient" class="form-select">
                    <option value="">Müşteri: Tümü</option>
                    <?php
                    $sql_clients = "SELECT id, username FROM users_client ORDER BY username ASC";
                    $result_clients = $conn->query($sql_clients);
                    while ($row = $result_clients->fetch_assoc()) {
                        echo "<option value='{$row['id']}'>{$row['username']}</option>";
                    }
                    ?>
                </select>
                
                <!-- Personel Filtre -->
                <select id="filterUser" class="form-select">
                    <option value="">Personel: Tümü</option>
                    <?php
                    $sql_users = "SELECT id, name, surname, userType FROM ac_users WHERE userType IN (1, 2) AND status <> 0 ORDER BY userType ASC, name ASC";
                    $result_users = $conn->query($sql_users);
                    while ($row = $result_users->fetch_assoc()) {
                        if($user_id ==$row['id']){
                            echo "<option selected value='{$row['id']}'>{$row['name']} {$row['surname']}</option>";
                        }else{
                            echo "<option value='{$row['id']}'>{$row['name']} {$row['surname']}</option>";
                        }
                    }
                    ?>
                </select>

                <!-- Projeler Filtre -->
                <select id="filterProjects" class="form-select">
                    <option value="Hepsi">Tüm Projeler</option>
                    <option value="Hiçbiri">Hiçbiri</option>
                    <option selected value="Tamamlanmadı">Tamamlanmadı</option>
                    <option value="Proje">Proje</option>
                    <option value="Beklemede">Beklemede</option>
                    <option value="Devam Eden">Devam Eden</option>
                    <option value="Onayda">Onayda</option>
                    <option value="Onaylandı">Onaylandı</option>
                    <option value="Tamamlandı">Tamamlandı</option>
                </select>
                
                <!-- Etkinlikler Filtre -->
                <select id="filterEvents" class="form-select">
                    <option value="Hepsi">Tüm Etkinlikler</option>
                    <option value="Hiçbiri">Hiçbiri</option>
                    <option selected value="0">Tamamlanmadı</option>
                    <option value="1">Tamamlandı</option>
                </select>
                <button class="btn btn-primary" id="filterButton">Filtrele</button>
            </div>
        </div>

        <table id="calendarTable">
            <!-- Filtrelenmiş sonuçlar burada gösterilecek -->
            <?php
            // Takvim için gün ve event/proje gösterimi burada
$day_counter = 0;
foreach ($calendar_days as $day) {
    if ($day_counter % 7 == 0) {
        echo $day_counter > 0 ? '</tr>' : '';
        echo '<tr>';
    }

    $date = $day['year'] . '-' . str_pad($day['month'], 2, '0', STR_PAD_LEFT) . '-' . str_pad($day['day'], 2, '0', STR_PAD_LEFT);
    $class = $day['current'] ? '' : 'other-month';

    if ($date === $today_date) {
        $class .= ' today';
    }

    echo '<td class="' . $class . '">';
    echo $day['day'];
    
    // Hover'da görünen + butonu
    echo '<i class="fa-solid fa-circle-plus add-event-icon" onclick="openSelectionModal(\'' . $date . '\')"></i>';

    // O gün için event veya projeleri listeliyoruz
    if (isset($events[$date])) {
        foreach ($events[$date] as $event) {
            if ($event['type'] == 'event') {
                echo '<div class="event" onclick="openModal(\'event\', ' . $event['id'] . ')">' . $event['title'] . '</div>';
            } else {
                echo '<div class="project" onclick="openProjectModal(' . $event['id'] . ', \'' . htmlspecialchars($event['title'], ENT_QUOTES) . '\')">';
                echo '<i class="fa-solid fa-circle-dot"></i> ' . $event['deadline'] . ' ' . $event['client'] . ' ' . $event['title'] . '</div>';
            }
        }
    }

    echo '</td>';

    $day_counter++;
}

echo '</tr>';
            ?>
        </table>
        <!-- PHP'den gelen month ve year değişkenlerini JS içinde kullanmak için hidden input -->
        <input type="hidden" id="currentMonth" value="<?php echo $month; ?>">
        <input type="hidden" id="currentYear" value="<?php echo $year; ?>">
    </div>
</div>

<!-- Seçim Modalı -->
<div id="addSelectionModal" class="modal hidden">
    <div class="modal-content">
        <div class="mb-4">
            <span id="addSelectionModalClose" class="close">&times;</span>
            <h2>Seçim Yap</h2>
        </div>
        <div class="panel grid grid-rows gap-4 px-6 pt-6">
            <button id="openProjectModalButton" class="btn btn-primary w-full gap-2 mt-2">Proje Ekle</button>
            <button id="openEventModalButton" class="btn btn-success w-full gap-2 mt-2">Etkinlik Ekle</button>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="modal hidden">
    <div class="modal-content">
        <div class="mb-4">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2 id="modalTitle">Düzenle</h2>
        </div>
        <form id="editForm">
            <div class="panel grid grid-rows gap-4 px-6 pt-6">
                <input type="hidden" id="editId" name="id">
                <input type="hidden" id="editType" name="type">
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p>Başlık</p>
                        <input type="text" id="editTitle" name="title" required class="form-input">
                    </div>
                      
                    <div>
                        <p>Tür</p>
                        <select type="select" id="editType" name="type" required class="form-input">
                            <option value="event">Etkinlik</option>
                            <option value="visit">Ziyaret</option>
                            <option value="special_day">Özel Gün</option>
                        </select>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 gap-4" id="dateInputs">
                    <div>
                        <div class="mb-6 grid grid-cols-3 gap-6">
                            <div>
                                <p id="dateLabel">Tarih</p>
                                <input type="date" id="editDateStart" name="date" class="form-input rounded-none rounded-tl-md rounded-bl-md">
                            </div>
                            <div id="startDateInputs">
                                <p>Başlangıç</p>
                                <input type="time" id="editTimeStart" name="timeStart" class="form-input rounded-none rounded-tr-md rounded-br-md">
                            </div>
                            <div>
                                <p>Bitiş</p>
                                <input type="time" id="editTimeEnd" name="timeEnd" class="form-input rounded-none rounded-tr-md rounded-br-md">
                            </div>
                        </div>
                    </div>
                    
                    <div id="allDayCheckbox">
                        <label>
                            <input type="checkbox" id="editAllDay" name="allDay"> Tüm Gün
                        </label>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-success w-full gap-2 mt-5">
                Kaydet
            </button>
            <button type="button" id="completeEventButton" class="btn btn-primary w-full gap-2 mt-2">
                Tamamla
            </button>
            <button type="button" id="deleteButton" class="btn btn-danger w-full gap-2 mt-2">
                Sil
            </button>
        </form>
    </div>
</div>

<!-- Edit Project Modal -->
<div id="editProjectModal" class="modal hidden">
    <div class="modal-content">
        <div class="mb-4">
            <span class="close" onclick="closeEditProjectModal()">&times;</span>
            <h2>Proje Düzenle</h2>
        </div>
        <form id="editProjectForm">
            <input type="hidden" id="editProjectId" name="id">
            <div class="panel grid grid-rows gap-4 px-6 pt-6">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-2 xl:grid-cols-2">
                    <!-- Proje Başlığı -->
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <p>Proje Başlığı</p>
                            <input name="title" id="editProjectTitle" required type="text" placeholder="Proje Başlığını Giriniz" class="form-input" />
                        </div>
                    </div>

                    <!-- Proje Türü -->
                    <div>
                        <p>Proje Türü</p>
                        <select name="projectType" id="editProjectType" required class="form-select text-white-dark">
                            <option value="">Proje Türü Seçiniz</option>
                            <!-- Proje türleri dinamik olarak eklenecek -->
                        </select>
                    </div>
                </div>

                <!-- Teslim Tarihi -->
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <p>Teslim Tarihi</p>
                        <div class="flex">
                            <input type="date" id="editDeadlineDate" name="deadlineDate" class="form-input rounded-none rounded-tl-md rounded-bl-md" />
                            <input type="time" id="editDeadlineTime" name="deadlineTime" class="form-input rounded-none rounded-tr-md rounded-br-md" value="18:30" />
                        </div>
                    </div>
                </div>

                <!-- Müşteri Seçimi -->
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <p>Müşteri</p>
                        <select name="client" id="editProjectClient" required class="form-select text-white-dark">
                            <option value="">Müşteri Seçiniz</option>
                            <!-- Müşteriler dinamik olarak eklenecek -->
                        </select>
                    </div>
                </div>

                <!-- Durum Seçimi -->
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <p>Proje Durumu</p>
                        <select name="status" id="editProjectStatus" required class="form-select text-white-dark">
                            <option value="Proje">Proje</option>
                            <option value="Beklemede">Beklemede</option>
                            <option value="Devam Eden">Devam Eden</option>
                            <option value="Onayda">Onayda</option>
                            <option value="Onaylandı">Onaylandı</option>
                            <option value="Tamamlandı">Tamamlandı</option>
                        </select>
                    </div>
                </div>

                <!-- Görevliler -->
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <p>Görevliler</p>
                        <input name="employee" id="editEmployee" type="text" class="form-input text-white-dark" placeholder="Lütfen Sistemde Kayıtlı Tam Adı Giriniz"/>
                        <input type="hidden" id="editEmployeeId" name="employeeId"> <!-- Seçilen görevlinin ID'si -->
                    </div>
                    <div class="profile-container" id="editProfileContainer">
                        <!-- Eklenen görevli kişilerin profil fotoğrafları burada listelenecek -->
                    </div>
                </div>

                <!-- Açıklama -->
                <div class="grid grid-cols-1 gap-4">
                    <p>Açıklama</p>
                    <textarea name="description" id="editProjectDescription" type="text" placeholder="Proje hakkında açıklama giriniz" class="form-input" style="min-height:75px;"></textarea>
                </div>
            </div>

            <button type="submit" class="btn btn-success w-full gap-2 mt-5">
                Kaydet
            </button>
            <button type="button" id="deleteProjectButton" class="btn btn-danger w-full gap-2 mt-5">
                Projeyi Sil
            </button>
            <button type="button" id="completeButton" class="btn btn-primary w-full gap-2 mt-2">
                Tamamla
            </button>
        </form>
    </div>
</div>

<!-- Create Event Modal -->
<div id="createEventModal" class="modal hidden">
    <div class="modal-content">
        <div class="mb-4">
            <span class="close" onclick="closeCreateModal()">&times;</span>
            <h2>Etkinlik Oluştur</h2>
        </div>
        <form id="createEventForm">
            <div class="panel grid grid-rows gap-4 px-6 pt-6">
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <p>Başlık</p>
                        <input type="text" id="createTitle" name="title" required class="form-input">
                    </div>
                </div>
                
                <div class="grid grid-cols-1 gap-4">
                    
                    <div class="mb-6 grid grid-cols-3 gap-6">
                        <div>
                            <p id="dateLabel">Tarih</p>
                            <input type="date" id="createDateStart" name="date" class="form-input rounded-none rounded-tl-md rounded-bl-md">
                        </div>
                        <div id="startDateInputs">
                            <p>Başlangıç</p>
                            <input type="time" id="createTimeStart" name="timeStart" class="form-input rounded-none rounded-tr-md rounded-br-md">
                        </div>
                        <div>
                            <p>Bitiş</p>
                            <input type="time" id="createTimeEnd" name="timeEnd" class="form-input rounded-none rounded-tr-md rounded-br-md">
                        </div>
                    </div>
                    
                    <div>
                        <label>
                            <input type="checkbox" id="createAllDay" name="allDay"> Tüm Gün
                        </label>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-success w-full gap-2 mt-5">
                Oluştur
            </button>
        </form>
    </div>
</div>

<!-- Proje Modal -->
<div id="projectModal" class="modal hidden">
    <div class="modal-content">
        <div class="mb-4">
            <span class="close" onclick="closeProjectModal()">&times;</span>
            <h2 id="projectModalTitle"></h2>
        </div>
        <div class="flex justify-between mt-4">
            <button style="width:30%" onclick="openEditProjectModal()" class="btn btn-primary">Düzenle</button>
            <button style="width:30%" onclick="viewProject()" class="btn btn-info">Görüntüle</button>
            <button style="width:30%" onclick="viewProjectNewTab()" class="btn btn-info">Yeni Sekmede Aç</button>
        </div>
    </div>
</div>

<script src="/pages/manager/calendar/calendar.js"></script>
<link rel="stylesheet" href="/pages/manager/calendar/calendar.css">