<?php
include "assets/db/db_connect.php";
include "./apps/php_TimeTR/php_timeTR.php";

error_reporting(E_ALL);
ini_set('display_errors', 1);

//Yardımcı Fonksiyonlar
function getClientInvoiceTotalOrder($clientID) {
    global $conn;
    
    $total = 0;

    // İlk olarak clientID'ye göre invoices tablosundan fatura ID'lerini alıyoruz
    $query = "SELECT id FROM invoices WHERE clientID = ? AND status != 'rejected'";
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

function getClientInvoiceTotalOrderNum($clientID) {
    global $conn;

    // Sorgu: clientID'ye göre invoices tablosundan status != 'rejected' olan kayıtların sayısını al
    $query = "SELECT COUNT(*) as invoice_count FROM invoices WHERE clientID = ? AND status != 'rejected'";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $clientID);
    $stmt->execute();
    $result = $stmt->get_result();

    // Sonuçtan fatura sayısını al
    $row = $result->fetch_assoc();
    $num = $row['invoice_count'] ?? 0;
    return $num;
}

function getClientInvoiceTotalOffer($clientID) {
    global $conn;
    
    $total = 0;

    // İlk olarak clientID'ye göre invoices tablosundan fatura ID'lerini alıyoruz
    $query = "SELECT id FROM invoices WHERE clientID = ? AND status = 'onOffer'";
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

function getClientInvoiceTotalOfferNum($clientID) {
    global $conn;

    // Sorgu: clientID'ye göre invoices tablosundan status != 'rejected' olan kayıtların sayısını al
    $query = "SELECT COUNT(*) as invoice_count FROM invoices WHERE clientID = ? AND status = 'onOffer'";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $clientID);
    $stmt->execute();
    $result = $stmt->get_result();

    // Sonuçtan fatura sayısını al
    $row = $result->fetch_assoc();
    $num = $row['invoice_count'] ?? 0;
    return $num;
}

function getClientInvoiceTotal($clientID) {
    global $conn;
    
    $total = 0;

    // İlk olarak clientID'ye göre invoices tablosundan fatura ID'lerini alıyoruz
    $query = "SELECT id FROM invoices WHERE clientID = ? AND type = 'sale' AND (status = 'completed' or status='onOrder')";
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
        return "0";
    }
    
    // Eğer 0 değilse, değeri 2 ondalıklı olarak döndür
    return number_format($value, 2, '.', '');
}

function translateStatus($status) {
    $statusList = [
        'onOffer'   => 'Teklif Aşamasında',
        'onOrder'   => 'Sipariş Hazırlanıyor',
        'completed' => 'Tamamlandı',
        'rejected'  => 'Reddedildi'
    ];

    return isset($statusList[$status]) ? $statusList[$status] : 'Bilinmeyen Durum';
}

function translateType($type) {
    $typeList = [
        'sale'     => 'Satış',
        'purchase' => 'Alış'
    ];

    return isset($typeList[$type]) ? $typeList[$type] : 'Bilinmeyen Tip';
}


// SQL Sorguları ve sonuçların çekilmesi (MySQLi kullanımı)
$clientId = $_GET['id'];

// SQL sorgusunu hazırlayın
$queryClient = "SELECT username FROM users_client WHERE id = ?";

if ($stmtClient = $conn->prepare($queryClient)) {
    // Parametreyi bağlayın ve sorguyu çalıştırın
    $stmtClient->bind_param('i', $clientId); // 'i' integer tipini belirtir
    $stmtClient->execute();
    $resultClient = $stmtClient->get_result();

    // Sonucu kontrol edin ve username'i alın
    if ($resultClient->num_rows > 0) {
        $rowClient = $resultClient->fetch_assoc();
        $clientUsername = $rowClient['username'];
    } else {
        echo "Kullanıcı bulunamadı.";
    }
    $stmtClient->close();
} else {
    echo "Sorgu hatası: " . $conn->error;
}

$invoiceTotal = formatValue(getClientInvoiceTotal($clientId));
$invoiceTotalOrder = formatValue(getClientInvoiceTotalOrder($clientId));
$invoiceTotalOffer = formatValue(getClientInvoiceTotalOffer($clientId));
$invoiceTotalOrderNum = getClientInvoiceTotalOrderNum($clientId);
$invoiceTotalOfferNum = getClientInvoiceTotalOfferNum($clientId);
$paymentTotal = formatValue(getUserTotalPayments($clientUsername));
$refundsTotal = formatValue(getUserTotalRefunds($clientUsername));
$balance = $invoiceTotal-$paymentTotal+$refundsTotal;
$balance = formatValue($balance);
$invoiceTotalOrder = formatValue($invoiceTotalOrder - $invoiceTotal);

