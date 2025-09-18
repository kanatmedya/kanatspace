<?php
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT'] . '/');
}
include ROOT_PATH . "assets/db/db_connect.php";
global $conn;

// Bugünün tarihini al
$today = new DateTime();
$current_month = intval($today->format('m'));
$current_year = intval($today->format('Y'));
$today_date = $today->format('Y-m-d');

$month = isset($_GET['month']) ? intval($_GET['month']) : $current_month;
$year = isset($_GET['year']) ? intval($_GET['year']) : $current_year;

// Türkçe ay isimleri
function getTurkishMonth($month) {
    $months = [
        1 => 'Ocak', 2 => 'Şubat', 3 => 'Mart', 4 => 'Nisan',
        5 => 'Mayıs', 6 => 'Haziran', 7 => 'Temmuz', 8 => 'Ağustos',
        9 => 'Eylül', 10 => 'Ekim', 11 => 'Kasım', 12 => 'Aralık'
    ];
    return $months[$month];
}

$user_id = $_SESSION['userID'];

// URL parametresinde yer alan status değerini al
$bind_events = false; // Parametre bağlamayı kontrol etmek için bir bayrak
$bind_projects = true; // Projects için parametre bağlama kontrolü

if (isset($_GET['Approved'])) {
    // Sadece onaylanmış projeler
    $sql_events = "SELECT e.id, e.title, e.dateStart, e.dateEnd, e.dateType 
                   FROM events e 
                   JOIN events_assignees ea ON e.id = ea.event_id 
                   WHERE e.status = 0"; // Hiç event getirme
    $sql_projects = "SELECT id, title, dateDeadline, client 
                     FROM projects 
                     WHERE status = 'Onaylandı' AND active = '1' 
                     ORDER BY dateDeadline";
    $bind_projects = false; // Bu durumda bind_param kullanılmamalı
} elseif (isset($_GET['All'])) {
    // Tümü durumu
    $sql_events = "SELECT e.id, e.title, e.dateStart, e.dateEnd, e.dateType 
                   FROM events e 
                   JOIN events_assignees ea ON e.id = ea.event_id 
                   WHERE ea.user_id = ? ORDER BY e.dateStart";
    $sql_projects = "SELECT p.id, p.title, p.dateDeadline, p.client
                     FROM projects p 
                     JOIN projects_assignees pa ON p.id = pa.project_id 
                     WHERE pa.user_id = ? AND p.active = '1' 
                     ORDER BY p.dateDeadline";
    $bind_events = true;
} else {
    // Varsayılan durum
    $sql_events = "SELECT e.id, e.title, e.dateStart, e.dateEnd, e.dateType 
                   FROM events e 
                   JOIN events_assignees ea ON e.id = ea.event_id 
                   WHERE ea.user_id = ? AND e.status=0 
                   ORDER BY e.dateStart";
    $sql_projects = "SELECT p.id, p.title, p.dateDeadline, p.client 
                     FROM projects p 
                     JOIN projects_assignees pa ON p.id = pa.project_id 
                     WHERE pa.user_id = ? AND p.status NOT IN ('Tamamlandı', 'Reddedildi', 'Onaylandı') 
                     AND p.active = '1' 
                     ORDER BY p.dateDeadline";
    $bind_events = true;
}

// Sorguları hazırla ve çalıştır
$stmt_events = $conn->prepare($sql_events);
if ($stmt_events === false) {
    die("Events sorgusunu hazırlarken hata oluştu: " . $conn->error);
}

$stmt_projects = $conn->prepare($sql_projects);
if ($stmt_projects === false) {
    die("Projects sorgusunu hazırlarken hata oluştu: " . $conn->error);
}

if ($bind_events) {
    if (!$stmt_events->bind_param("i", $user_id)) {
        die("Events parametrelerini bağlarken hata oluştu: " . $stmt_events->error);
    }
}

if ($bind_projects) {
    if (!$stmt_projects->bind_param("i", $user_id)) {
        die("Projects parametrelerini bağlarken hata oluştu: " . $stmt_projects->error);
    }
}

if (!$stmt_events->execute()) {
    die("Events sorgusunu çalıştırırken hata oluştu: " . $stmt_events->error);
}

$result_events = $stmt_events->get_result();

if (!$stmt_projects->execute()) {
    die("Projects sorgusunu çalıştırırken hata oluştu: " . $stmt_projects->error);
}
$result_projects = $stmt_projects->get_result();

$events = [];

