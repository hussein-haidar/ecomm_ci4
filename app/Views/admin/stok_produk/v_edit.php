<div class="row">
    <div class="col-md-3">
    </div>
    <div class="col-md-6">
        <div class="box">
            <div class="box-header with-border">
            </div>

            <!-- /.box-header -->
            <div class="box-body">
                <!-- pop up alert wrong-->
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

                <?php echo form_open_multipart('admin_kelola_data/update_stok/' . $data_stok['id_stok']); ?>

                <input type="hidden" name="sesi_user" value="<?= esc($sesi_user) ?>" class="form-control" readonly>

                <div class="form-group">
                    <label for="kode_stok">Kode Stok</label>
                    <input type="text" class="form-control" id="kode_stok" name="kode_stok" value="<?= $data_stok['kode_stok'] ?>" readonly>
                </div>

                <div class="form-group">
                    <label>Tanggal Masuk Stok</label>
                    <input type="date" name="tanggal_masuk_produk" value="<?= $data_stok['tanggal_masuk_produk'] ?>" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Nama Produk</label>
                    <select name="nama_produk" id="nama_produk" class="form-control" onchange="updateProdukDetails()">
                        <option value="">--Pilih Produk--</option>
                        <?php foreach ($data_produk as $value) { ?>
                            <option value="<?= $value['nama_produk'] ?>"
                                data-harga="<?= $value['harga_produk'] ?>"
                                data-berat="<?= $value['berat_produk'] ?>"
                                data-satuan-berat="<?= $value['satuan_berat'] ?>"
                                data-jenis="<?= $value['jenis_produk'] ?>"
                                data-ukuran="<?= $value['ukuran_produk'] ?>"
                                <?= (isset($data_stok['nama_produk']) && $data_stok['nama_produk'] == $value['nama_produk']) ? 'selected' : '' ?>>
                                <?= $value['nama_produk'] ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Jenis Produk</label>
                    <input type="text" id="jenis_produk" name="jenis_produk" value="<?= $data_stok['jenis_produk'] ?>" class="form-control" readonly>
                </div>

                <div class="form-group">
                    <label for="harga_produk">Harga Produk</label>
                    <input type="text" id="harga_produk" name="harga_produk"
                        value="<?= $data_stok['harga_produk'] ?>"
                        class="form-control" readonly>
                </div>

                <div class="form-group">
                    <label for="harga_produk">Ukuran Produk</label>
                    <input type="text" id="ukuran_produk" name="ukuran_produk" value="<?= $data_stok['ukuran_produk'] ?>" class="form-control" readonly>
                </div>

                <div class="form-group">
                    <label for="berat_produk">Berat Produk</label>
                    <input type="text" id="berat_produk" name="berat_produk" value="<?= $data_stok['berat_produk'] ?>" class="form-control" readonly>
                </div>

                <div class="form-group">
                    <label for="satuan_berat">Satuan Berat</label>
                    <input type="text" id="satuan_berat" name="satuan_berat" value="<?= $data_stok['satuan_berat'] ?>" class="form-control" readonly>
                </div>

                <div class="form-group">
                    <label for="jumlah_stok_produk">Jumlah Stok Produk</label>
                    <input type="number" name="jumlah_stok_produk" value="<?= $data_stok['jumlah_stok_produk'] ?>" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Satuan Produk</label>
                    <select id="satuan_produk" name="satuan_produk" class="form-control">
                        <option value="">--Pilih Satuan--</option>
                        <?php foreach ($data_satuan as $key => $value) { ?>
                            <option value="<?= $value['satuan_produk'] ?>"
                                <?= isset($data_stok['satuan_produk']) && $data_stok['satuan_produk'] == $value['satuan_produk'] ? 'selected' : '' ?>>
                                <?= $value['satuan_produk'] ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Simpan</button>
                    <a href="<?= base_url('admin_kelola_data/stok') ?>" class="btn btn-primary">Kembali</a>
                </div>
                <?php echo form_close() ?>
            </div>
        </div>
    </div>

    <div class="col-md-3">
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const namaProduk = document.getElementById('nama_produk');
        const kodeStokInput = document.getElementById('kode_stok');

        function fetchKodeStok() {
            const produk = namaProduk.value;
            if (produk) {
                fetch('<?= base_url("admin_kelola_data/generateKodeStok") ?>', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            nama_produk: produk
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.kode_stok) {
                            kodeStokInput.value = data.kode_stok;
                        }
                    })
                    .catch(error => console.error('Error:', error));
            }
        }

        namaProduk.addEventListener('change', () => {
            fetchKodeStok();
            updateProdukDetails();
        });
    });

    function updateProdukDetails() {
        const select = document.getElementById("nama_produk");
        const selectedOption = select.options[select.selectedIndex];

        const harga = selectedOption.getAttribute("data-harga");
        const berat = selectedOption.getAttribute("data-berat");
        const satuanBerat = selectedOption.getAttribute("data-satuan-berat");
        const jenisProduk = selectedOption.getAttribute("data-jenis");
        const varian = selectedOption.getAttribute("data-ukuran");

        document.getElementById("harga_produk").value = harga ?? "";
        document.getElementById("berat_produk").value = berat ?? "";
        document.getElementById("satuan_berat").value = satuanBerat ?? "";
        document.getElementById("jenis_produk").value = jenisProduk ?? "";

        const varianInput = document.getElementById("ukuran_produk");
        if (varianInput) {
            varianInput.value = varian ?? "";
        }
    }
</script>