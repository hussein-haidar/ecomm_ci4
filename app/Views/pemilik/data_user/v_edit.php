<div class="row">
    <div class="col-md-3">
    </div>
    <div class="col-md-6">
        <div class="box">
            <div class="box-header with-border">
            </div>

            <!-- /.box-header -->
            <div class="box-body">
                <?php
                $errors = session()->getFlashdata('errors');
                if (!empty($errors)) { ?>
                    <div class="alert alert-danger" role="alert">
                        <ul>
                            <?php foreach ($errors as $key => $value) { ?>
                                <li><?= esc($value) ?></li>
                            <?php } ?>
                        </ul>
                    </div>
                <?php } ?>

                <?php echo form_open_multipart('pemilik_kelola_user/update/' . $user['id_user']); ?>

                <div class="form-group">
                    <label>Nama Pengguna</label>
                    <input name="username" value="<?= $user['username'] ?>" class="form-control" placeholder="Masukkan Nama Pengguna" required>
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="number" name="password" value="<?= $user['password'] ?>" class="form-control" placeholder="Masukkan Password" required>
                </div>

                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" value="<?= $user['nama_lengkap'] ?>" class="form-control" placeholder="Masukkan Nama Lengkap" required>
                </div>

                <div class="form-group">
                    <label>Sesi User</label>
                    <input type="text" name="sesi_user" value="<?= esc($sesi_user) ?>" class="form-control" readonly>
                </div>

                <div class="form-group">
                    <label>Nama Title</label>
                    <input name="nama_title" value="<?= $user['nama_title'] ?>" class="form-control" placeholder="Masukkan Nama Title" required>
                </div>

                <div class="form-group">
                    <label>No Telepon User</label>
                    <input name="notelpon_user" value="<?= $user['notelpon_user'] ?>" class="form-control" placeholder="Masukkan No Telepon User" required>
                </div>

                <div class="form-group">
                    <label>Jobdesk User</label>
                    <input name="jobdesk_user" value="<?= $user['jobdesk_user'] ?>" class="form-control" placeholder="Masukkan Jobdesk User" required>
                </div>

                <div class="form-group">
                    <label>Level User</label>
                    <select id="level_user" name="level" class="form-control">
                        <option value="<?= $user['level'] ?>"> <?php if ($user['level'] == 1) {
                                                                    echo 'Pemilik';
                                                                } else if ($user['level'] == 2) {
                                                                    echo 'Admin Toko';
                                                                }
                                                                ?></option>
                        <option value="1">Pemilik</option>
                        <option value="2">Admin Toko</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Foto Profil Terkini</label>
                    <p></p>
                    <img src="<?= base_url('fotouser/' . $user['foto_user']) ?>" id="gambar_load" width="100px">
                </div>

                <div class="form-group">
                    <label>Upload Foto Profil Baru</label>
                    <p></p>
                    <input type="file" class="form-control" name="foto_user" id="preview_gambar">
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Simpan</button>
                    <a href="<?= base_url('pemilik_kelola_user/user') ?>" class="btn btn-primary">Kembali</a>
                </div>

                <?php echo form_close() ?>

            </div>
        </div>

    </div>
    <div class="col-md-3">
    </div>
</div>