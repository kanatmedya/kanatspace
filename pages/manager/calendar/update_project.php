<?php
include "../../../assets/db/db_connect.php";

$projectId = $_POST['id'];
$title = $_POST['title'];
$projectType = $_POST['projectType'];
$deadlineDate = $_POST['deadlineDate'];
$deadlineTime = $_POST['deadlineTime'];
$dateDeadline = $deadlineDate . ' ' . $deadlineTime; // Tarih ve saat birleştirilir
$client_id = $_POST['client_id'];
$status = $_POST['status'];
$description = $_POST['description'];
$employeeIds = explode(',', $_POST['employeeId']);

// Proje bilgilerini güncelle
$sql = "UPDATE projects SET title = ?, projectType = ?, dateDeadline = ?, client_id = ?, status = ?, description = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssssi", $title, $projectType, $dateDeadline, $client_id, $status, $description, $projectId);

if ($stmt->execute()) {
    // Görevlileri güncelleme işlemleri burada devam eder...
    echo "success";
} else {
    echo "error: " . $conn->error;
}
?>
