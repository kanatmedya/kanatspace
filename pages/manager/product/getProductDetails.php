<?php

include ("../../../db/db_connect.php");

header('Content-Type: application/json');

if (isset($_POST['name'])) {
    $productName = $_POST['name'];

    // Example query to fetch details based on product name
    $query = "SELECT * FROM products WHERE name = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $productName);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Prepare data to send back as JSON
        $response = array(
            'status' => $row['status'],
            'visibility' => $row['visibility'],
            'main_category' => $row['main_category'],
            'sub_category' => $row['sub_category'],
            'description' => $row['description'],
            'supplier' => $row['supplier'],
            'brand' => $row['brand'],
            'barcode' => $row['barcode'],
            'stock_code' => $row['stock_code'],
            'purchase_price' => $row['purchase_price'],
            'purchase_tax' => $row['purchase_tax'],
            'sale_price' => $row['sale_price'],
            'sale_tax' => $row['sale_tax']
            // Add more fields as necessary
        );

        echo json_encode($response);
    } else {
        echo json_encode(array('error' => 'Product not found'));
    }
} else {
    echo json_encode(array('error' => 'Invalid request'));
}
?>
