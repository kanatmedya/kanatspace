<?php
session_start();
include "assets/db/db_connect.php";

$data = json_decode(file_get_contents('php://input'), true);
$user = $_SESSION['name']; // session'daki name değeri
$note = $data['note'];
$dateCreate = date('Y-m-d H:i:s');
$status = 1;

// Order number için en yüksek değeri al
$stmt = $pdo->prepare("SELECT MAX(orderNumber) as maxOrderNumber FROM notes WHERE user = :user");
$stmt->execute(['user' => $user]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$orderNumber = $row['maxOrderNumber'] + 1;

$stmt = $pdo->prepare("INSERT INTO notes (user, note, dateCreate, status, orderNumber) VALUES (:user, :note, :dateCreate, :status, :orderNumber)");
$stmt->execute(['user' => $user, 'note' => $note, 'dateCreate' => $dateCreate, 'status' => $status, 'orderNumber' => $orderNumber]);

$newNoteId = $pdo->lastInsertId();

echo json_encode(['success' => true, 'note' => [
    'id' => $newNoteId,
    'note' => $note,
    'dateCreate' => $dateCreate,
    'status' => $status
]]);
?>