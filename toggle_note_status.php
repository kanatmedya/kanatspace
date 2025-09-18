<?php
session_start();
include "assets/db/db_connect.php";

$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id'];

$stmt = $pdo->prepare("SELECT status FROM notes WHERE id = :id");
$stmt->execute(['id' => $id]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

$newStatus = $row['status'] == 1 ? 0 : 1;
$dateUpdate = date('Y-m-d H:i:s');

$stmt = $pdo->prepare("UPDATE notes SET status = :status, dateUpdate = :dateUpdate WHERE id = :id");
$stmt->execute(['status' => $newStatus, 'dateUpdate' => $dateUpdate, 'id' => $id]);

echo json_encode(['success' => true, 'status' => $newStatus]);
?>