<?php
session_start();
$userId = $_SESSION['userID'];

if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT'] . '/');
}
include ROOT_PATH . "assets/db/db_connect.php";
global $conn;

$newStatus = "Tamamlandı"; // Yeni durum
$id = intval($_POST['id']); // POST'dan gelen id değeri

// Mevcut status değerini almak için SQL sorgusu
$sqlGetStatus = "SELECT status FROM projects WHERE id = ?";
$stmtGetStatus = $conn->prepare($sqlGetStatus);
$stmtGetStatus->bind_param("i", $id);
$stmtGetStatus->execute();
$stmtGetStatus->bind_result($previousStatus); // Mevcut status değeri
$stmtGetStatus->fetch();
$stmtGetStatus->close(); // Bağlantıyı kapat

// Durumu güncelleme sorgusu
$sqlUpdate = "UPDATE projects SET status = ? WHERE id = ?";
$stmtUpdate = $conn->prepare($sqlUpdate);
$stmtUpdate->bind_param("si", $newStatus, $id);

if ($stmtUpdate->execute()) {
    echo "success";
    // project_logs tablosuna log ekle
    logProjectChange($conn, $id, $userId, $previousStatus, $newStatus); // previousStatus, veritabanından gelen değer
} else {
    echo "error";
}
$stmtUpdate->close(); // Güncelleme işlemi tamamlandıktan sonra bağlantıyı kapat

// Log ekleme fonksiyonu
function logProjectChange($conn, $itemId, $userId, $previousStatus, $newStatus) {
    $stmtLog = $conn->prepare("INSERT INTO log_projects (`project_id`, `user`, `old`, `new`) VALUES (?, ?, ?, ?)");
    if (!$stmtLog) {
        throw new Exception("Log prepare failed: " . $conn->error);
    }
    $stmtLog->bind_param('iiss', $itemId, $userId, $previousStatus, $newStatus);
    $stmtLog->execute();
    $stmtLog->close(); // Bağlantıyı kapat
}
?>
