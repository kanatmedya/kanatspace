<?php
session_start();

include "../../assets/db/db_connect.php";

if (isset($_SESSION['status']) && isset($_SESSION['userID'])) {
    $userID = $_SESSION['userID'];

    if ($_POST['newPassword'] === $_POST['confirmPassword']) {
        $currentPassword = $_POST['currentPassword'];
        $newPassword = $_POST['newPassword'];

        // Fetch the current password hash from the database
        $stmt = $conn->prepare("SELECT password FROM ac_users WHERE id = ?");
        $stmt->bind_param('i', $userID);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($currentPassword, $user['password'])) {
            // Hash the new password
            $newHashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

            // Update the password in the database
            $stmt = $conn->prepare("UPDATE ac_users SET password = ? WHERE id = ?");
            $stmt->bind_param('si', $newHashedPassword, $userID);

            if ($stmt->execute()) {
                $response['success'] = true;
                $response['message'] = "Şifre güncellendi.";
            } else {
                $response['message'] = "Şifre güncellenemedi. Hata: " . $stmt->error;
            }
        } else {
            $response['message'] = "Mevcut şifre yanlış.";
        }
    } else {
        $response['message'] = "Yeni şifreler uyuşmuyor.";
    }
} else {
    $response['message'] = "Kullanıcı oturumu aktif değil.";
}

echo json_encode($response);
?>
