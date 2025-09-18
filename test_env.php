<?php
// Ortam tespiti test dosyası
echo "<h1>Ortam Tespiti Test</h1>";

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
    
    echo "<h2>Debug Bilgileri:</h2>";
    echo "Hostname: " . $hostname . "<br>";
    echo "Server IP: " . $serverIP . "<br>";
    echo "Document Root: " . $documentRoot . "<br>";
    echo "HTTP Host: " . $httpHost . "<br>";
    
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
    
    echo "<h2>Local Indicators:</h2>";
    foreach($localIndicators as $i => $indicator) {
        echo "Indicator $i: " . ($indicator ? 'TRUE' : 'FALSE') . "<br>";
    }
    
    // Eğer herhangi bir local gösterge varsa local ortam
    return in_array(true, $localIndicators) ? 'local' : 'server';
}

$server = detectEnvironment();
echo "<h2>Tespit Edilen Ortam: " . $server . "</h2>";

// Database bilgileri
$dbname = "u490326670_workspace";
$servername = "localhost";

if ($server == "server") {
    $username = "u490326670_admin";
    $password = "t+2Kx!54t";
    echo "<h3>Server kullanıcı bilgileri kullanılıyor</h3>";
} else if ($server == 'local') {
    $username = "root";
    $password = "";
    echo "<h3>Local kullanıcı bilgileri kullanılıyor</h3>";
}

echo "Username: " . $username . "<br>";
echo "Password: " . (empty($password) ? "BOŞ" : "DOLU") . "<br>";
?>