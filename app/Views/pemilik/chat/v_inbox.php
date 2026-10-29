<div class="box">
  <div class="box-header">
    <h3 class="box-title"><i class="fa fa-comments"></i> Percakapan Chat Pembeli</h3>
  </div>

  <div class="box-body">
    <?php if (session()->getFlashdata('pesan')) : ?>
      <div class="alert alert-success" role="alert">
        <?= session()->getFlashdata('pesan'); ?>
      </div>
    <?php endif; ?>

    <?php if (empty($chat_list)): ?>
      <div class="alert alert-info text-center">
        <i class="fa fa-inbox"></i> Belum ada percakapan chat. Ketika pembeli mengirim pesan tentang suatu produk, percakapannya akan muncul di sini.
      </div>
    <?php else: ?>
      <div class="table-responsive">
        <table id="example1" class="table table-bordered table-striped">
          <thead>
            <tr>
              <th width="1%">No</th>
              <th>Pembeli</th>
              <th>Produk</th>
              <th width="60">Foto</th>
              <th>Pesan Terakhir</th>
              <th>Waktu</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php $no = 1;
            foreach ($chat_list as $c):
              $fotoProduk = $c['foto_produk'] ?? '';
            ?>
              <tr>
                <td><?= $no++; ?></td>
                <td><?= esc($c['nama_pelanggan']) ?></td>
                <td><?= esc($c['nama_produk']) ?></td>
                <td>
                  <?php if ($fotoProduk): ?>
                    <img src="<?= base_url('fotoproduk/' . $fotoProduk) ?>" alt="<?= esc($c['nama_produk']) ?>"
                         style="width:50px;height:50px;object-fit:cover;border-radius:6px;border:1px solid #eee;"
                         onerror="this.style.display='none';this.nextElementSibling.style.display='block';">
                    <div style="display:none;width:50px;height:50px;align-items:center;justify-content:center;background:#f0f0f0;border-radius:6px;">
                      <i class="fa fa-box text-muted"></i>
                    </div>
                  <?php else: ?>
                    <div style="width:50px;height:50px;display:flex;align-items:center;justify-content:center;background:#f0f0f0;border-radius:6px;">
                      <i class="fa fa-box text-muted"></i>
                    </div>
                  <?php endif; ?>
                </td>
                <td>
                  <?php $nama_terakhir = $c['nama_pengirim'] ?: ($c['pengirim_terakhir'] === 'pelanggan' ? 'Pembeli' : 'Anda'); ?>
                  <?= $c['pengirim_terakhir'] === 'pelanggan'
                      ? '<span class="label label-primary">' . esc($nama_terakhir) . '</span>'
                      : '<span class="label label-success">' . esc($nama_terakhir) . '</span>' ?>
                  <?= esc(mb_strimwidth($c['pesan_terakhir'], 0, 100, '...')) ?>
                </td>
                <td><?= esc($c['waktu_terakhir']) ?></td>
                <td>
                  <a href="<?= base_url('chat_admin_pembeli/buka/' . urlencode($c['nama_pelanggan']) . '/' . urlencode($c['nama_produk'])) ?>" class="btn btn-primary btn-sm">
                    <i class="fa fa-comment-dots"></i> Buka
                    <?php if ($c['unread'] > 0): ?>
                      <span class="badge bg-red"><?= $c['unread'] ?></span>
                    <?php endif; ?>
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>
</div>
