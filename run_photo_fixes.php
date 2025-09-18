<?php
echo "<pre>";

// Local database bağlantısı
$dbname = "u490326670_workspace";
$servername = "localhost";
$username = "root";
$password = "";

try {
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    mysqli_set_charset($conn, 'utf8');
    
    // SQL mod ayarları
    mysqli_query($conn, "SET sql_mode = ''");
    mysqli_query($conn, "SET SESSION sql_mode = ''");
    
} catch (Exception $error) {
    echo "Database bağlantı hatası: " . $error->getMessage();
    exit;
}

if ($conn->connect_error) {
    die("Connection Error : " . $conn->connect_error);
}

echo "=== FOTOĞRAF YOLLARI DÜZELTİLİYOR ===\n\n";

try {
    
    // 1. ac_users.profilePicture düzeltme
    echo "1. Kullanıcı profil fotoğrafları (ac_users.profilePicture) düzeltiliyor...\n";
    
    // Eski Windows yollarını temizle
    $sql = "UPDATE ac_users SET profilePicture = REPLACE(profilePicture, 'C:\\\\wamp64\\\\www\\\\work.local\\\\', '') WHERE profilePicture LIKE 'C:\\\\wamp64\\\\www\\\\work.local\\\\%'";
    if (mysqli_query($conn, $sql)) {
        echo "   - Windows yolları temizlendi: " . mysqli_affected_rows($conn) . " kayıt\n";
    } else {
        echo "   - Hata: " . mysqli_error($conn) . "\n";
    }
    
    // Çift slash düzelt
    $sql = "UPDATE ac_users SET profilePicture = REPLACE(profilePicture, '//', '/') WHERE profilePicture LIKE '%//%'";
    if (mysqli_query($conn, $sql)) {
        echo "   - Çift slash düzeltildi: " . mysqli_affected_rows($conn) . " kayıt\n";
    } else {
        echo "   - Hata: " . mysqli_error($conn) . "\n";
    }
    
    // Başlangıç slash ekle (eğer yoksa)
    $sql = "UPDATE ac_users SET profilePicture = CONCAT('/', profilePicture) WHERE profilePicture NOT LIKE '/%' AND profilePicture != '' AND profilePicture IS NOT NULL";
    if (mysqli_query($conn, $sql)) {
        echo "   - Başlangıç slash eklendi: " . mysqli_affected_rows($conn) . " kayıt\n";
    } else {
        echo "   - Hata: " . mysqli_error($conn) . "\n";
    }
    
    // 2. ideas.designPhoto düzeltme
    echo "\n2. Ideas designPhoto düzeltiliyor...\n";
    
    // Eski Windows yollarını temizle
    $sql = "UPDATE ideas SET designPhoto = REPLACE(designPhoto, 'C:\\\\wamp64\\\\www\\\\work.local\\\\', '') WHERE designPhoto LIKE 'C:\\\\wamp64\\\\www\\\\work.local\\\\%'";
    if (mysqli_query($conn, $sql)) {
        echo "   - Windows yolları temizlendi: " . mysqli_affected_rows($conn) . " kayıt\n";
    } else {
        echo "   - Hata: " . mysqli_error($conn) . "\n";
    }
    
    // Çift slash düzelt
    $sql = "UPDATE ideas SET designPhoto = REPLACE(designPhoto, '//', '/') WHERE designPhoto LIKE '%//%'";
    if (mysqli_query($conn, $sql)) {
        echo "   - Çift slash düzeltildi: " . mysqli_affected_rows($conn) . " kayıt\n";
    } else {
        echo "   - Hata: " . mysqli_error($conn) . "\n";
    }
    
    // Başlangıç slash ekle (eğer yoksa)
    $sql = "UPDATE ideas SET designPhoto = CONCAT('/', designPhoto) WHERE designPhoto NOT LIKE '/%' AND designPhoto != '' AND designPhoto IS NOT NULL";
    if (mysqli_query($conn, $sql)) {
        echo "   - Başlangıç slash eklendi: " . mysqli_affected_rows($conn) . " kayıt\n";
    } else {
        echo "   - Hata: " . mysqli_error($conn) . "\n";
    }
    
    // 3. ideas.photos düzeltme (JSON formatında olabilir)
    echo "\n3. Ideas photos düzeltiliyor...\n";
    
    // Eski Windows yollarını temizle
    $sql = "UPDATE ideas SET photos = REPLACE(photos, 'C:\\\\wamp64\\\\www\\\\work.local\\\\', '') WHERE photos LIKE '%C:\\\\wamp64\\\\www\\\\work.local\\\\%'";
    if (mysqli_query($conn, $sql)) {
        echo "   - Windows yolları temizlendi: " . mysqli_affected_rows($conn) . " kayıt\n";
    } else {
        echo "   - Hata: " . mysqli_error($conn) . "\n";
    }
    
    // Çift slash düzelt
    $sql = "UPDATE ideas SET photos = REPLACE(photos, '//', '/') WHERE photos LIKE '%//%'";
    if (mysqli_query($conn, $sql)) {
        echo "   - Çift slash düzeltildi: " . mysqli_affected_rows($conn) . " kayıt\n";
    } else {
        echo "   - Hata: " . mysqli_error($conn) . "\n";
    }
    
    echo "\n=== KONTROL SORGULARI ===\n";
    
    // ac_users kontrol
    $sql = "SELECT 
                COUNT(*) as toplam,
                COUNT(CASE WHEN profilePicture LIKE '/%' THEN 1 END) as dogru_format,
                COUNT(CASE WHEN profilePicture IS NOT NULL AND profilePicture != '' AND profilePicture NOT LIKE '/%' THEN 1 END) as yanlis_format
            FROM ac_users WHERE profilePicture IS NOT NULL AND profilePicture != ''";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        echo "ac_users.profilePicture: Toplam {$row['toplam']}, Doğru format {$row['dogru_format']}, Yanlış format {$row['yanlis_format']}\n";
    }
    
    // ideas.designPhoto kontrol
    $sql = "SELECT 
                COUNT(*) as toplam,
                COUNT(CASE WHEN designPhoto LIKE '/%' THEN 1 END) as dogru_format,
                COUNT(CASE WHEN designPhoto IS NOT NULL AND designPhoto != '' AND designPhoto NOT LIKE '/%' THEN 1 END) as yanlis_format
            FROM ideas WHERE designPhoto IS NOT NULL AND designPhoto != ''";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        echo "ideas.designPhoto: Toplam {$row['toplam']}, Doğru format {$row['dogru_format']}, Yanlış format {$row['yanlis_format']}\n";
    }
    
    // ideas.photos kontrol
    $sql = "SELECT 
                COUNT(*) as toplam,
                COUNT(CASE WHEN photos NOT LIKE '%C:\\\\%' AND photos NOT LIKE '%//%' THEN 1 END) as temiz_format
            FROM ideas WHERE photos IS NOT NULL AND photos != ''";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        echo "ideas.photos: Toplam {$row['toplam']}, Temiz format {$row['temiz_format']}\n";
    }
    
    // Örnek veriler göster
    echo "\n=== ÖRNEK VERİLER ===\n";
    
    echo "ac_users.profilePicture örnekleri:\n";
    $result = mysqli_query($conn, "SELECT profilePicture FROM ac_users WHERE profilePicture IS NOT NULL AND profilePicture != '' LIMIT 5");
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "   - " . $row['profilePicture'] . "\n";
        }
    }
    
    echo "\nideas.designPhoto örnekleri:\n";
    $result = mysqli_query($conn, "SELECT designPhoto FROM ideas WHERE designPhoto IS NOT NULL AND designPhoto != '' LIMIT 3");
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "   - " . $row['designPhoto'] . "\n";
        }
    }
    
    echo "\n=== İŞLEM TAMAMLANDI ===\n";
    echo "Tüm fotoğraf yolları başarıyla düzeltildi!\n";
    
} catch (Exception $e) {
    echo "HATA: " . $e->getMessage() . "\n";
}

mysqli_close($conn);
echo "</pre>";
?>