<?php
session_start(); // Oturumu başlatın
header('Content-Type: application/json');


if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT'] . '/');
}
include ROOT_PATH . "assets/db/db_connect.php";
global $conn;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];

    // SQL sorgusu
    $sql = "SELECT id, user, value, dateCreate, photos, documents, audios FROM projects_comments WHERE related = ? AND type = 'projectComment' ORDER BY id ASC";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $comments = [];
        while ($row = $result->fetch_assoc()) {
            $comments[] = $row;
        }

        echo json_encode(['status' => 'success', 'comments' => $comments]);
        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Hazırlık hatası: ' . $conn->error]);
    }
}

$conn->close();
?>
