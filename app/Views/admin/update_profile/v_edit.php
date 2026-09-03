<div class="row">
    <div class="col-md-3">
    </div>
    <div class="col-md-6">
        <div class="box">
            <div class="box-header">
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

                <?= form_open_multipart(base_url('home_admin/update_profile/' . ($data_profil['id_user'] ?? ''))) ?>

                <div class="form-group">
                    <label>Username</label>
                    <input name="username" value="<?= session()->get('username') ?>" class="form-control" placeholder="Masukkan Nama Lengkap" readonly>
                </div>

                <div class="form-group profile">
                    <label>Kata Sandi</label>
                    <input name="password" type="text" value="<?= session()->get('password') ?>" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input name="nama_lengkap" value="<?= session()->get('nama_lengkap') ?>" class="form-control">
                </div>

                <div class="form-group">
                    <label>No Telepon User</label>
                    <input type="number" name="notelpon_user" value="<?= session()->get('notelpon_user') ?>" class="form-control">
                </div>

                <div class="form-group">
                    <label>Jobdesk</label>
                    <input name="jobdesk_user" value="<?= session()->get('jobdesk_user') ?>" class="form-control">
                </div>

                <div class="form-group profile">
                    <label>Upload Foto Profil Baru</label>
                    <p></p>
                    <input type="file" class="form-control profile" name="foto_user" id="preview_gambar">
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Simpan</button>
                    <a href="<?= base_url('home_admin/profil') ?>" class="btn btn-primary">Kembali</a>
                </div>

                <?= form_close() ?>

            </div>

        </div>

    </div>

    <div class="col-md-3">
    </div>
</div>