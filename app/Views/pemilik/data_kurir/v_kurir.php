<div class="box">
  <div class="box-header">
    <a href="<?= base_url('pemilik_kelola_user/add_kurir') ?>" class="btn btn-sm"><i class=" fa fa-plus-circle" aria-hidden="true"></i>
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
            <th>Jenis Kurir</th>
            <th>Ongkir</th>
            <th scope="col" width="auto">Aksi</th>
          </tr>
        </thead>
        <tbody>

          <?php $no = 1;
          foreach ($data_kurir as $key => $value) {
          ?>
            <tr>
              <td><?= $no++; ?></td>
              <td><?= $value['jenis_kurir']; ?></td>
              <td>Rp. <?= number_format($value['ongkir'], 0, ',', '.'); ?></td>
              <td>
                <?php if ($value['deleted_at'] == 0) : ?>
                  <!-- Tombol Edit dan Delete -->
                  <a href="<?= base_url('pemilik_kelola_user/edit_kurir/' . $value['id_kurir']) ?>" class="btn btn-xs btn-warning"><i class="fa fa-fw fa-edit"></i>Edit</a>
                  <button class="btn btn-danger btn-sm btn-xs" data-toggle="modal" data-target="#delete<?= $value['id_kurir'] ?>"><i class="fa fa-fw fa-trash"></i>Delete</button>
                <?php endif; ?>
              </td>
            <?php } ?>
            </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Modal delete-->
<?php foreach ($data_kurir as $key => $value) { ?>
  <div class="modal fade" id="delete<?= $value['id_kurir'] ?>">
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
              <center>Apakah Anda Ingin Menghapus Data&nbsp;<?= $value['jenis_kurir'] ?> ?</center>
            </p>
          </h4>
        </div>
        <div class="modal-footer">
          <a href="<?= base_url('pemilik_kelola_user/delete_kurir/' . $value['id_kurir']) ?>" class="btn btn-success pull-left btn-flat"> Delete</a>
          <button type="button" class="btn btn-danger btn-flat" data-dismiss="modal">Close</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
<?php } ?>