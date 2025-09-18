<?php

include("../../../../assets/db/db_connect.php");

// Form verilerini al
$accountType = $_POST['accountType'];
$username = $_POST['username'];
$clientTitle = $_POST['clientTitle'];
$taxOffice = $_POST['taxOffice'];
$taxNumber = $_POST['taxNumber'];
$address = $_POST['address'];
$district = $_POST['district'];
$city = $_POST['city'];
$country = $_POST['country'];
$phoneCompany = $_POST['phoneCompany'];
$email = $_POST['email'];
$web = $_POST['web'];
$name = $_POST['name'];
$phone = $_POST['phone'];

// Boş alan kontrolü
if (empty($clientTitle) && empty($taxNumber) && empty($taxOffice) && empty($address) && empty($addressDistrict) && empty($addressProvince) && empty($addressCountry) && empty($phone) && empty($email) && empty($web) && empty($contact) && empty($contactPhone)) {
    echo "Lütfen tüm alanları doldurun.";
    exit;
}

// Veritabanına ekleme
$sql = "INSERT INTO users_client (status, userType, accountType, username, clientTitle, taxOffice, taxNumber, address, district, city, country, phoneCompany, email, web, name, phone)
VALUES (1, 3, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssssssssssss", $accountType, $username, $clientTitle, $taxOffice, $taxNumber, $address, $district, $city, $country, $phoneCompany, $email, $web, $name, $phone);

if ($stmt->execute()) {
    echo "Yeni müşteri başarıyla eklendi.";
} else {
    echo "Hata: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
