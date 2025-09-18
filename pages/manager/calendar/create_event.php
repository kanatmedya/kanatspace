<?php
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT'] . '/');
}
include ROOT_PATH . "assets/db/db_connect.php";
global $conn;

session_start();

$creator = $_SESSION['userID'];
$user_id = $_SESSION['userID'];

$title = $_POST['title'];
$dateStart = $_POST['dateStart'];
$dateEnd = $_POST['dateEnd'];
$allDay = isset($_POST['allDay']) && $_POST['allDay'] === 'true' ? 'allday' : '';

// Event'i events tablosuna ekleyelim
$sql = "INSERT INTO events (title, dateStart, dateEnd, dateType, creator) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssss", $title, $dateStart, $dateEnd, $allDay, $creator);

if ($stmt->execute()) {
    // Yeni eklenen event'in ID'sini alalım
    $event_id = $conn->insert_id;

    // events_assignees tablosuna kullanıcı ve event_id ile kayıt ekleyelim
    $sql_assignee = "INSERT INTO events_assignees (event_id, user_id) VALUES (?, ?)";
    $stmt_assignee = $conn->prepare($sql_assignee);
    $stmt_assignee->bind_param("ii", $event_id, $user_id);

    if ($stmt_assignee->execute()) {
        echo "success";
    } else {
        echo "error: events_assignees eklenemedi";
    }
} else {
    echo "error: event eklenemedi";
}
?>