?>

<script>
    document.title = "<?php echo $clientUsername ?> Müşteri Carisi | <?php echo $companyName; ?>";
</script>

<div class="animate__animated p-6" :class="[$store.app.animation]">
    <h1 class="font-bold" style="font-size:24px;margin-right:20px"><?php echo $clientUsername; ?></h1><br>
    <div class="mb-6 grid gap-6 sm:grid-cols-2 xl:grid-cols-4">
        
        <!-- Toplam Sipariş Sayısı -->
        <div class="panel h-full p-0">
            <div class="flex p-5">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-primary/10 text-primary dark:bg-primary dark:text-white-light">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5">
                        <!-- Icon -->
                    </svg>
                </div>
                <div class="font-semibold ltr:ml-3 rtl:mr-3">
                    <p class="text-xl dark:text-white-light"><?php echo $invoiceTotalOrderNum ;?> <span class="orderTotal"><?php echo $invoiceTotalOfferNum;?></span></p>
                    <h5 class="text-xs text-[#506690]">Faturalar <span class="orderTotal">Siparişler</span></h5>
                </div>
            </div>
        </div>

        <!-- Toplam Sipariş Tutarı -->
        <div class="panel h-full p-0">
            <div class="flex p-5">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-danger/10 text-danger dark:bg-danger dark:text-white-light">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5">
                        <!-- Icon -->
                    </svg>
                </div>
                <div class="font-semibold ltr:ml-3 rtl:mr-3">
                    <p class="text-xl dark:text-white-light"><?php echo $invoiceTotal; ?> ₺ <span class="orderTotal"><?php echo $invoiceTotalOffer ?>₺</span></p>
                    <h5 class="text-xs text-[#506690]">Faturalar <span class="orderTotal">Siparişler</span></h5>
                </div>
            </div>
        </div>

        <!-- Alınan Ödeme -->
        <div class="panel h-full p-0">
            <div class="flex p-5">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-success/10 text-success dark:bg-success dark:text-white-light">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5">
                        <!-- Icon -->
                    </svg>
                </div>
                <div class="font-semibold ltr:ml-3 rtl:mr-3">
                    <p class="text-xl dark:text-white-light"><?php echo $paymentTotal ?> ₺</p>
                    <h5 class="text-xs text-[#506690]">Alınan Ödeme</h5>
                </div>
            </div>
        </div>

        <!-- Kalan Ödeme -->
        <div class="panel h-full p-0">
            <div class="flex p-5">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-warning/10 text-warning dark:bg-warning dark:text-white-light">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5">
                        <!-- Icon -->
                    </svg>
                </div>
                <div class="font-semibold ltr:ml-3 rtl:mr-3">
                    <p class="text-xl dark:text-white-light"><?php echo number_format($balance, 2, ',', '.'); ?> ₺</p>
                    <h5 class="text-xs text-[#506690]">Kalan Ödeme</h5>
                </div>
            </div>
        </div>

    </div>
    
<!-- Siparişler -->
<?php 
// SQL sorgusu
$queryOffers = "SELECT * FROM invoices WHERE clientID = ? AND (status = 'onOffer' OR status = 'rejected') ORDER BY date DESC"; // Client ID'ye göre sorgulama yapılıyor

