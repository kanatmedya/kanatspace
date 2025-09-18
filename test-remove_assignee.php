<?php

include 'assets/db/db_connect.php';
global $conn;

$projectId = $_POST['projectId'];
$userId = $_POST['userId'];

// Projeden kullanıcıyı kaldırmak için SQL sorgusu
$sql = "DELETE FROM projects_assignees WHERE project_id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);

// Hata ayıklamak için sorguyu kontrol edin
if ($stmt === false) {
    echo json_encode(["status" => "error", "message" => "SQL prepare hatası: " . $conn->error]);
    exit;
}

$stmt->bind_param("ii", $projectId, $userId);

if ($stmt->execute()) {
    // Silinen satır sayısını kontrol etmeden direkt başarı mesajı döndür
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error", "message" => "SQL çalıştırma hatası: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
