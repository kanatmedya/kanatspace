<?php
require_once('../../../../assets/db/db_connect.php'); // Tam yolu belirtiyoruz

// JSON verilerini al
$data = json_decode(file_get_contents('php://input'), true);

$note = $data['note'];
$orderNumber = $data['orderNumber'];

// Session başlat
session_start();
$username = $_SESSION['name'];

$query = "INSERT INTO notes (user, note, orderNumber, status) VALUES ('$username', '$note', $orderNumber, 0)";
$result = mysqli_query($conn, $query);

echo json_encode(['success' => $result]);
?>
