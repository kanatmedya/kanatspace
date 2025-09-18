<?php

date_default_timezone_set('Europe/Istanbul');

// Veritabanı bilgileri
$dbname = "u490326670_workspace";
$servername = "localhost";

// Otomatik ortam tespiti
function detectEnvironment() {
    // Yöntem 1: Server hostname kontrolü
    $hostname = gethostname();
    
    // Yöntem 2: IP adresi kontrolü
    $serverIP = $_SERVER['SERVER_ADDR'] ?? '';
    
    // Yöntem 3: Dosya yolu kontrolü (WAMP/XAMPP local ortamları)
    $documentRoot = $_SERVER['DOCUMENT_ROOT'] ?? '';
    
    // Yöntem 4: HTTP_HOST kontrolü
    $httpHost = $_SERVER['HTTP_HOST'] ?? '';
    
    // Local ortam tespiti
    $localIndicators = [
        // Hostname kontrolü
        strpos(strtolower($hostname), 'localhost') !== false,
        strpos(strtolower($hostname), 'wamp') !== false,
        strpos(strtolower($hostname), 'xampp') !== false,
        
        // IP kontrolü (local IP aralıkları)
        strpos($serverIP, '127.0.0.1') !== false,
        strpos($serverIP, '127.31.31.31') !== false,
        preg_match('/^127\./', $serverIP), // Tüm 127.x.x.x aralığı
        strpos($serverIP, '::1') !== false,
        preg_match('/^192\.168\./', $serverIP),
        preg_match('/^10\./', $serverIP),
        preg_match('/^172\.(1[6-9]|2[0-9]|3[0-1])\./', $serverIP),
        
        // Dosya yolu kontrolü
        strpos(strtolower($documentRoot), 'wamp') !== false,
        strpos(strtolower($documentRoot), 'xampp') !== false,
        strpos(strtolower($documentRoot), 'htdocs') !== false,
        strpos(strtolower($documentRoot), 'www') !== false,
        
        // HTTP_HOST kontrolü
        strpos($httpHost, 'localhost') !== false,
        strpos($httpHost, '.local') !== false,
        strpos($httpHost, '127.0.0.1') !== false,
        strpos($httpHost, '127.31.31.31') !== false,
        preg_match('/^127\./', $httpHost) // Tüm 127.x.x.x aralığı
    ];
    
    // Eğer herhangi bir local gösterge varsa local ortam
    return in_array(true, $localIndicators) ? 'local' : 'server';
}

$server = detectEnvironment();

if ($server == "server") {
    $username = "u490326670_admin";
    $password = "t+2Kx!54t";
} else if ($server == 'local') {
    $username = "root";
    $password = "";
}
try {
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    mysqli_set_charset($conn, 'utf8');
    $conn->query("SET time_zone = '+03:00'");
    
    // Local ortamda SQL modunu daha esnek hale getir (Hostinger MySQL 5.2.2 uyumluluğu için)
    if ($server == 'local') {
        // MySQL 5.2.2 gibi davranması için tüm strict modları kapat
        $conn->query("SET sql_mode = ''");
        $conn->query("SET SESSION sql_mode = ''");
        $conn->query("SET GLOBAL sql_mode = ''");
        // Eski MySQL davranışını etkinleştir
        $conn->query("SET old_alter_table = 1");
    }
} catch (PDOException $error) {
    echo $error->getMessage();
    echo "DB Error : Connection Not Established";
}

if ($conn->connect_error) {
    die("Connection Error : " . $conn->connect_error);
}
?>