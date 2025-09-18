<?php

if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT'] . '/');
}
include ROOT_PATH . "assets/db/db_connect.php";
global $conn;

$senders = '<option value="-1">Gönderici</option>';
$recievers = '<option value="-1">Alıcı</option>';


// Şirket Hesapları
$senders .= '<option disabled style="font-weight: bold;">Şirket Hesapları</option>';
$senders .= '<option value="Şirket Nakit Hesabı">Nakit Hesabı</option>';
$senders .= '<option value="Şirket Kart Hesabı">Kart Hesabı</option>';
$senders .= '<option value="Şirket Çek/Senet Hesabı">Çek/Senet Hesabı</option>';
$senders .= '<option value="Şirket Ortaklar Hesabı">Ortaklar Hesabı</option>';

$recievers .= '<option disabled style="font-weight: bold;">Şirket Hesapları</option>';
$recievers .= '<option value="Şirket Nakit Hesabı">Nakit Hesabı</option>';
$recievers .= '<option value="Şirket Kart Hesabı">Kart Hesabı</option>';
$recievers .= '<option value="Şirket Çek/Senet Hesabı">Çek/Senet Hesabı</option>';
$recievers .= '<option value="Şirket Ortaklar Hesabı">Ortaklar Hesabı</option>';


// Müşteri, bayi, tedarikçi ve personel hesapları
// Müşteri Hesapları
$senders .= '<option disabled></option>';
$senders .= '<option disabled style="font-weight: bold;">Müşteri Hesapları</option>';

$recievers .= '<option disabled></option>';
$recievers .= '<option disabled style="font-weight: bold;">Müşteri Hesapları</option>';

$sqlClient = "SELECT * FROM users_client WHERE accountType='client' ORDER BY username";
$resultClient = $conn->query($sqlClient);

while ($rowClient = $resultClient->fetch_assoc()) {
    $senders .= '<option value="' . $rowClient['username'] . '">' . $rowClient['username'] . '</option>';
    $recievers .= '<option value="' . $rowClient['username'] . '">' . $rowClient['username'] . '</option>';
}


// Bayi Hesapları
$senders .= '<option disabled></option>';
$senders .= '<option disabled style="font-weight: bold;">Bayi Hesapları</option>';

$recievers .= '<option disabled></option>';
$recievers .= '<option disabled style="font-weight: bold;">Bayi Hesapları</option>';

$sqlClient = "SELECT * FROM users_client WHERE accountType='dealer' ORDER BY username";
$resultClient = $conn->query($sqlClient);

while ($rowClient = $resultClient->fetch_assoc()) {
    $senders .= '<option value="' . $rowClient['username'] . '">' . $rowClient['username'] . '</option>';
    $recievers .= '<option value="' . $rowClient['username'] . '">' . $rowClient['username'] . '</option>';
}


// Tedarikçi Hesapları
$senders .= '<option disabled></option>';
$senders .= '<option disabled style="font-weight: bold;">Tedarikçi Hesapları</option>';

$recievers .= '<option disabled></option>';
$recievers .= '<option disabled style="font-weight: bold;">Tedarikçi Hesapları</option>';

$sqlClient = "SELECT * FROM users_client WHERE accountType='supplier' ORDER BY username";
$resultClient = $conn->query($sqlClient);

while ($rowClient = $resultClient->fetch_assoc()) {
    $senders .= '<option value="' . $rowClient['username'] . '">' . $rowClient['username'] . '</option>';
    $recievers .= '<option value="' . $rowClient['username'] . '">' . $rowClient['username'] . '</option>';
}


// Personel Hesapları
$senders .= '<option disabled></option>';
$senders .= '<option disabled style="font-weight: bold;">Personel Hesapları</option>';

$recievers .= '<option disabled></option>';
$recievers .= '<option disabled style="font-weight: bold;">Personel Hesapları</option>';

$sqlClient = "SELECT * FROM users_employee ORDER BY username";
$resultClient = $conn->query($sqlClient);

while ($rowClient = $resultClient->fetch_assoc()) {
    $senders .= '<option value="' . $rowClient['username'] . '">' . $rowClient['username'] . '</option>';
    $recievers .= '<option value="' . $rowClient['username'] . '">' . $rowClient['username'] . '</option>';
}


// Yönetici Hesapları
$senders .= '<option disabled></option>';
$senders .= '<option disabled style="font-weight: bold;">Yönetici Hesapları</option>';

$recievers .= '<option disabled></option>';
$recievers .= '<option disabled style="font-weight: bold;">Yönetici Hesapları</option>';

$sqlClient = "SELECT * FROM ac_users WHERE 1";
$resultClient = $conn->query($sqlClient);

while ($rowClient = $resultClient->fetch_assoc()) {
    $senders .= '<option value="' . $rowClient['name'] . ' ' . $rowClient['surname'] . '">' . $rowClient['name'] . ' ' . $rowClient['surname'] . '</option>';
    $recievers .= '<option value="' . $rowClient['name'] . ' ' . $rowClient['surname'] . '">' . $rowClient['name'] . ' ' . $rowClient['surname'] . '</option>';
}


// Şahıs Hesaplar
$senders .= '<option disabled></option>';
$senders .= '<option disabled style="font-weight: bold;">Şahıs Hesapları</option>';

$recievers .= '<option disabled></option>';
$recievers .= '<option disabled style="font-weight: bold;">Şahıs Hesapları</option>';

$sqlClient = "SELECT * FROM ac_users WHERE 1";
$resultClient = $conn->query($sqlClient);

while ($rowClient = $resultClient->fetch_assoc()) {
    $senders .= '<option value="' . $rowClient['name'] . ' ' . $rowClient['surname'] . '">' . $rowClient['name'] . ' ' . $rowClient['surname'] . '</option>';
    $recievers .= '<option value="' . $rowClient['name'] . ' ' . $rowClient['surname'] . '">' . $rowClient['name'] . ' ' . $rowClient['surname'] . '</option>';
}


echo json_encode([
    'senders' => $senders,
    'recievers' => $recievers
]);
?>