if ($result_events->num_rows > 0) {
    while ($row = $result_events->fetch_assoc()) {
        $start_date = date('Y-m-d', strtotime($row['dateStart']));
        $end_date = date('Y-m-d', strtotime($row['dateEnd']));
        $start_time = date('H:i', strtotime($row['dateStart']));
        $end_time = date('H:i', strtotime($row['dateEnd']));

        $current_date = $start_date;
        while (strtotime($current_date) <= strtotime($end_date)) {
            if (!isset($events[$current_date])) {
                $events[$current_date] = [];
            }
            $events[$current_date][] = [
                'id' => $row['id'],
                'start_time' => $start_time,
                'end_time' => $end_time,
                'title' => $row['title'],
                'type' => 'event',
                'dateType' => $row['dateType']
            ];
            $current_date = date('Y-m-d', strtotime($current_date . ' +1 day'));
        }
    }
}

if ($result_projects->num_rows > 0) {
    while ($row = $result_projects->fetch_assoc()) {
        $date = date('Y-m-d', strtotime($row['dateDeadline']));
        if (!isset($events[$date])) {
            $events[$date] = [];
        }
        
        if (isset($_GET['Approved'])){
            $events[$date][] = [
                'id' => $row['id'],
                'title' => $row['title'],
                'client' => $row['client'],
                'type' => 'project',
                'deadline' => date('H:i', strtotime($row['dateDeadline']))
            ];
        }else{
            $events[$date][] = [
                'id' => $row['id'],
                'title' => $row['title'],
                'client' => $row['client'],
                'type' => 'project',
                'deadline' => date('H:i', strtotime($row['dateDeadline']))
            ];
        }
    }
}

// Tarih işlemleri için gerekli fonksiyonlar
function getMonth($month, $year) {
    $num_days = date('t', strtotime($year . '-' . $month . '-01'));
    $num_days_last_month = date('j', strtotime('last day of previous month', strtotime($year . '-' . $month . '-01')));
    $days = array();
    $first_day_of_week = date('N', strtotime($year . '-' . $month . '-01'));

    // Önceki ayın son günlerini ekle
    for ($i = $first_day_of_week - 1; $i > 0; $i--) {
        $days[] = array('day' => $num_days_last_month - $i + 1, 'month' => $month - 1, 'year' => $year, 'current' => false);
    }

    // Bu ayın günlerini ekle
    for ($i = 1; $i <= $num_days; $i++) {
        $days[] = array('day' => $i, 'month' => $month, 'year' => $year, 'current' => true);
    }

    // Sonraki ayın ilk günlerini ekle
    $days_left = 42 - count($days);
    for ($i = 1; $i <= $days_left; $i++) {
        $days[] = array('day' => $i, 'month' => $month + 1, 'year' => $year, 'current' => false);
    }

    return $days;
}

// Takvim verilerini oluştur
$calendar_days = getMonth($month, $year);

?>

<style>
    table { border-collapse: collapse; width: 100%; }
    td { border: 1px solid #ddd; padding: 5px; width: 14.28%; height: 100px; vertical-align: top; position: relative; }
    .event, .project { 
        margin-bottom: 2px; 
        padding: 2px; 
        font-size: 12px; 
        border-radius: 5px; 
        transition: background-color 0.3s;
        cursor: pointer;
    }
    .event { 
        background-color: #3788d8; 
        color: white;
    }
    .event:hover {
        background-color: #2a67a4;
    }
    .project { 
        background-color: transparent; 
    }
    .project:hover {
        background-color: #e5e5e5;
    }
    .other-month { background-color: #f0f0f0; }
    .event-time { font-weight: bold; }
    .project i {
        color: #3788d8;
        margin-right: 5px;
    }
    .today {
        background-color: #fffadf;
    }
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.4);
    }
    .modal-content {
        background-color: #fefefe;
        margin: 15% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
        max-width: 800px;
    }
    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }
    .add-event-icon {
        position: absolute;
        top: 5px;
        right: 5px;
        display: none;
        cursor: pointer;
    }
    td:hover .add-event-icon {
        display: block;
    }
</style>

