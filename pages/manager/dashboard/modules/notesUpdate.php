<?php
require_once('../../../../assets/db/db_connect.php'); // Tam yolu belirtiyoruz

// JSON verisini al
$data = json_decode(file_get_contents('php://input'), true);

// Verileri al
$id = $data['id'];
$note = $data['note'];

// Notu güncelle
$query = "UPDATE notes SET note = '$note', dateUpdate = NOW() WHERE id = $id";
$result = mysqli_query($conn, $query);

// Yanıtı döndür
header('Content-Type: application/json');
echo json_encode(['success' => $result]);
?>
