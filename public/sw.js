const CACHE_NAME = 'fz-rent-cache-v2';
const urlsToCache = [
  '/',
  '/manifest.json'
];

self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => {
        return cache.addAll(urlsToCache);
      })
  );
  self.skipWaiting();
});

// Hapus cache versi lama saat aktivasi
self.addEventListener('activate', event => {
  event.waitUntil(
    caches.keys().then(cacheNames => {
      return Promise.all(
        cacheNames.map(cacheName => {
          if (cacheName !== CACHE_NAME) {
            return caches.delete(cacheName);
          }
        })
      );
    })
  );
  self.clients.claim();
});

// Strategi Network-First: Ambil dari server dulu, jika gagal (offline) baru ambil dari cache
self.addEventListener('fetch', event => {
  // Hanya proses GET request
  if (event.request.method !== 'GET') return;

  event.respondWith(
    fetch(event.request)
      .then(response => {
        // Jangan simpan cache jika response error atau bukan basic HTTP
        if (!response || response.status !== 200 || response.type !== 'basic') {
          return response;
        }

        // Klon response untuk disimpan ke cache
        const responseToCache = response.clone();
        caches.open(CACHE_NAME)
          .then(cache => {
            cache.put(event.request, responseToCache);
          });

        return response;
      })
      .catch(() => {
        // Jika offline atau jaringan gagal, ambil dari cache
        return caches.match(event.request);
      })
  );
});