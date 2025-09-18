// VAPID Keys
const applicationServerKey = urlB64ToUint8Array('YOUR_PUBLIC_VAPID_KEY');

function urlB64ToUint8Array(base64String) {
    try {
        const padding = '='.repeat((4 - base64String.length % 4) % 4);
        const base64 = (base64String + padding)
            .replace(/-/g, '+')
            .replace(/_/g, '/');

        const rawData = window.atob(base64);
        const outputArray = new Uint8Array(rawData.length);

        for (let i = 0; i < rawData.length; ++i) {
            outputArray[i] = rawData.charCodeAt(i);
        }
        return outputArray;
    } catch (error) {
        console.error("Invalid Base64 string in VAPID key:", error);
        return null;
    }
}


// Register Service Worker
if ('serviceWorker' in navigator && 'PushManager' in window) {
    navigator.serviceWorker.register('/service-worker.js')
    .then(function(swReg) {
        console.log('Service Worker is registered', swReg);
    })
    .catch(function(error) {
        console.error('Service Worker Error', error);
    });
}

function subscribeUser() {
    navigator.serviceWorker.ready.then(function(registration) {
        registration.pushManager.subscribe({
            userVisibleOnly: true,
            applicationServerKey: applicationServerKey
        }).then(function(subscription) {
            console.log('User is subscribed:', subscription);
            saveSubscription(subscription);
        }).catch(function(err) {
            console.log('Failed to subscribe the user: ', err);
        });
    });
}

function saveSubscription(subscription) {
    return fetch('/save-subscription', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(subscription)
    });
}

// Check for new notifications and send push notifications
function checkNewNotifications() {
    fetch('modules/commentNotifications/fetch-notifications.php')
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            data.notifications.forEach(notification => {
                if (notification.comReaded == 0 && notification.soundNotify == 0) {
                    // Send push notification
                    sendPushNotification(notification);

                    // Update notification sound status
                    notification.soundNotify = 1; // Mark as notified
                    notification.soundNotifyDate = new Date().toISOString();
                    updateNotificationSoundStatus(notification);
                }
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

function sendPushNotification(notification) {
    fetch('send-push-notification.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            title: notification.author,
            body: notification.comText,
            icon: 'assets/media/system/icon-512x512.png',
            url: 'project?id=' + notification.projectID
        })
    });
}

function updateNotificationSoundStatus(notification) {
    fetch('modules/commentNotifications/update-notification-sound-status.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(notification)
    });
}

setInterval(checkNewNotifications, 1000); // Check for new notifications every second
