<div class="box">
  <div class="box-header">
    <a href="<?= base_url('admin_kelola_data/stok') ?>" class="btn-sm"><i class="fa fa-arrow-right"></i>&nbsp;Stok Produk</a>
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
            <th>Kode Stok Produk</th>
            <th>Nama Produk</th>
            <th>Foto Produk</th>
            <th>Total Stok Produk</th>
            <th>Total Harga Stok</th>
          </tr>
        </thead>
        <tbody>
          <?php $no = 1;
          foreach ($data_stok as $key => $value) {
          ?>
            <tr>
              <td><?= $no++; ?></td>
              <td><?= $value['kode_stok']; ?></td>
              <td><?= $value['nama_produk']; ?></td>
              <td><img src="<?= base_url('fotoproduk/' . $value['foto_produk']) ?>" class="img-circle" width="80px" height="80px"></td>
              <td><?= $value['total_stok']; ?>
                <?= $value['satuan_produk']; ?>
              </td>
              <td>Rp. <?= number_format($value['total_harga'], 0, ',', '.'); ?></td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </div>
</div>