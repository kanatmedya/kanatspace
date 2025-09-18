<?php
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT'] . '/');
}
include ROOT_PATH . "assets/db/db_connect.php";
global $conn;

$type = $_POST['type']; // İşlem türünü al
$categories = '<option value="-1">Lütfen Kategori Seçiniz</option>';

$sql = "SELECT * FROM accounting_category WHERE type='$type' AND status='main'"; // Ana kategorileri getir
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categories .= '<option value="' . $row['id'] . '">' . $row['category'] . '</option>';
    }
} else {
    $categories = '<option value="-1">Kategori bulunamadı</option>';
}

echo $categories;
?>
