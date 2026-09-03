<div class="box">
  <div class="box-header">
  </div>

  <!-- /.box-header -->
  <div class="box-body">
    <!-- /.alert windows success -->
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
            <th>Nama Lengkap</th>
            <th>Sesi User</th>
            <th>Foto Profil</th>
            <th scope="col" width="auto">Aksi</th>
          </tr>
        </thead>
        <tbody>

          <?php $no = 1;
          foreach ($data_profil as $key => $value) {
          ?>
            <tr>
              <td><?= $no++; ?></td>
              <td><?= $value['nama_pelanggan']; ?></td>
              <td><?= $value['sesi_user']; ?></td>
              <td><img src="<?= !empty($value['foto_pelanggan']) && filter_var($value['foto_pelanggan'], FILTER_VALIDATE_URL) ? esc($value['foto_pelanggan']) : (!empty($value['foto_pelanggan']) && $value['foto_pelanggan'] !== 'profil_default.png' ? base_url('fotopelanggan/' . $value['foto_pelanggan']) : base_url('fotodefault/profil_default.png')) ?>" class="img-circle" width="80px" height="80px"></td>
              <td>
                <button class="btn btn-danger btn-sm btn-xs" data-toggle="modal" data-target="#delete<?= $value['id_pelanggan'] ?>"><i class="fa fa-fw fa-trash"></i>Delete</button>
              </td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Modal delete-->
<?php foreach ($data_profil as $key => $value) { ?>
  <div class="modal fade" id="delete<?= $value['id_pelanggan'] ?>">
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
              <center>Apakah Anda Ingin Menghapus Data&nbsp;<?= $value['nama_pelanggan'] ?> ?</center>
            </p>
          </h4>
        </div>
        <div class="modal-footer">
          <a href="<?= base_url('pemilik_kelola_user/delete_pelanggan/' . $value['id_pelanggan']) ?>" class="btn btn-success pull-left btn-flat"> Delete</a>
          <button type="button" class="btn btn-danger btn-flat" data-dismiss="modal">Close</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
<?php } ?>