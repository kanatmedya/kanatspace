<?php
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT'] . '/');
}
include ROOT_PATH . "assets/db/db_connect.php";
global $conn;

$id = intval($_POST['id']);

$sql = "DELETE FROM events WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo "success";
} else {
    echo "error";
}
?>