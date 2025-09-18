<?php
include "../../../assets/db/db_connect.php";

// Form verilerini alın
$clientID = isset($_POST['clientID']) ? $_POST['clientID'] : '';
$productNames = isset($_POST['product_name']) ? $_POST['product_name'] : [];
$productQuantities = isset($_POST['product_quantity']) ? $_POST['product_quantity'] : [];
$productPrices = isset($_POST['product_price']) ? $_POST['product_price'] : [];
$productTaxes = isset($_POST['product_tax']) ? $_POST['product_tax'] : [];
$docNumber = isset($_POST['docNumber']) ? $_POST['docNumber'] : '';
$plaka = isset($_POST['plaka']) ? $_POST['plaka'] : '';
$teslimAlan = isset($_POST['teslimAlan']) ? $_POST['teslimAlan'] : '';
$teslimEden = isset($_POST['teslimEden']) ? $_POST['teslimEden'] : '';
$title = isset($_POST['title']) ? $_POST['title'] : '';









// Fatura tablosuna yeni bir kayıt ekleyin
$date = date('Y-m-d H:i:s');
$creator = 'semih'; // Bu değeri dinamik yapabilirsiniz

// Fatura toplam tutarını hesaplayın
$totalAmount = 0;
if(empty($productQuantities) && empty($productPrices) && empty($productTaxes)){
    foreach ($productQuantities as $index => $quantity) {
    $price = $productPrices[$index];
    $tax = $productTaxes[$index];
    $totalAmount += $quantity * $price * (1 + $tax / 100);
}
}else{
    $totalAmount=0;
}

$invoiceSql = $conn->prepare("INSERT INTO dispatch (title,type,  date, clientID, note, amount, dateCreate, creator,docNumber,plaka,teslimAlan,teslimEden) VALUES (?,'purchase', ?, ?, 'Invoice description', ?, ?, ?, ?, ?, ?, ?)");
$invoiceSql->bind_param('ssssssssss', $title, $date, $clientID, $totalAmount, $date, $creator, $docNumber, $plaka, $teslimAlan, $teslimEden);

if ($invoiceSql->execute()) {
    $invoiceID = $conn->insert_id;

    // Her ürün için invoicesProducts tablosuna yeni kayıt ekleyin ve products tablosunu güncelleyin
    foreach ($productNames as $index => $productName) {
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
            $invoicesProductsSql = $conn->prepare("INSERT INTO dispatch_products (invoiceID, productID, description, quantity, price, tax) VALUES (?, ?, ?, ?, ?, ?)");
            $invoicesProductsSql->bind_param('iisdds', $invoiceID, $productID, $productName, $quantity, $price, $tax);

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

        echo '<script>location.replace("./../../../dispatchAdd");</script>';
} else {
    echo "Hata: " . $invoiceSql->error;
}

$conn->close();
?>