<div class="animate__animated p-6" :class="[$store.app.animation]">
    <div class="panel">
        <div class="mb-6 grid gap-6 sm:grid-cols-1 lg:grid-cols-3">
            <div style="display:flex;gap:15px;">

            </div>
            <div>
                <h1 class="text-lg font-semibold" style="text-align: center;"><?php echo getTurkishMonth($month) . ' ' . $year; ?></h1>
            </div>
            <div style="display:flex;gap:15px;flex-direction: row-reverse;">
            
                <a href="./calendar?All" class="btn btn-primary gap-2">
                    Tümü
                </a>
                
                <a href="./calendar?Approved" class="btn btn-info gap-2">
                    Onaylandı
                </a>
            </div>
        </div>
        <table>
        <tr>
            <th>Pzt</th>
            <th>Sal</th>
            <th>Çar</th>
            <th>Per</th>
            <th>Cum</th>
            <th>Cmt</th>
            <th>Paz</th>
        </tr>
        <?php
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
            echo '<i class="fa-solid fa-circle-plus add-event-icon" onclick="openCreateModal(\'' . $date . '\')"></i>';

            if (isset($events[$date])) {
                foreach ($events[$date] as $event) {
                    if ($event['type'] == 'event') {
                        $time_info = '';
                        if ($event['dateType'] != 'allday') {
                            $time_info = '<span class="event-time">' . $event['start_time'] . ' - ' . $event['end_time'] . '</span> ';
                        }
                        echo '<div class="event" onclick="openModal(\'event\', ' . $event['id'] . ')">' . $time_info . $event['title'] . '</div>';
                    } else {
                        echo '<div class="project" onclick="openProjectModal(' . $event['id'] . ', \'' . htmlspecialchars($event['title'], ENT_QUOTES) . '\')">';
                        echo '<i class="fa-solid fa-circle-dot"></i>' . $event['deadline'] . ' ' . $event['client'] . ' ' . $event['title'] . '</div>';
                    }
                }
            }

            echo '</td>';

            $day_counter++;
        }
        ?>
        </table>

        <div>
            <a href="?month=<?php echo $month - 1; ?>&year=<?php echo $year; ?>">Önceki Ay</a>
            <a href="?month=<?php echo $month + 1; ?>&year=<?php echo $year; ?>">Sonraki Ay</a>
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
                
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <p>Başlık</p>
                        <input type="text" id="editTitle" name="title" required class="form-input">
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
                    <div id="allDayCheckbox" style="display: none;">
                        <label>
                            <input type="checkbox" id="editAllDay" name="allDay"> Tüm Gün
                        </label>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-success w-full gap-2 mt-5">
                Kaydet
            </button>
            <button type="button" id="completeEventButton" class="btn btn-primary w-full gap-2 mt-2" style="display: none;">
                Tamamla
            </button>
            <button type="button" id="deleteButton" class="btn btn-danger w-full gap-2 mt-2" style="display: none;">
                Sil
            </button>
            <button type="button" id="completeButton" class="btn btn-primary w-full gap-2 mt-2" style="display: none;">
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
            <button style="width:30%" onclick="openEditModal()" class="btn btn-primary">Düzenle</button>
            <button style="width:30%" onclick="viewProject()" class="btn btn-info">Görüntüle</button>
            <button style="width:30%" onclick="viewProjectNewTab()" class="btn btn-info">Yeni Sekmede Aç</button>
        </div>
    </div>
</div>

<script>
function openModal(type, id) {
    $('#editType').val(type);
    $('#editId').val(id);
    $('#modalTitle').text(type === 'event' ? 'Etkinlik Düzenle' : 'Proje Düzenle');
    
    if (type === 'event') {
        $('#startDateInputs').show();
        $('#allDayCheckbox').show();
        $('#completeEventButton').show();
        $('#deleteButton').show();
        $('#completeButton').hide();
    } else {
        $('#startDateInputs').hide();
        $('#allDayCheckbox').hide();
        $('#deleteButton').hide();
        $('#completeEventButton').hide();
        $('#completeButton').show();
    }
    
    // AJAX ile mevcut veriyi çek
    $.ajax({
        url: 'pages/manager/calendar/get_item_data.php',
        method: 'GET',
        data: { type: type, id: id },
        success: function(response) {
            var data = JSON.parse(response);
            $('#editTitle').val(data.title);
            if (type === 'event') {
                $('#editDateStart').val(data.dateStart.split(' ')[0]);
                $('#editTimeStart').val(data.dateStart.split(' ')[1]);
                $('#editTimeEnd').val(data.dateEnd.split(' ')[1]);
                $('#editAllDay').prop('checked', data.dateType === 'allday');
            } else {
                $('#editDateStart').val(data.dateStart.split(' ')[0]);
                $('#editTimeStart').val(data.dateStart.split(' ')[1]);
            }
            $('#editModal').show();
        }
    });
}

function closeModal() {
    $('#editModal').hide();
}

