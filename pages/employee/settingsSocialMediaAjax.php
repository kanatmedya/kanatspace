<?php
session_start();

include "../../assets/db/db_connect.php";

$response = ["success" => false, "message" => ""];

if (isset($_SESSION['status']) && isset($_SESSION['userID'])) {
    $userID = $_SESSION['userID'];

    $stmt = $conn->prepare("UPDATE ac_users SET sc_linkedin=?, sc_behance=?, sc_facebook=?, sc_instagram=?, sc_twitter=? WHERE id=?");
    $stmt->bind_param('sssssi', $_POST['sc_linkedin'], $_POST['sc_behance'], $_POST['sc_facebook'], $_POST['sc_instagram'], $_POST['sc_twitter'], $userID);

    if ($stmt->execute()) {
        $response['success'] = true;
        $response['message'] = "Sosyal medya bilgileri güncellendi.";
    } else {
        $response['message'] = "Sosyal medya bilgileri güncellenemedi. Hata: " . $stmt->error;
    }
} else {
    $response['message'] = "Kullanıcı oturumu aktif değil.";
}

echo json_encode($response);
?>
