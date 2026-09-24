<div class="row">
    <div class="col-md-3">
    </div>
    <div class="col-md-6">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Tambah FAQ (Bantuan) Platform</h3>
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

                <?= form_open('superadmin_kebijakan_platform/save_faq'); ?>

                <div class="form-group">
                    <label>Kategori</label>
                    <select name="kategori" class="form-control" required>
                        <option value="">-- Pilih Kategori --</option>
                        <option value="pemesanan">Pemesanan</option>
                        <option value="pembayaran">Pembayaran</option>
                        <option value="pengiriman">Pengiriman</option>
                        <option value="retur">Retur &amp; Garansi</option>
                        <option value="akun">Akun &amp; Profil</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Pertanyaan</label>
                    <input name="pertanyaan" class="form-control" placeholder="Masukkan Pertanyaan" required>
                </div>

                <div class="form-group">
                    <label>Jawaban</label>
                    <textarea name="jawaban" class="form-control" rows="5" placeholder="Masukkan Jawaban" required></textarea>
                </div>

                <div class="form-group">
                    <label>Urutan Tampil</label>
                    <input type="number" name="urutan" class="form-control" value="0" min="0">
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Simpan</button>
                    <a href="<?= base_url('superadmin_kebijakan_platform/faq') ?>" class="btn btn-primary">Kembali</a>
                </div>
                <?= form_close(); ?>
            </div>
        </div>

    </div>
    <div class="col-md-3">
    </div>
</div>