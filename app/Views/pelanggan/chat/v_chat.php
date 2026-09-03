<?= view('layout_toko/v_navbar.php') ?>

<?php
$nama_pelanggan = session()->get('nama_pelanggan');
$produk_id_stok = isset($produk) && isset($produk['id_stok']) ? $produk['id_stok'] : 0;
$apiUrl = base_url('chat_admin_pembeli/api_messages/' . urlencode($nama_pelanggan) . '/' . urlencode($produk['nama_produk']));
$kirimUrl = base_url('chat_admin_pembeli/kirim');
$detailUrl = base_url('home_toko/detail_produk/' . urlencode($produk['nama_produk']));
?>

<body>
<main class="product-section">
    <div class="container my-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex flex-wrap align-items-center gap-2">
                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold flex-shrink-0" style="width:42px;height:42px;">
                    <i class="fas fa-store"></i>
                </div>
                <div>
                    <div class="fw-bold"><?= esc($produk['nama_produk']) ?></div>
                    <div class="small text-muted"><i class="fas fa-user"></i> <?= get_data_toko()['nama_toko'] ?? 'Admin Toko' ?></div>
                </div>
                <a href="<?= $detailUrl ?>" class="btn btn-sm btn-outline-secondary ms-auto">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
            <div class="card-body">
                <div id="chat-box" class="chat-box">
                    <div class="text-center text-muted py-4"><i class="fa fa-spinner fa-spin"></i> Memuat pesan...</div>
                </div>
                <form id="form-chat" class="d-flex gap-2 mt-3">
                    <input type="hidden" name="nama_produk" value="<?= esc($produk['nama_produk']) ?>">
                    <input type="hidden" name="id_stok" value="<?= esc($produk_id_stok) ?>">
                    <input type="text" name="pesan" id="input-pesan" class="form-control" placeholder="Tulis pesan tentang produk ini..." maxlength="1000" autocomplete="off">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i></button>
                </form>
            </div>
        </div>
    </div>
</main>

<style>
.chat-box {
    height: 420px;
    overflow-y: auto;
    background: #f7f7f7;
    border: 1px solid #e5e5e5;
    border-radius: 8px;
    padding: 15px;
}
.chat-bubble {
    max-width: 75%;
    padding: 8px 12px;
    border-radius: 12px;
    word-wrap: break-word;
}
.chat-bubble.me {
    background: #007bff;
    color: #fff;
    border-bottom-right-radius: 2px;
}
.chat-bubble.them {
    background: #fff;
    color: #333;
    border: 1px solid #e0e0e0;
    border-bottom-left-radius: 2px;
}
</style>

<script>
const namaProduk = <?= json_encode($produk['nama_produk']) ?>;
const pengirimSaya = 'pelanggan';
const apiUrl = <?= json_encode($apiUrl) ?>;
const kirimUrl = <?= json_encode($kirimUrl) ?>;

function escHtml(str) {
    const div = document.createElement('div');
    div.textContent = str;
    return div.innerHTML;
}

function formatWaktu(waktu) {
    if (!waktu) return '';
    const tgl = String(waktu).replace(' ', 'T');
    return new Date(tgl).toLocaleString('id-ID', { day: 'numeric', month: 'short', hour: '2-digit', minute: '2-digit' });
}

function renderMessages(messages) {
    const box = document.getElementById('chat-box');
    if (!messages.length) {
        box.innerHTML = '<div class="text-center text-muted py-4"><i class="far fa-comment-dots fa-2x d-block mb-2"></i>Belum ada pesan. Mulai percakapan tentang produk ini!</div>';
        return;
    }
    let html = '';
    messages.forEach(function(m) {
        const isMe = m.pengirim === pengirimSaya;
        let nama, tag;
        if (isMe) {
            nama = 'Anda';
            tag = '<span class="badge bg-light text-dark ms-1">Saya</span>';
        } else {
            nama = m.nama_pengirim || 'Admin';
            tag = '<span class="badge bg-warning text-dark ms-1">Admin</span>';
        }
        html += '<div class="d-flex ' + (isMe ? 'justify-content-end' : 'justify-content-start') + ' mb-2">'
            + '<div class="chat-bubble ' + (isMe ? 'me' : 'them') + '">'
            + '<div class="small fw-bold ' + (isMe ? 'text-white-50' : 'text-muted') + '">' + escHtml(nama) + tag + '</div>'
            + '<div>' + escHtml(m.pesan) + '</div>'
            + '<div class="small ' + (isMe ? 'text-white-50' : 'text-muted') + '" style="font-size:11px;text-align:right;">' + formatWaktu(m.waktu_chat) + '</div>'
            + '</div></div>';
    });
    box.innerHTML = html;
    box.scrollTop = box.scrollHeight;
}

function muatPesan() {
    fetch(apiUrl)
        .then(function(r) { return r.json(); })
        .then(function(data) {
            renderMessages(data.messages || []);
        })
        .catch(function() {
            document.getElementById('chat-box').innerHTML = '<div class="text-center text-muted py-4">Gagal memuat pesan.</div>';
        });
}

document.addEventListener('DOMContentLoaded', function() {
    muatPesan();
    setInterval(muatPesan, 3000);

    document.getElementById('form-chat').addEventListener('submit', function(e) {
        e.preventDefault();
        const input = document.getElementById('input-pesan');
        const pesan = input.value.trim();
        if (!pesan) return;

        const formData = new FormData();
        formData.append('nama_produk', namaProduk);
        formData.append('id_stok', <?= $produk_id_stok ?>);
        formData.append('pesan', pesan);

        fetch(kirimUrl, { method: 'POST', body: formData })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data.success) {
                    input.value = '';
                    muatPesan();
                } else {
                    Swal.fire({ icon: 'error', title: 'Oops...', text: data.message || 'Gagal mengirim pesan.' });
                }
            })
            .catch(function() {
                Swal.fire({ icon: 'error', title: 'Oops...', text: 'Gagal mengirim pesan.' });
            });
    });
});
</script>

<?= view('layout_toko/v_footer.php') ?>
