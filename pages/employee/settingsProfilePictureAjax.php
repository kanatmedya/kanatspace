<?php
session_start();

include "../../assets/db/db_connect.php";

$response = ["success" => false, "message" => ""];

if (isset($_SESSION['status']) && isset($_SESSION['userID'])) {
    $userID = $_SESSION['userID'];

    if (isset($_FILES['profilePicture']) && $_FILES['profilePicture']['error'] == UPLOAD_ERR_OK) {
        $uploadDir = '../../uploads/users/profile/';
        $dbFilePath = 'uploads/users/profile/'; // Path to save in database
        $fileName = basename($_FILES['profilePicture']['name']);
        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
        $fileBaseName = pathinfo($fileName, PATHINFO_FILENAME);

        // Shorten file name if necessary
        $maxFileNameLength = 255 - strlen($uploadDir) - strlen($fileExtension) - 1;
        if (strlen($fileBaseName) > $maxFileNameLength) {
            $fileBaseName = substr($fileBaseName, 0, $maxFileNameLength);
        }

        $uploadFile = $uploadDir . $fileBaseName . '.' . $fileExtension;
        $dbFilePath .= $fileBaseName . '.' . $fileExtension;

        // Fetch the current profile picture
        $stmt = $conn->prepare("SELECT profilePicture FROM ac_users WHERE id = ?");
        $stmt->bind_param('i', $userID);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if (move_uploaded_file($_FILES['profilePicture']['tmp_name'], $uploadFile)) {
            // Delete old profile picture if it's not the default
            if ($row['profilePicture'] != 'default.jpg' && file_exists('../../' . $row['profilePicture'])) {
                if (!unlink('../../' . $row['profilePicture'])) {
                    $response['message'] = 'Eski dosya silinemedi.';
                    echo json_encode($response);
                    exit;
                }
            }

            // Update database with the new file path
            $stmt = $conn->prepare("UPDATE ac_users SET profilePicture = ? WHERE id = ?");
            $stmt->bind_param('si', $dbFilePath, $userID);

            if ($stmt->execute()) {
                // Update session with the new profile picture
                $_SESSION['pic'] = $dbFilePath;

                $response['success'] = true;
                $response['newImageUrl'] = $dbFilePath;
            } else {
                $response['message'] = 'Veritabanı güncellenirken bir hata oluştu.';
            }
        } else {
            $response['message'] = 'Dosya yüklenirken bir hata oluştu.';
        }
    } else {
        $response['message'] = 'Geçersiz dosya.';
    }
} else {
    $response['message'] = 'Kullanıcı oturumu aktif değil.';
}

echo json_encode($response);
?>
