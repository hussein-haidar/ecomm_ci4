<?php
$cp_nama_pelanggan = session()->get('nama_pelanggan');
if (empty($cp_nama_pelanggan)) {
    return;
}
$cp_toko = get_data_toko();
$cp_nama_toko = $cp_toko['nama_toko'] ?? 'Admin Toko';
?>

<div id="chatPop" class="chat-pop chat-pop-hidden">
    <div class="chat-pop-header">
        <button type="button" class="chat-pop-back" id="chatPopBack" onclick="chatPopTampilInbox()" title="Kembali">
            <i class="fas fa-arrow-left"></i>
        </button>
        <div class="chat-pop-title" id="chatPopTitle"><i class="fas fa-store me-1"></i>Chat <?= esc($cp_nama_toko) ?></div>
        <button type="button" class="btn-close-white" onclick="tutupChatPop()" title="Tutup">&times;</button>
    </div>
    <div class="chat-pop-body" id="chatPopBody">
        <div class="chat-pop-empty"><i class="fa fa-spinner fa-spin"></i> Memuat pesan...</div>
    </div>
    <div class="chat-pop-footer d-none" id="chatPopFooter">
        <form id="chatPopForm" autocomplete="off" class="position-relative">
            <input type="text" id="chatPopInput" placeholder="Tulis pesan untuk admin..." maxlength="1000">
            <button type="submit" title="Kirim"><i class="fas fa-paper-plane"></i></button>
        </form>
    </div>
</div>

<script>
const chatPopSaya = <?= json_encode($cp_nama_pelanggan) ?>;
const chatPopToko = <?= json_encode($cp_nama_toko) ?>;
const chatPopApiInbox = <?= json_encode(base_url('chat_admin_pembeli/api_inbox')) ?>;
const chatPopApiKirim = <?= json_encode(base_url('chat_admin_pembeli/kirim')) ?>;
const chatPopApiPesan = <?= json_encode(base_url('chat_admin_pembeli/api_messages/')) ?>;
const baseUrlFoto = <?= json_encode(base_url('fotoproduk/')) ?>;
let chatPopProduk = null;
let chatPopIdStok = null;
let chatPopFoto = '';
let chatPopTimer = null;
let chatPopIdStokMap = {};

function chatPopEscHtml(str) {
    const div = document.createElement('div');
    div.textContent = str;
    return div.innerHTML;
}

function chatPopFormatWaktu(waktu) {
    if (!waktu) return '';
    try {
        const tgl = String(waktu).replace(' ', 'T');
        return new Date(tgl).toLocaleString('id-ID', { day: 'numeric', month: 'short', hour: '2-digit', minute: '2-digit' });
    } catch (e) { return ''; }
}

function chatPopUrlPesan(namaProduk) {
    return chatPopApiPesan + encodeURIComponent(namaProduk);
}

function chatPopConversiIdStok(namaProduk) {
    fetch(chatPopUrlPesan(namaProduk))
        .then(r => r.json())
        .then(data => {
            const msg = data.messages && data.messages.length > 0 ? data.messages[0] : null;
            if (msg && msg.id_stok) {
                chatPopIdStok = msg.id_stok;
            } else {
                chatPopIdStok = 0;
            }
        })
        .catch(() => {
            chatPopIdStok = 0;
        });
    return chatPopIdStok;
}

function chatPopItemClick(el) {
    const namaProduk = decodeURIComponent(el.getAttribute('data-produk'));
    chatPopIdStok = el.getAttribute('data-id-stok') || 0;
    const foto = el.getAttribute('data-foto') ? decodeURIComponent(el.getAttribute('data-foto')) : '';
    chatPopTampilPercakapan(namaProduk, chatPopIdStok, foto);
}

function bukaChatPopUp(event) {
    if (event) event.preventDefault();
    document.getElementById('chatPop').classList.remove('chat-pop-hidden');
    if (!chatPopProduk) {
        chatPopTampilInbox();
    } else {
        chatPopTampilPercakapan(chatPopProduk);
    }
    chatPopMulaiTimer();
}

