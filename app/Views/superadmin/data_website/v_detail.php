<br>

<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-building"></i> Detail Toko : <?= esc($toko['nama_toko']) ?></h3>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <table class="table table-bordered">
            <tr>
                <th style="width: 30%;">Nama Toko</th>
                <td><?= esc($toko['nama_toko']) ?></td>
            </tr>
            <tr>
                <th>Pemilik</th>
                <td><?= esc($toko['nama_lengkap'] ?? '-') ?></td>
            </tr>
            <tr>
                <th>Username Pemilik</th>
                <td><?= esc($toko['username'] ?? '-') ?></td>
            </tr>
            <tr>
                <th>Kontak Pemilik</th>
                <td><?= esc($toko['notelpon_user'] ?? '-') ?></td>
            </tr>
            <tr>
                <th>Email Pemilik</th>
                <td><?= esc($toko['email_user'] ?? '-') ?></td>
            </tr>
            <tr>
                <th>Alamat Pusat</th>
                <td><?= esc($toko['alamat_pusat'] ?? '-') ?></td>
            </tr>
            <tr>
                <th>Alamat Cabang</th>
                <td><?= esc($toko['alamat_cabang'] ?? '-') ?></td>
            </tr>
            <tr>
                <th>WhatsApp Pusat</th>
                <td><?= esc($toko['wa_pusat'] ?? '-') ?></td>
            </tr>
            <tr>
                <th>WhatsApp Cabang</th>
                <td><?= esc($toko['wa_cabang'] ?? '-') ?></td>
            </tr>
            <tr>
                <th>Tema Website</th>
                <td><?= esc($toko['tema_website'] ?? 'default') ?></td>
            </tr>
            <tr>
                <th>Status</th>
                <td>
                    <?php if ($toko['is_checked'] == 2) { ?>
                        <span class="label label-success">Aktif</span>
                    <?php } elseif ($toko['is_checked'] == 0) { ?>
                        <span class="label label-warning">Menunggu Verifikasi</span>
                    <?php } else { ?>
                        <span class="label label-danger">Nonaktif</span>
                    <?php } ?>
                </td>
            </tr>
        </table>

<a href="<?= base_url('superadmin_kelola_data/toko') ?>" class="btn btn-primary"><i class="fa fa-arrow-left"></i> Kembali</a>
        <?php if ($toko['is_checked'] == 2) { ?>
            <form method="POST" action="<?= base_url('superadmin_kelola_data/update_verifikasi') ?>" class="d-inline" onsubmit="return confirm('Yakin ingin menonaktifkan toko ini?');">
                <?= csrf_field() ?>
                <input type="hidden" name="id_website" value="<?= $toko['id_website'] ?>">
                <input type="hidden" name="action" value="tolak">
                <button type="submit" class="btn btn-danger"><i class="fa fa-times"></i> Nonaktifkan</button>
            </form>
        <?php } elseif ($toko['is_checked'] == 0) { ?>
            <form method="POST" action="<?= base_url('superadmin_kelola_data/update_verifikasi') ?>" class="d-inline" onsubmit="return confirm('Setujui toko ini?');">
                <?= csrf_field() ?>
                <input type="hidden" name="id_website" value="<?= $toko['id_website'] ?>">
                <input type="hidden" name="action" value="setujui">
                <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Setujui</button>
            </form>
            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#tolakModal" data-id="<?= $toko['id_website'] ?>" data-nama="<?= esc($toko['nama_toko']) ?>"><i class="fa fa-times"></i> Tolak</button>
        <?php } else { ?>
            <form method="POST" action="<?= base_url('superadmin_kelola_data/update_verifikasi') ?>" class="d-inline" onsubmit="return confirm('Yakin ingin mengaktifkan toko ini?');">
                <?= csrf_field() ?>
                <input type="hidden" name="id_website" value="<?= $toko['id_website'] ?>">
                <input type="hidden" name="action" value="setujui">
                <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Aktifkan</button>
            </form>
        <?php } ?>
    </div>
    <!-- /.box-body -->
</div>
<!-- /.box -->

<!-- Modal Tolak Toko -->
<div class="modal fade" id="tolakModal" tabindex="-1" role="dialog" aria-labelledby="tolakModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form method="POST" action="<?= base_url('superadmin_kelola_data/update_verifikasi') ?>" id="tolakForm">
        <?= csrf_field() ?>
        <input type="hidden" name="id_website" id="tolak_id_website">
        <input type="hidden" name="action" value="tolak">
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