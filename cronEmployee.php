<?php

error_reporting(E_ALL); 
ini_set('display_errors', 1);

date_default_timezone_set('Europe/Istanbul');

include("assets/db/db_connect.php");

// SQL sorgusu
$sql = "UPDATE users_employee SET workStatus = 'waiting'";

// Sorguyu çalıştır
if ($conn->query($sql) === TRUE) {
    echo "Kayıtlar başarıyla güncellendi.";
} else {
    echo "Hata: " . $conn->error;
}

// Bağlantıyı kapat
$conn->close();
?>
