<div class="box">
  <div class="box-header">
    <a href="<?= base_url('superadmin_kebijakan_platform/add_faq') ?>" class="btn btn-sm">
      <i class="fa fa-plus-circle" aria-hidden="true"></i> Tambah FAQ
    </a>
    <a href="<?= base_url('superadmin_kebijakan_platform/syarat') ?>" class="btn btn-sm">
      <i class="fa fa-file-text-o" aria-hidden="true"></i> Data Syarat &amp; Ketentuan
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
            <th>Kategori</th>
            <th>Pertanyaan</th>
            <th>Jawaban</th>
            <th>Urutan</th>
            <th scope="col" width="auto">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php $no = 1; ?>
          <?php foreach ($faq as $value) : ?>
            <tr>
              <td><?= $no++; ?></td>
              <td><?= esc($value['kategori']); ?></td>
              <td><?= esc($value['pertanyaan']); ?></td>
              <td><?= esc(mb_strimwidth($value['jawaban'], 0, 80, '...')); ?></td>
              <td><?= esc($value['urutan']); ?></td>
              <td>
                <a href="<?= base_url('superadmin_kebijakan_platform/edit_faq/' . $value['id_faq']) ?>" class="btn btn-xs btn-warning">
                  <i class="fa fa-fw fa-edit"></i> Edit
                </a>
                <button class="btn btn-danger btn-sm btn-xs" data-toggle="modal" data-target="#delete<?= $value['id_faq']; ?>">
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
<?php foreach ($faq as $value) : ?>
  <div class="modal fade" id="delete<?= $value['id_faq']; ?>" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel<?= $value['id_faq'] ?>" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title" id="deleteModalLabel<?= $value['id_faq'] ?>">Hapus FAQ</h4>
        </div>
        <div class="modal-body">
          <h4>
            <p>
              <center>Apakah Anda Ingin Menghapus Pertanyaan&nbsp;"<?= esc($value['pertanyaan']); ?>" ?</center>
            </p>
          </h4>
        </div>
        <div class="modal-footer">
          <a href="<?= base_url('superadmin_kebijakan_platform/delete_faq/' . $value['id_faq']); ?>" class="btn btn-success">Hapus</a>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
        </div>
      </div>
    </div>
  </div>
<?php endforeach; ?>