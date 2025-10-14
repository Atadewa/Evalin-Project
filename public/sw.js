const CACHE_NAME = 'evalin-static-v1';
const RUNTIME_CACHE = 'evalin-runtime-v1';
const OFFLINE_URL = '/offline.html';

const PRECACHE_URLS = [
  '/',
  '/css/app.css',
  '/js/app.js',
  '/favicon.ico',
  OFFLINE_URL
];

self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME).then((cache) => {
      return cache.addAll(PRECACHE_URLS);
    }).then(() => self.skipWaiting())
  );
});

self.addEventListener('activate', (event) => {
  event.waitUntil(
    (async () => {
      const keys = await caches.keys();
      await Promise.all(
        keys.map((key) => {
          if (key !== CACHE_NAME && key !== RUNTIME_CACHE) {
            return caches.delete(key);
          }
        })
      );
      await self.clients.claim();
    })()
  );
});

// Fetch handler: cache-first for navigation and precached assets, network-first for API calls
self.addEventListener('fetch', (event) => {
  const { request } = event;

  // Only handle GET
  if (request.method !== 'GET') return;

  const requestUrl = new URL(request.url);

  // API requests: network-first, fallback to cache
  if (requestUrl.pathname.startsWith('/siswa/ujian') || requestUrl.pathname.startsWith('/api')) {
    event.respondWith(
      fetch(request)
        .then((resp) => {
          const copy = resp.clone();
          caches.open(RUNTIME_CACHE).then((cache) => cache.put(request, copy));
          return resp;
        })
        .catch(() => caches.match(request).then((r) => r || caches.match(OFFLINE_URL)))
    );
    return;
  }

  // For navigation (HTML) requests, return network-first then fallback to offline page
  if (request.mode === 'navigate') {
    event.respondWith(
      fetch(request)
        .then((resp) => {
          const copy = resp.clone();
          caches.open(RUNTIME_CACHE).then((cache) => cache.put(request, copy));
          return resp;
        })
        .catch(() => caches.match(request).then((r) => r || caches.match(OFFLINE_URL)))
    );
    return;
  }

  // For other requests: cache-first
  event.respondWith(
    caches.match(request).then((cached) => cached || fetch(request).then((resp) => {
      if (!resp || resp.status !== 200) return resp;
      const copy = resp.clone();
      caches.open(RUNTIME_CACHE).then((cache) => cache.put(request, copy));
      return resp;
    }).catch(() => caches.match(OFFLINE_URL)))
  );
});

// Listen for messages (skipWaiting)
self.addEventListener('message', (event) => {
  if (event.data && event.data.type === 'SKIP_WAITING') {
    self.skipWaiting();
  }
});
