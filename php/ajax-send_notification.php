<?php
function sendFCMNotification($title, $body) {
    $serverKey = "YOUR_SERVER_KEY"; // Firebase Cloud Messaging (FCM) Server Key
    $tokens = file("tokens.txt", FILE_IGNORE_NEW_LINES);

    if (empty($tokens)) {
        echo "Kayıtlı token bulunamadı!";
        return;
    }

    $payload = [
        "registration_ids" => $tokens,
        "notification" => [
            "title" => $title,
            "body" => $body,
            "icon" => "https://yourwebsite.com/icon.png",
            "click_action" => "https://yourwebsite.com"
        ]
    ];

    $headers = [
        "Authorization: key=$serverKey",
        "Content-Type: application/json"
    ];

    $ch = curl_init("https://fcm.googleapis.com/fcm/send");
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    $response = curl_exec($ch);
    curl_close($ch);

    echo $response;
}

// Örnek kullanım
sendFCMNotification("Yeni Güncelleme!", "Web sitemize göz atın!");
?>
