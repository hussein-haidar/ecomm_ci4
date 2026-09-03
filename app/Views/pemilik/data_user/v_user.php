<div class="box">
  <div class="box-header">
    <a href="<?= base_url('pemilik_kelola_user/add') ?>" class="btn btn-sm"><i class=" fa fa-plus-circle" aria-hidden="true"></i>
      Tambah Data</a>
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
            <th>Nama Pengguna</th>
            <th>Nama Lengkap</th>
            <th>Sesi User</th>
            <th>Nama Title</th>
            <th>Foto Profil</th>
            <th>No Telpon User</th>
            <th>Jobdesk User</th>
            <th>Level</th>
            <th>Terakhir Login</th>
            <th>Menu Setting</th>
            <th scope="col" width="auto">Aksi</th>
          </tr>
        </thead>
        <tbody>

          <?php $no = 1;
          foreach ($user as $key => $value) {
          ?>
            <tr>
              <td><?= $no++; ?></td>
              <td><?= $value['username']; ?></td>
              <td><?= $value['nama_lengkap']; ?></td>
              <td><?= $value['sesi_user']; ?></td>
              <td><?= $value['nama_title']; ?></td>
              <td><img src="<?= base_url('fotouser/' . $value['foto_user']) ?>" class="img-circle" width="80px" height="80px"></td>
              <td><?= $value['notelpon_user']; ?></td>
              <td><?= $value['jobdesk_user']; ?></td>
              <td><?php if ($value['level'] == 1) {
                    echo 'Pemilik';
                  } else if ($value['level'] == 2) {
                    echo 'Admin Toko';
                  }
                  ?>
              </td>
              </td>
              <td><?= $value['last_login']; ?></td>
              </td>
              <td>
                <!-- Menu Setting User -->
                <span class="me-2 fw-bold">Menu Setting User</span>
                <form method="post" action="<?= base_url('pemilik_kelola_user/update_tampilan_varian') ?>">
                  <!-- Kirim ID user target -->
                  <input type="hidden" name="id_user" value="<?= $value['id_user'] ?>">

                  <label class="me-3">
                    <input type="checkbox" name="tampilkan_varian" value="1"
                      <?= isset($value['tampilkan_varian']) && $value['tampilkan_varian'] == 1 ? 'checked' : '' ?>
                      onchange="this.form.submit()">
                    Tampilkan Menu Varian Produk
                  </label>

                </form>

              </td>
              <td>
                <a href="<?= base_url('pemilik_kelola_user/edit/' . $value['id_user']) ?>" class="btn btn-xs btn-warning"><i class="fa fa-fw fa-edit"></i>Edit</a>
                <button class="btn btn-danger btn-sm btn-xs" data-toggle="modal" data-target="#delete<?= $value['id_user'] ?>"><i class="fa fa-fw fa-trash"></i>Delete</button>
              </td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Modal delete-->
<?php foreach ($user as $key => $value) { ?>
  <div class="modal fade" id="delete<?= $value['id_user'] ?>">
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
              <center>Apakah Anda Ingin Menghapus Data&nbsp;<?= $value['nama_lengkap'] ?> ?</center>
            </p>
          </h4>
        </div>
        <div class="modal-footer">
          <a href="<?= base_url('pemilik_kelola_user/delete_user/' . $value['id_user']) ?>" class="btn btn-success pull-left btn-flat"> Delete</a>
          <button type="button" class="btn btn-danger btn-flat" data-dismiss="modal">Close</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
<?php } ?>