// Client ID'yi hazırlayıp sorguyu çalıştırın
if ($stmtOffers = $conn->prepare($queryOffers)) {
    $stmtOffers->bind_param('i', $clientId);
    $stmtOffers->execute();
    $resOffers = $stmtOffers->get_result(); // Sonuçları alıyoruz

    if ($resOffers->num_rows > 0) { ?>
<!-- Diğer bölümler -->
<div class="mb-5">
    
    <div class="panel border-[#e0e6ed] px-0 dark:border-[#1b2e4b]">

        <!-- Tablo -->
        <div class="invoice-table">
            <div class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns">
                <div class="dataTable-top">
                    <div class="flex items-center gap-2">
                        <h2 class="font-bold">
                            Siparişler
                        </h2>
                    </div>
                    <div class="dataTable-search">
                        <input class="dataTable-input" placeholder="Search..." type="text">
                    </div>
                    
                    <a href="/invoiceAdd" class="btn btn-primary gap-2">
                        <!-- Button Icon -->
                        Yeni
                    </a>
                </div>
                <div class="dataTable-container">
                    <table id="myTable" class="whitespace-nowrap dataTable-table">
                        <thead>
                            <tr>
                                <!--<th>No</th>-->
                                <th>Tarih</th>
                                <th>Sipariş Başlığı</th>
                                <th>Türü</th>
                                <th>Durumu</th>
                                <th>Tutar</th>
                                <th>Eylemler</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
        // Tablo satırlarını listeleyin
        while ($rowOffers = $resOffers->fetch_array(MYSQLI_ASSOC)) {
            if($rowOffers['status']=='completed'){
                $style = "success";
            }else if($rowOffers['status']=='rejected'){
                $style = "danger";
            }else{
                $style = "primary";
            }
        
        ?>
                <tr>
                    <!--<td>#<?php echo $rowOffers['id'] ?></td>-->
                    <td><a href="invoice?id=<?php echo $rowOffers['id'] ?>"><?php echo dateTR($rowOffers['date']).'<br>'. dayTR($rowOffers['date']).'  '.timeTR($rowOffers['date']); ?></a></td>
                    <td><a href="invoice?id=<?php echo $rowOffers['id'] ?>" class="font-semibold"><?php echo $rowOffers['title'] ?></a></td>
                    <td><?php echo translateType($rowOffers['type']) ?></td>
                    <td><span class="badge badge-outline-<?php echo $style ?>"><?php echo translateStatus($rowOffers['status']) ?></span></td>
                    <td><?php echo number_format($rowOffers['amount'], 2, ',', '.') ?></td>
                    <td>
                        <div class="flex gap-4 items-center">
                        <span class="badge badge-outline-success">PDF</span>
                        <span class="badge badge-outline-primary">Link</span>
                            <!--<a href="apps-invoice-edit.html" class="hover:text-info">Düzenle</a>
                            <button type="button" class="hover:text-danger" @click="deleteRow(<?php echo $rowOffers['id']; ?>)">Sil</button>-->
                        </div>
                    </td>
                </tr>
            <?php } ?>
                    </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
} else {
        
    }
    $stmtOffers->close();
} else {
    echo "Sorgu hatası: " . $conn->error;
}
?>

<!-- Faturalar -->
<?php 
// SQL sorgusu
$queryOrders = "SELECT * FROM invoices WHERE clientID = ? AND (status = 'onOrder' OR status = 'completed') AND type = 'sale' ORDER BY date DESC";
// Client ID'ye göre sorgulama yapılıyor

// Client ID'yi hazırlayıp sorguyu çalıştırın
if ($stmtOrders = $conn->prepare($queryOrders)) {
    $stmtOrders->bind_param('i', $clientId);
    $stmtOrders->execute();
    $resOrders = $stmtOrders->get_result(); // Sonuçları alıyoruz

    if ($resOrders->num_rows > 0) { ?>
<!-- Diğer bölümler -->
<div class="mb-5" x-data="">
    
    <div class="panel border-[#e0e6ed] px-0 dark:border-[#1b2e4b]">

        <!-- Tablo -->
        <div class="invoice-table">
            <div class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns">
                <div class="dataTable-top">
                    <div class="flex items-center gap-2">
                        <h2 class="font-bold">
                            Siparişler
                        </h2>
                    </div>
                    <div class="dataTable-search">
                        <input class="dataTable-input" placeholder="Search..." type="text">
                    </div>
                    
                    <a href="/invoiceAdd" class="btn btn-primary gap-2">
                        <!-- Button Icon -->
                        Yeni
                    </a>
                </div>
                <div class="dataTable-container">
                    <table id="myTable" class="whitespace-nowrap dataTable-table">
                        <thead>
                            <tr>
                                <!--<th>No</th>-->
                                <th>Tarih</th>
                                <th>Sipariş Başlığı</th>
                                <th>Türü</th>
                                <th>Durumu</th>
                                <th>Tutar</th>
                                <th>Eylemler</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
        // Tablo satırlarını listeleyin
        while ($rowOrders = $resOrders->fetch_array(MYSQLI_ASSOC)) {
            if($rowOrders['status']=='completed'){
                $style = "success";
            }else if($rowOrders['status']=='rejected'){
                $style = "danger";
            }else{
                $style = "primary";
            }
        
        ?>
                <tr>
                    <!--<td>#<?php echo $rowOrders['id'] ?></td>-->
                    <td><a href="invoice?id=<?php echo $rowOrders['id'] ?>"><?php echo dateTR($rowOrders['date']).'<br>'. dayTR($rowOrders['date']).'  '.timeTR($rowOrders['date']); ?></a></td>
                    <td><a href="invoice?id=<?php echo $rowOrders['id'] ?>" class="font-semibold"><?php echo $rowOrders['title'] ?></a></td>
                    <td><?php echo translateType($rowOrders['type']) ?></td>
                    <td><span class="badge badge-outline-<?php echo $style ?>"><?php echo translateStatus($rowOrders['status']) ?></span></td>
                    <td><?php echo number_format($rowOrders['amount'], 2, ',', '.') ?></td>
                    <td>
                        <div class="flex gap-4 items-center">
                        <span class="badge badge-outline-success">PDF</span>
                        <span class="badge badge-outline-primary">Link</span>
                            <!--<a href="apps-invoice-edit.html" class="hover:text-info">Düzenle</a>
                            <button type="button" class="hover:text-danger" @click="deleteRow(<?php echo $rowOrders['id']; ?>)">Sil</button>-->
                        </div>
                    </td>
                </tr>
            <?php } ?>
                    </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
} else {
        
    }
    $stmtOrders->close();
} else {
    echo "Sorgu hatası: " . $conn->error;
}
?>

