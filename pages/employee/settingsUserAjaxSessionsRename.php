<?php
// Hata raporlamayı aç
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT'] . '/');
}
include ROOT_PATH . "assets/db/db_connect.php";

// JSON yanıtı
$response = ["success" => false, "message" => ""];

// Veritabanı bağlantısını kontrol et
if ($conn->connect_error) {
    die("Veritabanı bağlantı hatası: " . $conn->connect_error);
}

// POST ile gelen verileri al
$sessionToken = isset($_POST['sessionToken']) ? $_POST['sessionToken'] : null;
$sessionName = isset($_POST['sessionName']) ? $_POST['sessionName'] : null;

if ($sessionToken && $sessionName) {
    // İsim 16 karakterden uzun mu kontrol et
    if (strlen($sessionName) > 24) {
        $response['message'] = 'Oturum adı 24 karakterden uzun olamaz.';
        echo json_encode($response);
        exit;
    }

    // Veritabanı güncelleme sorgusu
    $stmt = $conn->prepare("UPDATE user_tokens SET sessionName = ? WHERE token = ?");
    $stmt->bind_param('ss', $sessionName, $sessionToken);

    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = 'Oturum adı başarıyla güncellendi.';
    } else {
        $response['message'] = 'Güncelleme sırasında bir hata oluştu.';
    }

    $stmt->close();
} else {
    $response['message'] = 'Geçersiz veri.';
}

// Yanıtı JSON olarak döndür
echo json_encode($response);
?>
