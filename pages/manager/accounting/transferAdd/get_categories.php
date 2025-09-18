<?php

include '../../../../assets/db/db_connect.php';
global $conn;

$type = $_POST['type'];

$sql = "SELECT * FROM accounting_category WHERE type = ? AND categorySub=''";
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die("Sorgu hatası: " . $conn->error);
}

$stmt->bind_param("s", $type);
$stmt->execute();
$result = $stmt->get_result();

if ($result === false) {
    die("Sonuç hatası: " . $stmt->error);
}

$options = '<option value="-1">Kategori Seçiniz</option>';
while ($row = $result->fetch_assoc()) {
    $options .= '<option value="' . $row['id'] . '">' . $row['category'] . '</option>';
}

echo $options;
?>
