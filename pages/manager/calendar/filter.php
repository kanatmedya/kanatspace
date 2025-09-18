<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT'] . '/');
}

include ROOT_PATH . "assets/db/db_connect.php";

$client = $_GET['client'] ?? '';
$user = $_GET['user'] ?? '';
$projectStatus = $_GET['projectStatus'] ?? '';
$eventStatus = $_GET['eventStatus'] ?? '';
$month = $_GET['month'] ?? date('m'); // Eğer GET parametre yoksa mevcut ayı al
$year = $_GET['year'] ?? date('Y');   // Eğer GET parametre yoksa mevcut yılı al

$user_id = $_SESSION['userID'];

// Proje sorgusu
$sql_projects = "SELECT DISTINCT p.id, p.title, p.dateDeadline, p.client_id, p.status 
                 FROM projects p 
                 JOIN projects_assignees pa ON p.id = pa.project_id 
                 WHERE p.active = '1'";

// Proje filtreleri
$params_projects = [];
$bind_types_projects = '';

// Müşteri filtresi
if (!empty($client) && $client != 'Tümü') {
    $sql_projects .= " AND p.client_id = ?";
    $bind_types_projects .= 'i';
    $params_projects[] = $client;
}

// Personel filtresi
if (!empty($user) && $user != 'Tümü') {
    $sql_projects .= " AND pa.user_id = ?";
    $bind_types_projects .= 'i';
    $params_projects[] = $user;
}

// Proje durumu filtresi
if (!empty($projectStatus) && $projectStatus != 'Hepsi') {
    if ($projectStatus == 'Tamamlanmadı') {
        $sql_projects .= " AND p.status != 'Tamamlandı'";
    } elseif ($projectStatus == 'Hiçbiri') {
        $sql_projects = ""; // Hiçbir proje gösterilmeyecek
    } else {
        $sql_projects .= " AND p.status = ?";
        $bind_types_projects .= 's';
        $params_projects[] = $projectStatus;
    }
}

// Etkinlik sorgusu
$sql_events = "SELECT DISTINCT e.id, e.type, e.title, e.dateStart, e.dateEnd, e.dateType, e.status 
               FROM events e 
               JOIN events_assignees ea ON e.id = ea.event_id 
               WHERE 1=1";

// Etkinlik filtreleri
$params_events = [];
$bind_types_events = '';

// Müşteri filtresi etkinlikler için
if (!empty($client) && $client != 'Tümü') {
    $sql_events .= " AND e.client_id = ?";
    $bind_types_events .= 'i';
    $params_events[] = $client;
}

// Personel filtresi etkinlikler için
if (!empty($user) && $user != 'Tümü') {
    $sql_events .= " AND ea.user_id = ?";
    $bind_types_events .= 'i';
    $params_events[] = $user;
}

// Etkinlik durumu filtresi
if ($eventStatus == '0') {
    $sql_events .= " AND e.status = 0"; // Tamamlanmamış etkinlikler
} elseif ($eventStatus == '1') {
    $sql_events .= " AND e.status = 1"; // Tamamlanmış etkinlikler
} elseif ($eventStatus == 'Hiçbiri') {
    $sql_events = ""; // Hiçbir etkinlik gösterilmeyecek
}

// Proje sorgusunu çalıştır
$project_results = [];
if (!empty($sql_projects)) {
    $stmt_projects = $conn->prepare($sql_projects);
    if ($stmt_projects !== false) {
        if (!empty($params_projects)) {
            $stmt_projects->bind_param($bind_types_projects, ...$params_projects);
        }
        $stmt_projects->execute();
        $result_projects = $stmt_projects->get_result();

        // Projeleri işle
        while ($row = $result_projects->fetch_assoc()) {
            $project_results[] = $row;
        }
    }
}

// Etkinlik sorgusunu çalıştır
$event_results = [];
if (!empty($sql_events)) {
    $stmt_events = $conn->prepare($sql_events);
    if ($stmt_events !== false) {
        if (!empty($params_events)) {
            $stmt_events->bind_param($bind_types_events, ...$params_events);
        }
        $stmt_events->execute();
        $result_events = $stmt_events->get_result();

        // Etkinlikleri işle
        while ($row = $result_events->fetch_assoc()) {
            $event_results[] = $row;
        }
    }
}

// Takvimi oluştur
$calendar_days = getMonth($month, $year);
$events = [];

// Etkinlikleri takvime ekle
foreach ($event_results as $row) {
    $start_date = date('Y-m-d H:i:s', strtotime($row['dateStart']));
    $end_date = date('Y-m-d H:i:s', strtotime($row['dateEnd']));
    $current_date = $start_date;
    while (strtotime($current_date) <= strtotime($end_date)) {
        $events[] = [
            'id' => $row['id'],
            'title' => $row['title'],
            'type' => $row['type'],
            'date' => $current_date,
            'dateType' => $row['dateType']
        ];
        $current_date = date('Y-m-d H:i:s', strtotime($current_date . ' +1 day'));
    }
}

