<?php

include("../../../../assets/db/db_connect.php");

if (isset($_POST['searchTerm'])) {
    $searchTerm = $_POST['searchTerm'];
    $sql = "SELECT name, stockCode, price, tax FROM products WHERE name LIKE '%$searchTerm%' OR stockCode LIKE '%$searchTerm%'";
    $result = $conn->query($sql);

    $products = array();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
    }
    echo json_encode($products);
}

$conn->close();
?>
