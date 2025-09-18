<?php

if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT'] . '/');
}
include ROOT_PATH . "assets/db/db_connect.php";
global $conn;

// Giriş verilerini alın ve JSON olarak parse edin
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Gelen verilerin loglanması
error_log("Raw input: " . $input);
error_log("Parsed data: " . print_r($data, true));

$taskId = isset($data['id']) ? $data['id'] : null;
$newStatus = isset($data['status']) ? $data['status'] : null;
$newOrder = isset($data['order']) ? $data['order'] : null;

// Gelen verileri kontrol edelim
error_log("Received data: Task ID: $taskId, New Status: $newStatus, New Order: $newOrder");

if ($taskId && $newStatus && $newOrder) {
    // SQL sorgusu
    $sql = "UPDATE projects SET status = ?, displayOrder = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        error_log("Error preparing statement: " . $conn->error);
        echo json_encode(['success' => false, 'error' => $conn->error]);
        exit;
    }
    $stmt->bind_param("sii", $newStatus, $newOrder, $taskId);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode(['success' => true]);
    } else {
        error_log("No rows affected or error: " . $stmt->error);
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }

    $stmt->close();
} else {
    error_log("Invalid input: " . json_encode($data));
    echo json_encode(['success' => false, 'error' => 'Invalid input']);
}

$conn->close();
?>
