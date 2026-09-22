<div class="box">
  <div class="box-header">
  </div>

  <!-- /.box-header -->
  <div class="box-body">
    <!-- alert success add data -->
    <?php
    if (session()->getFlashdata('pesan')) {
      echo '<div class="alert alert-success" role="alert">';
      echo session()->getFlashdata('pesan');
      echo '</div>';
    }
    ?>
    <div class="table-responsive">
      <table id="example1" class="table table-bordered table-striped ">
        <thead>
          <tr>
            <th scope="col" width="1%">No</th>
            <th>Waktu Pembayaran</th>
            <th>Pelanggan</th>
            <th>Bank Tujuan</th>
            <th>No Rekening</th>
            <th>Total Bayar</th>
            <th>Bukti Bayar</th>
            <th>Status Bayar</th>
          </tr>
        </thead>
        <tbody>
          <?php $no = 1;
          foreach ($pembayaran as $key => $value) {
          ?>
            <tr>
              <td><?= $no++; ?></td>
              <td>
                <?php
                setlocale(LC_TIME, 'id_ID');
                $tanggal = strtotime($value['waktu_pembayaran']);
                $bulan = array(
                  1 => 'Januari',
                  'Februari',
                  'Maret',
                  'April',
                  'Mei',
                  'Juni',
                  'Juli',
                  'Agustus',
                  'September',
                  'Oktober',
                  'November',
                  'Desember'
                );
                $formattedDate = date('d', $tanggal) . ' ' . $bulan[date('n', $tanggal)] . ' ' . date('Y', $tanggal) . ' ' . date('H:i:s', $tanggal);
                echo $formattedDate;
                ?>
              </td>
              <td><?= $value['nama_pelanggan']; ?></td>
              <td><?= $value['bank_tujuan']; ?></td>
              <td><?= $value['no_rek']; ?></td>
              <td>Rp. <?= number_format($value['total_bayar'], 0, ',', '.'); ?></td>
              <td><img src="<?= base_url('fotobayar/' . $value['foto_bayar']) ?>" class="img-circle" width="80px" height="80px"><a href="<?= base_url('admin_kelola_data/view_foto/' . $value['id_bayar']) ?>"> Lihat Foto</a></td>
              <td>
                <!-- Form gabungan: dropdown dengan auto-submit dan input alasan jika dibatalkan -->
                <form method="post" action="<?= base_url('admin_kelola_data/konfirm_status_bayar') ?>" class="d-inline">
                  <?= csrf_field() ?>
                  <input type="hidden" name="id_bayar" value="<?= $value['id_bayar']; ?>">
                  <input type="hidden" name="sesi_user" value="<?= esc($sesi_user) ?>" readonly>
                  <!-- Dropdown untuk memilih status -->
                  <select name="status_bayar"
                    class="form-control d-inline w-auto"
                    onchange="toggleAlasan(this, <?= $value['id_bayar']; ?>)"
                    style="display: inline-block; width: auto;
        <?php
            if ($value['status_bayar'] == 'Dibayar') {
              echo 'background-color: #5cb85c; color: white;';
            } elseif ($value['status_bayar'] == 'Dibatalkan') {
              echo 'background-color: #d9534f; color: white;';
            } else {
              echo 'background-color: #f0ad4e; color: white;';
            }
        ?>">
                    <option value="Belum Bayar" <?= $value['status_bayar'] == 'Belum Bayar' ? 'selected' : ''; ?>>Belum Bayar</option>
                    <option value="Dibatalkan" <?= $value['status_bayar'] == 'Dibatalkan' ? 'selected' : ''; ?>>Dibatalkan</option>
                    <option value="Dibayar" <?= $value['status_bayar'] == 'Dibayar' ? 'selected' : ''; ?>>Dibayar</option>
                  </select>

                  <!-- Input alasan pembatalan (hanya muncul jika status Dibatalkan) -->
                  <div id="alasan-div-<?= $value['id_bayar']; ?>" style="margin-top: 5px; display: <?= $value['status_bayar'] == 'Dibatalkan' ? 'block' : 'none'; ?>;">
                    <input type="text" name="alasan_batal" class="form-control mt-2" placeholder="Masukkan alasan pembatalan" value="<?= isset($value['alasan_batal']) ? $value['alasan_batal'] : '' ?>">
                    <button type="submit" class="btn btn-danger btn-sm mt-1">Kirim</button>
                  </div>
                </form>

                <!-- Script JS untuk toggle input alasan -->
                <script>
                  function toggleAlasan(select, id) {
                    const div = document.getElementById('alasan-div-' + id);
                    if (select.value === 'Dibatalkan') {
                      div.style.display = 'block';
                    } else {
                      div.style.display = 'none';
                      select.form.submit(); // auto submit jika bukan dibatalkan
                    }
                  }
                </script>
              </td>
            </tr>
          <?php } ?>
        </tbody>

      </table>
    </div>
  </div>
</div>