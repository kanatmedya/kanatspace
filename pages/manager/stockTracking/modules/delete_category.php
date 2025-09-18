<?php

include("../../../../assets/db/db_connect.php");

// POST isteği ve id kontrolü
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);

    // kategori veritabanından silme işlemi
    $sql = "DELETE FROM products_category WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        // Başarılı bir şekilde silindiğini varsayalım
        $response = array('success' => true);
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    } else {
        // SQL sorgusu başarısız olduysa hata döndürelim
        $response = array('success' => false);
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
} else {
    // İsteği yanıtlayacak geçerli bir id yoksa hata döndürelim
    $response = array('success' => false);
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}
?>
