<div class="row">
    <div class="col-md-3">
    </div>
    <div class="col-md-6">
        <div class="box">
            <div class="box-header">
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

                <?php echo form_open_multipart('pemilik_kelola_data/update_produk/' . $data_produk['id_produk']); ?>

                <input type="hidden" name="sesi_user" value="<?= esc($sesi_user) ?>" class="form-control" readonly>

                <div class="form-group">
                    <label for="kode_produk">Kode Produk</label>
                    <input type="text" class="form-control" value="<?= $data_produk['kode_produk'] ?>" id="kode_produk" name="kode_produk" readonly>
                </div>

                <div class="form-group">
                    <label>Jenis Produk</label>
                    <select id="id_jenis" name="id_jenis" class="form-control" required>
                        <option value="">--Pilih Jenis Produk--</option>
                        <?php foreach ($data_jenis as $key => $value) { ?>
                            <option value="<?= $value['id_jenis'] ?>" <?= $data_produk['id_jenis'] == $value['id_jenis'] ? 'selected' : '' ?>><?= $value['jenis_produk'] ?></option>
                        <?php } ?>
                    </select>
                </div>

                <?php if (getUserPreference('tampilkan_varian')) : ?>
                    <div class="form-group" id="kolom-varian">
                        <label>Varian Produk</label>
                        <select id="id_varian" name="id_varian" class="form-control">
                            <option value="">--Pilih Varian--</option>
                            <?php foreach ($data_varian as $key => $value) { ?>
                                <option value="<?= $value['id_varian'] ?>" <?= $data_produk['id_varian'] == $value['id_varian'] ? 'selected' : '' ?>><?= $value['varian_produk'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                <?php endif; ?>

                <div class="form-group">
                    <label>Nama Produk</label>
                    <input name="nama_produk" id="nama_produk" value="<?= $data_produk['nama_produk'] ?>" class="form-control" placeholder="Masukkan Nama Produk" readonly>
                </div>

                <div class="form-group">
                    <label>Ukuran Produk</label>
                    <input type="text" id="ukuran_produk" name="ukuran_produk" value="<?= $data_produk['ukuran_produk'] ?>" class="form-control">
                </div>

                <div class="form-group">
                    <label>Berat Produk</label>
                    <input type="text" id="berat_produk" name="berat_produk" value="<?= $data_produk['berat_produk'] ?>" class="form-control">
                </div>

                <div class="form-group">
                    <label>Satuan Berat</label>
                    <select id="satuan_berat" name="satuan_berat" class="form-control">
                        <option value="">--Pilih Satuan--</option>
                        <?php foreach ($data_satuan as $key => $value) { ?>
                            <option value="<?= $value['satuan_produk'] ?>"
                                <?= isset($data_produk['satuan_berat']) && $data_produk['satuan_berat'] == $value['satuan_produk'] ? 'selected' : '' ?>>
                                <?= $value['satuan_produk'] ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Harga Produk</label>
                    <input type="number" id="harga_produk" name="harga_produk" value="<?= $data_produk['harga_produk'] ?>" class="form-control" placeholder="Masukkan Harga Produk" required>
                </div>

                <div class="form-group">
                    <label>Deskripsi Produk</label>
                    <input type="text" name="deskripsi_produk" value="<?= $data_produk['deskripsi_produk'] ?>" class="form-control" placeholder="Masukkan Deskripsi Produk" required>
                </div>

                <div class="form-group">
                    <label>Foto Produk Terkini</label>
                    <p></p>
                    <img src="<?= base_url('fotoproduk/' . $data_produk['foto_produk']) ?>" id="gambar_load" width="100px">
                </div>

                <div class="form-group">
                    <label>Upload Foto Produk Baru</label>
                    <p></p>
                    <input type="file" class="form-control" name="foto_produk" id="preview_gambar">
                </div>

                <div class="form-group">
                    <label>Gallery Foto Produk (Multiple, opsional)</label>
                    <input type="file" class="form-control" name="gallery_images[]" id="gallery_images" multiple accept="image/*">
                    <small class="text-muted">Pilih multiple foto untuk gallery (depan, belakang, detail, dll)</small>
                    <?php if (!empty($data_produk['gallery_images'])): ?>
                        <div class="mt-2">
                            <?php $gallery = json_decode($data_produk['gallery_images'], true) ?? []; ?>
                            <?php foreach ($gallery as $img): ?>
                                <img src="<?= base_url('fotoproduk/' . $img) ?>" width="60" class="m-1 border">
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label>Foto Panduan Ukuran / Size Guide (opsional)</label>
                    <input type="file" class="form-control" name="size_guide_image" id="size_guide_image" accept="image/*">
                    <small class="text-muted">Gambar tabel ukuran (dada, pinggang, panjang, dll)</small>
                    <?php if (!empty($data_produk['size_guide_image'])): ?>
                        <img src="<?= base_url('fotoproduk/' . $data_produk['size_guide_image']) ?>" width="80" class="mt-2">
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label>URL Video Produk (YouTube/Vimeo/MP4 link, opsional)</label>
                    <input type="url" class="form-control" name="video_url" id="video_url" placeholder="https://youtube.com/watch?v=... atau https://example.com/video.mp4" value="<?= esc($data_produk['video_url'] ?? '') ?>">
                    <small class="text-muted">Link YouTube, Vimeo, atau direct MP4</small>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Simpan</button>
                    <a href="<?= base_url('pemilik_kelola_data/produk') ?>" class="btn btn-primary">Kembali</a>
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
        const namaJenis = document.getElementById('id_jenis');
        const namavarian = document.getElementById('id_varian');
        const kodeProdukInput = document.getElementById('kode_produk');
        const namaProdukInput = document.getElementById('nama_produk');

        // Fungsi untuk mengambil kode produk dan nama produk
        function fetchProdukDetails() {
            const jenis = namaJenis.value;
            let varian = namavarian ? namavarian.value : null; // Jika elemen varian ada, ambil nilainya

            // Kirim data hanya jika jenis dipilih
            if (jenis) {
                // Jika varian tidak dipilih atau tidak ada, hanya kirim jenis
                const data = {
                    id_jenis: jenis
                };
                if (varian) {
                    data.id_varian = varian; // Hanya tambahkan varian jika dipilih
                }

                // Mengambil kode produk
                $.ajax({
                    url: '<?= base_url("pemilik_kelola_data/generateKodeProduk") ?>',
                    type: 'GET',
                    data: data,
                    success: function(response) {
                        if (response.kode_produk) {
                            kodeProdukInput.value = response.kode_produk;
                        } else {
                            console.error('Kode produk tidak ditemukan:', response);
                        }
                    },
                    error: function() {
                        alert('Gagal mengambil kode produk. Pastikan jenis sudah dipilih.');
                    }
                });

                // Mengambil nama produk
                $.ajax({
                    url: '<?= base_url("pemilik_kelola_data/generateNamaProduk") ?>',
                    type: 'GET',
                    data: data,
                    success: function(response) {
                        if (response.nama_produk) {
                            namaProdukInput.value = response.nama_produk;
                        } else {
                            console.error('Nama produk tidak ditemukan:', response);
                        }
                    },
                    error: function() {
                        alert('Gagal mengambil nama produk. Pastikan jenis sudah dipilih.');
                    }
                });
            }
        }

        // Event listener untuk memicu pengambilan kode dan nama produk
        namaJenis.addEventListener('change', fetchProdukDetails);

        // Cek apakah elemen id_varian ada sebelum menambahkan event listener
        if (namavarian) {
            namavarian.addEventListener('change', fetchProdukDetails);
        }
    });
</script>