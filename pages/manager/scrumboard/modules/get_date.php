<?php
header('Content-Type: application/json');

include "../../../../assets/db/db_connect.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];

    // SQL sorgusu
    $sql = "SELECT dateDeadline FROM projects WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->bind_result($dateDeadline);
        if ($stmt->fetch()) {
            echo json_encode(['status' => 'success', 'dateDeadline' => $dateDeadline]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Kayıt bulunamadı']);
        }
        $stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Hazırlık hatası: ' . $conn->error]);
    }
}

$conn->close();
?>
