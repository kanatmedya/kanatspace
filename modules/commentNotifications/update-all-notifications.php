<?php
session_start();
header('Content-Type: application/json');

include '../../assets/db/db_connect.php';

// Kullanıcının oturumdan alınan adını al
$userName = isset($_SESSION['name']) ? $_SESSION['name'] : null;

if ($userName === null) {
    echo json_encode(['success' => false, 'error' => 'Kullanıcı oturumu bulunamadı']);
    exit();
}

// Bildirimleri okundu olarak işaretleyin
$sql = "UPDATE nt_comments SET readed = 1 WHERE username='$userName' AND author!='$userName'";
if ($conn->query($sql) === TRUE) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Error updating records: ' . $conn->error]);
}

$conn->close();
?>
