<div class="row">
    <div class="col-md-3"></div>

    <div class="col-md-6">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Form Tambah Stok</h3>
            </div>

            <div class="box-body">
                <?php $errors = session()->getFlashdata('errors'); ?>
                <?php if (!empty($errors)) : ?>
                    <div class="alert alert-danger" role="alert">
                        <ul>
                            <?php foreach ($errors as $value) : ?>
                                <li><?= esc($value) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('admin_kelola_data/save_stok') ?>" method="post" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <input type="hidden" name="sesi_user" value="<?= esc($sesi_user) ?>" readonly>

                    <div class="form-group">
                        <label for="kode_stok">Kode Stok</label>
                        <input type="text" class="form-control" id="kode_stok" name="kode_stok" placeholder="Kode Stok" readonly>
                    </div>

                    <div class="form-group">
                        <label for="tanggal_masuk_produk">Tanggal Masuk Stok</label>
                        <input type="date" name="tanggal_masuk_produk" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label for="nama_produk">Nama Produk</label>
                        <select id="nama_produk" name="nama_produk" class="form-control" onchange="updateProdukDetails()" required>
                            <option value="" disabled selected>Pilih Produk</option>
                            <?php foreach ($data_produk as $value) : ?>
                                <option value="<?= $value['nama_produk'] ?>"
                                    data-harga="<?= $value['harga_produk'] ?>"
                                    data-berat="<?= $value['berat_produk'] ?>"
                                    data-satuan-berat="<?= $value['satuan_berat'] ?>"
                                    data-jenis="<?= $value['jenis_produk'] ?>"
                                    data-ukuran="<?= $value['ukuran_produk'] ?>">
                                    <?= $value['nama_produk'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="jenis_produk">Jenis Produk</label>
                        <input type="text" id="jenis_produk" name="jenis_produk" class="form-control" readonly>
                    </div>

                    <div class="form-group">
                        <label for="harga_produk">Harga Produk</label>
                        <input type="text" id="harga_produk" name="harga_produk" value="" class="form-control" readonly>
                    </div>

                    <div class="form-group">
                        <label for="harga_produk">Ukuran Produk</label>
                        <input type="text" id="ukuran_produk" name="ukuran_produk" value="" class="form-control" readonly>
                    </div>

                    <div class="form-group">
                        <label for="berat_produk">Berat Produk</label>
                        <input type="text" id="berat_produk" name="berat_produk" class="form-control" readonly>
                    </div>

                    <div class="form-group">
                        <label for="satuan_berat">Satuan Berat</label>
                        <input type="text" id="satuan_berat" name="satuan_berat" class="form-control" readonly>
                    </div>

                    <div class="form-group">
                        <label for="jumlah_stok_produk">Jumlah Stok Produk</label>
                        <input type="number" name="jumlah_stok_produk" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Satuan Berat</label>
                        <select id="satuan_produk" name="satuan_produk" class="form-control">
                            <option value="">--Pilih Satuan--</option>
                            <?php foreach ($data_satuan as $key => $value) { ?>
                                <option value="<?= $value['satuan_produk'] ?>"><?= esc($value['satuan_produk']) ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Simpan</button>
                        <a href="<?= base_url('admin_kelola_data/stok') ?>" class="btn btn-primary">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-3"></div>
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