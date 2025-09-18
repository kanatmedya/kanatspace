<script src="/firebase.js" type="module"></script>

<?php
function sendNotification($title, $message, $icon, $userTokens) {
    $serverKey = "YOUR_FIREBASE_SERVER_KEY"; // 🔥 Firebase Console’dan aldığın FCM Server Key

    if (empty($userTokens)) {
        return ["success" => false, "message" => "Kullanıcı tokenları boş."];
    }

    // Firebase API'ye gönderilecek veri
    $payload = [
        "registration_ids" => $userTokens,
        "notification" => [
            "title" => $title,
            "body" => $message,
            "icon" => $icon,
            "click_action" => "https://yourwebsite.com"
        ]
    ];

    // API isteği için başlıklar
    $headers = [
        "Authorization: key=$serverKey",
        "Content-Type: application/json"
    ];

    // Firebase Cloud Messaging API'ye isteği gönder
    $ch = curl_init("https://fcm.googleapis.com/fcm/send");
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}
?>
