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
                                    <a href="<?= base_url('superadmin_kelola_data/nonaktifkan/' . $value['id_website']) ?>" class="btn btn-danger btn-xs" onclick="return confirm('Tolak toko ini?');"><i class="fa fa-times"></i> Tolak</a>
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