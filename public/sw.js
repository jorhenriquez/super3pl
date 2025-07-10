self.addEventListener('install', function(e) {
  console.log('Service Worker instalado');
});

self.addEventListener('fetch', function(event) {
  // Puedes personalizar el cache aquí
});
