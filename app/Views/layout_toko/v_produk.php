<?php
function renderBintangServer($rating)
{
    $s = '';
    $val = round(((float)$rating) * 2) / 2;
    for ($i = 1; $i <= 5; $i++) {
        if ($val >= $i) {
            $s .= '<i class="fas fa-star text-warning"></i>';
        } elseif ($val === $i - 0.5) {
            $s .= '<i class="fas fa-star-half-alt text-warning"></i>';
        } else {
            $s .= '<i class="far fa-star text-muted"></i>';
        }
    }
    return $s;
}
?>

<?php if (empty($produk_data ?? [])): ?>
    <div class="alert alert-light border text-center text-muted py-5">
        <i class="fas fa-box-open fa-2x d-block mb-2"></i>
        Belum ada produk untuk kategori ini.
    </div>
<?php endif; ?>

<?php foreach ($produk_data ?? [] as $produk) : ?>
    <?php
    $stok = (int) $produk['jumlah_stok_produk'];
    $stokClass = $stok <= 0 ? 'text-danger' : ($stok <= 5 ? 'text-warning' : 'text-success');
    $stokText = $stok <= 0 ? 'Habis' : ($stok <= 5 ? 'Sisa ' . $stok : 'Tersedia');
    $hasMultipleSizes = count($produk['ukuran_list'] ?? []) > 1;
    ?>
    <div class="col-12 mb-3" data-aos="fade-up" data-nama-produk="<?= esc($produk['nama_produk']) ?>">
        <div class="card h-100 shadow-sm border-0">
            <div class="row g-0">
                <!-- Product Image -->
                <div class="col-12 col-md-4">
                    <img src="<?= base_url('fotoproduk/' . $produk['foto_produk']) ?>"
                         alt="<?= esc($produk['nama_produk']) ?>"
                         class="card-img-top img-fluid object-fit-cover">
                </div>

                <!-- Product Info -->
                <div class="col-12 col-md-8">
                    <div class="card-body d-flex flex-column h-100 p-3 p-md-4">
                        <!-- Product Name -->
                        <h5 class="card-title fw-bold mb-2 text-truncate h5-product">
                            <a href="<?= base_url('home_toko/detail_produk/' . urlencode($produk['nama_produk'])) ?>" class="text-dark text-decoration-none">
                                <?= esc($produk['nama_produk']) ?>
                            </a>
                        </h5>

                        <!-- Product Meta -->
                        <div class="mb-2">
                            <small class="text-muted d-block mb-1"><strong>Ukuran:</strong> <?= esc($produk['ukuran_produk']) ?></small>
                            <small class="<?= $stokClass ?> d-block"><strong>Stock:</strong> <?= $stokText ?></small>
                        </div>

                        <!-- Price -->
                        <div class="mb-3">
                            <span class="fs-5 fw-bold text-primary mb-0">Rp <?= number_format($produk['harga_produk'], 0, ',', '.') ?></span>
                        </div>

                        <!-- Rating -->
                        <div class="mb-3">
                            <div class="d-flex align-items-center gap-2 small text-warning">
                                <?= renderBintangServer($produk['rating_summary']['rata_rata'] ?? 0) ?>
                                <strong><?= number_format($produk['rating_summary']['rata_rata'] ?? 0, 1) ?></strong>
                                <small class="text-muted">(<?= (int)($produk['rating_summary']['total'] ?? 0) ?> ulasan)</small>
                                <?php if (($produk['rating_summary']['rata_rata'] ?? 0) >= 4.5): ?>
                                    <span class="badge bg-success rounded-pill px-2 py-0 h6-badge"><i class="fas fa-thumbs-up"></i> Terbaik</span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="mt-auto d-flex gap-2 flex-wrap">
                            <?php if ($user_logged_in && $stok > 0): ?>
                                <?= form_open_multipart('pelanggan_kelola_data/add_to_cart'); ?>
                                <input type="hidden" name="id_stok" value="<?= esc($produk['id_stok']) ?>">
                                <input type="hidden" name="nama_produk" value="<?= esc($produk['nama_produk']) ?>">
                                <input type="hidden" name="satuan_produk" value="<?= esc($produk['satuan_produk']) ?>">
                                <input type="hidden" name="harga_produk" value="<?= esc($produk['harga_produk']) ?>">
                                <input type="hidden" name="berat_produk" value="<?= esc($produk['berat_produk'] ?? 0) ?>">

                                <?php if ($hasMultipleSizes): ?>
                                    <div class="mb-2">
                                        <label for="ukuran_produk_<?= esc($produk['id_stok']) ?>" class="form-label small fw-medium mb-1">Ukuran:</label>
                                        <select id="ukuran_produk_<?= esc($produk['id_stok']) ?>" name="ukuran_produk" class="form-select form-select-sm" required>
                                            <option value="">--Pilih Ukuran--</option>
                                            <?php foreach ($produk['ukuran_list'] as $ukuran): ?>
                                                <option value="<?= esc(trim($ukuran)) ?>"><?= esc(trim($ukuran)) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                <?php else: ?>
                                    <input type="hidden" name="ukuran_produk" value="<?= esc(trim($produk['ukuran_list'][0] ?? '')) ?>">
                                <?php endif; ?>

                                <div class="mb-2 d-flex align-items-center gap-2">
                                    <label for="jumlah_produk_<?= esc($produk['id_stok']) ?>" class="form-label small fw-medium mb-0">Qty:</label>
                                    <input type="number" id="jumlah_produk_<?= esc($produk['id_stok']) ?>" name="jumlah_produk"
                                           min="1" value="1" max="<?= $stok ?>" class="form-control form-control-sm text-center w-75">
                                </div>

                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary btn-sm rounded-pill py-2 px-3">
                                        <i class="fas fa-shopping-cart me-1"></i>Keranjang
                                    </button>
                                    <button type="submit" formaction="<?= base_url('pelanggan_kelola_data/beli_sekarang') ?>" class="btn btn-success btn-sm rounded-pill py-2 px-3">
                                        <i class="fas fa-bolt me-1"></i>Beli
                                    </button>
                                </div>
                                <?= form_close() ?>
                            <?php else: ?>
                                <?php if ($stok > 0): ?>
                                    <a href="<?= base_url('home_toko/login') ?>" class="btn btn-primary btn-sm rounded-pill py-2 px-3 flex-fill">
                                        <i class="fas fa-sign-in-alt me-1"></i>Login untuk beli
                                    </a>
                                <?php else: ?>
                                    <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill w-100" disabled>
                                        <i class="fas fa-ban me-1"></i>Stok Habis
                                    </button>
                                <?php endif; ?>
                            <?php endif; ?>

                            <?php if ($user_logged_in && $stok > 0): ?>
                                <a href="#" onclick="bukaChatProduk(event, <?= esc(json_encode($produk['nama_produk']), 'attr') ?>, <?= (int)$produk['id_stok'] ?>, <?= esc(json_encode($produk['foto_produk'] ?? ''), 'attr') ?>)" class="btn btn-outline-primary btn-sm rounded-pill py-2 px-3">
                                    <i class="fas fa-comments"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>

<script>
    (function() {
        function muatStok() {
            fetch('<?= site_url('home_toko/ambil_stok') ?>')
                .then(response => response.json())
                .then(data => {
                    data.forEach(produk => {
                        const stokElement = document.getElementById('stok-' + produk.id_stok);
                        if (stokElement && stokElement.textContent != produk.jumlah_stok_produk) {
                            stokElement.textContent = produk.jumlah_stok_produk;
                        }
                    });
                })
                .catch(err => {
                    console.error("Gagal memuat stok:", err);
                });
        }
        muatStok();
        setInterval(muatStok, 10000);
    })();
</script>