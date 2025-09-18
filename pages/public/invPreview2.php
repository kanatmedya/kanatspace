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
    
    // Company Logo White
    $sqlCompanyLogoWhite = "SELECT * FROM settings WHERE type = 'logo-vertical-white'";
    $resCompanyLogoWhite = $conn->query($sqlCompanyLogoWhite);
    $rowCompanyLogoWhite = $resCompanyLogoWhite->fetch_array();
    $companyLogoWhite = $rowCompanyLogoWhite['value'];
    
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
        
    $invoiceType1 = $rowInv['type'];
    if($invoiceType1=='sale'){
        $invoiceType1='Satış';
    }else if($invoiceType1=='purchase'){
        $invoiceType1='Alış';
    }
    
    $invoiceType2 = $rowInv['status'];
    if($invoiceType2=='onOffer'){
        $invoiceType2='Teklifi';
        $invoiceType3='TEKLİF';
        $invoiceType3Lower='Teklif';
    }else if($invoiceType2=='onOrder'){
        $invoiceType2='Siparisi';
        $invoiceType3='SİPARİŞ';
        $invoiceType3Lower='Sipariş';
    }else if($invoiceType2=='completed'){
        $invoiceType2='Faturası';
        $invoiceType3='FATURA';
        $invoiceType3Lower='Fatura';
    }else if($invoiceType2=='rejected'){
        $invoiceType2='Faturası (İptal Edildi)';
        $invoiceType3='FATURA';
        $invoiceType3Lower='Fatura';
    }
    
    $isBranded = $rowInv['branded'];
            
    $invoiceType = $invoiceType1 . ' ' . $invoiceType2;


    include"invoice/invoma/index.php";

    
}else{
    include"invoice/invoma/index.php";

}

?>