<?php
// Hata raporlamayı aç
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// ROOT_PATH tanımla
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT'] . '/');
}
include ROOT_PATH . "assets/db/db_connect.php";

// Yanıt dizisi
$response = ["success" => false, "message" => ""];

// Veritabanı bağlantısını kontrol et
if ($conn->connect_error) {
    die("Veritabanı bağlantı hatası: " . $conn->connect_error);
}

// POST isteğini kontrol et
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    session_start();
    if (!isset($_SESSION['userID'])) {
        $response["message"] = "Kullanıcı oturumu aktif değil.";
        echo json_encode($response);
        exit;
    }

    $userID = $_SESSION['userID'];

    // Form verilerini al
    $name = isset($_POST['name']) ? $_POST['name'] : null;
    $surname = isset($_POST['surname']) ? $_POST['surname'] : null;
    $phoneCompany = isset($_POST['phoneCompany']) ? $_POST['phoneCompany'] : null;
    $phonePersonal = isset($_POST['phone']) ? $_POST['phone'] : null;
    $email = isset($_POST['email']) ? $_POST['email'] : null;
    $emailPersonal = isset($_POST['emailPersonal']) ? $_POST['emailPersonal'] : null;
    $birthday = isset($_POST['birthday']) ? $_POST['birthday'] : null;

    // Profil fotoğrafını kontrol et ve yükle
    if (isset($_FILES['profilePicture']) && $_FILES['profilePicture']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../../uploads/users/profile/';
        $fileName = basename($_FILES['profilePicture']['name']);
        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
        $fileBaseName = pathinfo($fileName, PATHINFO_FILENAME);
        
        // Dosya adını kısalt gerekirse
        $maxFileNameLength = 255 - strlen($uploadDir) - strlen($fileExtension) - 1;
        if (strlen($fileBaseName) > $maxFileNameLength) {
            $fileBaseName = substr($fileBaseName, 0, $maxFileNameLength);
        }
        
        $uploadFile = $uploadDir . $fileBaseName . '.' . $fileExtension;
        $profilePicturePath = 'uploads/users/profile/' . $fileBaseName . '.' . $fileExtension;
        
        if (!move_uploaded_file($_FILES['profilePicture']['tmp_name'], $uploadFile)) {
            $profilePicturePath = null; // Upload başarısızsa null
        }
    } else {
        $profilePicturePath = null; // Profil fotoğrafı güncellenmediyse boş bırak
    }

    // Gerekli verilerin kontrolü
    if (!$name || !$surname || !$email || !$phoneCompany || !$birthday) {
        $response["message"] = "Gerekli alanlar boş bırakılamaz.";
        echo json_encode($response);
        exit;
    }

    // Veritabanı güncelleme sorgusu
    $stmt = $conn->prepare("UPDATE ac_users SET name = ?, surname = ?, phone = ?, phonePersonal = ?, mail = ?, emailPersonal = ?, birthday = ?" . ($profilePicturePath ? ", profilePicture = ?" : "") . " WHERE id = ?");
    if ($profilePicturePath) {
        $stmt->bind_param('ssssssssi', $name, $surname, $phoneCompany, $phonePersonal, $email, $emailPersonal, $birthday, $profilePicturePath, $userID);
    } else {
        $stmt->bind_param('sssssssi', $name, $surname, $phoneCompany, $phonePersonal, $email, $emailPersonal, $birthday, $userID);
    }

    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = "Bilgiler başarıyla güncellendi.";
    } else {
        $response['message'] = "Güncelleme sırasında bir hata oluştu: " . $stmt->error;
    }

    $stmt->close();
    echo json_encode($response);
} else {
    $response["message"] = "Geçersiz istek.";
    echo json_encode($response);
}
?>
