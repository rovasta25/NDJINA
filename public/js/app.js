(function () {
    'use strict';

    /* ---------- Animations d'apparition au scroll ---------- */
    var reveals = document.querySelectorAll('.reveal');
    if ('IntersectionObserver' in window && reveals.length) {
        var io = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    io.unobserve(entry.target);
                }
            });
        }, { threshold: 0.12 });
        reveals.forEach(function (el) { io.observe(el); });
    } else {
        reveals.forEach(function (el) { el.classList.add('visible'); });
    }

    /* ---------- Apparition en cascade des cartes ---------- */
    var staggers = document.querySelectorAll('.stagger');
    if ('IntersectionObserver' in window && staggers.length) {
        var so = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    var children = entry.target.children;
                    for (var i = 0; i < children.length; i++) {
                        children[i].style.transitionDelay = (0.08 * i) + 's';
                    }
                    entry.target.classList.add('in');
                    so.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });
        staggers.forEach(function (el) { so.observe(el); });
    } else {
        staggers.forEach(function (el) { el.classList.add('in'); });
    }

    /* ---------- Menu mobile ---------- */
    var navToggle = document.getElementById('nav-toggle');
    var mainNav = document.getElementById('main-nav');
    if (navToggle && mainNav) {
        navToggle.addEventListener('click', function () {
            mainNav.classList.toggle('open');
        });
    }

    /* ---------- Toasts : disparition auto ---------- */
    var toasts = document.querySelectorAll('.flash-wrap .toast');
    toasts.forEach(function (t) {
        setTimeout(function () {
            t.style.transition = 'opacity .4s, transform .4s';
            t.style.opacity = '0';
            t.style.transform = 'translateX(24px)';
            setTimeout(function () { t.remove(); }, 420);
        }, 5000);
    });

    /* ---------- Modale de confirmation ---------- */
    var modal = document.getElementById('confirm-modal');
    if (modal) {
        var confirmOk = document.getElementById('confirm-ok');
        var confirmTitle = document.getElementById('confirm-title');
        var confirmText = document.getElementById('confirm-text');
        var pendingForm = null;

        var closeModal = function () {
            modal.hidden = true;
            pendingForm = null;
        };

        document.querySelectorAll('[data-modal-close]').forEach(function (b) {
            b.addEventListener('click', closeModal);
        });

        modal.addEventListener('click', function (e) {
            if (e.target === modal) closeModal();
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && !modal.hidden) closeModal();
        });

        confirmOk.addEventListener('click', function () {
            if (pendingForm) {
                var f = pendingForm;
                closeModal();
                f.submit();
            }
        });

        document.querySelectorAll('form[data-confirm]').forEach(function (form) {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                pendingForm = form;
                confirmTitle.textContent = 'Confirmer l\'action';
                confirmText.textContent = form.getAttribute('data-confirm') || 'Êtes-vous sûr ?';
                modal.hidden = false;
            });
        });
    }

    /* ---------- Liste des alertes : temps réel + filtres ---------- */
    var listEl = document.getElementById('alerts-list');

    if (listEl && window.alertesApi) {
        var filters = {
            q: document.getElementById('f-q'),
            groupe: document.getElementById('f-groupe'),
            urgence: document.getElementById('f-urgence'),
            periode: document.getElementById('f-periode'),
            ville: document.getElementById('f-ville')
        };

        var L = window.labels || {};
        var timer = null;

        var esc = function (s) {
            var d = document.createElement('div');
            d.textContent = s == null ? '' : String(s);
            return d.innerHTML;
        };

        var load = function () {
            var params = new URLSearchParams();
            Object.keys(filters).forEach(function (k) {
                var v = filters[k] && filters[k].value;
                if (v) params.set(k, v);
            });

            fetch(window.alertesApi + '?' + params.toString(), {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            })
            .then(function (r) { return r.json(); })
            .then(function (res) {
                render(res.data || []);
            })
            .catch(function () { /* laisser la liste telle quelle */ });
        };

        var render = function (items) {
            if (!items.length) {
                listEl.innerHTML = '<div class="empty-state"><h3>Aucune alerte</h3><p class="muted">' +
                    esc(L.empty || '') + '</p></div>';
                return;
            }

            var html = items.map(function (a) {
                var poche = a.quantite > 1 ? L.poches : L.poche;
                var rep = a.reponses_count > 1 ? L.reponses : L.reponse;
                var lieu = a.lieu ? a.lieu + ' · ' : '';
                var url = window.alerteBase.replace('__ID__', a.id);

                return '<a href="' + esc(url) + '" class="alert-item ' + esc(a.urgence) + (a.mine ? ' mine' : '') + '">' +
                    '<div class="alert-item-groupe"><span class="groupe-badge">' + esc(a.groupe) + '</span>' +
                    '<span class="urgence-badge urgence-' + esc(a.urgence) + '">' + esc(a.urgence_label) + '</span></div>' +
                    '<div class="alert-item-body">' +
                    '<div class="alert-item-title">' + esc(a.quantite) + ' ' + esc(poche) + ' de sang ' + esc(a.groupe) +
                    (a.mine ? ' <span class="mine-badge">Votre alerte</span>' : '') + '</div>' +
                    '<div class="alert-item-meta">' +
                    '<span><svg class="icon"><use href="#i-pin"></use></svg>' + esc(lieu) + esc(a.ville) + '</span>' +
                    '<span><svg class="icon"><use href="#i-clock"></use></svg>' + esc(a.temps) + '</span>' +
                    '<span><svg class="icon"><use href="#i-users"></use></svg>' + esc(a.reponses_count) + ' ' + esc(rep) + '</span>' +
                    '</div></div>' +
                    '<div class="alert-item-phone"><svg class="icon"><use href="#i-phone"></use></svg> ' + esc(a.user.phone) + '</div>' +
                    '<span class="alert-item-cta">' + esc(L.voir || 'Voir') + ' <svg class="icon"><use href="#i-arrow"></use></svg></span>' +
                    '</a>';
            }).join('');

            listEl.innerHTML = html;
        };

        var scheduleReload = function () {
            clearTimeout(timer);
            timer = setTimeout(load, 350);
        };

        Object.keys(filters).forEach(function (k) {
            var el = filters[k];
            if (el) {
                el.addEventListener('input', scheduleReload);
                el.addEventListener('change', scheduleReload);
            }
        });

        load();
        setInterval(load, 20000);
    }
})();
