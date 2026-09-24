<div class="box">
  <div class="box-header">
    <a href="<?= base_url('kebijakan_toko/add_syarat') ?>" class="btn btn-sm">
      <i class="fa fa-plus-circle" aria-hidden="true"></i> Tambah Syarat &amp; Ketentuan
    </a>
    <a href="<?= base_url('kebijakan_toko/faq') ?>" class="btn btn-sm">
      <i class="fa fa-question-circle-o" aria-hidden="true"></i> Data FAQ (Bantuan)
    </a>
  </div>

  <div class="box-body">
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
            <th>Urutan</th>
            <th>Judul</th>
            <th>Isi</th>
            <th scope="col" width="auto">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php $no = 1; ?>
          <?php foreach ($skt as $value) : ?>
            <tr>
              <td><?= $no++; ?></td>
              <td><?= esc($value['urutan']); ?></td>
              <td><?= esc($value['judul']); ?></td>
              <td><?= esc(mb_strimwidth($value['isi'], 0, 80, '...')); ?></td>
              <td>
                <a href="<?= base_url('kebijakan_toko/edit_syarat/' . $value['id_skt']) ?>" class="btn btn-xs btn-warning">
                  <i class="fa fa-fw fa-edit"></i> Edit
                </a>
                <button class="btn btn-danger btn-sm btn-xs" data-toggle="modal" data-target="#delete<?= $value['id_skt']; ?>">
                  <i class="fa fa-fw fa-trash"></i> Delete
                </button>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Modal Delete -->
<?php foreach ($skt as $value) : ?>
  <div class="modal fade" id="delete<?= $value['id_skt']; ?>" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel<?= $value['id_skt'] ?>" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title" id="deleteModalLabel<?= $value['id_skt'] ?>">Hapus Syarat &amp; Ketentuan</h4>
        </div>
        <div class="modal-body">
          <h4>
            <p>
              <center>Apakah Anda Ingin Menghapus Bagian&nbsp;"<?= esc($value['judul']); ?>" ?</center>
            </p>
          </h4>
        </div>
        <div class="modal-footer">
          <a href="<?= base_url('kebijakan_toko/delete_syarat/' . $value['id_skt']); ?>" class="btn btn-success">Hapus</a>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
        </div>
      </div>
    </div>
  </div>
<?php endforeach; ?>