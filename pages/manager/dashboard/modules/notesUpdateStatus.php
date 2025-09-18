<?php
require_once('../../../../assets/db/db_connect.php'); // Tam yolu belirtiyoruz

// JSON verisini al
$data = json_decode(file_get_contents('php://input'), true);

// Verileri al
$id = $data['id'];

// Durumu güncelle ve yeni durumu al
$query = "UPDATE notes SET status = IF(status = 1, 0, 1), dateUpdate = NOW() WHERE id = $id";
$result = mysqli_query($conn, $query);

$statusQuery = "SELECT status FROM notes WHERE id = $id";
$statusResult = mysqli_query($conn, $statusQuery);
$statusRow = mysqli_fetch_assoc($statusResult);

// Yanıtı döndür
header('Content-Type: application/json');
echo json_encode(['success' => $result, 'status' => $statusRow['status']]);
?>
