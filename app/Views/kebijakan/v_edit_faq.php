<div class="row">
    <div class="col-md-3">
    </div>
    <div class="col-md-6">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Edit FAQ (Bantuan)</h3>
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

                <?= form_open('kebijakan_toko/update_faq/' . $faq['id_faq']); ?>

                <div class="form-group">
                    <label>Kategori</label>
                    <?php $kategoriLama = $faq['kategori']; ?>
                    <select name="kategori" class="form-control" required>
                        <option value="">-- Pilih Kategori --</option>
                        <option value="pemesanan" <?= $kategoriLama == 'pemesanan' ? 'selected' : '' ?>>Pemesanan</option>
                        <option value="pembayaran" <?= $kategoriLama == 'pembayaran' ? 'selected' : '' ?>>Pembayaran</option>
                        <option value="pengiriman" <?= $kategoriLama == 'pengiriman' ? 'selected' : '' ?>>Pengiriman</option>
                        <option value="retur" <?= $kategoriLama == 'retur' ? 'selected' : '' ?>>Retur &amp; Garansi</option>
                        <option value="akun" <?= $kategoriLama == 'akun' ? 'selected' : '' ?>>Akun &amp; Profil</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Pertanyaan</label>
                    <input name="pertanyaan" value="<?= esc($faq['pertanyaan']) ?>" class="form-control" placeholder="Masukkan Pertanyaan" required>
                </div>

                <div class="form-group">
                    <label>Jawaban</label>
                    <textarea name="jawaban" class="form-control" rows="5" placeholder="Masukkan Jawaban" required><?= esc($faq['jawaban']) ?></textarea>
                </div>

                <div class="form-group">
                    <label>Urutan Tampil</label>
                    <input type="number" name="urutan" class="form-control" value="<?= esc($faq['urutan']) ?>" min="0">
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Simpan</button>
                    <a href="<?= base_url('kebijakan_toko/faq') ?>" class="btn btn-primary">Kembali</a>
                </div>
                <?= form_close() ?>
            </div>
        </div>

    </div>
    <div class="col-md-3">
    </div>
</div>