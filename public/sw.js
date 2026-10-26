self.addEventListener('install', function () {
    self.skipWaiting();
});

self.addEventListener('activate', function (event) {
    event.waitUntil(self.clients.claim());
});

self.addEventListener('push', function (event) {
    var data = {};

    try {
        data = event.data ? event.data.json() : {};
    } catch (e) {
        data = { title: 'NDJINA', body: event.data ? event.data.text() : '' };
    }

    var title = data.title || 'NDJINA';
    var options = {
        body: data.body || 'Un nouveau besoin de sang vient d\'être publié.',
        icon: 'icon-192.png',
        badge: 'icon-192.png',
        tag: data.tag || 'ndjina',
        data: { url: data.url || '/' },
        vibrate: [120, 60, 120]
    };

    event.waitUntil(self.registration.showNotification(title, options));
});

self.addEventListener('notificationclick', function (event) {
    event.notification.close();

    var url = (event.notification.data && event.notification.data.url) || '/';

    event.waitUntil(
        self.clients.matchAll({ type: 'window', includeUncontrolled: true }).then(function (list) {
            for (var i = 0; i < list.length; i++) {
                if (list[i].url.indexOf(url) !== -1 && 'focus' in list[i]) {
                    return list[i].focus();
                }
            }

            if (self.clients.openWindow) {
                return self.clients.openWindow(url);
            }
        })
    );
});
