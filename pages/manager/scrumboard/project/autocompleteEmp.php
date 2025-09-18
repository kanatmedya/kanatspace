<?php

if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT'] . '/');
}

include ROOT_PATH . "assets/db/db_connect.php";

if (isset($_GET['term'])) {
    $term = $_GET['term'] . '%'; // Arama terimi

    // Kullanıcıları isme göre arar (name ve surname)
    $sql = "SELECT id, CONCAT(name, ' ', surname) AS full_name, profilePicture 
            FROM ac_users 
            WHERE (status=1 OR status=2) AND CONCAT(name, ' ', surname) LIKE ? 
            LIMIT 10"; // Maksimum 10 sonuç göster

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $term);
    $stmt->execute();
    $result = $stmt->get_result();

    $employees = [];

    while ($row = $result->fetch_assoc()) {
        $employees[] = [
            'id' => $row['id'],
            'label' => $row['full_name'],
            'profilePicture' => $row['profilePicture']
        ];
    }

    echo json_encode($employees);
}

$conn->close();
