<?php

function sendPushNotify($NotifyUserIds, $NotifyTitle, $NotifyBody, $NotifyIcon, $NotifyClickAction) {
    $NotifyServerKey = "BOfaaiHYs_TI4Qu0yjDi1tnv44YIfOs5F9AIF8NYHZsy0Ve0hKWJzKljOVFyBkje_aHFyjyvewJGyHk60nV6inI"; // 🔥 Firebase Server Key
    
    global $conn;

    if (empty($NotifyUserIds)) {
        return ["success" => false, "message" => "Kullanıcı ID'leri eksik."];
    }

    // Kullanıcıların `firebase_notify_status = 1` olan tüm cihazlarının tokenlarını al
    $NotifyTokens = [];
    $NotifyPlaceholders = implode(',', array_fill(0, count($NotifyUserIds), '?'));
    $NotifyQuery = "SELECT DISTINCT firebase_token FROM user_tokens WHERE user_id IN ($NotifyPlaceholders) AND firebase_notify_status = 1 AND firebase_token IS NOT NULL AND expiry > NOW()";
    
    $NotifyStmt = $conn->prepare($NotifyQuery);
    
    if (!$NotifyStmt) {
        return ["success" => false, "message" => "Veritabanı hatası: " . $conn->error];
    }
    
    $NotifyTypes = str_repeat('i', count($NotifyUserIds)); // user_id integer olduğu için 'i'
    $NotifyStmt->bind_param($NotifyTypes, ...$NotifyUserIds);
    
    if (!$NotifyStmt->execute()) {
        return ["success" => false, "message" => "Sorgu çalıştırılamadı: " . $NotifyStmt->error];
    }
    
    $NotifyResult = $NotifyStmt->get_result();
    while ($NotifyRow = $NotifyResult->fetch_assoc()) {
        $NotifyTokens[] = $NotifyRow["firebase_token"];
    }
    $NotifyStmt->close();

    if (empty($NotifyTokens)) {
        return ["success" => false, "message" => "Bildirim alacak aktif cihaz bulunamadı."];
    }

    // Firebase API'ye gönderilecek veri
    $NotifyPayload = [
        "registration_ids" => $NotifyTokens,
        "notification" => [
            "title" => $NotifyTitle,
            "body" => $NotifyBody,
            "icon" => $NotifyIcon,
            "click_action" => $NotifyClickAction
        ]
    ];

    // API isteği için başlıklar
    $NotifyHeaders = [
        "Authorization: key=$NotifyServerKey",
        "Content-Type: application/json"
    ];

    // Firebase Cloud Messaging API'ye isteği gönder
    $NotifyCh = curl_init("https://fcm.googleapis.com/fcm/send");
    curl_setopt($NotifyCh, CURLOPT_HTTPHEADER, $NotifyHeaders);
    curl_setopt($NotifyCh, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($NotifyCh, CURLOPT_POST, true);
    curl_setopt($NotifyCh, CURLOPT_POSTFIELDS, json_encode($NotifyPayload));
    $NotifyResponse = curl_exec($NotifyCh);
    curl_close($NotifyCh);

    return json_decode($NotifyResponse, true);
}

?>
