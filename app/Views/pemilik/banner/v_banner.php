<div class="box">
  <div class="box-header">
    <a href="<?= base_url('pemilik_kelola_data/add_banner') ?>" class="btn btn-sm">
      <i class="fa fa-plus-circle" aria-hidden="true"></i> Tambah Data
    </a>
    <a href="<?= base_url('pemilik_kelola_data/data_dihapus_banner') ?>" class="btn btn-sm">Lihat Data Dihapus</a>
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
            <th>Gambar</th>
            <th>Judul</th>
            <th>Deskripsi</th>
            <th>Link</th>
            <th>Status</th>
            <th scope="col" width="auto">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php $no = 1; ?>
          <?php foreach ($banner as $value) : ?>
            <tr>
              <td><?= $no++; ?></td>
              <td>
                <img src="<?= base_url('fotobanner/' . $value['foto_banner']) ?>" width="100" alt="<?= esc($value['judul_banner']) ?>">
              </td>
              <td><?= esc($value['judul_banner']); ?></td>
              <td><?= esc($value['deskripsi_banner'] ?? '-'); ?></td>
              <td>
                <?= !empty($value['link_banner']) ? '<a href="' . esc($value['link_banner']) . '" target="_blank">' . esc($value['link_banner']) . '</a>' : '-' ?>
              </td>
              <td>
                <?php if ($value['status'] == 1) : ?>
                  <span class="label label-success">Aktif</span>
                <?php else : ?>
                  <span class="label label-default">Nonaktif</span>
                <?php endif; ?>
              </td>
              <td>
                <?php if ($value['deleted_at'] == 0) : ?>
                  <a href="<?= base_url('pemilik_kelola_data/edit_banner/' . $value['id_banner']) ?>" class="btn btn-xs btn-warning">
                    <i class="fa fa-fw fa-edit"></i> Edit
                  </a>
                  <a href="<?= base_url('pemilik_kelola_data/status_banner/' . $value['id_banner']) ?>" class="btn btn-xs btn-info">
                    <i class="fa fa-fw fa-power-off"></i> <?= $value['status'] == 1 ? 'Nonaktifkan' : 'Aktifkan' ?>
                  </a>
                  <button class="btn btn-danger btn-sm btn-xs" data-toggle="modal" data-target="#delete<?= $value['id_banner']; ?>">
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
<?php foreach ($banner as $value) : ?>
  <div class="modal fade" id="delete<?= $value['id_banner']; ?>" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title" id="deleteModalLabel">Hapus Banner Promo</h4>
        </div>
        <div class="modal-body">
          <h4>
            <p>
              <center>Apakah Anda Ingin Menghapus Data&nbsp;<?= esc($value['judul_banner']) ?> ?</center>
            </p>
          </h4>
        </div>
        <div class="modal-footer">
          <a href="<?= base_url('pemilik_kelola_data/delete_banner/' . $value['id_banner']); ?>" class="btn btn-success">Hapus</a>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
        </div>
      </div>
    </div>
  </div>
<?php endforeach; ?>