<?php
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT'] . '/');
}
include ROOT_PATH . "assets/db/db_connect.php";
global $conn;

$type = $_GET['type'];
$id = intval($_GET['id']);

if ($type === 'event') {
    $sql = "SELECT title, type, dateStart, dateEnd, dateType FROM events WHERE id = ?";
} else {
    $sql = "SELECT title, dateDeadline AS dateStart FROM projects WHERE id = ?";
}

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

echo json_encode($data);
?>