// Projeleri takvime ekle
foreach ($project_results as $row) {
    $deadline_date = date('Y-m-d H:i:s', strtotime($row['dateDeadline']));
    $status = $row['status'];
    
    $client = $row['client_id'];
    
    // Sorguyu hazırlayın
    $sqlClientName = "SELECT username FROM users_client WHERE id = ?";
    $stmtClientName = $conn->prepare($sqlClientName);
    if ($stmtClientName) {
        // Değeri bağlayın ve sorguyu çalıştırın
        $stmtClientName->bind_param("i", $client);
        $stmtClientName->execute();
        
        // Sonuçları alın
        $resultClientName = $stmtClientName->get_result();
        if ($resultClientName->num_rows > 0) {
            $rowClientName = $resultClientName->fetch_assoc();
            $clientName = $rowClientName['username']; // Username değeri $username değişkenine atanıyor
        } else {
            $clientName = null; // Eğer kullanıcı bulunmazsa null atanır
        }
        
        // Belleği temizleyin
        $stmtClientName->close();
    } else {
        $clientName = null; // Sorgu başarısız olursa null atanır
    }
    
    $circleIcon = '<i class="fa-solid fa-circle-dot" aria-hidden="true"></i>';

    if ($status == 'Tamamlandı') {
        $circleIcon = '<i class="fa-solid fa-circle-dot" aria-hidden="true" style="color: green;"></i>';
    } elseif ($status == 'Onayda') {
        $circleIcon = '<i class="fa-solid fa-circle-dot" aria-hidden="true" style="color: yellow;"></i>';
    } elseif ($status == 'Beklemede') {
        $circleIcon = '<i class="fa-solid fa-circle-dot" aria-hidden="true" style="color: orange;"></i>';
    } elseif ($status != 'Tamamlandı' && strtotime($row['dateDeadline']) < time()) {
        $circleIcon = '<i class="fa-solid fa-circle-dot" aria-hidden="true" style="color: red;"></i>';
    }

    $events[] = [
        'id' => $row['id'],
        'title' => htmlspecialchars($clientName, ENT_QUOTES) . ' ' . htmlspecialchars($row['title'], ENT_QUOTES),
        'type' => 'project',
        'date' => $deadline_date,
        'dateType' => 'timed',
        'icon' => $circleIcon
    ];
}

// $events dizisini tarih ve saate göre sıralama
usort($events, function ($a, $b) {
    return strtotime($a['date']) - strtotime($b['date']);
});

// Takvim çıktısını oluştur
$day_counter = 0;
$html_output = '';

foreach ($calendar_days as $day) {
    if ($day_counter % 7 == 0) {
        $html_output .= $day_counter > 0 ? '</tr>' : '';
        $html_output .= '<tr>';
    }

    $date = $day['year'] . '-' . str_pad($day['month'], 2, '0', STR_PAD_LEFT) . '-' . str_pad($day['day'], 2, '0', STR_PAD_LEFT);
    $class = $day['current'] ? '' : 'other-month';

    $today = date('Y-m-d');
    $style = $date === $today ? 'background: lightgoldenrodyellow;' : '';

    $html_output .= '<td class="' . $class . '" style="' . $style . '">';
    $html_output .= $day['day'];
    $html_output .= '<i class="fa-solid fa-circle-plus add-event-icon" onclick="openSelectionModal(\'' . $date . '\')"></i>';

    foreach ($events as $event) {
        if (strpos($event['date'], $date) === 0) {
            if($event['dateType']=="timed"){
                $time = date('H:i', strtotime($event['date'])) . " ";
            }else{
                $time = "";
            }
            
            if ($event['type'] == 'event') {
                $html_output .= '<div class="event event-event" onclick="openModal(\'event\', ' . $event['id'] . ')">' . $time . $event['title'] . '</div>';
            }else if ($event['type'] == 'special_day') {
                $html_output .= '<div class="event event-special_day" onclick="openModal(\'event\', ' . $event['id'] . ')">' . $time  . $event['title'] . '</div>';
            }else if ($event['type'] == 'visit') {
                $html_output .= '<div class="event event-visit" onclick="openModal(\'event\', ' . $event['id'] . ')">' . $time  . $event['title'] . '</div>';
            } else {
                $html_output .= '<div class="project" onclick="openProjectModal(' . $event['id'] . ', \'' . htmlspecialchars($event['title'], ENT_QUOTES) . '\')">';
                $html_output .= $event['icon'] . $time  . $event['title'] . '</div>';
            }
        }
    }

    $html_output .= "</td>";
    $day_counter++;
}

$html_output .= '</tr>';
echo $html_output;

// Yardımcı fonksiyonlar
function getMonth($month, $year) {
    $num_days = date('t', strtotime($year . '-' . $month . '-01'));
    $num_days_last_month = date('j', strtotime('last day of previous month', strtotime($year . '-' . $month . '-01')));
    $days = array();
    $first_day_of_week = date('N', strtotime($year . '-' . $month . '-01'));

    for ($i = $first_day_of_week - 1; $i > 0; $i--) {
        $days[] = array('day' => $num_days_last_month - $i + 1, 'month' => $month - 1, 'year' => $year, 'current' => false);
    }

    for ($i = 1; $i <= $num_days; $days[] = ['day' => $i, 'month' => $month, 'year' => $year, 'current' => true], $i++);

    $days_left = 42 - count($days);
    for ($i = 1; $i <= $days_left; $days[] = ['day' => $i, 'month' => $month + 1, 'year' => $year, 'current' => false], $i++);

    return $days;
}

?>
