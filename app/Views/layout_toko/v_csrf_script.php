<script>
// ==================== CSRF Global (setara Laravel) ====================
// Otomatis menyuntikkan token CSRF ke semua form POST dan menambahkan
// header X-CSRF-TOKEN pada permintaan AJAX (fetch & $.ajax) non-GET.
(function() {
    var csrfToken = '<?= csrf_token() ?>';
    var csrfName = '<?= csrf_token_name() ?>';
    var csrfHeader = 'X-CSRF-TOKEN';

    // 1) Form biasa: suntikkan hidden field csrf saat submit bila belum ada.
    document.addEventListener('submit', function(e) {
        var form = e.target;
        if (!form || form.tagName !== 'FORM') return;
        var method = (form.getAttribute('method') || 'get').toLowerCase();
        if (method === 'get') return;
        if (form.querySelector('input[name="' + csrfName + '"]')) return;

        var input = document.createElement('input');
        input.type = 'hidden';
        input.name = csrfName;
        input.value = csrfToken;
        form.appendChild(input);
    }, true);

    // 2) fetch(): tambahkan header X-CSRF-TOKEN utk metode non-GET.
    var origFetch = window.fetch;
    if (origFetch) {
        window.fetch = function(input, init) {
            init = init || {};
            init.credentials = init.credentials || 'same-origin';
            var method = (init.method || '').toUpperCase();
            if (!method && typeof input === 'string') method = 'GET';
            if (method && method !== 'GET' && method !== 'HEAD') {
                var headers = init.headers || {};
                if (headers instanceof Headers) {
                    headers.set(csrfHeader, csrfToken);
                } else {
                    headers[csrfHeader] = csrfToken;
                }
                init.headers = headers;
            }
            return origFetch.call(this, input, init);
        };
    }

    // 3) jQuery.ajax()/$.post(): tambahkan header X-CSRF-TOKEN utk non-GET.
    if (window.jQuery && window.jQuery.ajax) {
        var origAjax = window.jQuery.ajax;
        window.jQuery.ajax = function(url, settings) {
            var s = (typeof url === 'string') ? settings : url;
            s = s || {};
            var type = ((s.type || s.method || 'GET') + '').toUpperCase();
            if (type !== 'GET' && type !== 'HEAD') {
                s.headers = s.headers || {};
                s.headers[csrfHeader] = csrfToken;
            }
            return origAjax.call(this, url, s);
        };
    }
})();
// ==================== Akhir CSRF Global ====================
</script>
