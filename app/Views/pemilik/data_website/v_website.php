<div class="box">
  <div class="box-header">
    <a href="<?= base_url('pemilik_kelola_website/add') ?>" class="btn btn-sm"><i class="fa fa-plus-circle" aria-hidden="true"></i>
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
            <th>Pemilik Toko</th>
            <th>Nama Toko</th>
            <th>Alamat Toko Pusat</th>
            <th>No Telpon Toko Pusat</th>
            <th>Alamat Toko Cabang</th>
            <th>No Telpon Toko Cabang</th>
            <th>Footer Title</th>
            <th>Link IG</th>
            <th>Link FB</th>
            <th>Link Tiktok</th>
            <th>Logo Website</th>
            <th>Background Website</th>
            <th scope="col" width="auto">Aksi</th>
          </tr>
        </thead>
        <tbody>

          <?php $no = 1;
          foreach ($data_website as $key => $value) {
          ?>
            <tr>
              <td><?= $no++; ?></td>
              <td><?= $value['sesi_user']; ?></td>
              <td><?= $value['nama_toko']; ?></td>
              <td><?= $value['alamat_pusat']; ?></td>
              <td><?= $value['wa_pusat']; ?></td>
              <td><?= $value['alamat_cabang']; ?></td>
              <td><?= $value['wa_cabang']; ?></td>
              <td><?= $value['footer_title']; ?></td>
              <td><?= $value['link_IG']; ?></td>
              <td><?= $value['link_FB']; ?></td>
              <td><?= $value['link_Tiktok']; ?></td>
              <td><img src="<?= base_url('logowebsite/' . $value['logo_website']) ?>" class="img-circle" width="80px" height="80px"></td>
              <td><img src="<?= base_url('bgdweb/' . $value['bgd_web']) ?>" class="img-circle" width="80px" height="80px"></td>
              <td>
                <!-- Tombol status dengan warna berdasarkan nilai terbaru -->
                <label class="me-3">
                  Status Toko:
                </label>
                <!-- Tombol status dengan warna berdasarkan nilai terbaru -->
                <form method="post" action="<?= base_url('pemilik_kelola_website/update_checked') ?>" class="d-inline">
                  <?= csrf_field() ?>
                  <input type="hidden" name="id_website" value="<?= $value['id_website']; ?>">
                  <input type="hidden" name="is_checked" value="<?= $value['is_checked'] == 1 ? '2' : '1'; ?>">

                  <button type="submit"
                    class="btn btn-xs"
                    style="cursor: pointer; <?= $value['is_checked'] == 2 ? 'background-color: #5cb85c; color: white;' : 'background-color: #d9534f; color: white;' ?>">
                    <?= $value['is_checked'] == 2 ? 'Aktif' : 'Non Aktif' ?>
                  </button>
                </form>
                <a href=" <?= base_url('pemilik_kelola_website/edit/' . $value['id_website']) ?>" class="btn btn-xs btn-warning"><i class="fa fa-fw fa-edit"></i>Edit</a>
                <button class="btn btn-danger btn-xs" data-toggle="modal" data-target="#delete<?= $value['id_website'] ?>"><i class="fa fa-fw fa-trash"></i>Delete</button>
              </td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Modal delete-->
<?php foreach ($data_website as $key => $value) { ?>
  <div class="modal fade" id="delete<?= $value['id_website'] ?>">
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
              <center>Apakah Anda Ingin Menghapus Data&nbsp;<?= $value['nama_toko'] ?> ?</center>
            </p>
          </h4>
        </div>
        <div class="modal-footer">
          <a href="<?= base_url('pemilik_kelola_website/delete/' . $value['id_website']) ?>" class="btn btn-success pull-left btn-flat">&nbsp;Delete</a>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
<?php } ?>