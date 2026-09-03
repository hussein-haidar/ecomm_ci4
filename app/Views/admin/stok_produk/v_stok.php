<div class="box">
  <div class="box-header">
    <td><a href="<?= base_url('admin_kelola_data/add_stok') ?>" class="btn-sm"><i class="fa fa-plus-circle" aria-hidden="true"></i>
        Tambah Data</a></td>
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
            <th>Tanggal Masuk</th>
            <th>Nama Produk</th>
            <th>Foto Produk</th>
            <th>Jenis Produk</th>
            <th>Jumlah Stok Produk</th>
            <th>Ukuran Produk</th>
            <th>Total Harga Produk</th>
            <th>Total Berat Produk</th>
            <th scope="col" width="auto">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php $no = 1;
          foreach ($data_stok as $key => $value) {
          ?>
            <tr>
              <td><?= $no++; ?></td>
              <td><?= $value['kode_stok']; ?></td>
              <td>
                <?php
                setlocale(LC_TIME, 'id_ID');
                $tanggal = strtotime($value['tanggal_masuk_produk']);
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
                $formattedDate = date('d', $tanggal) . ' ' . $bulan[date('n', $tanggal)] . ' ' . date('Y', $tanggal);
                echo $formattedDate;
                ?>
              </td>
              <td><?= $value['nama_produk']; ?></td>
              <td><img src="<?= base_url('fotoproduk/' . $value['foto_produk']) ?>" class="img-circle" width="80px" height="80px"></td>
              <td><?= $value['jenis_produk']; ?></td>
              <td><?= $value['jumlah_stok_produk']; ?>
                <?= $value['satuan_produk']; ?>
              </td>
              <td><?= $value['ukuran_produk']; ?></td>
              <td>Rp. <?= number_format($value['total_harga'], 0, ',', '.'); ?></td>
              <td><?= $value['total_berat']; ?>
                <?= $value['satuan_berat']; ?>
              </td>
              <td>
                <a href="<?= base_url('admin_kelola_data/edit_stok/' . $value['id_stok']) ?>" class="btn btn-xs btn-warning"><i class="fa fa-fw fa-edit"></i>Edit</a>
                <button class="btn btn-danger btn-sm btn-xs" data-toggle="modal" data-target="#delete<?= $value['id_stok'] ?>"><i class="fa fa-fw fa-trash"></i>Delete</button>
              </td>
            </tr>
          <?php } ?>
        </tbody>

      </table>
    </div>
  </div>
</div>

<!-- Modal delete-->
<?php foreach ($data_stok as $key => $value) { ?>
  <div class="modal fade" id="delete<?= $value['id_stok'] ?>">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Delete <?= $title ?></h4>
        </div>
        <div class="modal-body">
          <h4>
            <p>
              <center>Apakah Anda Ingin Menghapus Data&nbsp;<?= $value['nama_produk'] ?> ?</center>
            </p>
          </h4>
        </div>

        <div class="modal-footer">
          <a href="<?= base_url('admin_kelola_data/delete_stok/' . $value['id_stok']) ?>" class="btn btn-success pull-left btn-flat">&nbsp;Delete</a>
          <button type="button" class="btn btn-danger btn-flat" data-dismiss="modal">Close</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
<?php } ?>