
<?php

include ("../../../db/db_connect.php");

if (isset($_POST['name'])) {
    $name = $_POST['name'];
    $sorgu = "SELECT * FROM products WHERE name = '$name'";
    $result = $conn->query($sorgu);

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
        echo json_encode($product);
    } else {
        echo json_encode(['error' => 'Ürün bulunamadı']);
    }
} else {
    echo json_encode(['error' => 'Geçersiz istek']);
}
$conn->close();
?>