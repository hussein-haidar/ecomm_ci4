<?php
$cp_nama_pelanggan = session()->get('nama_pelanggan');
if (empty($cp_nama_pelanggan)) {
    return;
}
$cp_toko = get_data_toko();
$cp_nama_toko = $cp_toko['nama_toko'] ?? 'Admin Toko';
?>

<style>
#chatPopLauncher {
    position: fixed;
    right: 20px;
    bottom: 20px;
    z-index: 1060;
    width: 58px;
    height: 58px;
    border-radius: 50%;
    font-size: 24px;
    box-shadow: 0 4px 14px rgba(0, 0, 0, .25);
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
}
#chatPopLauncher .chat-pop-badge {
    position: absolute;
    top: -4px;
    right: -4px;
    background: #dc3545;
    color: #fff;
    border-radius: 50%;
    min-width: 22px;
    height: 22px;
    font-size: 12px;
    line-height: 22px;
    text-align: center;
    padding: 0 5px;
    box-shadow: 0 0 0 2px #fff;
}
#chatPop {
    position: fixed;
    right: 20px;
    bottom: 90px;
    z-index: 1055;
    width: 360px;
    max-width: calc(100vw - 30px);
    height: 480px;
    max-height: calc(100vh - 130px);
    display: flex;
    flex-direction: column;
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 8px 30px rgba(0, 0, 0, .25);
}
#chatPop.chat-pop-hidden { display: none !important; }
.chat-pop-header {
    background: #007bff;
    color: #fff;
    padding: 12px 14px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-shrink: 0;
    gap: 8px;
}
.chat-pop-header .chat-pop-title { font-weight: 600; font-size: 14px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.chat-pop-header .btn-close-white { background: transparent; border: none; color: #fff; font-size: 20px; line-height: 1; padding: 0 4px; }
.chat-pop-body {
    flex: 1;
    overflow-y: auto;
    background: #f6f7f9;
    padding: 10px;
}
.chat-pop-body .chat-pop-empty { text-align: center; color: #999; padding: 30px 10px; }
.chat-pop-item {
    display: flex;
    align-items: center;
    gap: 10px;
    background: #fff;
    border: 1px solid #e8e8e8;
    border-radius: 10px;
    padding: 10px;
    margin-bottom: 8px;
    cursor: pointer;
    transition: box-shadow .15s;
}
.chat-pop-item:hover { box-shadow: 0 2px 8px rgba(0, 0, 0, .1); }
.chat-pop-item .chat-pop-item-avatar {
    width: 40px; height: 40px; border-radius: 50%;
    background: #007bff; color: #fff;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.chat-pop-item .chat-pop-item-info { flex: 1; min-width: 0; }
.chat-pop-item .chat-pop-item-name { font-weight: 600; font-size: 13px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.chat-pop-item .chat-pop-item-pesan { font-size: 12px; color: #777; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.chat-pop-item .chat-pop-item-meta { text-align: right; flex-shrink: 0; }
.chat-pop-item .chat-pop-item-waktu { font-size: 11px; color: #aaa; }
.chat-pop-item .chat-pop-item-unread {
    background: #dc3545; color: #fff; border-radius: 50%;
    min-width: 18px; height: 18px; font-size: 11px; line-height: 18px; text-align: center;
    display: inline-block; padding: 0 4px;
}
.chat-pop-msg { display: flex; margin-bottom: 10px; }
.chat-pop-msg.justify-content-end { justify-content: flex-end; }
.chat-pop-msg .chat-pop-bubble { max-width: 80%; padding: 8px 12px; border-radius: 12px; word-wrap: break-word; }
.chat-pop-msg .chat-pop-bubble.me { background: #007bff; color: #fff; border-bottom-right-radius: 2px; }
.chat-pop-msg .chat-pop-bubble.them { background: #fff; color: #333; border: 1px solid #e0e0e0; border-bottom-left-radius: 2px; }
.chat-pop-msg .chat-pop-nama { font-size: 12px; font-weight: 600; }
.chat-pop-msg .chat-pop-tag {
    font-size: 10px; font-weight: 600; text-transform: uppercase;
    padding: 1px 5px; border-radius: 8px; margin-left: 4px;
}
.chat-pop-msg .chat-pop-tag.tag-admin { background: #ffc107; color: #7a5c00; }
.chat-pop-msg .chat-pop-tag.tag-saya { background: rgba(255,255,255,.25); color: #fff; }
.chat-pop-msg .chat-pop-waktu { font-size: 11px; text-align: right; }
.chat-pop-msg .me .chat-pop-waktu { color: rgba(255,255,255,.7); }
.chat-pop-msg .them .chat-pop-waktu { color: #aaa; }
.chat-pop-back { background: none; border: none; color: #fff; font-size: 18px; padding: 0 6px 0 0; }
.chat-pop-footer { flex-shrink: 0; background: #fff; border-top: 1px solid #eee; padding: 8px 10px; }
.chat-pop-footer form { display: flex; gap: 6px; }
.chat-pop-footer input { flex: 1; border: 1px solid #ddd; border-radius: 20px; padding: 8px 14px; font-size: 13px; outline: none; }
.chat-pop-footer input:focus { border-color: #007bff; }
.chat-pop-footer button { background: #007bff; border: none; color: #fff; border-radius: 50%; width: 38px; height: 38px; flex-shrink: 0; }
</style>

<button type="button" id="chatPopLauncher" class="btn btn-primary" title="Chat dengan admin" onclick="bukaChatPopUp(event)">
    <i class="fas fa-comments"></i>
    <span id="chatPopBadge" class="chat-pop-badge d-none">0</span>
</button>

<div id="chatPop" class="chat-pop-hidden">
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
        <form id="chatPopForm" autocomplete="off">
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
let chatPopProduk = null;
let chatPopIdStok = null;
let chatPopTimer = null;

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
    return chatPopApiPesan + encodeURIComponent(chatPopSaya) + '/' + encodeURIComponent(namaProduk);
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
    chatPopTampilPercakapan(namaProduk);
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

function chatPopTampilPercakapan(namaProduk, idStok) {
    chatPopProduk = namaProduk;
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

function bukaChatProduk(event, namaProduk, idStok) {
    if (event) event.preventDefault();
    document.getElementById('chatPop').classList.remove('chat-pop-hidden');
    chatPopTampilPercakapan(namaProduk, idStok);
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
                    html += '<div class="chat-pop-item" data-produk="' + encodeURIComponent(c.nama_produk) + '" data-id-stok="' + idStok + '" onclick="chatPopItemClick(this)">'
                        + '<div class="chat-pop-item-avatar"><i class="fas fa-store"></i></div>'
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
    badge.textContent = total;
    badge.classList.toggle('d-none', total <= 0);
    const navBadge = document.getElementById('navbar-chat-count');
    if (navBadge) {
        navBadge.textContent = total;
        navBadge.classList.toggle('d-none', total <= 0);
    }
}

function chatPopRenderPesan(messages) {
    const box = document.getElementById('chatPopBody');
    if (!messages.length) {
        box.innerHTML = '<div class="chat-pop-empty"><i class="far fa-comment-dots fa-2x d-block mb-2"></i>Belum ada pesan.<br>Mulai percakapan dengan admin tentang produk ini!</div>';
        return;
    }
    let html = '';
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
        html += '<div class="chat-pop-msg ' + (isMe ? 'justify-content-end' : '') + '">'
            + '<div class="chat-pop-bubble ' + (isMe ? 'me' : 'them') + '">'
            + '<div class="chat-pop-nama ' + (isMe ? 'text-white-50' : 'text-muted') + '">' + chatPopEscHtml(nama) + tag + '</div>'
            + '<div>' + chatPopEscHtml(m.pesan) + '</div>'
            + '<div class="chat-pop-waktu">' + chatPopEscHtml(chatPopFormatWaktu(m.waktu_chat)) + '</div>'
            + '</div></div>';
    });
    box.innerHTML = html;
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
</script>
