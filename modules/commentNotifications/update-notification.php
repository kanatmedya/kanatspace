<?php
// update-notification.php

header('Content-Type: application/json');

include '../../assets/db/db_connect.php';

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['id'])) {
    $id = $data['id'];

    // Bildirimi okundu olarak işaretleyin
    $stmt = $conn->prepare("UPDATE nt_comments SET readed = 1 WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param('i', $id);
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to execute query: ' . $stmt->error]);
        }
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to prepare statement: ' . $conn->error]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'No ID provided']);
}

$conn->close();
?>
