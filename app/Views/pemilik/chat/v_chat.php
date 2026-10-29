<style>
.chat-box {
    height: 420px;
    overflow-y: auto;
    background: #f7f7f7;
    border: 1px solid #e5e5e5;
    border-radius: 6px;
    padding: 15px;
}
.chat-bubble {
    max-width: 75%;
    padding: 8px 12px;
    border-radius: 12px;
    word-wrap: break-word;
}
.chat-bubble.me {
    background: #3c8dbc;
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

<div class="box">
  <div class="box-header with-border">
    <h3 class="box-title">
      <i class="fa fa-user"></i> <?= esc($nama_pelanggan ?? '') ?>
    <small>— <?= esc($nama_produk ?? '') ?></small>
    </h3>
    <a href="<?= base_url('chat_admin_pembeli/index') ?>" class="btn btn-sm btn-default pull-right">
      <i class="fa fa-arrow-left"></i> Kembali ke Inbox
    </a>
  </div>

  <?php if (!empty($produk) && !empty($produk['nama_produk'])): ?>
  <div class="box-body" style="padding:12px 15px 0;">
    <div style="display:flex;align-items:center;gap:12px;background:#fff;border:1px solid #e5e5e5;border-radius:8px;overflow:hidden;box-shadow:0 1px 1px rgba(0,0,0,0.02);">
      <div style="display:flex;align-items:center;gap:5px;padding:5px 12px;background:#e8f4ff;border-right:1px solid #d6e9f8;">
        <i class="fa fa-question-circle" style="color:#3c8dbc;"></i>
        <span style="color:#3c8dbc;font-weight:600;font-size:12px;">Menanyakan produk ini</span>
      </div>
      <div style="display:flex;align-items:center;gap:12px;padding:10px 12px;flex:1;min-width:0;">
        <div style="width:56px;height:56px;flex:0 0 56px;">
          <?php if (!empty($produk['foto_produk'])): ?>
            <img src="<?= base_url('fotoproduk/' . $produk['foto_produk']) ?>"
                 alt="<?= esc($produk['nama_produk']) ?>"
                 style="width:100%;height:100%;object-fit:cover;border-radius:6px;border:1px solid #eee;"
                 onerror="this.style.display='none';this.nextElementSibling.style.display='flex';">
          <?php endif; ?>
          <div style="width:100%;height:100%;display:<?= !empty($produk['foto_produk']) ? 'none' : 'flex' ?>;align-items:center;justify-content:center;background:#f0f0f0;border-radius:6px;">
            <i class="fa fa-box text-muted"></i>
          </div>
        </div>
        <div style="flex:1;min-width:0;">
          <div style="font-weight:600;font-size:14px;color:#333;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
            <?= esc($produk['nama_produk']) ?>
          </div>
          <div style="display:flex;align-items:center;gap:8px;margin-top:2px;">
            <span style="color:#dd4b39;font-weight:700;">Rp <?= number_format($produk['harga_produk'] ?? 0, 0, ',', '.') ?></span>
            <?php if (!empty($produk['ukuran_produk'])): ?>
              <span style="color:#999;font-size:12px;">| <?= esc($produk['ukuran_produk']) ?></span>
            <?php endif; ?>
          </div>
        </div>
        <a href="<?= base_url('home_toko/detail_produk/' . urlencode($produk['nama_produk'])) ?>"
           target="_blank" class="btn btn-sm btn-default">
          <i class="fa fa-external-link"></i> Lihat Produk
        </a>
      </div>
    </div>
  </div>
  <?php endif; ?>

  <div class="box-body">
    <div id="chat-box" class="chat-box">
      <div class="text-center text-muted py-4"><i class="fa fa-spinner fa-spin"></i> Memuat pesan...</div>
    </div>

<form id="form-chat" class="form-inline mt-3">
        <input type="hidden" name="nama_pelanggan" value="<?= esc($nama_pelanggan ?? '') ?>">
<input type="hidden" name="nama_produk" value="<?= esc($nama_produk ?? '') ?>">
<input type="hidden" name="id_stok" value="<?= esc($id_stok ?? '') ?>">
      <div class="input-group" style="width:100%;">
        <input type="text" name="pesan" id="input-pesan" class="form-control" placeholder="Ketik balasan untuk pembeli..." maxlength="1000" autocomplete="off">
        <span class="input-group-btn">
          <button type="submit" class="btn btn-primary"><i class="fa fa-paper-plane"></i> Kirim</button>
        </span>
      </div>
    </form>
  </div>
</div>

<script>
const namaPelanggan = <?= json_encode($nama_pelanggan ?? '') ?>;
const namaProduk = <?= json_encode($nama_produk ?? '') ?>;
const pengirimSaya = 'admin';
const apiUrl = <?= json_encode(base_url('chat_admin_pembeli/api_messages/' . urlencode($nama_pelanggan ?? '') . '/' . urlencode($nama_produk ?? ''))) ?>;
const kirimUrl = <?= json_encode(base_url('chat_admin_pembeli/kirim')) ?>;

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
        box.innerHTML = '<div class="text-center text-muted py-4"><i class="fa fa-inbox fa-2x d-block mb-2"></i>Belum ada pesan.</div>';
        return;
    }
    let html = '';
    messages.forEach(function(m) {
        const isMe = m.pengirim === pengirimSaya;
        let nama, tag;
        if (isMe) {
            nama = m.nama_pengirim || 'Anda';
            tag = '<span class="label label-success" style="margin-left:5px;">Admin</span>';
        } else {
            nama = m.nama_pengirim || namaPelanggan;
            tag = '<span class="label label-primary" style="margin-left:5px;">Pembeli</span>';
        }
        const align = isMe ? 'right' : 'left';
        html += '<div style="text-align:' + align + ';margin-bottom:10px;">'
            + '<div class="chat-bubble ' + (isMe ? 'me' : 'them') + '" style="display:inline-block;">'
            + '<div class="small fw-bold" style="' + (isMe ? 'color:#e8f0f8;' : 'color:#888;') + '">' + escHtml(nama) + tag + '</div>'
            + '<div>' + escHtml(m.pesan) + '</div>'
            + '<div class="small" style="font-size:11px;text-align:right;' + (isMe ? 'color:#e8f0f8;' : 'color:#aaa;') + '">' + formatWaktu(m.waktu_chat) + '</div>'
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
        formData.append('nama_pelanggan', namaPelanggan);
        formData.append('nama_produk', namaProduk);
        formData.append('id_stok', <?= $id_stok ?? 0 ?>);
        formData.append('pesan', pesan);

        fetch(kirimUrl, { method: 'POST', body: formData })
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data.success) {
                    input.value = '';
                    muatPesan();
                } else {
                    alert(data.message || 'Gagal mengirim pesan.');
                }
            })
            .catch(function() {
                alert('Gagal mengirim pesan.');
            });
    });
});
</script>
