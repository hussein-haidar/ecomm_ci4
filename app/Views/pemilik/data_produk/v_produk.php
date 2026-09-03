<div class="box">
  <div class="box-header">
    <a href="<?= base_url('pemilik_kelola_data/add_produk') ?>" class="btn btn-sm">
      <i class="fa fa-plus-circle" aria-hidden="true"></i> Tambah Data
    </a>
    <a href="<?= base_url('pemilik_kelola_data/data_dihapus_produk') ?>" class="btn btn-sm">Lihat Data Dihapus</a>
  </div>

  <!-- /.box-header -->
  <div class="box-body">
    <!-- Alert Windows Success -->
    <?php if (session()->getFlashdata('pesan')) : ?>
      <div class="alert alert-success" role="alert">
        <?= session()->getFlashdata('pesan'); ?>
      </div>
    <?php endif; ?>
    <div class="table-responsive">
      <table id="example1" class="table table-bordered table-striped ">
        <thead>
          <tr>
            <th scope="col" width="1%">No</th>
            <th>Kode Produk</th>
            <th>Jenis Produk</th>
            <!-- Cek apakah ada motif produk di database -->
            <?php if (isset($data_produk[0]['varian_produk']) != '') : ?>
              <th>Varian Produk</th>
            <?php endif; ?>
            <th>Nama Produk</th>
            <th>Ukuran Produk</th>
            <th>Berat Produk</th>
            <th>Harga Produk</th>
            <th>Foto Produk</th>
            <th>Deskripsi Produk</th>
            <th>Pilih Carousel</th>
            <th scope="col" width="auto">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php $no = 1;
          foreach ($data_produk as $key => $value) {
          ?>
            <tr>
              <td><?= $no++; ?></td>
              <td><?= $value['kode_produk']; ?></td>
              <td><?= $value['jenis_produk']; ?></td>
              <?php if (isset($value['varian_produk']) != '') : ?>
                <td><?= $value['varian_produk']; ?></td>
              <?php endif; ?>
              <td><?= $value['nama_produk']; ?></td>
              <td><?= $value['ukuran_produk']; ?></td>
              <td><?= $value['berat_produk']; ?>
                <?= $value['satuan_berat']; ?>
              </td>
              <td>Rp. <?= number_format($value['harga_produk'], 0, ',', '.'); ?></td>
              <td><img src="<?= base_url('fotoproduk/' . $value['foto_produk']) ?>" class="img-circle" width="80px" height="80px"></td>
              <td><?= $value['deskripsi_produk']; ?></td>
              <td>
                <form action="<?= base_url('pemilik_kelola_data/update_carousel') ?>" method="post" style="display:inline;">
                  <input type="hidden" name="id_produk" value="<?= $value['id_produk'] ?>">
                  <input type="checkbox" name="checked" value="1" <?= ($value['checked'] == 1 ? 'checked' : '') ?> onchange="this.form.submit()">
                </form>
              </td>
              <td>
                <?php if ($value['deleted_at'] == 0) : ?>
                  <!-- Tombol Edit dan Delete -->
                  <a href="<?= base_url('pemilik_kelola_data/edit_produk/' . $value['id_produk']) ?>" class="btn btn-xs btn-warning"><i class="fa fa-fw fa-edit"></i>Edit</a>
                  <button class="btn btn-danger btn-sm btn-xs" data-toggle="modal" data-target="#delete<?= $value['id_produk'] ?>"><i class="fa fa-fw fa-trash"></i>Delete</button>
                <?php endif; ?>
              </td>
            <?php } ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Modal delete-->
<?php foreach ($data_produk as $key => $value) { ?>
  <div class="modal fade" id="delete<?= $value['id_produk'] ?>">
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
              <center>Apakah Anda Ingin Menghapus Data&nbsp;<?= $value['nama_produk'] ?> ?</b></center>
            </p>
          </h4>
        </div>
        <div class="modal-footer">
          <a href="<?= base_url('pemilik_kelola_data/delete_produk/' . $value['id_produk']) ?>" class="btn btn-success pull-left btn-flat"> Delete</a>
          <button type="button" class="btn btn-danger btn-flat" data-dismiss="modal">Close</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
<?php } ?>