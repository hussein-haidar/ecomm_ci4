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
            <th>Kode Beli</th>
            <th>Waktu Pembelian</th>
            <th>Produk</th>
            <th>Foto Produk</th>
            <th>Jumlah Produk</th>
            <th>Total Harga</th>
            <th>Total Berat</th>
          </tr>
        </thead>
        <tbody>
          <?php $no = 1;
          foreach ($pembelian as $key => $value) {
          ?>
            <tr>
              <td><?= $no++; ?></td>
              <td><?= $value['kode_beli']; ?></td>
              <td>
                <?php
                setlocale(LC_TIME, 'id_ID');
                $tanggal = strtotime($value['waktu_pembelian']);
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
              <td><?= $value['nama_produk']; ?></td>
              <td><img src="<?= base_url('fotoproduk/' . $value['foto_produk']) ?>" class="img-circle" width="80px" height="80px"></td>

              <td><?= $value['jumlah_produk']; ?></td>
              <td>Rp. <?= number_format($value['total_harga'], 0, ',', '.') ?></td>
              <td><?= $value['total_berat']; ?></td>
            </tr>
          <?php } ?>
        </tbody>

      </table>
    </div>
  </div>
</div>