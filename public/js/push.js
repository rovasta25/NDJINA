(function () {
    var root = document.querySelector('[data-push]');
    if (!root) return;

    var button = root.querySelector('[data-push-btn]');
    var status = root.querySelector('[data-push-status]');
    var vapidKey = root.getAttribute('data-vapid');
    var endpoint = root.getAttribute('data-url');
    var csrf = root.getAttribute('data-csrf');
    var swUrl = root.getAttribute('data-sw') || '/sw.js';

    var supported = 'serviceWorker' in navigator && 'PushManager' in window && 'Notification' in window;

    if (!supported || !vapidKey) {
        if (button) button.style.display = 'none';
        if (status) status.textContent = 'Notifications non disponibles sur ce navigateur.';
        return;
    }

    var setStatus = function (text) {
        if (status) status.textContent = text;
    };

    var setButton = function (visible, label) {
        if (!button) return;
        button.style.display = visible ? '' : 'none';
        if (label) button.textContent = label;
    };

    var urlBase64ToUint8Array = function (base64String) {
        var padding = '='.repeat((4 - base64String.length % 4) % 4);
        var base64 = (base64String + padding).replace(/-/g, '+').replace(/_/g, '/');
        var raw = window.atob(base64);
        var output = new Uint8Array(raw.length);

        for (var i = 0; i < raw.length; ++i) {
            output[i] = raw.charCodeAt(i);
        }

        return output;
    };

    var save = function (subscription) {
        var json = subscription.toJSON();

        return fetch(endpoint, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrf
            },
            body: JSON.stringify({
                endpoint: json.endpoint,
                keys: json.keys,
                contentEncoding: (json.keys && json.keys.contentEncoding) || 'aes128gcm'
            })
        });
    };

    var refresh = function () {
        return navigator.serviceWorker.register(swUrl).then(function (registration) {
            return navigator.serviceWorker.ready.then(function (ready) {
                return ready.pushManager.getSubscription();
            });
        });
    };

    refresh().then(function (subscription) {
        if (subscription) {
            save(subscription).catch(function () {});
            setStatus('Les notifications sont activées sur cet appareil.');
            setButton(true, 'Désactiver');
            button.setAttribute('data-active', '1');
        } else if (Notification.permission === 'granted') {
            setStatus('Autorisation accordée : activez pour recevoir les alertes.');
            setButton(true, 'Activer les notifications');
        } else if (Notification.permission === 'denied') {
            setStatus('Notifications bloquées dans les réglages du navigateur.');
            setButton(false);
        } else {
            setStatus('Recevez une alerte dès qu\'un besoin est publié.');
            setButton(true, 'Activer les notifications');
        }
    }).catch(function () {
        setStatus('Notifications indisponibles pour le moment.');
        setButton(false);
    });

    if (!button) return;

    button.addEventListener('click', function () {
        button.disabled = true;

        if (button.getAttribute('data-active') === '1') {
            refresh().then(function (subscription) {
                if (!subscription) return;
                var body = JSON.stringify({ endpoint: subscription.endpoint });

                return fetch(endpoint, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrf
                    },
                    body: body
                }).then(function () {
                    return subscription.unsubscribe();
                });
            }).then(function () {
                button.removeAttribute('data-active');
                setButton(true, 'Activer les notifications');
                setStatus('Notifications désactivées sur cet appareil.');
            }).catch(function () {
                setStatus('Impossible de désactiver les notifications.');
            }).then(function () {
                button.disabled = false;
            });

            return;
        }

        Notification.requestPermission().then(function (permission) {
            if (permission !== 'granted') {
                setStatus('Autorisation refusée.');
                button.disabled = false;
                return;
            }

            return navigator.serviceWorker.register(swUrl)
                .then(function () { return navigator.serviceWorker.ready; })
                .then(function (ready) {
                    return ready.pushManager.subscribe({
                        userVisibleOnly: true,
                        applicationServerKey: urlBase64ToUint8Array(vapidKey)
                    });
                })
                .then(function (subscription) {
                    return save(subscription).then(function () {
                        button.setAttribute('data-active', '1');
                        setButton(true, 'Désactiver');
                        setStatus('Notifications activées sur cet appareil.');
                    });
                });
        }).catch(function () {
            setStatus('Activation impossible : vérifiez le navigateur.');
        }).then(function () {
            button.disabled = false;
        });
    });
})();