<div class="mb-5" x-data="">
    
    <div class="panel border-[#e0e6ed] px-0 dark:border-[#1b2e4b]">

        <!-- Tablo -->
        <div class="invoice-table">
            <div class="dataTable-wrapper dataTable-loading no-footer sortable searchable fixed-columns">
                <div class="dataTable-top">
                    <div class="flex items-center gap-2">
                        <h2 class="font-bold">
                            Ödemeler
                        </h2>
                    </div>
                    <div class="dataTable-search">
                        <input class="dataTable-input" placeholder="Search..." type="text">
                    </div>
                    
                    <a href="/invoiceAdd" class="btn btn-primary gap-2">
                        <!-- Button Icon -->
                        Yeni
                    </a>
                </div>
                <div class="dataTable-container">
                    <table id="myTable" class="whitespace-nowrap dataTable-table">
                        <thead>
                            <tr>
                                <!--<th>No</th>-->
                                <th>Tarih</th>
                                <th>Gönderici</th>
                                <th>Alıcı</th>
                                <th>Tutar</th>
                                <th>Açıklama</th>
                                <th>Eylemler</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php

// SQL sorgusu
$queryPayments = "SELECT * FROM accounting_transactions WHERE sender = '$clientUsername' OR reciever = '$clientUsername' ORDER BY date DESC"; // Client ID'ye göre sorgulama yapılıyor

// Client ID'yi hazırlayıp sorguyu çalıştırın
if ($stmtPayments = $conn->prepare($queryPayments)) {
    $stmtPayments->execute();
    $resTabPayments = $stmtPayments->get_result(); // Sonuçları alıyoruz

    if ($resTabPayments->num_rows > 0) {
        // Tablo satırlarını listeleyin
        while ($rowTabPayments = $resTabPayments->fetch_array(MYSQLI_ASSOC)) {
            if($rowTabPayments['reciever']==$clientUsername){
                $rowAmount = $rowTabPayments['amount'] * -1;
            }else{
                $rowAmount = $rowTabPayments['amount'];
            }
        ?>
                <tr>
                    <!--<td><a href="payment?id=<?php echo $rowTabPayments['id'] ?>" class="text-primary underline font-semibold">#<?php echo $rowTabPayments['id'] ?></a></td>-->
                    <td><?php echo dateTR($rowTabPayments['date']).'<br>'. dayTR($rowTabPayments['date']).'  '.timeTR($rowTabPayments['date']); ?></td>
                    <td><?php echo $rowTabPayments['sender'] ?></td>
                    <td><?php echo $rowTabPayments['reciever'] ?></td>
                    <td><?php echo number_format($rowAmount, 2, ',', '.') ?></td>
                    <td><?php echo $rowTabPayments['description'] ?></td>
                    <td>
                        <div class="flex gap-4 items-center">
                            <span class="badge badge-outline-success">PDF</span>
                            <span class="badge badge-outline-primary">Link</span>
                            <!--<a href="apps-invoice-edit.html" class="hover:text-info">Düzenle</a>
                            <button type="button" class="hover:text-danger" @click="deleteRow(<?php echo $rowTabPayments['id']; ?>)">Sil</button>-->
                        </div>
                    </td>
                </tr>
            <?php }
    } else {
        echo "<tr><td colspan='6'>Kayıt bulunamadı</td></tr>";
    }
    $stmtPayments->close();
} else {
    echo "Sorgu hatası: " . $conn->error;
}
?>
                    </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

</div>