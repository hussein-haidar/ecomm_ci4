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

                <?php echo form_open_multipart('pemilik_kelola_data/update_varian/' . $varian_produk['id_varian']); ?>

                <input type="hidden" name="sesi_user" value="<?= esc($sesi_user) ?>" class="form-control" readonly>

                <div class="form-group">
                    <label>Motif Produk</label>
                    <input name="varian_produk" value="<?= $varian_produk['varian_produk'] ?>" class="form-control" placeholder="Masukkan Nama Produk" required>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Simpan</button>
                    <a href="<?= base_url('pemilik_kelola_data/varian') ?>" class="btn btn-primary">Kembali</a>
                </div>

                <?php echo form_close() ?>

            </div>
        </div>

    </div>
    <div class="col-md-3">
    </div>
</div>