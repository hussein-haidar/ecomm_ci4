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
              <th>Pesan Terakhir</th>
              <th>Waktu</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php $no = 1;
            foreach ($chat_list as $c): ?>
              <tr>
                <td><?= $no++; ?></td>
                <td><?= esc($c['nama_pelanggan']) ?></td>
                <td><?= esc($c['nama_produk']) ?></td>
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
