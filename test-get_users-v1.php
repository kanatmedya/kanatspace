<?php

include 'assets/db/db_connect.php';
global $conn;

$term = $_GET['term']; // Arama terimi

// SQL sorgusu
$sql = "SELECT id, name, surname FROM ac_users WHERE userType IN (1, 2) AND (name LIKE ? OR surname LIKE ?)";
$stmt = $conn->prepare($sql);
$searchTerm = "%" . $term . "%";
$stmt->bind_param("ss", $searchTerm, $searchTerm);
$stmt->execute();
$result = $stmt->get_result();

$users = array();
while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}

// Kullanıcıların JSON formatında dönmesi
if (empty($users)) {
    // Eğer eşleşme yoksa "Eşleşen Veri Yok" mesajı döndür
    echo json_encode([["label" => "Eşleşen Veri Yok", "value" => ""]]);
} else {
    echo json_encode($users);
}

$stmt->close();
$conn->close();
?>
