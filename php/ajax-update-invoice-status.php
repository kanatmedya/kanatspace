<?php
include "../assets/db/db_connect.php"; // Veritabanı bağlantısı

header('Content-Type: application/json'); // JSON çıktısı döndürmek için

if(isset($_POST['id'], $_POST['status'])) {
    $id = intval($_POST['id']);
    $allowedStatuses = ['onOffer', 'onOrder', 'completed', 'rejected']; // Sadece izin verilen statüler

    if (!in_array($_POST['status'], $allowedStatuses)) {
        echo json_encode(["status" => "error", "message" => "Geçersiz durum"]);
        exit;
    }

    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE invoices SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $id);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Durum başarıyla güncellendi"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Veritabanı hatası"]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["status" => "error", "message" => "Eksik parametre"]);
}
?>
