<?php
include "db_connect.php"; // Veritabanı bağlantısı
$user_id = $_SESSION['userID'];

// Filtrelerden gelen verileri al
$client = isset($_GET['client']) ? $_GET['client'] : 'all';
$personnel = isset($_GET['personnel']) ? $_GET['personnel'] : 'all';
$project_status = isset($_GET['project_status']) ? $_GET['project_status'] : 'all';
$event_status = isset($_GET['event_status']) ? $_GET['event_status'] : 'all';

// Event ve proje sorgularını filtrele
$sql_events = "SELECT id, title, dateStart, dateEnd FROM events WHERE user_id = ? AND status = ?";
$sql_projects = "SELECT p.id, p.title, p.dateDeadline, p.client FROM projects p JOIN projects_assignees pa ON p.id = pa.project_id WHERE pa.user_id = ? AND p.status != 'Tamamlandı'";

// Parametrelerle sorguları çalıştır
$stmt_events = $conn->prepare($sql_events);
$stmt_events->bind_param("ii", $user_id, $event_status == 'all' ? 0 : $event_status);
$stmt_events->execute();
$result_events = $stmt_events->get_result();

$stmt_projects = $conn->prepare($sql_projects);
$stmt_projects->bind_param("i", $user_id);
$stmt_projects->execute();
$result_projects = $stmt_projects->get_result();

// Dinamik olarak takvim içeriğini oluştur ve döndür
// ...
?>
