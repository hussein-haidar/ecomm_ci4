<div class="row">
    <div class="col-md-3">
    </div>
    <div class="col-md-6">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Edit Syarat &amp; Ketentuan Platform</h3>
            </div>

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

                <?= form_open('superadmin_kebijakan_platform/update_syarat/' . $skt['id_skt']); ?>

                <div class="form-group">
                    <label>Judul Bagian</label>
                    <input name="judul" value="<?= esc($skt['judul']) ?>" class="form-control" placeholder="Contoh: Informasi Produk, Pembayaran, Pengiriman, dst." required>
                </div>

                <div class="form-group">
                    <label>Isi</label>
                    <textarea name="isi" class="form-control" rows="6" placeholder="Masukkan Isi Syarat & Ketentuan" required><?= esc($skt['isi']) ?></textarea>
                </div>

                <div class="form-group">
                    <label>Urutan Tampil</label>
                    <input type="number" name="urutan" class="form-control" value="<?= esc($skt['urutan']) ?>" min="0">
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Simpan</button>
                    <a href="<?= base_url('superadmin_kebijakan_platform/syarat') ?>" class="btn btn-primary">Kembali</a>
                </div>
                <?= form_close() ?>
            </div>
        </div>

    </div>
    <div class="col-md-3">
    </div>
</div>