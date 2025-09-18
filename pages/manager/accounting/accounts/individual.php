<?php

echo'<script>
    document.title = "Bireysel Cariler | '.$companyName.'";
</script>';

//Tablodaki Bilgiler İçin Sorgu
$sqlTotal = "SELECT id FROM users_client WHERE accountType = 'individual'";

$resultTotal = $conn->query($sqlTotal);
$totalResults = $resultTotal->num_rows;


// Tablo sayfalarını oluşturmak için yapılacak sorgular
// Tüm ürünlerin sayısını alacak sorgu
$countSql = "SELECT COUNT(*) AS total FROM users_client WHERE accountType = 'individual'";
$countResult = $conn->query($countSql);
$totalItems = $countResult->fetch_assoc()['total'];

// Sayfa numarasını ve sayfa başına ürün sayısını alın
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$itemsPerPage = isset($_GET['queue']) ? (int) $_GET['queue'] : 25; // Her sayfa için gösterilecek ürün sayısı

// Limit ve offset hesaplaması
$offset = ($page - 1) * $itemsPerPage;
$sql = "SELECT 
    uc.id,
    uc.username,
    uc.profilePicture,
    uc.accountType,
    uc.taxNumber,
    uc.name,

    -- invoiceTotal: invoices ve invoices_products tablolarındaki ilişkili satırları hesaplar
    COALESCE(
        SUM(
            CASE 
                WHEN i.type = 'sale' 
                     AND (i.status = 'completed' OR i.status = 'onOrder') 
                     AND i.clientID = uc.id 
                THEN (ip.price * ip.quantity * (ip.tax / 100 + 1)) -- Fiyat x Miktar x (Vergi Oranı + 1)
                ELSE 0 
            END
        ), 0
    ) AS invoiceTotal,

    -- paymentTotal: accounting_transactions tablosunda sender eşleşenlerin toplamını alır
    COALESCE(
        (SELECT SUM(amount) 
         FROM accounting_transactions 
         WHERE sender = uc.username), 0
    ) AS paymentTotal,

    -- refundsTotal: accounting_transactions tablosunda reciever eşleşenlerin toplamını alır
    COALESCE(
        (SELECT SUM(amount) 
         FROM accounting_transactions 
         WHERE reciever = uc.username), 0
    ) AS refundsTotal,

    -- balance: invoiceTotal - paymentTotal + refundsTotal hesaplamasını doğrudan yapıyoruz
    COALESCE(
        SUM(
            CASE 
                WHEN i.type = 'sale' 
                     AND (i.status = 'completed' OR i.status = 'onOrder') 
                     AND i.clientID = uc.id 
                THEN (ip.price * ip.quantity * (ip.tax / 100 + 1)) -- Fiyat x Miktar x (Vergi Oranı + 1)
                ELSE 0 
            END
        ), 0
    ) - COALESCE(
        (SELECT SUM(amount) 
         FROM accounting_transactions 
         WHERE sender = uc.username), 0
    ) + COALESCE(
        (SELECT SUM(amount) 
         FROM accounting_transactions 
         WHERE reciever = uc.username), 0
    ) AS balance

FROM 
    users_client uc
LEFT JOIN 
    invoices i ON uc.id = i.clientID
LEFT JOIN 
    invoices_products ip ON i.id = ip.invoiceID
WHERE 
    uc.accountType = 'individual'
GROUP BY 
    uc.id, uc.username, uc.accountType, uc.profilePicture, uc.taxNumber, uc.name

ORDER BY 
    -- balance hesaplaması için direkt ifadeyi kullanıyoruz
    COALESCE(
        SUM(
            CASE 
                WHEN i.type = 'sale' 
                     AND (i.status = 'completed' OR i.status = 'onOrder') 
                     AND i.clientID = uc.id 
                THEN (ip.price * ip.quantity * (ip.tax / 100 + 1)) -- Fiyat x Miktar x (Vergi Oranı + 1)
                ELSE 0 
            END
        ), 0
    ) - COALESCE(
        (SELECT SUM(amount) 
         FROM accounting_transactions 
         WHERE sender = uc.username), 0
    ) + COALESCE(
        (SELECT SUM(amount) 
         FROM accounting_transactions 
         WHERE reciever = uc.username), 0
    ) DESC, 
    CASE 
        WHEN COALESCE(
            SUM(
                CASE 
                    WHEN i.type = 'sale' 
                         AND (i.status = 'completed' OR i.status = 'onOrder') 
                         AND i.clientID = uc.id 
                    THEN (ip.price * ip.quantity * (ip.tax / 100 + 1)) -- Fiyat x Miktar x (Vergi Oranı + 1)
                    ELSE 0 
                END
            ), 0
        ) - COALESCE(
            (SELECT SUM(amount) 
             FROM accounting_transactions 
             WHERE sender = uc.username), 0
        ) + COALESCE(
            (SELECT SUM(amount) 
             FROM accounting_transactions 
             WHERE reciever = uc.username), 0
        ) = 0 THEN 1
        ELSE 0
    END,
    -- balance ASC
    COALESCE(
        SUM(
            CASE 
                WHEN i.type = 'sale' 
                     AND (i.status = 'completed' OR i.status = 'onOrder') 
                     AND i.clientID = uc.id 
                THEN (ip.price * ip.quantity * (ip.tax / 100 + 1)) -- Fiyat x Miktar x (Vergi Oranı + 1)
                ELSE 0 
            END
        ), 0
    ) - COALESCE(
        (SELECT SUM(amount) 
         FROM accounting_transactions 
         WHERE sender = uc.username), 0
    ) + COALESCE(
        (SELECT SUM(amount) 
         FROM accounting_transactions 
         WHERE reciever = uc.username), 0
    ) ASC

