(function () {
    'use strict';

    function isStandalone() {
        return window.matchMedia('(display-mode: standalone)').matches ||
            window.navigator.standalone === true;
    }

    var userAgent = navigator.userAgent;
    var isIOS = /iphone|ipad|ipod/i.test(userAgent);
    var isSafari = isIOS || (/safari/i.test(userAgent) && !/chrome|crios|chromium|edg\/|opr|fxios/i.test(userAgent));
    var dismissedKey = 'pwa-install-dismissed';

    var deferredPrompt = null;
    var fab = null;
    var modal = null;

    function ensureStyles() {
        if (document.getElementById('pwa-install-styles')) return;
        var style = document.createElement('style');
        style.id = 'pwa-install-styles';
        style.textContent =
            '@keyframes pwaFadeIn { from { opacity: 0; } to { opacity: 1; } }' +
            '@keyframes pwaSlideUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }';
        document.head.appendChild(style);
    }

    function createFab() {
        if (fab || document.getElementById('pwa-install-fab')) return;
        ensureStyles();

        fab = document.createElement('div');
        fab.id = 'pwa-install-fab';
        fab.style.cssText = 'position:fixed;bottom:20px;right:20px;z-index:9999;animation:pwaSlideUp .35s ease;';

        var btn = document.createElement('button');
        btn.type = 'button';
        btn.style.cssText =
            'display:flex;align-items:center;gap:8px;padding:12px 20px;border:none;border-radius:9999px;' +
            'background:linear-gradient(135deg,#667eea,#764ba2);color:#fff;font-family:inherit;' +
            'font-size:14px;font-weight:700;cursor:pointer;box-shadow:0 10px 25px -5px rgba(102,126,234,.5);';
        btn.innerHTML =
            '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>' +
            '<span>Install App</span>';
        btn.addEventListener('click', showInstallPrompt);
        fab.appendChild(btn);
        document.body.appendChild(fab);
    }

    function hideFab() {
        if (fab) { fab.remove(); fab = null; }
    }

    function showInstallPrompt() {
        if (deferredPrompt) {
            deferredPrompt.prompt();
            deferredPrompt.userChoice.then(function (choice) {
                deferredPrompt = null;
                if (choice.outcome === 'accepted') {
                    hideFab();
                }
            });
        } else if (isSafari) {
            showIOSInstructions();
        }
    }

    function showIOSInstructions() {
        if (modal || document.getElementById('pwa-install-modal')) return;
        ensureStyles();

        modal = document.createElement('div');
        modal.id = 'pwa-install-modal';
        modal.style.cssText =
            'position:fixed;inset:0;z-index:10000;background:rgba(15,23,42,.6);' +
            'display:flex;align-items:center;justify-content:center;padding:20px;animation:pwaFadeIn .25s ease;';

        var box = document.createElement('div');
        box.style.cssText =
            'background:#fff;border-radius:20px;padding:24px;max-width:360px;width:100%;' +
            'text-align:center;box-shadow:0 25px 50px -12px rgba(0,0,0,.25);animation:pwaSlideUp .3s ease;';
        box.innerHTML =
            '<div style="width:56px;height:56px;margin:0 auto 16px;border-radius:16px;' +
            'background:linear-gradient(135deg,#667eea,#764ba2);display:flex;align-items:center;justify-content:center;">' +
            '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>' +
            '</div>' +
            '<h3 style="margin:0 0 8px;font-size:18px;font-weight:800;color:#0f172a;font-family:inherit;">Install Aplikasi</h3>' +
            '<p style="margin:0 0 16px;font-size:14px;color:#475569;line-height:1.6;">Untuk menginstall aplikasi ini di perangkat Anda:</p>' +
            '<ol style="text-align:left;margin:0 0 20px;padding-left:20px;color:#475569;font-size:14px;line-height:2;">' +
            '<li>Buka menu <strong>Bagikan</strong> di Safari ' +
            '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align:-2px;"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg></li>' +
            '<li>Ketuk <strong>&quot;Add to Home Screen&quot;</strong></li>' +
            '<li>Ketuk <strong>&quot;Add&quot;</strong></li>' +
            '</ol>' +
            '<button type="button" style="width:100%;padding:12px;border:none;border-radius:12px;' +
            'background:#e2e8f0;color:#0f172a;font-weight:700;font-size:14px;cursor:pointer;font-family:inherit;">Nanti</button>';

        box.querySelector('button').addEventListener('click', function () {
            try { localStorage.setItem(dismissedKey, '1'); } catch (e) {}
            hideModal();
            hideFab();
        });
        modal.appendChild(box);
        document.body.appendChild(modal);
    }

    function hideModal() {
        if (modal) { modal.remove(); modal = null; }
    }

    window.addEventListener('beforeinstallprompt', function (e) {
        e.preventDefault();
        deferredPrompt = e;
        createFab();
    });

    window.addEventListener('appinstalled', function () {
        deferredPrompt = null;
        hideFab();
    });

    window.addEventListener('pwa-install-show', function () {
        showInstallPrompt();
    });

    if (isStandalone()) return;

    if (isIOS) {
        var dismissed = false;
        try { dismissed = localStorage.getItem(dismissedKey) === '1'; } catch (e) {}
        if (!dismissed) setTimeout(createFab, 1500);
    }
})();
