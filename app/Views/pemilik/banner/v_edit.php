<div class="row">
    <div class="col-md-3">
    </div>
    <div class="col-md-6">
        <div class="box">
            <div class="box-header">
            </div>

            <!-- /.box-header -->
            <div class="box-body">
                <!-- pop up alert -->
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

                <?php echo form_open_multipart('pemilik_kelola_data/update_banner/' . $banner['id_banner']); ?>

                <input type="hidden" name="sesi_user" value="<?= esc($sesi_user) ?>" class="form-control" readonly>
                <input type="hidden" name="foto_lama" value="<?= esc($banner['foto_banner']) ?>">

                <div class="form-group">
                    <label>Judul Banner</label>
                    <input name="judul_banner" value="<?= esc($banner['judul_banner']) ?>" class="form-control" placeholder="Masukkan Judul Banner" required>
                </div>

                <div class="form-group">
                    <label>Deskripsi Banner</label>
                    <textarea name="deskripsi_banner" class="form-control" rows="3" placeholder="Konten/teks banner (opsional)"><?= esc($banner['deskripsi_banner'] ?? '') ?></textarea>
                </div>

                <div class="form-group">
                    <label>Link Banner (opsional)</label>
                    <input name="link_banner" value="<?= esc($banner['link_banner'] ?? '') ?>" class="form-control" placeholder="https://... (saat banner diklik)">
                </div>

                <div class="form-group">
                    <label>Foto Banner Saat Ini</label><br>
                    <img src="<?= base_url('fotobanner/' . $banner['foto_banner']) ?>" width="200" alt="<?= esc($banner['judul_banner']) ?>">
                </div>

                <div class="form-group">
                    <label>Ganti Foto Banner (opsional)</label>
                    <input type="file" class="form-control" name="foto_banner" id="preview_gambar" accept="image/*">
                    <small class="text-muted">Kosongkan jika tidak ingin mengganti. Format PNG, JPG, JPEG, GIF - Max 1792 KB.</small>
                </div>

                <div class="form-group">
                    <label>Aktif di Slider</label><br>
                    <input type="checkbox" name="status" value="1" <?= $banner['status'] == 1 ? 'checked' : '' ?>> &nbsp;Tampilkan di carousel toko
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Simpan</button>
                    <a href="<?= base_url('pemilik_kelola_data/banner') ?>" class="btn btn-primary">Kembali</a>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>

    </div>
    <div class="col-md-3">
    </div>
</div>