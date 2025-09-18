<?php

include("../../../../assets/db/db_connect.php");

$name = $_POST['name'];

$stmt = $conn->prepare("SELECT * FROM products WHERE name = ?");
$stmt->bind_param("s", $name);
$stmt->execute();
$result = $stmt->get_result();
$product_data = $result->fetch_assoc();
$stmt->close();
$conn->close();

echo json_encode($product_data);
?>