LIMIT $itemsPerPage OFFSET $offset;
";
$result = $conn->query($sql);

$last = $offset + $itemsPerPage;

if ($last > $totalResults) {
    $last = $totalResults;
}


//Yardımcı Fonksiyonlar
function getClientInvoiceTotalOffer($clientID) {
    global $conn;
    
    $total = 0;

    // İlk olarak clientID'ye göre invoices tablosundan fatura ID'lerini alıyoruz
    $query = "SELECT id FROM invoices WHERE clientID = ? AND status='onOffer'";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $clientID);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($invoice = $result->fetch_assoc()) {
        $invoiceID = $invoice['id'];
        
        // Şimdi bu invoiceID'ye göre invoices_products tablosundan price, quantity ve tax'ları alıyoruz
        $productQuery = "SELECT price, quantity, tax FROM invoices_products WHERE invoiceID = ?";
        $productStmt = $conn->prepare($productQuery);
        $productStmt->bind_param('i', $invoiceID);
        $productStmt->execute();
        $productResult = $productStmt->get_result();
        
        while ($product = $productResult->fetch_assoc()) {
            $price = $product['price'];
            $quantity = $product['quantity'];
            $tax = $product['tax'];
            
            // Toplam hesaplama: (price * quantity) + ((price * quantity) * tax / 100)
            $subtotal = ($price * $quantity) + (($price * $quantity) * $tax / 100);
            $total += $subtotal;
        }
    }

    // Sonucu virgülden sonra 2 basamak olacak şekilde biçimlendir ve return et
    return number_format($total, 2, '.', '');
}

function getClientInvoiceTotal($clientID) {
    global $conn;
    
    $total = 0;

    // İlk olarak clientID'ye göre invoices tablosundan fatura ID'lerini alıyoruz
    $query = "SELECT id FROM invoices WHERE clientID = ? AND type = 'sale' AND (status = 'completed' or status = 'onOrder')";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $clientID);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($invoice = $result->fetch_assoc()) {
        $invoiceID = $invoice['id'];
        
        // Şimdi bu invoiceID'ye göre invoices_products tablosundan price, quantity ve tax'ları alıyoruz
        $productQuery = "SELECT price, quantity, tax FROM invoices_products WHERE invoiceID = ?";
        $productStmt = $conn->prepare($productQuery);
        $productStmt->bind_param('i', $invoiceID);
        $productStmt->execute();
        $productResult = $productStmt->get_result();
        
        while ($product = $productResult->fetch_assoc()) {
            $price = $product['price'];
            $quantity = $product['quantity'];
            $tax = $product['tax'];
            
            // Toplam hesaplama: (price * quantity) + ((price * quantity) * tax / 100)
            $subtotal = ($price * $quantity) + (($price * $quantity) * $tax / 100);
            $total += $subtotal;
        }
    }

    // Sonucu virgülden sonra 2 basamak olacak şekilde biçimlendir ve return et
    return number_format($total, 2, '.', '');
}

function getUserTotalPayments($username) {
    global $conn;
    
    // Verilen username ile sender alanı eşleşen toplam amount'u bul
    $query = "SELECT SUM(amount) as total FROM accounting_transactions WHERE sender = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $data = $result->fetch_assoc();
    
    // Sonucu virgülden sonra 2 basamak olacak şekilde biçimlendir ve return et
    return number_format($data['total'] ? $data['total'] : 0, 2, '.', '');
}

function getUserTotalRefunds($username) {
    global $conn;
    
    // Verilen username ile sender alanı eşleşen toplam amount'u bul
    $query = "SELECT SUM(amount) as total FROM accounting_transactions WHERE reciever = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $data = $result->fetch_assoc();
    
    // Sonucu virgülden sonra 2 basamak olacak şekilde biçimlendir ve return et
    return number_format($data['total'] ? $data['total'] : 0, 2, '.', '');
}

function formatValue($value) {
    // Eğer değer 0 ise "-" döndür
    if ($value == 0) {
        return "-";
    }
    
    // Eğer 0 değilse, değeri 2 ondalıklı olarak döndür
    return number_format($value, 2, '.', '');
}

