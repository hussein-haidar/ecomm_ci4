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

                <?php echo form_open_multipart('pemilik_kelola_data/update_bank/' . $bank['id_bank']); ?>

                <input type="hidden" name="sesi_user" value="<?= esc($sesi_user) ?>" class="form-control" readonly>

                <div class="form-group">
                    <label>Nama Rekening</label>
                    <input name="a_n" value="<?= $bank['a_n'] ?>" class="form-control" placeholder="Masukkan Nama Rekening" required>
                </div>

                <div class="form-group">
                    <label>Nama Bank</label>
                    <input name="nama_bank" value="<?= $bank['nama_bank'] ?>" class="form-control" placeholder="Masukkan Nama Bank" required>
                </div>

                <div class="form-group">
                    <label>No Rekening</label>
                    <input name="no_rek" value="<?= $bank['no_rek'] ?>" class="form-control" placeholder="Masukkan No Rekening" required>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Simpan</button>
                    <a href="<?= base_url('pemilik_kelola_data/bank') ?>" class="btn btn-primary">Kembali</a>
                </div>

                <?php echo form_close() ?>

            </div>
        </div>

    </div>
    <div class="col-md-3">
    </div>
</div>