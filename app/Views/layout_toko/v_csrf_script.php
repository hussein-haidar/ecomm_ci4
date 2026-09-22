<script>
// ==================== CSRF Global (setara Laravel) ====================
// Token CSRF diambil SEGAR dari server sesaat sebelum submit/AJAX, sehingga
// selalu cocok dengan session aktif. Ini menghindari 403 saat session
// sempat kedaluwarsa (token lama di halaman vs hash baru di server).
(function() {
    var csrfToken = '<?= csrf_hash() ?>';
    var csrfName = '<?= config('Security')->tokenName ?>';
    var csrfHeader = 'X-CSRF-TOKEN';
    var csrfUrl = '<?= site_url('auth/csrf') ?>';
    var lastSync = 0;

    // Ambil token terbaru dari server (GET auth/csrf). Bila session lama
    // sudah mati, GET ini membuat session baru & tokennya yang dipakai POST.
    function ambilTokenSinkron(maxAgeSec) {
        maxAgeSec = maxAgeSec || 0;
        if (Date.now() - lastSync >= maxAgeSec * 1000) {
            try {
                var xhr = new XMLHttpRequest();
                xhr.open('GET', csrfUrl, false);
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                xhr.send(null);
                if (xhr.status === 200) {
                    var data = JSON.parse(xhr.responseText);
                    if (data && data.token) {
                        csrfToken = data.token;
                    }
                }
            } catch (e) {}
            lastSync = Date.now();
        }
        return csrfToken;
    }

    // Perbarui (atau buat) hidden field CSRF di form.
    function injectCsrf(form) {
        if (!form || form.tagName !== 'FORM') return;
        var method = (form.getAttribute('method') || 'get').toLowerCase();
        if (method === 'get') return;

        var token = ambilTokenSinkron(0); // selalu segar sebelum submit

        var input = form.querySelector('input[name="' + csrfName + '"]');
        if (input) {
            input.value = token;
        } else {
            input = document.createElement('input');
            input.type = 'hidden';
            input.name = csrfName;
            input.value = token;
            form.appendChild(input);
        }
    }

    // 1) Form biasa (event 'submit').
    document.addEventListener('submit', function(e) {
        injectCsrf(e.target);
    }, true);

    // 1b) form.submit() programatik tidak memicu event 'submit'.
    var origSubmit = HTMLFormElement.prototype.submit;
    HTMLFormElement.prototype.submit = function() {
        injectCsrf(this);
        return origSubmit.apply(this, arguments);
    };

    // 2) fetch(): tambahkan header X-CSRF-TOKEN utk metode non-GET.
    var origFetch = window.fetch;
    if (origFetch) {
        window.fetch = function(input, init) {
            init = init || {};
            init.credentials = init.credentials || 'same-origin';
            var method = (init.method || '').toUpperCase();
            if (!method && typeof input === 'string') method = 'GET';
            if (method && method !== 'GET' && method !== 'HEAD') {
                // Token segar (maks 60 detik) agar AJAX juga selalu valid.
                var token = ambilTokenSinkron(60);
                var headers = init.headers || {};
                if (headers instanceof Headers) {
                    headers.set(csrfHeader, token);
                } else {
                    headers[csrfHeader] = token;
                }
                init.headers = headers;
            }
            return origFetch.call(this, input, init);
        };
    }

    // 3) jQuery.ajax()/$.post(): tambahkan header X-CSRF-TOKEN utk non-GET.
    //    Dipanggil ulang setelah DOM siap karena jQuery bisa dimuat BELAKANGAN.
    function patchJqueryAjax() {
        if (window.jQuery && window.jQuery.ajax && !window.jQuery.ajax.__csrf_patched) {
            var origAjax = window.jQuery.ajax;
            window.jQuery.ajax = function(url, settings) {
                var s = (typeof url === 'string') ? settings : url;
                s = s || {};
                var type = ((s.type || s.method || 'GET') + '').toUpperCase();
                if (type !== 'GET' && type !== 'HEAD') {
                    s.headers = s.headers || {};
                    s.headers[csrfHeader] = ambilTokenSinkron(60);
                }
                return origAjax.call(this, url, s);
            };
            window.jQuery.ajax.__csrf_patched = true;
        }
    }
    patchJqueryAjax();
    document.addEventListener('DOMContentLoaded', patchJqueryAjax);
    if (window.jQuery && window.jQuery.ready) {
        window.jQuery(patchJqueryAjax);
    }
})();
// ==================== Akhir CSRF Global ====================
</script>