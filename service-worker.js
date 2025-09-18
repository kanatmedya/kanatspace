const CACHE_NAME = "3.2.1.8";
const urlsToCache = [
  "/assets/css/style.css",
  "uploads/logo/icon-192x192.png",
  "uploads/logo/icon-512x512.png"
];

// Install service worker
self.addEventListener("install", (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then((cache) => {
        return cache.addAll(urlsToCache);
      })
      .catch((error) => {
        console.error("Failed to open cache:", error);
      })
  );
});

// Cache and return requests
self.addEventListener("fetch", (event) => {
  event.respondWith(
    caches.match(event.request)
      .then((response) => {
        if (response) {
          return response;
        }
        return fetch(event.request);
      })
  );
});

// Update service worker
self.addEventListener("activate", (event) => {
  const cacheWhitelist = [CACHE_NAME];
  event.waitUntil(
    caches.keys().then((cacheNames) => {
      return Promise.all(
        cacheNames.map((cacheName) => {
          if (cacheWhitelist.indexOf(cacheName) === -1) {
            return caches.delete(cacheName);
          }
        })
      );
    })
  );
});

if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('/service-worker.js')
    .then(function(swReg) {
        
    })
    .catch(function(error) {
        console.error('Service Worker Error', error);
    });
}
