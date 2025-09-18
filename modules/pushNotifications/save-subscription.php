<?php
session_start();
header('Content-Type: application/json');

include ("../../db/db_connect.php");

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['endpoint']) && isset($data['keys']['p256dh']) && isset($data['keys']['auth'])) {
    $endpoint = $data['endpoint'];
    $p256dh = $data['keys']['p256dh'];
    $auth = $data['keys']['auth'];

    $stmt = $conn->prepare("INSERT INTO subscriptions (endpoint, p256dh, auth) VALUES (?, ?, ?)");
    if ($stmt) {
        $stmt->bind_param('sss', $endpoint, $p256dh, $auth);
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Sorgu çalıştırma hatası: ' . $stmt->error]);
        }
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'error' => 'Sorgu hazırlama hatası: ' . $conn->error]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Abone bilgileri sağlanmadı']);
}

$conn->close();
?>