function formatValueCC($value,$cc) {
    // Eğer değer 0 ise "-" döndür
    if ($value == 0) {
        return "-";
    }
    
    // Sayıyı 2 ondalıklı formatta, virgülle ayırarak döndür
    $formattedValue = number_format($value, 2, ',', '.');
    
    // Türk Lirası simgesi ekle
    return $formattedValue . $cc;
}

// Total Sales
$queryTotalSales = "
    SELECT SUM(ip.price * ip.quantity * (ip.tax / 100 + 1)) AS totalSales
    FROM users_client uc
    JOIN invoices i ON uc.id = i.clientID
    JOIN invoices_products ip ON i.id = ip.invoiceID
    WHERE uc.accountType = 'individual'
      AND i.type = 'sale'
      AND i.status IN ('completed', 'onOrder')
";
$stmtTotalSales = $conn->prepare($queryTotalSales);
$stmtTotalSales->execute();
$resultTotalSales = $stmtTotalSales->get_result();
$dataTotalSales = $resultTotalSales->fetch_assoc();
$totalSales = $dataTotalSales['totalSales'] ? $dataTotalSales['totalSales'] : 0;

// Total Sales Number
$queryTotalSalesNum = "
    SELECT COUNT(ip.invoiceID) AS totalSalesNum
    FROM users_client uc
    JOIN invoices i ON uc.id = i.clientID
    JOIN invoices_products ip ON i.id = ip.invoiceID
    WHERE uc.accountType = 'individual'
      AND i.type = 'sale'
      AND i.status IN ('completed', 'onOrder')
";
$stmtTotalSalesNum = $conn->prepare($queryTotalSalesNum);
$stmtTotalSalesNum->execute();
$resultTotalSalesNum = $stmtTotalSalesNum->get_result();
$dataTotalSalesNum = $resultTotalSalesNum->fetch_assoc();
$totalSalesNum = $dataTotalSalesNum['totalSalesNum'] ? $dataTotalSalesNum['totalSalesNum'] : 0;

// Total Offers
$queryTotalOffers = "
    SELECT SUM(ip.price * ip.quantity * (ip.tax / 100 + 1)) AS totalOffers
    FROM users_client uc
    JOIN invoices i ON uc.id = i.clientID
    JOIN invoices_products ip ON i.id = ip.invoiceID
    WHERE uc.accountType = 'individual'
      AND i.type = 'sale'
      AND i.status = 'onOffer'
";
$stmtTotalOffers = $conn->prepare($queryTotalOffers);
$stmtTotalOffers->execute();
$resultTotalOffers = $stmtTotalOffers->get_result();
$dataTotalOffers = $resultTotalOffers->fetch_assoc();
$totalOffers = $dataTotalOffers['totalOffers'] ? $dataTotalOffers['totalOffers'] : 0;

// Total Offers Number
$queryTotalOffersNum = "
    SELECT COUNT(ip.invoiceID) AS totalOffersNum
    FROM users_client uc
    JOIN invoices i ON uc.id = i.clientID
    JOIN invoices_products ip ON i.id = ip.invoiceID
    WHERE uc.accountType = 'individual'
      AND i.type = 'sale'
      AND i.status = 'onOffer'
";
$stmtTotalOffersNum = $conn->prepare($queryTotalOffersNum);
$stmtTotalOffersNum->execute();
$resultTotalOffersNum = $stmtTotalOffersNum->get_result();
$dataTotalOffersNum = $resultTotalOffersNum->fetch_assoc();
$totalOffersNum = $dataTotalOffersNum['totalOffersNum'] ? $dataTotalOffersNum['totalOffersNum'] : 0;

// Total Payments
$queryTotalPayments = "
    SELECT SUM(atr.amount) AS totalPayments
    FROM users_client uc
    JOIN accounting_transactions atr ON uc.username = atr.sender
    WHERE uc.accountType = 'individual'
";
$stmtTotalPayments = $conn->prepare($queryTotalPayments);
$stmtTotalPayments->execute();
$resultTotalPayments = $stmtTotalPayments->get_result();
$dataTotalPayments = $resultTotalPayments->fetch_assoc();
$totalPayments = $dataTotalPayments['totalPayments'] ? $dataTotalPayments['totalPayments'] : 0;

// Total Payments Number
$queryTotalPaymentsNum = "
    SELECT COUNT(atr.id) AS totalPaymentsNum
    FROM users_client uc
    JOIN accounting_transactions atr ON uc.username = atr.sender
    WHERE uc.accountType = 'individual'
";
$stmtTotalPaymentsNum = $conn->prepare($queryTotalPaymentsNum);
$stmtTotalPaymentsNum->execute();
$resultTotalPaymentsNum = $stmtTotalPaymentsNum->get_result();
$dataTotalPaymentsNum = $resultTotalPaymentsNum->fetch_assoc();
$totalPaymentsNum = $dataTotalPaymentsNum['totalPaymentsNum'] ? $dataTotalPaymentsNum['totalPaymentsNum'] : 0;

// Total Refunds
$queryTotalRefunds = "
    SELECT SUM(atr.amount) AS totalRefunds
    FROM users_client uc
    JOIN accounting_transactions atr ON uc.username = atr.reciever
    WHERE uc.accountType = 'individual'
