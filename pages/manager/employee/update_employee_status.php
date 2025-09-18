<?php

include "../../../assets/db/db_connect.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $status = $_POST['status'];
    $date = isset($_POST['date']) ? $_POST['date'] : date('Y-m-d'); // Tarih gönderilmezse bugünün tarihi

    $update_sql = "UPDATE emp_info SET workStatus = ? WHERE id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("si", $status, $id);
    $result = $stmt->execute();

    $log_sql = "INSERT INTO emp_worklogs (employeeID, workStatus, date) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($log_sql);
    $stmt->bind_param("iss", $id, $status, $date);
    $log_result = $stmt->execute();

    if ($result && $log_result) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }

    $stmt->close();
}

$conn->close();
?>
