<?php
// Veritabanındaki tabloları listeleyen dosya

// Local database bağlantısı
$dbname = "u490326670_workspace";
$servername = "localhost";
$username = "root";
$password = "";

try {
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    mysqli_set_charset($conn, 'utf8');
    
} catch (Exception $error) {
    echo "Database bağlantı hatası: " . $error->getMessage();
    exit;
}

if ($conn->connect_error) {
    die("Connection Error : " . $conn->connect_error);
}

echo "=== VERITABANI TABLOLARI ===\n";

// Tabloları listele
$result = mysqli_query($conn, "SHOW TABLES");

if ($result) {
    $tables = [];
    while ($row = mysqli_fetch_array($result)) {
        $tables[] = $row[0];
    }
    
    echo "Toplam " . count($tables) . " tablo bulundu:\n\n";
    
    foreach($tables as $table) {
        echo "- " . $table . "\n";
    }
    
    echo "\n=== FOTOĞRAF İLE İLGİLİ TABLOLAR ===\n";
    foreach($tables as $table) {
        if (strpos(strtolower($table), 'photo') !== false || 
            strpos(strtolower($table), 'picture') !== false ||
            strpos(strtolower($table), 'image') !== false) {
            echo "- " . $table . "\n";
        }
    }
    
    echo "\n=== COMMENT İLE İLGİLİ TABLOLAR ===\n";
    foreach($tables as $table) {
        if (strpos(strtolower($table), 'comment') !== false) {
            echo "- " . $table . "\n";
        }
    }
    
    echo "\n=== PROJECT İLE İLGİLİ TABLOLAR ===\n";
    foreach($tables as $table) {
        if (strpos(strtolower($table), 'project') !== false) {
            echo "- " . $table . "\n";
        }
    }
    
    echo "\n=== USER İLE İLGİLİ TABLOLAR ===\n";
    foreach($tables as $table) {
        if (strpos(strtolower($table), 'user') !== false) {
            echo "- " . $table . "\n";
        }
    }
    
} else {
    echo "Tablolar listelenemedi: " . mysqli_error($conn);
}

mysqli_close($conn);
?>