<?php
require 'vendor/autoload.php';

use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

// Your private and public VAPID keys
$auth = [
    'VAPID' => [
        'subject' => 'mailto:YOUR_EMAIL@example.com',
        'publicKey' => 'YOUR_PUBLIC_VAPID_KEY',
        'privateKey' => 'YOUR_PRIVATE_VAPID_KEY',
    ],
];

// Fetch subscriptions from your database
$subscriptions = fetchSubscriptionsFromDatabase();

$webPush = new WebPush($auth);

foreach ($subscriptions as $subscription) {
    $sub = Subscription::create(json_decode($subscription['subscription'], true));

    $report = $webPush->sendNotification(
        $sub,
        json_encode([
            'title' => $_POST['title'],
            'body' => $_POST['body'],
            'icon' => $_POST['icon'],
            'data' => [
                'url' => $_POST['url']
            ]
        ])
    );

    // Handle eventual errors here
    foreach ($report as $endpoint => $status) {
        if ($status['success']) {
            echo "Message sent successfully to $endpoint.";
        } else {
            echo "Message failed to sent to $endpoint: {$status['message']}";
        }
    }
}

function fetchSubscriptionsFromDatabase() {
    // Implement this function to fetch subscriptions from your database
    // This is just a placeholder function
    return [];
}
?>