";
$stmtTotalRefunds = $conn->prepare($queryTotalRefunds);
$stmtTotalRefunds->execute();
$resultTotalRefunds = $stmtTotalRefunds->get_result();
$dataTotalRefunds = $resultTotalRefunds->fetch_assoc();
$totalRefunds = $dataTotalRefunds['totalRefunds'] ? $dataTotalRefunds['totalRefunds'] : 0;


$balance = formatValueCC($totalSales-$totalPayments+$totalRefunds,"₺");
$totalSales = formatValueCC($totalSales,"₺");
$totalOffers = formatValueCC($totalOffers,"₺");
$totalPayments = formatValueCC($totalPayments,"₺");

?>

<div class="animate__animated p-6" :class="[$store.app.animation]">
    <h1 style="font-size:24px;margin-right:20px">Müşteri Carileri</h1><br>
    
        <div class="mb-6 grid gap-6 sm:grid-cols-2 xl:grid-cols-4">
        
        <div class="panel h-full p-0">
            <div class="flex p-5">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-primary/10 text-primary dark:bg-primary dark:text-white-light">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5">
                        <circle cx="12" cy="6" r="4" stroke="currentColor" stroke-width="1.5"></circle>
                        <path opacity="0.5" d="M18 9C19.6569 9 21 7.88071 21 6.5C21 5.11929 19.6569 4 18 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                        <path opacity="0.5" d="M6 9C4.34315 9 3 7.88071 3 6.5C3 5.11929 4.34315 4 6 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                        <ellipse cx="12" cy="17" rx="6" ry="4" stroke="currentColor" stroke-width="1.5"></ellipse>
                        <path opacity="0.5" d="M20 19C21.7542 18.6153 23 17.6411 23 16.5C23 15.3589 21.7542 14.3847 20 14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                        <path opacity="0.5" d="M4 19C2.24575 18.6153 1 17.6411 1 16.5C1 15.3589 2.24575 14.3847 4 14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                    </svg>
                </div>
                <div class="font-semibold ltr:ml-3 rtl:mr-3">
                    <p class="text-xl dark:text-white-light"><?php echo $totalResults?></p>
                    <h5 class="text-xs text-[#506690]">Müşteri</h5>
                </div>
            </div>
        </div>

        <div class="panel h-full p-0">
            <div class="flex p-5">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-danger/10 text-danger dark:bg-danger dark:text-white-light">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5">
                        <path d="M10.0464 14C8.54044 12.4882 8.67609 9.90087 10.3494 8.22108L15.197 3.35462C16.8703 1.67483 19.4476 1.53865 20.9536 3.05046C22.4596 4.56228 22.3239 7.14956 20.6506 8.82935L18.2268 11.2626" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                        <path opacity="0.5" d="M13.9536 10C15.4596 11.5118 15.3239 14.0991 13.6506 15.7789L11.2268 18.2121L8.80299 20.6454C7.12969 22.3252 4.55237 22.4613 3.0464 20.9495C1.54043 19.4377 1.67609 16.8504 3.34939 15.1706L5.77323 12.7373" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                    </svg>
                </div>
                <div class="font-semibold ltr:ml-3 rtl:mr-3">
                    <p class="text-xl dark:text-white-light"><?php echo $totalSalesNum; ?> <span class="orderTotal"><?php echo $totalOffersNum; ?></span></p>
                    <h5 class="text-xs text-[#506690]">Satış (Adet) <span class="orderTotal">Sipariş</span></h5>
                </div>
            </div>
        </div>

        <div class="panel h-full p-0">
            <div class="flex p-5">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-success/10 text-success dark:bg-success dark:text-white-light">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5">
                        <path d="M10 22C14.4183 22 18 18.4183 18 14C18 9.58172 14.4183 6 10 6C5.58172 6 2 9.58172 2 14C2 15.2355 2.28008 16.4056 2.7802 17.4502C2.95209 17.8093 3.01245 18.2161 2.90955 18.6006L2.58151 19.8267C2.32295 20.793 3.20701 21.677 4.17335 21.4185L5.39939 21.0904C5.78393 20.9876 6.19071 21.0479 6.54976 21.2198C7.5944 21.7199 8.76449 22 10 22Z" stroke="currentColor" stroke-width="1.5"></path>
                        <path opacity="0.5" d="M18 14.5018C18.0665 14.4741 18.1324 14.4453 18.1977 14.4155C18.5598 14.2501 18.9661 14.1882 19.3506 14.2911L19.8267 14.4185C20.793 14.677 21.677 13.793 21.4185 12.8267L21.2911 12.3506C21.1882 11.9661 21.2501 11.5598 21.4155 11.1977C21.7908 10.376 22 9.46242 22 8.5C22 4.91015 19.0899 2 15.5 2C12.7977 2 10.4806 3.64899 9.5 5.9956" stroke="currentColor" stroke-width="1.5"></path>
                        <g opacity="0.5">
                            <path d="M7.5 14C7.5 14.5523 7.05228 15 6.5 15C5.94772 15 5.5 14.5523 5.5 14C5.5 13.4477 5.94772 13 6.5 13C7.05228 13 7.5 13.4477 7.5 14Z" fill="currentColor"></path>
                            <path d="M11 14C11 14.5523 10.5523 15 10 15C9.44772 15 9 14.5523 9 14C9 13.4477 9.44772 13 10 13C10.5523 13 11 13.4477 11 14Z" fill="currentColor"></path>
                            <path d="M14.5 14C14.5 14.5523 14.0523 15 13.5 15C12.9477 15 12.5 14.5523 12.5 14C12.5 13.4477 12.9477 13 13.5 13C14.0523 13 14.5 13.4477 14.5 14Z" fill="currentColor"></path>
                        </g>
                    </svg>
                </div>
                <div class="font-semibold ltr:ml-3 rtl:mr-3">
                    <p class="text-xl dark:text-white-light"><?php echo $totalSales; ?> <span class="orderTotal"><?php echo $totalOffers; ?></span></p>
                    <h5 class="text-xs text-[#506690]">Satış (₺) <span class="orderTotal">Sipariş</span></h5>
                </div>
            </div>
        </div>

        <div class="panel h-full p-0">
            <div class="flex p-5">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-success/10 text-success dark:bg-success dark:text-white-light">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5">
                        <path d="M10 22C14.4183 22 18 18.4183 18 14C18 9.58172 14.4183 6 10 6C5.58172 6 2 9.58172 2 14C2 15.2355 2.28008 16.4056 2.7802 17.4502C2.95209 17.8093 3.01245 18.2161 2.90955 18.6006L2.58151 19.8267C2.32295 20.793 3.20701 21.677 4.17335 21.4185L5.39939 21.0904C5.78393 20.9876 6.19071 21.0479 6.54976 21.2198C7.5944 21.7199 8.76449 22 10 22Z" stroke="currentColor" stroke-width="1.5"></path>
                        <path opacity="0.5" d="M18 14.5018C18.0665 14.4741 18.1324 14.4453 18.1977 14.4155C18.5598 14.2501 18.9661 14.1882 19.3506 14.2911L19.8267 14.4185C20.793 14.677 21.677 13.793 21.4185 12.8267L21.2911 12.3506C21.1882 11.9661 21.2501 11.5598 21.4155 11.1977C21.7908 10.376 22 9.46242 22 8.5C22 4.91015 19.0899 2 15.5 2C12.7977 2 10.4806 3.64899 9.5 5.9956" stroke="currentColor" stroke-width="1.5"></path>
                        <g opacity="0.5">
                            <path d="M7.5 14C7.5 14.5523 7.05228 15 6.5 15C5.94772 15 5.5 14.5523 5.5 14C5.5 13.4477 5.94772 13 6.5 13C7.05228 13 7.5 13.4477 7.5 14Z" fill="currentColor"></path>
                            <path d="M11 14C11 14.5523 10.5523 15 10 15C9.44772 15 9 14.5523 9 14C9 13.4477 9.44772 13 10 13C10.5523 13 11 13.4477 11 14Z" fill="currentColor"></path>
                            <path d="M14.5 14C14.5 14.5523 14.0523 15 13.5 15C12.9477 15 12.5 14.5523 12.5 14C12.5 13.4477 12.9477 13 13.5 13C14.0523 13 14.5 13.4477 14.5 14Z" fill="currentColor"></path>
                        </g>
                    </svg>
                </div>
                <div class="font-semibold ltr:ml-3 rtl:mr-3">
                    <p class="text-xl dark:text-white-light"><?php echo $balance; ?></p>
                    <h5 class="text-xs text-[#506690]">Kalan (₺)<!-- accounting_transactions tablosundaki categorySub 'Müşteri Tahsilatı' olanların toplamı yukarıdaki satış bakiyeden çıkartılacak  --></h5>
                </div>
            </div>
        </div>
    </div>
    <!-- start main content section -->
    <div x-data="products">
        <div class="relative flex h-full gap-5 sm:min-h-0 xl:h-[calc(100vh-_150px)]"
            :class="{'min-h-[999px]' : isShowChatMenu}">

            <div class="panel flex-1 overflow-y-auto">
                <div class="mb-5 sm:absolute sm:top-5 ltr:sm:left-5 rtl:sm:right-5">
                    <div class="relative flex items-center justify-between gap-4">
                        <a href="accountAdd" class="btn btn-primary gap-2 px-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" class="h-5 w-5">
                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                            Ekle
                        </a>
                    </div>
                </div>
                <div class="product-table">
                    <div class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns">
                        <div class="dataTable-top">
                            <div class="dataTable-search">
                                <input id="dataTable-search-input" class="dataTable-input" placeholder="Search..."
                                    type="text">
                            </div>
                        </div>
                        <div class="dataTable-container">
                            <table id="myTable" class="whitespace-nowrap dataTable-table">
                                <thead>
                                    <tr>
                                        <th data-sortable="" style="width: 34.7856%;">
                                            <a href="#" class="dataTable-sorter">Cari Adı</a>
                                        </th>
                                        <th data-sortable="" style="width: 10.5118%;">
                                            <a href="#" class="dataTable-sorter">VK No</a>
                                        </th>
                                        <th data-sortable="" style="width: 10.5118%;">
                                            <a href="#" class="dataTable-sorter">Yetkili</a>
                                        </th>
                                        <th data-sortable="" style="width: 10.0968%;">
                                            <a href="#" class="dataTable-sorter">Siparişler (₺)</a>
                                        </th>
                                        <th data-sortable="" style="width: 10.0968%;">
                                            <a href="#" class="dataTable-sorter">Faturalar (₺)</a>
                                        </th>
                                        <th data-sortable="" style="width: 11.2725%;">
                                            <a href="#" class="dataTable-sorter">Ödemeler (₺)</a>
                                        </th>
                                        <th data-sortable="" style="width: 11.2725%;">
                                            <a href="#" class="dataTable-sorter">İadeler (₺)</a>
                                        </th>
                                        <th data-sortable="" style="width: 11.2725%;">
                                            <a href="#" class="dataTable-sorter">Kalan (₺)</a>
                                        </th>
                                        <th data-sortable="false" style="width: 13.3472%;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            $invoiceTotalOffer = formatValueCC(getClientInvoiceTotalOffer($row['id']),"₺");
                                            $invoiceTotal = getClientInvoiceTotal($row['id']);
                                            $paymentTotal = getUserTotalPayments($row['username']);
                                            $refundsTotal = getUserTotalRefunds($row['username']);
                                            
                                            $balance = $invoiceTotal-$paymentTotal+$refundsTotal;
                                            $balance = formatValueCC($balance,"₺");
                                            
                                            $invoiceTotal = formatValueCC($invoiceTotal,"₺");
                                            $paymentTotal = formatValueCC($paymentTotal,"₺");
                                            $refundsTotal = formatValueCC($refundsTotal,"₺");
                                            
                                            echo "<tr class='product-row' data-product-name='{$row['username']}'>";
                                            echo "<td>
                                                    <div class='flex items-center gap-2'>
                                                        <div class='p-px bg-white-dark/30 rounded w-max'>
                                                            <img class='h-8 w-8 rounded object-cover' src='{$row['profilePicture']}' alt='{$row['username']}'>
                                                        </div>
                                                        <div>

                                                            <a href='account-individual?id={$row['id']}  ' class='font-semibold text-primary hover:underline line-clamp-1 whitespace-normal'>
                                                                {$row['username']}
                                                            </a>
                                                        </div>
                                                    </div>
                                                  </td>";
                                            echo "<td><div class='font-semibold'>{$row['taxNumber']}</div></td>";
                                            echo "<td><div class='font-semibold'>{$row['name']}</div></td>";
                                            echo "<td><div class='font-semibold'>{$invoiceTotalOffer}</div></td>";
                                            echo "<td><div class='font-semibold'>{$invoiceTotal}</div></td>";
                                            echo "<td><div class='font-semibold'>{$paymentTotal}</div></td>";
                                            echo "<td><div class='font-semibold'>{$refundsTotal}</div></td>";
                                            echo "<td><div class='font-semibold'>{$balance}</div></td>";
                                            echo "<td>
                                                    <div class='flex gap-4 items-center'>
                                                        <a href='productUpdate.php?id={$row['id']}' class='hover:text-info'>
                                                            <svg width='24' height='24' viewBox='0 0 24 24' fill='none' xmlns='http://www.w3.org/2000/svg' class='w-4.5 h-4.5'>
                                                                <path opacity='0.5' d='M22 10.5V12C22 16.714 22 19.0711 20.5355 20.5355C19.0711 22 16.714 22 12 22C7.28595 22 4.92893 22 3.46447 20.5355C2 19.0711 2 16.714 2 12C2 7.28595 2 4.92893 3.46447 3.46447C4.92893 2 7.28595 2 12 2H13.5' stroke='currentColor' stroke-width='1.5' stroke-linecap='round'></path>
                                                                <path d='M17.3009 2.80624L16.652 3.45506L10.6872 9.41993C10.2832 9.82394 10.0812 10.0259 9.90743 10.2487C9.70249 10.5114 9.52679 10.7957 9.38344 11.0965C9.26191 11.3515 9.17157 11.6225 8.99089 12.1646L8.41242 13.9L8.03811 15.0229C7.9492 15.2897 8.01862 15.5837 8.21744 15.7826C8.41626 15.9814 8.71035 16.0508 8.97709 15.9619L10.1 15.5876L11.8354 15.0091C12.3775 14.8284 12.6485 14.7381 12.9035 14.6166C13.2043 14.4732 13.4886 14.2975 13.7513 14.0926C13.9741 13.9188 14.1761 13.7168 14.5801 13.3128L20.5449 7.34795L21.1938 6.69914C22.2687 5.62415 22.2687 3.88124 21.1938 2.80624C20.1188 1.73125 18.3759 1.73125 17.3009 2.80624Z' stroke='currentColor' stroke-width='1.5'></path>
                                                                <path opacity='0.5' d='M16.6522 3.45508C16.6522 3.45508 16.7333 4.83381 17.9499 6.05034C19.1664 7.26687 20.5451 7.34797 20.5451 7.34797M10.1002 15.5876L8.4126 13.9' stroke='currentColor' stroke-width='1.5'></path>
                                                            </svg>
                                                        </a>
                                                        <a href='productDetails.php?id={$row['id']}' class='hover:text-primary' >
                                                            <svg width='24' height='24' viewBox='0 0 24 24' fill='none' xmlns='http://www.w3.org/2000/svg' class='w-5 h-5'>
                                                                <path opacity='0.5' d='M3.27489 15.2957C2.42496 14.1915 2 13.6394 2 12C2 10.3606 2.42496 9.80853 3.27489 8.70433C4.97196 6.49956 7.81811 4 12 4C16.1819 4 19.028 6.49956 20.7251 8.70433C21.575 9.80853 22 10.3606 22 12C22 13.6394 21.575 14.1915 20.7251 15.2957C19.028 17.5004 16.1819 20 12 20C7.81811 20 4.97196 17.5004 3.27489 15.2957Z' stroke='currentColor' stroke-width='1.5'></path>
                                                                <path d='M15 12C15 13.6569 13.6569 15 12 15C10.3431 15 9 13.6569 9 12C9 10.3431 10.3431 9 12 9C13.6569 9 15 10.3431 15 12Z' stroke='currentColor' stroke-width='1.5'></path>
                                                            </svg>
                                                        </a>
                                                        <button type='button' class='hover:text-danger' @click='showAlert({$row['id']})'>
                                                            <svg width='24' height='24' viewBox='0 0 24 24' fill='none' xmlns='http://www.w3.org/2000/svg' class='w-5 h-5'>
                                                                <path d='M20.5001 6H3.5' stroke='currentColor' stroke-width='1.5' stroke-linecap='round'></path>
                                                                <path d='M18.8334 8.5L18.3735 15.3991C18.1965 18.054 18.108 19.3815 17.243 20.1907C16.378 21 15.0476 21 12.3868 21H11.6134C8.9526 21 7.6222 21 6.75719 20.1907C5.89218 19.3815 5.80368 18.054 5.62669 15.3991L5.16675 8.5' stroke='currentColor' stroke-width='1.5' stroke-linecap='round'></path>
                                                                <path opacity='0.5' d='M9.5 11L10 16' stroke='currentColor' stroke-width='1.5' stroke-linecap='round'></path>
                                                                <path opacity='0.5' d='M14.5 11L14 16' stroke='currentColor' stroke-width='1.5' stroke-linecap='round'></path>
                                                                <path opacity='0.5' d='M6.5 6C6.55588 6 6.58382 6 6.60915 5.99936C7.43259 5.97849 8.15902 5.45491 8.43922 4.68032C8.44784 4.65649 8.45667 4.62999 8.47434 4.57697L8.57143 4.28571C8.65431 4.03708 8.69575 3.91276 8.75071 3.8072C8.97001 3.38607 9.37574 3.09364 9.84461 3.01877C9.96213 3 10.0932 3 10.3553 3H13.6447C13.9068 3 14.0379 3 14.1554 3.01877C14.6243 3.09364 15.03 3.38607 15.2493 3.8072C15.3043 3.91276 15.3457 4.03708 15.4286 4.28571L15.5257 4.57697C15.5433 4.62992 15.5522 4.65651 15.5608 4.68032C15.841 5.45491 16.5674 5.97849 17.3909 5.99936C17.4162 6 17.4441 6 17.5 6' stroke='currentColor' stroke-width='1.5'></path>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='6'>Veri bulunamadı</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="dataTable-bottom">
                            <div class="dataTable-info">
                                <?php
                                if ($last != 0) {
                                    $offset1 = $offset + 1;
                                } else {
                                    $offset1 = 0;
                                }
                                echo 'Toplam ' . $totalResults . ' sonuç içersinden ' . $offset1 . ' - ' . $last . ' Arası Gösteriliyor '; ?>
                            </div>
                            <div class="dataTable-dropdown">
                                <label><span class="ml-2">
                                        <select class="dataTable-selector" onchange="degis()">
                                            <option value="25" <?php if ($itemsPerPage == 25)
                                                echo 'selected'; ?>>
                                                25
                                            </option>
                                            <option value="50" <?php if ($itemsPerPage == 50)
                                                echo 'selected'; ?>>
                                                50
                                            </option>
                                            <option value="100" <?php if ($itemsPerPage == 100)
                                                echo 'selected'; ?>>
                                                100
                                            </option>
                                            <option value="1000" <?php if ($itemsPerPage == 1000)
                                                echo 'selected'; ?>>
                                                1000
                                            </option>
                                        </select>
                                    </span></label>
                            </div>
                            <nav class="dataTable-pagination">
                                <ul class="dataTable-pagination-list">
                                    <?php if ($page > 1): ?>
                                        <li class="pager"><a href="?page=1&queue=<?= $itemsPerPage ?>"><svg width="24"
                                                    height="24" viewBox="0 0 24 24" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 rtl:rotate-180">
                                                    <path d="M13 19L7 12L13 5" stroke="currentColor" stroke-width="1.5"
                                                        stroke-linecap="round" stroke-linejoin="round"></path>
                                                    <path opacity="0.5" d="M16.9998 19L10.9998 12L16.9998 5"
                                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round"></path>
                                                </svg></a></li>
                                        <li class="pager"><a href="?page=<?= $page - 1 ?>&queue=<?= $itemsPerPage ?>"><svg
                                                    width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 rtl:rotate-180">
                                                    <path d="M13 19L7 12L13 5" stroke="currentColor" stroke-width="1.5"
                                                        stroke-linecap="round" stroke-linejoin="round"></path>
                                                </svg></a></li>
                                    <?php endif; ?>

                                    <?php for ($i = 1; $i <= ceil($totalItems / $itemsPerPage); $i++): ?>
                                        <li class="<?= $i == $page ? 'active' : '' ?>"><a
                                                href="?page=<?= $i ?>&queue=<?= $itemsPerPage ?>"><?= $i ?></a></li>
                                    <?php endfor; ?>

                                    <?php if ($page < ceil($totalItems / $itemsPerPage)): ?>
                                        <li class="pager"><a href="?page=<?= $page + 1 ?>&queue=<?= $itemsPerPage ?>"><svg
                                                    width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 rtl:rotate-180">
                                                    <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5"
                                                        stroke-linecap="round" stroke-linejoin="round"></path>
                                                </svg></a></li>
                                        <li class="pager"><a
                                                href="?page=<?= ceil($totalItems / $itemsPerPage) ?>&queue=<?= $itemsPerPage ?>"><svg
                                                    width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 rtl:rotate-180">
                                                    <path d="M11 19L17 12L11 5" stroke="currentColor" stroke-width="1.5"
                                                        stroke-linecap="round" stroke-linejoin="round"></path>
                                                    <path opacity="0.5" d="M6.99976 19L12.9998 12L6.99976 5"
                                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round"></path>
                                                </svg></a></li>
                                    <?php endif; ?>
                                </ul>
                            </nav>



                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end main content section -->
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('dataTable-search-input');
        const rows = document.querySelectorAll('.product-row');

        searchInput.addEventListener('input', function () {
            const searchTerm = searchInput.value.toLowerCase();

            rows.forEach(row => {
                const productName = row.getAttribute('data-product-name').toLowerCase();

                if (productName.includes(searchTerm)) {
                    row.style.display = 'table-row'; // show the row
                } else {
                    row.style.display = 'none'; // hide the row
                }
            });
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const updateLinks = document.querySelectorAll('.update-link');
        updateLinks.forEach(link => {
            link.addEventListener('click', function (event) {
                event.preventDefault();
                const row = this.closest('tr');
                const productId = row.dataset.productId;
                window.location.href = `productUpdate.php?id=${productId}`;
            });
        });
    });