function openCreateModal(date) {
    $('#createDateStart').val(date);
    $('#createEventModal').show();
}

function closeCreateModal() {
    $('#createEventModal').hide();
}

let currentProjectId = null;

function openProjectModal(id, title) {
    currentProjectId = id;
    $('#projectModalTitle').text(title);
    $('#projectModal').show();
}

function closeProjectModal() {
    $('#projectModal').hide();
}

function openEditModal() {
    closeProjectModal();
    openModal('project', currentProjectId);
}

function viewProject() {
    window.location.href = 'project?id=' + currentProjectId;
}

function viewProjectNewTab() {
    window.open('project?id=' + currentProjectId, "_blank");
}

$('#editForm').submit(function(e) {
    e.preventDefault();
    var formData = $(this).serialize();
    
    if ($('#editType').val() === 'event') {
        formData += '&dateStart=' + $('#editDateStart').val() + ' ' + $('#editTimeStart').val();
        formData += '&dateEnd=' + $('#editDateStart').val() + ' ' + $('#editTimeEnd').val();
        formData += '&allDay=' + $('#editAllDay').prop('checked');
    } else {
        formData += '&dateDeadline=' + $('#editDateStart').val() + ' ' + $('#editTimeStart').val();
    }
    
    $.ajax({
        url: 'pages/manager/calendar/update_item.php',
        method: 'POST',
        data: formData,
        success: function(response) {
            if(response === 'success') {
                closeModal();
                location.reload();
            } else {
                alert('Bir hata oluştu. Lütfen tekrar deneyin.');
            }
        }
    });
});

$('#deleteButton').click(function() {
    if (confirm('Bu etkinliği silmek istediğinizden emin misiniz?')) {
        $.ajax({
            url: 'pages/manager/calendar/delete_item.php',
            method: 'POST',
            data: {
                type: 'event',
                id: $('#editId').val()
            },
            success: function(response) {
                if(response === 'success') {
                    closeModal();
                    location.reload();
                } else {
                    alert('Bir hata oluştu. Lütfen tekrar deneyin.');
                }
            }
        });
    }
});

$('#completeButton').click(function() {
    if (confirm('Bu projeyi tamamlamak istediğinizden emin misiniz?')) {
        $.ajax({
            url: 'pages/manager/calendar/complete_project.php',
            method: 'POST',
            data: {
                id: $('#editId').val()
            },
            success: function(response) {
                if(response === 'success') {
                    closeModal();
                    location.reload();
                } else {
                    alert('Bir hata oluştu. Lütfen tekrar deneyin.');
                }
            }
        });
    }
});

$('#completeEventButton').click(function() {
    if (confirm('Bu projeyi tamamlamak istediğinizden emin misiniz?')) {
        $.ajax({
            url: 'pages/manager/calendar/complete_event.php',
            method: 'POST',
            data: {
                id: $('#editId').val()
            },
            success: function(response) {
                if(response === 'success') {
                    closeModal();
                    location.reload();
                } else {
                    alert('Bir hata oluştu. Lütfen tekrar deneyin.');
                }
            }
        });
    }
});

$('#createEventForm').submit(function(e) {
    e.preventDefault();
    var formData = $(this).serialize();
    formData += '&dateStart=' + $('#createDateStart').val() + ' ' + $('#createTimeStart').val();
    formData += '&dateEnd=' + $('#createDateStart').val() + ' ' + $('#createTimeEnd').val();
    formData += '&allDay=' + $('#createAllDay').prop('checked');
    
    $.ajax({
        url: 'pages/manager/calendar/create_event.php',
        method: 'POST',
        data: formData,
        success: function(response) {
            if(response === 'success') {
                closeCreateModal();
                location.reload();
            } else {
                alert('Bir hata oluştu. Lütfen tekrar deneyin.');
            }
        }
    });
});

$('#editAllDay').change(function() {
    if($(this).is(':checked')) {
        $('#editTimeStart').val('00:00').prop('disabled', true);
        $('#editTimeEnd').val('23:59').prop('disabled', true);
    } else {
        $('#editTimeStart').prop('disabled', false);
        $('#editTimeEnd').prop('disabled', false);
    }
});

$('#createAllDay').change(function() {
    if($(this).is(':checked')) {
        $('#createTimeStart').val('00:00').prop('disabled', true);
        $('#createTimeEnd').val('23:59').prop('disabled', true);
    } else {
        $('#createTimeStart').prop('disabled', false);
        $('#createTimeEnd').prop('disabled', false);
    }
});
</script>
