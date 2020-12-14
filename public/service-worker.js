var CACHE_NAME = 'cache-v1';
var urlsToCache = [
  '/',
  '/favicon.png',
  '/css/custom.css',
  '/css/slider.css',
  '/css/placeholder-loading.css',
  '/bower_components/bootstrap/dist/css/bootstrap.min.css',
  '/bower_components/font-awesome/css/font-awesome.min.css',
  '/bower_components/font-awesome/fonts/fontawesome-webfont.eot',
  '/bower_components/font-awesome/fonts/fontawesome-webfont.svg',
  '/bower_components/font-awesome/fonts/fontawesome-webfont.ttf',
  '/bower_components/font-awesome/fonts/fontawesome-webfont.woff',
  '/bower_components/font-awesome/fonts/fontawesome-webfont.woff2',
  '/bower_components/font-awesome/fonts/FontAwesome.otf',
  '/bower_components/admin-lte/dist/css/AdminLTE.min.css',
  '/bower_components/jquery/dist/jquery.min.js',
  '/js/custom.js',
  '/bower_components/bootstrap/dist/js/bootstrap.min.js',
  '/bower_components/admin-lte/dist/js/adminlte.min.js',
  '/offline.html'
];

self.addEventListener('install', function(event) {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(function(cache) {
        console.log('Opened cache');
        return cache.addAll(urlsToCache);
      })
  );
});

self.addEventListener('fetch', function(event) {
  event.respondWith(
    // Try the cache
    caches.match(event.request).then(function(response) {
      if (response) {
        return response;
      }
      return fetch(event.request).then(function(response) {
        if (response.status === 404) {
          return caches.match('pages/404.html');
        }
        return response
      });
    }).catch(function() {
      // If both fail, show a generic fallback:
      return caches.match('/offline.html');
    })
  );
  // event.respondWith(
  //   caches.match(event.request)
  //     .then(function(response) {
  //       if (response) {
  //         return response;
  //       }
  //       var fetchRequest = event.request.clone();

  //       return fetch(fetchRequest).then(
  //         function(response) {
  //           if(!response || response.status !== 200 || response.type !== 'basic') {
  //             return response;
  //           }
  //           var responseToCache = response.clone();

  //           caches.open(CACHE_NAME)
  //             .then(function(cache) {
  //               cache.put(event.request, responseToCache);
  //             });

  //           return response;
  //         }
  //       );
  //     })
  //   );
});

self.addEventListener('activate', function(event) {
  event.waitUntil(
    caches.keys().then(function(cacheNames) {
      return Promise.all(
        cacheNames.filter(function(cacheName) {
          // Return true if you want to remove this cache,
          // but remember that caches are shared across
          // the whole origin
        }).map(function(cacheName) {
          return caches.delete(cacheName);
        })
      );
    })
  );
});