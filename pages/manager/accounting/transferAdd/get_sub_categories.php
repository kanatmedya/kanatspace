<?php
include '../../../../assets/db/db_connect.php';
global $conn;

$categoryId = $_POST['categoryId'];

$sql = "SELECT * FROM accounting_category WHERE categorySub = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $categoryId);
$stmt->execute();
$result = $stmt->get_result();

$options = '<option value="-1">Alt Kategori Seçiniz</option>';
while ($row = $result->fetch_assoc()) {
    $options .= '<option value="' . $row['id'] . '">' . $row['category'] . '</option>';
}

echo $options;
?>
