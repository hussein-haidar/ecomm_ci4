<?php
if (session()->getFlashdata('pesan')) {
    echo '<div class="alert alert-success" role="alert">' . session()->getFlashdata('pesan') . '</div>';
}
?>

<br>

<div class="box">
    <div class="box-header with-border">
        <!-- Button trigger modal -->
        <h3 class="box-title"><i class="fa fa-building"></i> Data Toko</h3>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <div class="table-responsive">
            <table id="example1" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Toko</th>
                        <th>Pemilik</th>
                        <th>Telepon</th>
                        <th>Alamat</th>
                        <th>Status</th>
                        <th>Tema</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    $no = 1;
                    foreach ($semua_toko as $value) { ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= esc($value['nama_toko']) ?></td>
                            <td><?= esc($value['nama_lengkap'] ?? '-') ?></td>
                            <td><?= esc($value['notelpon_user'] ?? '-') ?></td>
                            <td><?= esc($value['alamat_pusat'] ?? '-') ?></td>
                            <td>
                                <?php if ($value['is_checked'] == 2) { ?>
                                    <span class="label label-success">Aktif</span>
                                <?php } elseif ($value['is_checked'] == 0) { ?>
                                    <span class="label label-warning">Menunggu Verifikasi</span>
                                <?php } else { ?>
                                    <span class="label label-danger">Nonaktif</span>
                                <?php } ?>
                            </td>
                            <td><?= esc($value['tema_website'] ?? 'default') ?></td>
                            <td>
                                <a href="<?= base_url('superadmin_kelola_data/detail/' . $value['id_website']) ?>" class="btn btn-info btn-xs"><i class="fa fa-eye"></i></a>
                                <?php if ($value['is_checked'] == 2) { ?>
                                    <a href="<?= base_url('superadmin_kelola_data/nonaktifkan/' . $value['id_website']) ?>" class="btn btn-danger btn-xs" onclick="return confirm('Yakin ingin menonaktifkan toko ini?');"><i class="fa fa-times"></i> Nonaktifkan</a>
                                <?php } elseif ($value['is_checked'] == 0) { ?>
                                    <a href="<?= base_url('superadmin_kelola_data/aktifkan/' . $value['id_website']) ?>" class="btn btn-success btn-xs" onclick="return confirm('Setujui toko ini?');"><i class="fa fa-check"></i> Setujui</a>
                                    <button type="button" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#tolakModal" data-id="<?= $value['id_website'] ?>" data-nama="<?= esc($value['nama_toko']) ?>"><i class="fa fa-times"></i> Tolak</button>
                                <?php } else { ?>
                                    <a href="<?= base_url('superadmin_kelola_data/aktifkan/' . $value['id_website']) ?>" class="btn btn-success btn-xs" onclick="return confirm('Yakin ingin mengaktifkan toko ini?');"><i class="fa fa-check"></i> Aktifkan</a>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- /.box-body -->
</div>
<!-- /.box -->

<!-- Modal Tolak Toko -->
<div class="modal fade" id="tolakModal" tabindex="-1" role="dialog" aria-labelledby="tolakModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form method="POST" action="<?= base_url('superadmin_kelola_data/nonaktifkan') ?>" id="tolakForm">
        <?= csrf_field() ?>
        <input type="hidden" name="id_website" id="tolak_id_website">
        <div class="modal-header bg-danger text-white">
          <h5 class="modal-title" id="tolakModalLabel"><i class="fa fa-times-circle"></i> Tolak Pendaftaran Toko</h5>
          <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">
          <p>Anda akan menolak toko <strong id="tolak_nama_toko"></strong>.</p>
          <div class="form-group">
            <label for="alasan_tolak">Alasan Penolakan <span class="text-danger">*</span></label>
            <textarea name="alasan_tolak" id="alasan_tolak" class="form-control" rows="4" placeholder="Tulis alasan penolakan di sini..." required></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-danger">Ya, Tolak & Simpan Alasan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
$(function () {
    $('#tolakModal').on('show.bs.modal', function (e) {
        var btn = $(e.relatedTarget);
        var id = btn.data('id');
        var nama = btn.data('nama');
        $('#tolak_id_website').val(id);
        $('#tolak_nama_toko').text(nama);
        $('#alasan_tolak').val('');
    });
});
</script>