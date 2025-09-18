<?php

include "../../../assets/db/db_connect.php";
global $conn;

header('Content-Type: application/json');

// Gönderilen JSON verilerini al
$data = json_decode(file_get_contents("php://input"), true);

// Verilerin alındığından emin olun
if (isset($data['id']) && isset($data['title']) && isset($data['start']) && isset($data['end']) && isset($data['description']) && isset($data['badge'])) {
    $id = $data['id'];
    $title = $data['title'];
    $start = $data['start'];
    $end = $data['end'];
    $description = $data['description'];
    $badge = $data['badge'];

    // Burada veritabanı güncelleme işlemini gerçekleştirin
    $sql = "UPDATE app_calendar SET title = ?, dateStart = ?, dateEnd = ?, description = ?, badge = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $title, $start, $end, $description, $badge, $id);

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => $stmt->error]);
    }
} else {
    echo json_encode(["success" => false, "error" => "Eksik veri"]);
}
?>