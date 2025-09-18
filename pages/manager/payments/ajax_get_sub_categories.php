<?php
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT'] . '/');
}
include ROOT_PATH . "assets/db/db_connect.php";
global $conn;

$categoryId = $_POST['categoryId']; // Ana kategori seçilir
$subCategories = '<option value="-1">Lütfen Alt Kategori Seçiniz</option>';

$sql = "SELECT * FROM accounting_category WHERE categorySub='$categoryId' AND status='sub'"; // Alt kategorileri alıyoruz
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    $subCategories .= '<option value="' . $row['id'] . '">' . $row['category'] . '</option>';
}

echo $subCategories;
?>
