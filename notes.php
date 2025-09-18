<?php
session_start();
include "assets/db/db_connect.php";

$user = $_SESSION['name']; // session'daki name değeri

$stmt = $pdo->prepare("SELECT * FROM notes WHERE user = :user AND status = 1 ORDER BY orderNumber ASC");
$stmt->execute(['user' => $user]);
$notes = $stmt->fetchAll(PDO::FETCH_ASSOC);

$completedStmt = $pdo->prepare("SELECT * FROM notes WHERE user = :user AND status = 0 AND dateUpdate > DATE_SUB(NOW(), INTERVAL 1 WEEK) ORDER BY dateUpdate DESC");
$completedStmt->execute(['user' => $user]);
$completedNotes = $completedStmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode(array_merge($notes, $completedNotes));
?>