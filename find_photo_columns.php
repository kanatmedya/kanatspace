<?php
// Tablolardaki fotoğraf sütunlarını bulan dosya

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

echo "=== FOTOĞRAF SÜTUNLARI ARAMA ===\n\n";

// Tabloları al
$result = mysqli_query($conn, "SHOW TABLES");
$tables = [];
while ($row = mysqli_fetch_array($result)) {
    $tables[] = $row[0];
}

$photo_columns = [];

foreach($tables as $table) {
    echo "Tablo: $table\n";
    
    // Sütunları al
    $columns_result = mysqli_query($conn, "DESCRIBE $table");
    
    if ($columns_result) {
        while ($column = mysqli_fetch_array($columns_result)) {
            $column_name = $column[0];
            $column_type = $column[1];
            
            // Fotoğraf ile ilgili sütunları ara
            if (strpos(strtolower($column_name), 'photo') !== false ||
                strpos(strtolower($column_name), 'picture') !== false ||
                strpos(strtolower($column_name), 'image') !== false ||
                strpos(strtolower($column_name), 'avatar') !== false ||
                strpos(strtolower($column_name), 'profile') !== false ||
                strpos(strtolower($column_name), 'pic') !== false) {
                
                echo "  -> FOTOĞRAF SÜTUNU: $column_name ($column_type)\n";
                $photo_columns[] = "$table.$column_name";
                
                // Bu sütundaki örnek verileri göster
                $sample_result = mysqli_query($conn, "SELECT $column_name FROM $table WHERE $column_name IS NOT NULL AND $column_name != '' LIMIT 5");
                if ($sample_result && mysqli_num_rows($sample_result) > 0) {
                    echo "     Örnek veriler:\n";
                    while ($sample = mysqli_fetch_array($sample_result)) {
                        echo "     - " . $sample[0] . "\n";
                    }
                }
            }
        }
    }
    echo "\n";
}

echo "=== ÖZET ===\n";
echo "Toplam " . count($photo_columns) . " fotoğraf sütunu bulundu:\n";
foreach($photo_columns as $column) {
    echo "- $column\n";
}

mysqli_close($conn);
?>