</script>
<script>
    // Form submit işlemi için JavaScript fonksiyonu
    function degis() {
        // Seçilen değeri alıyoruz
        var selectedValue = document.querySelector('.dataTable-selector').value;
        // Yeni URL oluşturuyoruz
        var newUrl = window.location.href.split('?')[0] + '?queue=' + selectedValue;
        // Sayfayı yeniliyoruz
        window.location.href = newUrl;
    }
</script>

<!-- script -->
<script>
    function showAlert(id) {
        new window.Swal({
            icon: 'warning',
            title: 'Ürünü silmek istediğinizden emin misiniz?',
            text: 'Bu işlem geri alınamaz.',
            showCancelButton: true,
            confirmButtonText: 'Sil',
            padding: '2em',
        }).then((result) => {
            if (result.value) {
                // AJAX isteği oluştur
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'pages/manager/product/modules/delete_product.php', true);
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                xhr.onload = function () {
                    if (xhr.status === 200) {
                        // Başarılı yanıt geldiğinde SweetAlert ile mesaj göster
                        var response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            new window.Swal('Silindi!', 'Ürün Silindi.', 'success');
                            setTimeout(function () {
                                // Sayfayı yenile
                                window.location.reload();
                            }, 2000);
                        } else {
                            new window.Swal('Hata!', 'Ürün silinirken bir hata oluştu.', 'error');
                        }
                    } else {
                        console.error('Error deleting item');
                        new window.Swal('Hata!', 'Ürün silinirken bir hata oluştu.', 'error');
                    }
                };
                xhr.onerror = function () {
                    console.error('Request failed');
                    new window.Swal('Hata!', 'Ürün silinirken bir hata oluştu.', 'error');
                };
                xhr.send('id=' + id);
            }
        });
    }
</script>