<div class="box">
  <div class="box-header">
    <a href="<?= base_url('pemilik_kelola_data/add_bank') ?>" class="btn btn-sm">
      <i class="fa fa-plus-circle" aria-hidden="true"></i> Tambah Data
    </a>
    <a href="<?= base_url('pemilik_kelola_data/data_dihapus_bank') ?>" class="btn btn-sm">Lihat Data Dihapus</a>
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
            <th>Nama Toko</th>
            <th>Nama Rekening</th>
            <th>Nama Bank</th>
            <th>No Rekening</th>
            <th scope="col" width="auto">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php $no = 1; ?>
          <?php foreach ($bank as $value) : ?>
            <tr>
              <td><?= $no++; ?></td>
              <td><?= esc($value['nama_toko'] ?? '-'); ?></td>
              <td><?= $value['a_n']; ?></td>
              <td><?= $value['nama_bank']; ?></td>
              <td><?= $value['no_rek']; ?></td>
              <td>
                <?php if ($value['deleted_at'] == 0) : ?>
                  <!-- Tombol Edit dan Delete -->
                  <a href="<?= base_url('pemilik_kelola_data/edit_bank/' . $value['id_bank']) ?>" class="btn btn-xs btn-warning">
                    <i class="fa fa-fw fa-edit"></i> Edit
                  </a>
                  <button class="btn btn-danger btn-sm btn-xs" data-toggle="modal" data-target="#delete<?= $value['id_bank']; ?>">
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
<?php foreach ($bank as $value) : ?>
  <div class="modal fade" id="delete<?= $value['id_bank']; ?>" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
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
              <center>Apakah Anda Ingin Menghapus Data&nbsp;<?= $value['a_n'] ?> ?</center>
            </p>
          </h4>
        </div>
        <div class="modal-footer">
          <a href="<?= base_url('pemilik_kelola_data/delete_bank/' . $value['id_bank']); ?>" class="btn btn-success">Hapus</a>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
        </div>
      </div>
    </div>
  </div>
<?php endforeach; ?>