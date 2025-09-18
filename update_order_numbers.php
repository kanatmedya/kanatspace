<?php
session_start();
include "assets/db/db_connect.php";

$data = json_decode(file_get_contents('php://input'), true);

if (!empty($data['order'])) {
    foreach ($data['order'] as $index => $id) {
        $stmt = $pdo->prepare("UPDATE notes SET orderNumber = :orderNumber WHERE id = :id");
        $stmt->execute(['orderNumber' => $index + 1, 'id' => $id]);
    }
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error']);
}
?>