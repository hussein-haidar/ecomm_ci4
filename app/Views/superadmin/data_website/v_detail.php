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
                    <?php } else { ?>
                        <span class="label label-danger">Nonaktif</span>
                    <?php } ?>
                </td>
            </tr>
        </table>

        <a href="<?= base_url('superadmin_kelola_data/toko') ?>" class="btn btn-primary"><i class="fa fa-arrow-left"></i> Kembali</a>
        <?php if ($toko['is_checked'] == 2) { ?>
            <a href="<?= base_url('superadmin_kelola_data/nonaktifkan/' . $toko['id_website']) ?>" class="btn btn-danger" onclick="return confirm('Yakin ingin menonaktifkan toko ini?');"><i class="fa fa-times"></i> Nonaktifkan</a>
        <?php } else { ?>
            <a href="<?= base_url('superadmin_kelola_data/aktifkan/' . $toko['id_website']) ?>" class="btn btn-success" onclick="return confirm('Yakin ingin mengaktifkan toko ini?');"><i class="fa fa-check"></i> Aktifkan</a>
        <?php } ?>
    </div>
    <!-- /.box-body -->
</div>
<!-- /.box -->