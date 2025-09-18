import { initializeApp } from "https://www.gstatic.com/firebasejs/9.6.1/firebase-app.js";
import { getMessaging, getToken, onMessage } from "https://www.gstatic.com/firebasejs/9.6.1/firebase-messaging.js";

const firebaseConfig = {
    apiKey: "AIzaSyByHvWdp0RXsjPH9firKiIYODdRNF3_rfM",
    authDomain: "kanatspace-bildirim.firebaseapp.com",
    projectId: "kanatspace-bildirim",
    storageBucket: "kanatspace-bildirim.firebasestorage.app",
    messagingSenderId: "108046975145",
    appId: "1:108046975145:web:3b73bd8fe61711d48ba9b8",
    measurementId: "G-JMPJRHLRSV"
};

const app = initializeApp(firebaseConfig);
const messaging = getMessaging(app);

// Service Worker Kaydı
if ("serviceWorker" in navigator) {
    navigator.serviceWorker.register("/firebase-messaging-sw.js")
        .then((registration) => {
            return navigator.serviceWorker.ready;
        })
        .then((registration) => {
            requestPermission();
        })
        .catch((error) => {
            console.log("Service Worker kaydedilirken hata oluştu:", error);
        });
}

// Bildirim İzni ve Firebase Token Alma
function requestPermission() {
    Notification.requestPermission().then(permission => {
        if (permission === "granted") {
            navigator.serviceWorker.ready.then(registration => {
                getToken(messaging, {
                    vapidKey: "BOfaaiHYs_TI4Qu0yjDi1tnv44YIfOs5F9AIF8NYHZsy0Ve0hKWJzKljOVFyBkje_aHFyjyvewJGyHk60nV6inI",
                    serviceWorkerRegistration: registration
                })
                .then(token => {
                    sendTokenToServer(token);
                })
                .catch(err => console.log("🚨 Token alma hatası:", err));
            });
        } else {
            console.log("📌 Kullanıcı bildirim izni vermedi.");
        }
    });
}

// Firebase Token'ı Sunucuya Gönder
function sendTokenToServer(token) {
    fetch("/php/ajax-update-firebase-token.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ token: token })
    })
    .then(response => response.json())
    .catch(error => console.error("🚨 AJAX Hatası:", error));
}

// Kullanıcı Giriş Yaptıktan Sonra Firebase Token'ı Güncelle
function updateFirebaseTokenAfterLogin() {
    getToken(messaging, { vapidKey: "BOfaaiHYs_TI4Qu0yjDi1tnv44YIfOs5F9AIF8NYHZsy0Ve0hKWJzKljOVFyBkje_aHFyjyvewJGyHk60nV6inI" })
        .then(token => {
            fetch("/php/ajax-update-firebase-token.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ token: token })
            })
            .then(response => response.json())
            .catch(error => console.error("🚨 AJAX Hatası:", error));
        })
        .catch(error => console.error("🚨 Firebase Token Alma Hatası:", error));
}

// Kullanıcı giriş yapmışsa (remember_me çerezi varsa) Firebase Token'ı güncelle
if (document.cookie.includes('remember_me')) {
    updateFirebaseTokenAfterLogin();
}

// Bildirim alındığında çalışacak kod
onMessage(messaging, payload => {
    new Notification(payload.notification.title, {
        body: payload.notification.body,
        icon: payload.notification.icon
    });
});
