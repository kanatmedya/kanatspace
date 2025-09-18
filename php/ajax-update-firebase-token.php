<?php
include "../assets/db/db_connect.php"; // Veritabanı bağlantısı
session_start();

header('Content-Type: application/json'); // JSON formatında yanıt döndürmek için

$data = json_decode(file_get_contents("php://input"), true);
$firebaseToken = $data['token'] ?? null;
$userId = $_SESSION['userID'] ?? null;
$sessionToken = $_COOKIE['remember_me'] ?? null; // Kullanıcının oturumunu belirleyen benzersiz token

if (!$userId || !$firebaseToken || !$sessionToken) {
    echo json_encode(["success" => false, "message" => "Eksik veri."]);
    exit;
}

// Kullanıcının mevcut cihaz kaydını kontrol et
$stmt = $conn->prepare("SELECT id FROM user_tokens WHERE user_id = ? AND token = ?");
$stmt->bind_param("is", $userId, $sessionToken);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    // Eğer cihaz zaten kayıtlıysa `firebase_token` güncelle
    $stmt = $conn->prepare("UPDATE user_tokens SET firebase_token = ? WHERE user_id = ? AND token = ?");
    $stmt->bind_param("sis", $firebaseToken, $userId, $sessionToken);
    $stmt->execute();
} else {
    // Eğer bu cihaz için giriş kaydı yoksa, yeni bir satır ekleyelim
    $stmt = $conn->prepare("INSERT INTO user_tokens (user_id, user_type, token, firebase_token, expiry, ip_address, protocol_version, browser_name, browser_version, operating_system, device_type, platform, bit_version) 
                            VALUES (?, ?, ?, ?, DATE_ADD(NOW(), INTERVAL 30 DAY), ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iissssssssss", $userId, $_SESSION['status'], $sessionToken, $firebaseToken, $_SERVER['REMOTE_ADDR'], $_SERVER['SERVER_PROTOCOL'], get_browser_name($_SERVER['HTTP_USER_AGENT']), get_browser_version($_SERVER['HTTP_USER_AGENT']), get_os($_SERVER['HTTP_USER_AGENT']), get_device_type($_SERVER['HTTP_USER_AGENT']), get_platform($_SERVER['HTTP_USER_AGENT']), get_bit_version($_SERVER['HTTP_USER_AGENT']));
    $stmt->execute();
}

echo json_encode(["success" => true, "message" => "Firebase token güncellendi."]);
$stmt->close();
?>
