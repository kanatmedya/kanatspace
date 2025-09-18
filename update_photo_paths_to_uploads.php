<?php
// Veritabanındaki fotoğraf yollarını uploads/ yapısına güncelleme scripti

// Veritabanı bağlantısı
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "u490326670_workspace";

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Veritabanı bağlantısı başarılı.\n";

    // 1. ac_users tablosundaki profilePicture sütununu güncelle
    echo "\n=== ac_users.profilePicture güncelleniyor ===\n";
    
    // Önce mevcut değerleri kontrol et
    $stmt = $pdo->query("SELECT id, profilePicture FROM ac_users WHERE profilePicture IS NOT NULL AND profilePicture != '' LIMIT 10");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Mevcut profilePicture değerleri (ilk 10):\n";
    foreach ($users as $user) {
        echo "ID: {$user['id']}, profilePicture: {$user['profilePicture']}\n";
    }
    
    // profilePicture yollarını uploads/users/profile/ yapısına güncelle
    $updateQuery = "UPDATE ac_users SET profilePicture = CASE 
        WHEN profilePicture IS NOT NULL AND profilePicture != '' AND profilePicture NOT LIKE 'uploads/%' 
        THEN CONCAT('uploads/users/profile/', SUBSTRING_INDEX(profilePicture, '/', -1))
        ELSE profilePicture 
    END 
    WHERE profilePicture IS NOT NULL AND profilePicture != ''";
    
    $stmt = $pdo->prepare($updateQuery);
    $stmt->execute();
    $affectedRows = $stmt->rowCount();
    echo "ac_users.profilePicture güncellendi. Etkilenen satır sayısı: $affectedRows\n";

    // 2. projects tablosundaki fotoğraf sütunlarını güncelle
    echo "\n=== projects tablosu kontrol ediliyor ===\n";
    
    // projects tablosunun sütunlarını kontrol et
    $stmt = $pdo->query("DESCRIBE projects");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $photoColumns = [];
    foreach ($columns as $column) {
        if (strpos(strtolower($column['Field']), 'photo') !== false || 
            strpos(strtolower($column['Field']), 'image') !== false ||
            strpos(strtolower($column['Field']), 'picture') !== false) {
            $photoColumns[] = $column['Field'];
        }
    }
    
    echo "projects tablosundaki fotoğraf sütunları: " . implode(', ', $photoColumns) . "\n";
    
    foreach ($photoColumns as $column) {
        echo "\n--- $column sütunu güncelleniyor ---\n";
        
        // Mevcut değerleri kontrol et
        $stmt = $pdo->query("SELECT id, $column FROM projects WHERE $column IS NOT NULL AND $column != '' LIMIT 5");
        $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "Mevcut $column değerleri (ilk 5):\n";
        foreach ($projects as $project) {
            echo "ID: {$project['id']}, $column: {$project[$column]}\n";
        }
        
        // Fotoğraf yollarını uploads/projects/ yapısına güncelle
        $updateQuery = "UPDATE projects SET $column = CASE 
            WHEN $column IS NOT NULL AND $column != '' AND $column NOT LIKE 'uploads/%' 
            THEN CONCAT('uploads/projects/', SUBSTRING_INDEX($column, '/', -1))
            ELSE $column 
        END 
        WHERE $column IS NOT NULL AND $column != ''";
        
        $stmt = $pdo->prepare($updateQuery);
        $stmt->execute();
        $affectedRows = $stmt->rowCount();
        echo "projects.$column güncellendi. Etkilenen satır sayısı: $affectedRows\n";
    }

    // 3. ideas tablosundaki fotoğraf sütunlarını kontrol et
    echo "\n=== ideas tablosu kontrol ediliyor ===\n";
    
    try {
        $stmt = $pdo->query("DESCRIBE ideas");
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $photoColumns = [];
        foreach ($columns as $column) {
            if (strpos(strtolower($column['Field']), 'photo') !== false || 
                strpos(strtolower($column['Field']), 'image') !== false ||
                strpos(strtolower($column['Field']), 'picture') !== false) {
                $photoColumns[] = $column['Field'];
            }
        }
        
        echo "ideas tablosundaki fotoğraf sütunları: " . implode(', ', $photoColumns) . "\n";
        
        foreach ($photoColumns as $column) {
            echo "\n--- $column sütunu güncelleniyor ---\n";
            
            // Fotoğraf yollarını uploads/ideas/ yapısına güncelle
            $updateQuery = "UPDATE ideas SET $column = CASE 
                WHEN $column IS NOT NULL AND $column != '' AND $column NOT LIKE 'uploads/%' 
                THEN CONCAT('uploads/ideas/', SUBSTRING_INDEX($column, '/', -1))
                ELSE $column 
            END 
            WHERE $column IS NOT NULL AND $column != ''";
            
            $stmt = $pdo->prepare($updateQuery);
            $stmt->execute();
            $affectedRows = $stmt->rowCount();
            echo "ideas.$column güncellendi. Etkilenen satır sayısı: $affectedRows\n";
        }
    } catch (Exception $e) {
        echo "ideas tablosu bulunamadı veya erişilemedi: " . $e->getMessage() . "\n";
    }

    // 4. Güncellenmiş değerleri kontrol et
    echo "\n=== Güncellenmiş değerler kontrol ediliyor ===\n";
    
    $stmt = $pdo->query("SELECT id, profilePicture FROM ac_users WHERE profilePicture IS NOT NULL AND profilePicture != '' LIMIT 5");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Güncellenmiş ac_users.profilePicture değerleri (ilk 5):\n";
    foreach ($users as $user) {
        echo "ID: {$user['id']}, profilePicture: {$user['profilePicture']}\n";
    }

    echo "\nFotoğraf yolları başarıyla uploads/ yapısına güncellendi!\n";

} catch(PDOException $e) {
    echo "Hata: " . $e->getMessage() . "\n";
}
?>