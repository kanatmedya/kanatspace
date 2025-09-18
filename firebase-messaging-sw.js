importScripts("https://www.gstatic.com/firebasejs/8.10.1/firebase-app.js");
importScripts("https://www.gstatic.com/firebasejs/8.10.1/firebase-messaging.js");

firebase.initializeApp({
    apiKey: "AIzaSyByHvWdp0RXsjPH9firKiIYODdRNF3_rfM",
    authDomain: "kanatspace-bildirim.firebaseapp.com",
    projectId: "kanatspace-bildirim",
    storageBucket: "kanatspace-bildirim.firebasestorage.app",
    messagingSenderId: "108046975145",
    appId: "1:108046975145:web:3b73bd8fe61711d48ba9b8",
    measurementId: "G-JMPJRHLRSV"
});

const messaging = firebase.messaging();

function base64ToUint8Array(base64String) {
    const padding = '='.repeat((4 - base64String.length % 4) % 4);
    const base64 = (base64String + padding)
        .replace(/-/g, '+')
        .replace(/_/g, '/');

    const rawData = window.atob(base64); // 🔥 Eğer burada hata alırsan, Key hatalıdır!
    
    const outputArray = new Uint8Array(rawData.length);
    for (let i = 0; i < rawData.length; ++i) {
        outputArray[i] = rawData.charCodeAt(i);
    }
    return outputArray;
}

self.addEventListener("activate", (event) => {
    event.waitUntil(self.clients.claim());
});

self.addEventListener("pushsubscriptionchange", async (event) => {
    try {
        const applicationServerKey = base64ToUint8Array("BOfaaiHYs_TI4Qu0yjDi1tnv44YIfOs5F9AIF8NYHZsy0Ve0hKWJzKljOVFyBkje_aHFyjyvewJGyHk60nV6inI");
        const newSubscription = await self.registration.pushManager.subscribe({
            userVisibleOnly: true,
            applicationServerKey: applicationServerKey
        });
    } catch (err) {
        console.error("Yeni push aboneliği oluşturulamadı:", err);
    }
});

messaging.onBackgroundMessage((payload) => {
    self.registration.showNotification(payload.notification.title, {
        body: payload.notification.body,
        icon: payload.notification.icon
    });
});
