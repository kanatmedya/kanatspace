<?php
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT'] . '/');
}
include ROOT_PATH . "assets/db/db_connect.php";
global $conn;

$type = $_POST['type'];
$id = intval($_POST['id']);
$title = $_POST['title'];

if ($type === 'event') {
    $dateStart = $_POST['dateStart'];
    $dateEnd = $_POST['dateEnd'];
    $allDay = isset($_POST['allDay']) && $_POST['allDay'] === 'true' ? 'allday' : 'timed';
    $sql = "UPDATE events SET title = ?, dateStart = ?, dateEnd = ?, dateType = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $title, $dateStart, $dateEnd, $allDay, $id);
} else {
    $dateDeadline = $_POST['dateDeadline'];
    $sql = "UPDATE projects SET title = ?, dateDeadline = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $title, $dateDeadline, $id);
}

if ($stmt->execute()) {
    echo "success";
} else {
    echo "error";
}
?>