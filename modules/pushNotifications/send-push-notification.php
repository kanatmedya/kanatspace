<?php
use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

require 'vendor/autoload.php';

include ("../../db/db_connect.php");

$data = json_decode(file_get_contents('php://input'), true);

$title = $data['title'];
$body = $data['body'];
$icon = $data['icon'];
$url = $data['url'];

// Get all subscriptions
$sql = "SELECT * FROM subscriptions";
$result = $conn->query($sql);
$subscriptions = [];

while ($row = $result->fetch_assoc()) {
    $subscriptions[] = Subscription::create([
        'endpoint' => $row['endpoint'],
        'keys' => [
            'p256dh' => $row['p256dh'],
            'auth' => $row['auth'],
        ],
    ]);
}

// Payload
$payload = json_encode([
    'title' => $title,
    'body' => $body,
    'icon' => $icon,
    'url' => $url
]);

// VAPID Auth
$auth = [
    'VAPID' => [
        'subject' => 'mailto:YOUR_EMAIL@example.com',
        'publicKey' => 'YOUR_PUBLIC_VAPID_KEY',
        'privateKey' => 'YOUR_PRIVATE_VAPID_KEY',
    ],
];

// WebPush
$webPush = new WebPush($auth);

foreach ($subscriptions as $subscription) {
    $webPush->sendNotification($subscription, $payload);
}

// Handle results
foreach ($webPush->flush() as $report) {
    $endpoint = $report->getRequest()->getUri()->__toString();

    if ($report->isSuccess()) {
        echo "[v] Message sent successfully for subscription {$endpoint}.";
    } else {
        echo "[x] Message failed to sent for subscription {$endpoint}: {$report->getReason()}";
    }
}

$conn->close();
?>
