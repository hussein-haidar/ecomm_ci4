<div class="box">
  <div class="box-header">
    <a href="<?= base_url('pemilik_kelola_data/add_satuan') ?>" class="btn btn-sm">
      <i class="fa fa-plus-circle" aria-hidden="true"></i> Tambah Data
    </a>
    <a href="<?= base_url('pemilik_kelola_data/data_satuan_dihapus') ?>" class="btn btn-sm">Lihat Data Dihapus</a>
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
      <table id="example1" class="table table-bordered table-striped">
        <thead>
          <tr>
            <th scope="col" width="1%">No</th>
            <th>Satuan Produk</th>
            <th scope="col" width="auto">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php $no = 1; ?>
          <?php foreach ($satuan_produk as $value) : ?>
            <tr>
              <td><?= $no++; ?></td>
              <td><?= $value['satuan_produk']; ?></td>
              <td>
                <?php if ($value['deleted_at'] == 0) : ?>
                  <!-- Tombol Edit dan Delete -->
                  <a href="<?= base_url('pemilik_kelola_data/edit_satuan/' . $value['id_satuan']) ?>" class="btn btn-xs btn-warning">
                    <i class="fa fa-fw fa-edit"></i> Edit
                  </a>
                  <button class="btn btn-danger btn-sm btn-xs" data-toggle="modal" data-target="#delete<?= $value['id_satuan']; ?>">
                    <i class="fa fa-fw fa-trash"></i> Delete
                  </button>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Modal Delete -->
<?php foreach ($satuan_produk as $value) : ?>
  <div class="modal fade" id="delete<?= $value['id_satuan']; ?>" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title" id="deleteModalLabel">Hapus Produk</h4>
        </div>
        <div class="modal-body">
          <h4>
            <p>
              <center>Apakah Anda Ingin Menghapus Data&nbsp;<?= $value['satuan_produk'] ?> ?</center>
            </p>
          </h4>
        </div>
        <div class="modal-footer">
          <a href="<?= base_url('pemilik_kelola_data/delete_satuan/' . $value['id_satuan']); ?>" class="btn btn-success">Hapus</a>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
        </div>
      </div>
    </div>
  </div>
<?php endforeach; ?>