<?php
include "../../.././../assets/db/db_connect.php";
include "../../.././../assets/php/randomKey.php";
include "../../.././../assets/php/routing.php";

session_start();

// Form verilerini alın
$clientID = isset($_POST['clientID']) ? $_POST['clientID'] : ''; 
$title = isset($_POST['title']) ? $_POST['title'] : ''; 
$status = isset($_POST['status']) ? $_POST['status'] :'';
$type = isset($_POST['type']) ? $_POST['type'] :'';
$note = isset($_POST['note']) ? $_POST['note'] : '';
$date = isset($_POST['date']) ? $_POST['date'] :date('Y-m-d H:i:s');
$datePayment = isset($_POST['datePayment']) ? $_POST['datePayment'] : '';
$docNumber = isset($_POST['docNumber']) ? $_POST['docNumber'] : '';


$secretKey = generateRandomKey(11);

$productNames = isset($_POST['product_name']) ? $_POST['product_name'] : []; 
$productDescriptions = isset($_POST['product_description']) ? $_POST['product_description'] : []; 
$productQuantities = isset($_POST['product_quantity']) ? $_POST['product_quantity'] : []; 
$productPrices = isset($_POST['product_price']) ? $_POST['product_price'] : []; 
$productTaxes = isset($_POST['product_tax']) ? $_POST['product_tax'] : []; 


// Fatura tablosuna yeni bir kayıt ekleyin
$creator = $_SESSION['name']; // Bu değeri dinamik yapabilirsiniz

// Fatura toplam tutarını hesaplayın
$amount = 0;
foreach ($productQuantities as $index => $quantity) {
    $price = $productPrices[$index];
    $tax = $productTaxes[$index];
    $amount += $quantity * $price * (1 + $tax / 100);
}

$invoiceSql = $conn->prepare("INSERT INTO invoices (type, status, date, clientID, note, amount, creator, title, datePayment, docNumber, secretKey) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$invoiceSql->bind_param('sssssssssss', $type,$status,$date, $clientID, $note, $amount, $creator, $title, $datePayment, $docNumber, $secretKey);

if ($invoiceSql->execute()) {
    $invoiceID = $conn->insert_id;

    // Her ürün için invoicesProducts tablosuna yeni kayıt ekleyin ve products tablosunu güncelleyin
    foreach ($productNames as $index => $productName) {
        $productDescription = $productDescriptions[$index];
        $quantity = $productQuantities[$index];
        $price = $productPrices[$index];
        $tax = $productTaxes[$index];

        // Ürünün productID'sini alın
        $productSql = $conn->prepare("SELECT id, stock FROM products WHERE name=?");
        $productSql->bind_param('s', $productName);
        $productSql->execute();
        $productResult = $productSql->get_result();
        if ($productResult->num_rows > 0) {
            $productData = $productResult->fetch_assoc();
            $productID = $productData['id'];
            $newStock = $productData['stock'] - $quantity;

            // invoicesProducts tablosuna yeni kayıt ekleyin
            $invoicesProductsSql = $conn->prepare("INSERT INTO invoices_products (invoiceID, productID, name, description, quantity, price, tax) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $invoicesProductsSql->bind_param('iissdds', $invoiceID, $productID, $productName, $productDescription, $quantity, $price, $tax);

            if ($invoicesProductsSql->execute()) {
                // products tablosunu güncelleyin (stok düşürme)
                $productsSql = $conn->prepare("UPDATE products SET stock = ? WHERE id = ?");
                $productsSql->bind_param('ii', $newStock, $productID);
                if ($productsSql->execute() !== TRUE) {
                    echo "Hata: " . $productsSql->error;
                }
            } else {
                echo "Hata: " . $invoicesProductsSql->error;
            }
        } else {
            echo "Ürün bulunamadı: " . $productName;
        }
    }

    go('/invoiceAdd',0);
} else {
    echo "Hata: " . $invoiceSql->error;
}

$conn->close();
?>
