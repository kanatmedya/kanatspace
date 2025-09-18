<?php
include('../../../../assets/db/db_connect.php'); // Tam yolu belirtiyoruz

// Session'daki kullanıcı adını alın
session_start();
$username = $_SESSION['name'];

// Geçerli tarihi ve bir hafta önceki tarihi alın
$currentDate = date('Y-m-d H:i:s');
$oneWeekAgo = date('Y-m-d H:i:s', strtotime('-1 week'));

$query = "SELECT * FROM notes WHERE user = '$username' AND (status = 0 OR (status = 1 AND dateUpdate > '$oneWeekAgo')) ORDER BY status ASC, orderNumber DESC, dateUpdate DESC";
$result = mysqli_query($conn, $query);

$notes = array();
while ($row = mysqli_fetch_assoc($result)) {
    $notes[] = $row;
}

// JSON formatında çıktıyı döndür
header('Content-Type: application/json');
echo json_encode($notes);
?>
