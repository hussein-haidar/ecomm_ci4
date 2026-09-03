<div class="row">
    <div class="col-md-3">
    </div>
    <div class="col-md-6">
        <div class="box">
            <div class="box-header with-border">
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

                <?php echo form_open_multipart('pemilik_kelola_user/save_kurir'); ?>

                <input type="hidden" name="sesi_user" value="<?= esc($sesi_user) ?>" class="form-control" readonly>
                
                <div class="form-group">
                    <label>Jenis Kurir</label>
                    <input name="jenis_kurir" class="form-control" placeholder="Masukkan Jenis Kurir" required>
                </div>

                <div class="form-group">
                    <label>Ongkir</label>
                    <input name="ongkir" class="form-control" placeholder="Masukkan Ongkir" required>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Simpan</button>
                    <a href="<?= base_url('pemilik_kelola_user/kurir') ?>" class="btn btn-primary">Kembali</a>
                </div>

                <?php echo form_close(); ?>

            </div>
        </div>

    </div>
    <div class="col-md-3">
    </div>
</div>