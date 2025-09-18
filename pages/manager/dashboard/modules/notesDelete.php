<?php
require_once('../../../../assets/db/db_connect.php'); // Tam yolu belirtiyoruz

// JSON verisini al
$data = json_decode(file_get_contents('php://input'), true);

// Verileri al
$id = $data['id'];

// Notu sil
$query = "DELETE FROM notes WHERE id = $id";
$result = mysqli_query($conn, $query);

// Yanıtı döndür
header('Content-Type: application/json');
echo json_encode(['success' => $result]);
?>
