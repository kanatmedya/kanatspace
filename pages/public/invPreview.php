<?php
include "assets/db/db_connect.php";
include "apps/php_TimeTR/php_timeTR.php";

global $conn;

//Post Edilen ID'ye göre invoice bilgilerini alıyor
if(isset($_GET['key'])){
    $invKey = $_GET['key'];
    $sqlInv = "SELECT * FROM invoices WHERE secretKey = '$invKey'";
    $resInv = $conn->query($sqlInv);
    $rowInv = $resInv->fetch_array();

    $clientID = $rowInv['clientID'];
    $invID = $rowInv['id'];
    $invTitle = $rowInv['title'];

    $sqlCli = "SELECT * FROM users_client WHERE id = '$clientID'";
    $resCli = $conn->query($sqlCli);
    $rowCli = $resCli->fetch_array();

    // Company Name
    $sqlCompanyName = "SELECT * FROM settings WHERE type = 'companyNameFull'";
    $resCompanyName = $conn->query($sqlCompanyName);
    $rowCompanyName = $resCompanyName->fetch_array();
    $companyName = $rowCompanyName['value'];
    
    // Company Address
    $sqlCompanyAddress = "SELECT * FROM settings WHERE type = 'companyAdress'";
    $resCompanyAddress = $conn->query($sqlCompanyAddress);
    $rowCompanyAddress = $resCompanyAddress->fetch_array();
    $companyAddress = $rowCompanyAddress['value'];
    
    // Company Website
    $sqlCompanyWeb = "SELECT * FROM settings WHERE type = 'companyWeb'";
    $resCompanyWeb = $conn->query($sqlCompanyWeb);
    $rowCompanyWeb = $resCompanyWeb->fetch_array();
    $companyWeb = $rowCompanyWeb['value'];
    
    // Company Phone
    $sqlCompanyPhone = "SELECT * FROM settings WHERE type = 'companyPhone'";
    $resCompanyPhone = $conn->query($sqlCompanyPhone);
    $rowCompanyPhone = $resCompanyPhone->fetch_array();
    $companyPhone = $rowCompanyPhone['value'];
    
    // Company Email
    $sqlCompanyMail = "SELECT * FROM settings WHERE type = 'companyMail'";
    $resCompanyMail = $conn->query($sqlCompanyMail);
    $rowCompanyMail = $resCompanyMail->fetch_array();
    $companyMail = $rowCompanyMail['value'];


    include"invoice/preview.php";

    
}else{
    include"./pages/error/401.php";
}

?>