<div class="row">
    <div class="col-md-3">
    </div>
    <div class="col-md-6">
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

                <center>
                    <div class="form-group">
                        <label>Foto Profil</label>
                        <p></p>
                        <img src="<?= base_url('fotouser/' . session()->get('foto_user')) ?>" class="img-circle" id="gambar_load" width="100px" height="100px">
                    </div>
                </center>

                <div class="form-group">
                    <label>Username</label>
                    <input name="username" value="<?= session()->get('username') ?>" class="form-control" placeholder="Masukkan Nama Lengkap" readonly>
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input name="password" type="password" id="ShowPass" value="<?= session()->get('password') ?>" class="form-control" readonly>
                    <input type="checkbox" onclick="myFunction()">&nbsp; Show Password
                </div>

                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input name="nama_lengkap" value="<?= session()->get('nama_lengkap') ?>" class="form-control" readonly>
                </div>

                <div class="form-group">
                    <label>No Telepon User</label>
                    <input type="number" value="<?= session()->get('notelpon_user') ?>" class="form-control" readonly>
                </div>

                <div class="form-group">
                    <label>Jobdesk User</label>
                    <input type="text" value="<?= session()->get('jobdesk_user') ?>" class="form-control" readonly>
                </div>

                <div class="form-group">
                    <label>Level</label>
                    <input type="text" name="level" value="<?php if (session()->get('level') == 1) {
                                                                echo 'Pemilik';
                                                            } else if (session()->get('level') == 2) {
                                                                echo 'Admin Toko';
                                                            }
                                                            ?>" class="form-control" readonly>

                </div>

                <div class="form-group">
                    <label>Terakhir Login</label>
                    <?php
                    setlocale(LC_TIME, 'id_ID');
                    $tanggal = strtotime(session()->get('last_login'));
                    $bulan = [
                        1 => 'Januari',
                        'Februari',
                        'Maret',
                        'April',
                        'Mei',
                        'Juni',
                        'Juli',
                        'Agustus',
                        'September',
                        'Oktober',
                        'November',
                        'Desember'
                    ];
                    $formattedDate = date('d', $tanggal) . ' ' . $bulan[date('n', $tanggal)] . ' ' . date('Y H:i:s', $tanggal);
                    ?>
                    <input type="text" value="<?= $formattedDate ?>" class="form-control" readonly>
                </div>
                <td>
                    <a href="<?= base_url('home_admin/edit/' . session()->get('id_user')) ?>" class="btn btn-warning">
                        <i class="fa fa-fw fa-edit"></i>Edit Profile
                    </a>
                </td>

                <div class="form-group">
                    <label>Menu Setting User</label>
                    <form method="post" action="<?= base_url('home_admin/update_tampilan_motif_kriteria') ?>">
                        <?= csrf_field() ?>
                        <label>
                            <input type="checkbox" name="tampilkan_motif" value="1" <?= $tampilkan_motif ? 'checked' : '' ?>>
                            Tampilkan Menu Motif Produk
                        </label>
                        <br>
                        <label>
                            <input type="checkbox" name="tampilkan_kriteria" value="1" <?= $tampilkan_kriteria ? 'checked' : '' ?>>
                            Tampilkan Kolom Kriteria Produk
                        </label>
                        <br>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>

            </div>

        </div>

    </div>

    <div class="col-md-3">
    </div>
</div>