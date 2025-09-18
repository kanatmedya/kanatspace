<?php
// nt_comments tablosunun sütunlarını kontrol et
$dbname = "u490326670_workspace";
$servername = "localhost";
$username = "root";
$password = "";

try {
    $conn = new mysqli($servername, $username, $password, $dbname);
    $conn->set_charset("utf8");
    
    if ($conn->connect_error) {
        die("Bağlantı hatası: " . $conn->connect_error);
    }
    
    echo "<h2>nt_comments Tablosu Sütunları</h2>";
    
    $sql = "DESCRIBE nt_comments";
    $result = mysqli_query($conn, $sql);
    
    if ($result) {
        echo "<table border='1'>";
        echo "<tr><th>Sütun Adı</th><th>Tip</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row['Field'] . "</td>";
            echo "<td>" . $row['Type'] . "</td>";
            echo "<td>" . $row['Null'] . "</td>";
            echo "<td>" . $row['Key'] . "</td>";
            echo "<td>" . $row['Default'] . "</td>";
            echo "<td>" . $row['Extra'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "Hata: " . mysqli_error($conn);
    }
    
    $conn->close();
} catch (Exception $e) {
    echo "Hata: " . $e->getMessage();
}
?>