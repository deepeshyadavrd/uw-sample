const CACHE_NAME = "urbanwood-static-v1";
const ASSETS = [
  // "./", // homepage
  "./catalog/view/javascript/assets/css/main2.css",
  "./catalog/view/javascript/assets/css/bootstrap.min.css",
  "./catalog/view/javascript/assets/js/3.2.1-jquery.min.js",
  "./catalog/view/javascript/assets/image/urbanwoodlogo.png",
  // Add more static assets here (CSS, JS, images)
];

// Install & cache assets
self.addEventListener("install", event => {
  event.waitUntil(
    caches.open(CACHE_NAME).then(cache => {
      return cache.addAll(ASSETS);
    })
  );
  self.skipWaiting();
});

// Activate and clean old cache
self.addEventListener("activate", event => {
  event.waitUntil(
    caches.keys().then(keys =>
      Promise.all(
        keys.map(key => {
          if (key !== CACHE_NAME) return caches.delete(key);
        })
      )
    )
  );
  self.clients.claim();
});

// Serve from cache first
// self.addEventListener("fetch", event => {
//   event.respondWith(
//     caches.match(event.request).then(res => {
//       return res || fetch(event.request);
//     })
//   );
// });

self.addEventListener('fetch', function (event) {
  const request = event.request;

  // Only cache GET requests for images
  if (request.method === 'GET' && request.destination === 'image') {
    event.respondWith(
      caches.open('image-cache-v1').then(function (cache) {
        return cache.match(request).then(function (cachedResponse) {
          if (cachedResponse) {
            return cachedResponse;
          }

          return fetch(request).then(function (networkResponse) {
            cache.put(request, networkResponse.clone());
            return networkResponse;
          });
        });
      })
    );
  }
});

// self.addEventListener('fetch', event => {
//   // Bypass CSS cache on development
//   if (event.request.url.includes('.css') && 
//       event.request.url.includes('localhost')) {
//     event.respondWith(
//       fetch(event.request) // Always fetch fresh CSS
//         .catch(() => caches.match(event.request))
//     );
//     return;
//   }

//   // For images: Cache with network fallback
//   if (event.request.destination === 'image') {
//     event.respondWith(
//       caches.match(event.request)
//         .then(cached => cached || fetch(event.request))
//     );
//   }
// });