function tutupChatPop() {
    document.getElementById('chatPop').classList.add('chat-pop-hidden');
    chatPopHentikanTimer();
}

function chatPopHentikanTimer() {
    if (chatPopTimer) { clearInterval(chatPopTimer); chatPopTimer = null; }
}

function chatPopMulaiTimer() {
    chatPopHentikanTimer();
    chatPopTimer = setInterval(function() {
        if (document.getElementById('chatPop').classList.contains('chat-pop-hidden')) {
            chatPopHentikanTimer();
            return;
        }
        if (chatPopProduk) {
            chatPopMuatPesan(chatPopProduk);
        } else {
            chatPopMuatInbox(true);
        }
    }, 3000);
}

function chatPopTampilInbox() {
    chatPopProduk = null;
    document.getElementById('chatPopFooter').classList.add('d-none');
    document.getElementById('chatPopBack').classList.add('d-none');
    document.getElementById('chatPopTitle').innerHTML = '<i class="fas fa-store me-1"></i>Chat ' + chatPopEscHtml(chatPopToko);
    chatPopMuatInbox(true);
}

function chatPopTampilPercakapan(namaProduk, idStok, fotoProduk) {
    chatPopProduk = namaProduk;
    chatPopFoto = fotoProduk || '';
    document.getElementById('chatPopFooter').classList.remove('d-none');
    document.getElementById('chatPopBack').classList.remove('d-none');
    document.getElementById('chatPopTitle').innerHTML = '<i class="fas fa-comments me-1"></i>' + chatPopEscHtml(namaProduk);
    document.getElementById('chatPopInput').focus();
    if (idStok) {
        chatPopIdStok = idStok;
    } else {
        chatPopIdStok = chatPopConversiIdStok(namaProduk);
    }
    chatPopMuatPesan(namaProduk);
}

function bukaChatProduk(event, namaProduk, idStok, fotoProduk) {
    if (event) event.preventDefault();
    document.getElementById('chatPop').classList.remove('chat-pop-hidden');
    chatPopTampilPercakapan(namaProduk, idStok, fotoProduk);
    chatPopMulaiTimer();
}

function chatPopMuatInbox(renderBody) {
    fetch(chatPopApiInbox)
        .then(function(r) { return r.json(); })
        .then(function(data) {
            const list = data.conversations || [];
            chatPopSetBadge(list.reduce(function(t, c) { return t + (parseInt(c.unread) || 0); }, 0));
            chatPopIdStokMap = {};
            if (!renderBody) return;
            const box = document.getElementById('chatPopBody');
            if (!list.length) {
                box.innerHTML = '<div class="chat-pop-empty"><i class="far fa-comment-dots fa-2x d-block mb-2"></i>Belum ada percakapan.<br>Buka halaman produk lalu klik <strong>Chat Penjual</strong>.</div>';
            } else {
                let html = '';
                list.forEach(function(c) {
                    const namaTerakhir = c.nama_pengirim
                        ? (c.pengirim_terakhir === 'admin' ? c.nama_pengirim + ' (Admin)' : 'Anda')
                        : (c.pengirim_terakhir === 'admin' ? 'Admin' : 'Anda');
                    const unread = parseInt(c.unread) || 0;
                    const idStok = c.id_stok || 0;
                    chatPopIdStokMap[encodeURIComponent(c.nama_produk)] = idStok;

                    // Foto produk di list (thread open dari inbox), fallback ke avatar toko
                    let avatarHtml;
                    if (c.foto_produk) {
                        const fotoUrl = baseUrlFoto + encodeURIComponent(c.foto_produk);
                        avatarHtml = '<div class="chat-pop-item-avatar">'
                            + '<img src="' + fotoUrl + '" alt="">'
                            + '<div class="fallback d-none"><i class="fas fa-store"></i></div>'
                            + '</div>';
                    } else {
                        avatarHtml = '<div class="chat-pop-item-avatar fallback"><i class="fas fa-store"></i></div>';
                    }

                    html += '<div class="chat-pop-item" data-produk="' + encodeURIComponent(c.nama_produk) + '" data-id-stok="' + idStok + '" data-foto="' + encodeURIComponent(c.foto_produk || '') + '" onclick="chatPopItemClick(this)">'
                        + avatarHtml
                        + '<div class="chat-pop-item-info">'
                        + '<div class="chat-pop-item-name">' + chatPopEscHtml(c.nama_produk) + '</div>'
                        + '<div class="chat-pop-item-pesan"><span>' + chatPopEscHtml(namaTerakhir) + ':</span> ' + chatPopEscHtml(c.pesan_terakhir || '') + '</div>'
                        + '</div>'
                        + '<div class="chat-pop-item-meta">'
                        + '<div class="chat-pop-item-waktu">' + chatPopEscHtml(chatPopFormatWaktu(c.waktu_terakhir)) + '</div>'
                        + (unread > 0 ? '<div class="chat-pop-item-unread">' + unread + '</div>' : '')
                        + '</div>'
                        + '</div>';
                });
                box.innerHTML = html;
            }
        })
        .catch(function() {
            if (renderBody) {
                document.getElementById('chatPopBody').innerHTML = '<div class="chat-pop-empty">Gagal memuat percakapan.</div>';
            }
        });
}

