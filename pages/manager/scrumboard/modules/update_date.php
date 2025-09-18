<?php

include "../../../../assets/db/db_connect.php";

header('Content-Type: application/json');


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $dateDeadline = $_POST['dateDeadline'];

    // SQL sorgusu
    $sql = "UPDATE projects SET dateDeadline = ? WHERE id = ?";

    // Hazırlık ve bağlama
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("si", $dateDeadline, $id);
        
        // Sorguyu çalıştır
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Başarıyla güncellendi']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Sorgu hatası: ' . $stmt->error]);
        }

        // Kaynakları serbest bırak
        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Hazırlık hatası: ' . $conn->error]);
    }
}

$conn->close();
?>
