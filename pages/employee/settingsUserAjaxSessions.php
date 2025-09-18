<?php
session_start();

if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT'] . '/');
}
include ROOT_PATH . "assets/db/db_connect.php";

// AJAX ile gelen isteği kontrol edelim
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'delete' && isset($_POST['token'])) {
        $token = $_POST['token'];
        $userId = $_SESSION['userID']; // Oturumdaki kullanıcının ID'si

        // Token'ı veritabanından silme işlemi
        $deleteQuery = "DELETE FROM user_tokens WHERE user_id = ? AND token = ?";
        $stmt = $conn->prepare($deleteQuery);
        $stmt->bind_param('is', $userId, $token);
        $stmt->execute();

        // Yanıtı JSON formatında dönelim
        if ($stmt->affected_rows > 0) {
            echo json_encode(['status' => 'success', 'message' => 'Oturum başarıyla kapatıldı.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Oturum kapatılamadı.']);
        }
        $stmt->close();
        exit();
    } elseif ($_POST['action'] === 'list') {
        // Oturumları listeleme işlemi
        $userId = $_SESSION['userID']; // Oturumdaki kullanıcının ID'si
        $query = "SELECT token, sessionName, operating_system, device_type, ip_address, dateCreate, expiry, browser_name, browser_version FROM user_tokens WHERE user_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        // Oturumları liste olarak döndürelim
        $sessions = [];
        while ($row = $result->fetch_assoc()) {
            $sessions[] = $row;
        }
        echo json_encode($sessions);
        exit();
    }
}
?>