function chatPopSetBadge(total) {
    const badge = document.getElementById('chatPopBadge');
    if (badge) {
        badge.textContent = total;
        badge.classList.toggle('d-none', total <= 0);
    }
    const navBadge = document.getElementById('navbar-chat-count');
    if (navBadge) {
        navBadge.textContent = total;
        navBadge.classList.toggle('d-none', total <= 0);
    }
}

function chatPopRenderPesan(messages) {
    const box = document.getElementById('chatPopBody');

    // Kartu produk (foto + badge "Menanyakan produk ini") di atas percakapan
    let kartuHtml = '';
    if (chatPopFoto) {
        kartuHtml = '<div class="chat-pop-kartu-produk">'
            + '<div class="chat-pop-kartu-badge"><i class="fas fa-question-circle"></i> Menanyakan produk ini</div>'
            + '<div class="chat-pop-kartu-body">'
            + '<div class="chat-pop-item-avatar">'
            + '<img class="chat-pop-kartu-foto" src="' + baseUrlFoto + encodeURIComponent(chatPopFoto) + '" alt="">'
            + '<div class="fallback d-none"><i class="fas fa-box"></i></div>'
            + '</div>'
            + '<div class="chat-pop-kartu-nama">' + chatPopEscHtml(chatPopProduk) + '</div>'
            + '</div>'
            + '</div>';
    }

    let isiHtml = '';
    if (!messages.length) {
        isiHtml = '<div class="chat-pop-empty"><i class="far fa-comment-dots fa-2x d-block mb-2"></i>Belum ada pesan.<br>Mulai percakapan dengan admin tentang produk ini!</div>';
    } else {
        messages.forEach(function(m) {
            const isMe = m.pengirim === 'pelanggan';
            let nama, tag;
            if (isMe) {
                nama = 'Anda';
                tag = '<span class="chat-pop-tag tag-saya">Saya</span>';
            } else {
                nama = m.nama_pengirim || chatPopToko;
                tag = '<span class="chat-pop-tag tag-admin">Admin</span>';
            }
            isiHtml += '<div class="chat-pop-msg ' + (isMe ? 'justify-content-end' : '') + '">'
                + '<div class="chat-pop-bubble ' + (isMe ? 'me' : 'them') + '">'
                + '<div class="chat-pop-nama ' + (isMe ? 'text-white-50' : 'text-muted') + '">' + chatPopEscHtml(nama) + tag + '</div>'
                + '<div>' + chatPopEscHtml(m.pesan) + '</div>'
                + '<div class="chat-pop-waktu">' + chatPopEscHtml(chatPopFormatWaktu(m.waktu_chat)) + '</div>'
                + '</div></div>';
        });
    }
    box.innerHTML = kartuHtml + isiHtml;
    box.scrollTop = box.scrollHeight;
}

function chatPopMuatPesan(namaProduk) {
    fetch(chatPopUrlPesan(namaProduk))
        .then(function(r) { return r.json(); })
        .then(function(data) {
            chatPopRenderPesan(data.messages || []);
            chatPopMuatInbox(false);
        })
        .catch(function() {
            document.getElementById('chatPopBody').innerHTML = '<div class="chat-pop-empty">Gagal memuat pesan.</div>';
        });
}

// Perbarui badge chat di navbar secara real-time walau popup tertutup
setInterval(function() {
    if (document.getElementById('chatPop').classList.contains('chat-pop-hidden')) {
        chatPopMuatInbox(false);
    }
}, 5000);

(function() {
    const form = document.getElementById('chatPopForm');
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const input = document.getElementById('chatPopInput');
        const pesan = input.value.trim();
        if (!pesan || !chatPopProduk) return;

        const formData = new FormData();
        formData.append('nama_produk', chatPopProduk);
        formData.append('id_stok', chatPopIdStok);
        formData.append('pesan', pesan);

        fetch(chatPopApiKirim, { method: 'POST', body: formData })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data.success) {
                    input.value = '';
                    chatPopMuatPesan(chatPopProduk);
                } else if (typeof Swal !== 'undefined') {
                    Swal.fire({ icon: 'error', title: 'Oops...', text: data.message || 'Gagal mengirim pesan.' });
                } else {
                    alert(data.message || 'Gagal mengirim pesan.');
                }
            })
            .catch(function() {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({ icon: 'error', title: 'Oops...', text: 'Gagal mengirim pesan.' });
                } else {
                    alert('Gagal mengirim pesan.');
                }
            });
    });
})();

// ============= SESSION EXPIRY COUNTDOWN =============
(function() {
    const SESSION_LIFETIME_HOURS = parseInt('<?= env('SESSION_MAX_ACTIVE_HOURS', 24) ?>') || 24;
    const SESSION_WARNING_SECONDS = 30;
    let sessionExpiryTime = null;

    function formatDuration(seconds) {
        const h = Math.floor(seconds / 3600);
        const m = Math.floor((seconds % 3600) / 60);
        const s = seconds % 60;
        const parts = [];
        if (h > 0) parts.push(h + 'j');
        if (m > 0) parts.push(m + 'm');
        if (s > 0 || parts.length === 0) parts.push(s + 'd');
        return parts.join(' ');
    }

    function showSessionWarning(remaining) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: '<i class="fas fa-clock text-warning mr-2"></i>Meta Sesi!',
                html: '<div class="text-left">Sesi Anda akan habis dalam <strong>' +
                     formatDuration(remaining) + '</strong>.<br>Anda akan otomatis logout. Pilih "Perpanjang" untuk melanjutkan.</div>',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Perpanjang',
                cancelButtonText: 'Tidak, Logout',
                timer: remaining * 1000,
                timerProgressBar: true
            }).then(function(result) {
                window.location.reload();
            });
        } else {
            if (confirm('Sesi Anda akan habis dalam ' + formatDuration(remaining) + '. Klik OK untuk perpanjang atau BATAL untuk logout.')) {
                window.location.reload();
            }
        }
    }

    // Poll session expiry tiap 5 detik
    setInterval(function() {
        fetch('<?= base_url('chat_admin_pembeli/api_session_expiry') ?>', { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data.expiry_at) {
                    sessionExpiryTime = new Date(data.expiry_at).getTime();
                }
            })
            .catch(function() {});

        if (!sessionExpiryTime) return;

        const now = Date.now();
        const remainingMs = sessionExpiryTime - now;
        const remainingSec = Math.ceil(remainingMs / 1000);

        if (remainingSec <= 0) {
            sessionExpiryTime = null;
            Swal.fire({ title: 'Sesi Habis', text: 'Silakan login kembali.', icon: 'info', showConfirmButton: false, timer: 3000 })
                .then(function() { window.location.href = '<?= base_url('auth/login_user') ?>'; });
        } else if (remainingSec <= SESSION_WARNING_SECONDS) {
            showSessionWarning(remainingSec);
            sessionExpiryTime = null;
        }
    }, 5000);
})();