<?php
function katalogListRendaBintang($rating)
{
    $s = '';
    $val = round(((float) $rating) * 2) / 2;
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
<?php foreach ($produk_data as $produk) : ?>
    <?php
    $avgRating = 0;
    $totalReview = 0;
    $isFlash = !empty($produk['harga_flash']);
    $stok = (int) $produk['jumlah_stok_produk'];
    $stokClass = $stok <= 0 ? 'text-danger' : ($stok <= 5 ? 'text-warning' : 'text-success');
    $stokText = $stok <= 0 ? 'Habis' : ($stok <= 5 ? 'Sisa ' . $stok : 'Tersedia');
    $hasMultipleSizes = count($produk['ukuran_list'] ?? []) > 1;
    ?>
    <div class="col-12 mb-3" data-aos="fade-up">
        <article class="product-item h-100"
                 data-id-stok="<?= esc($produk['id_stok']) ?>"
                 data-nama-produk="<?= esc($produk['nama_produk']) ?>"
                 data-harga="<?= esc($produk['harga_produk']) ?>"
                 data-stok="<?= $stok ?>">

            <!-- Badge -->
            <?php if (!empty($produk['badge'])): ?>
                <span class="badge position-absolute m-3 rounded-pill"><?= esc($produk['badge']) ?></span>
            <?php elseif ($isFlash): ?>
                <span class="badge bg-danger position-absolute m-3 rounded-pill"><i class="fas fa-bolt me-1"></i>FLASH SALE</span>
            <?php elseif ($stok <= 0): ?>
                <span class="badge bg-secondary position-absolute m-3 rounded-pill"><i class="fas fa-times-circle me-1"></i>HABIS</span>
            <?php elseif ($stok <= 5): ?>
                <span class="badge bg-warning text-dark position-absolute m-3 rounded-pill"><i class="fas fa-exclamation-triangle me-1"></i>SISA <?= $stok ?></span>
            <?php endif; ?>

            <div class="row g-0 h-100">
                <!-- Product Image -->
                <div class="col-12 col-md-4 col-lg-3">
                    <div class="position-relative overflow-hidden bg-light">
                        <a href="<?= base_url('home_toko/detail_produk/' . urlencode($produk['nama_produk'])) ?>" aria-label="Lihat detail <?= esc($produk['nama_produk']) ?>">
                            <img src="<?= base_url('fotoproduk/' . $produk['foto_produk']) ?>"
                                 class="img-fluid w-100 h-100 object-fit-cover"
                                 alt="<?= esc($produk['nama_produk']) ?>"
                                 loading="lazy">
                        </a>
                    </div>
                </div>

                <!-- Product Info -->
                <div class="col-12 col-md-8 col-lg-9">
                    <div class="card-body d-flex flex-column h-100 p-3 p-md-4">
                        <!-- Product Name -->
                        <h5 class="card-title fw-bold mb-2 text-truncate">
                            <a href="<?= base_url('home_toko/detail_produk/' . urlencode($produk['nama_produk'])) ?>" class="text-dark text-decoration-none">
                                <?= esc($produk['nama_produk']) ?>
                            </a>
                        </h5>

                        <!-- Rating -->
                        <div class="mb-2">
                            <div class="d-flex align-items-center gap-2 small text-warning">
                                <span><?= katalogListRendaBintang($avgRating) ?></span>
                                <?php if ($totalReview > 0): ?>
                                    <span class="text-muted"><?= number_format($avgRating, 1) ?> (<?= $totalReview ?> ulasan)</span>
                                <?php else: ?>
                                    <span class="text-muted">Belum ada ulasan</span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Stock Status -->
                        <div class="mb-3">
                            <small class="<?= $stokClass ?> d-flex align-items-center gap-1 fw-medium">
                                <?php if ($stok <= 0): ?>
                                    <i class="fas fa-times-circle"></i>
                                <?php elseif ($stok <= 5): ?>
                                    <i class="fas fa-exclamation-triangle"></i>
                                <?php else: ?>
                                    <i class="fas fa-check-circle"></i>
                                <?php endif; ?>
                                <span id="stok-<?= esc($produk['id_stok']) ?>"><?= $stokText ?></span>
                                <?php if ($stok > 0): ?>
                                    <span class="text-muted">(<?= esc($produk['satuan_produk']) ?>)</span>
                                <?php endif; ?>
                            </small>
                        </div>

                        <!-- Price & Actions -->
                        <div class="mt-auto d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <?php if ($isFlash): ?>
                                    <span class="text-danger fw-bold fs-5">Rp<?= number_format($produk['harga_produk'], 0, ',', '.') ?></span>
                                <?php else: ?>
                                    <span class="text-primary fw-bold fs-5">Rp<?= number_format($produk['harga_produk'], 0, ',', '.') ?></span>
                                <?php endif; ?>
                            </div>

                            <div class="d-flex gap-2 flex-wrap">
                                <?php if ($stok > 0): ?>
                                    <?php if ($user_logged_in): ?>
                                        <?= form_open_multipart('pelanggan_kelola_data/add_to_cart', ['class' => 'd-flex gap-2 flex-wrap align-items-end', 'id' => 'product-form-' . $produk['id_stok']]) ?>
                                        <input type="hidden" name="id_stok" value="<?= esc($produk['id_stok']) ?>">
                                        <input type="hidden" name="nama_produk" value="<?= esc($produk['nama_produk']) ?>">
                                        <input type="hidden" name="satuan_produk" value="<?= esc($produk['satuan_produk']) ?>">
                                        <input type="hidden" name="harga_produk" value="<?= esc($produk['harga_produk']) ?>">
                                        <?php if ($hasMultipleSizes): ?>
                                            <div class="d-flex align-items-center gap-2 mb-2 mb-md-0">
                                                <label for="ukuran_produk_<?= esc($produk['id_stok']) ?>" class="form-label small fw-medium mb-0">Ukuran:</label>
                                                <select id="ukuran_produk_<?= esc($produk['id_stok']) ?>" name="ukuran_produk" class="form-select form-select-sm w-auto" required>
                                                    <option value="">Pilih Ukuran</option>
                                                    <?php foreach ($produk['ukuran_list'] as $ukuran): ?>
                                                        <option value="<?= esc($ukuran) ?>"><?= esc($ukuran) ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        <?php else: ?>
                                            <input type="hidden" name="ukuran_produk" value="<?= esc($produk['ukuran_list'][0] ?? '') ?>">
                                        <?php endif; ?>
                                        <div class="d-flex align-items-center gap-2 mb-2 mb-md-0">
                                            <label for="jumlah_produk_<?= esc($produk['id_stok']) ?>" class="form-label small fw-medium mb-0">Qty:</label>
                                            <input type="number" id="jumlah_produk_<?= esc($produk['id_stok']) ?>" name="jumlah_produk"
                                                   min="1" value="1" max="<?= $stok ?>" class="form-control form-control-sm w-75">
                                        </div>
                                        <div class="d-flex gap-2">
                                            <button type="submit" class="btn btn-primary rounded-pill py-2 px-3">
                                                <i class="fas fa-shopping-cart me-1"></i>Keranjang
                                            </button>
                                            <button type="submit" formaction="<?= base_url('pelanggan_kelola_data/beli_sekarang') ?>" class="btn btn-success rounded-pill py-2 px-3">
                                                <i class="fas fa-bolt me-1"></i>Beli
                                            </button>
                                        </div>
                                        <?= form_close() ?>
                                    <?php else: ?>
                                        <div class="d-flex gap-2">
                                            <a href="<?= base_url('home_toko/login') ?>" class="btn btn-primary rounded-pill py-2 px-3">
                                                <i class="fas fa-sign-in-alt me-1"></i>Login
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <button type="button" class="btn btn-outline-secondary rounded-pill py-2 px-3 w-100" disabled>
                                        <i class="fas fa-ban me-1"></i>Stok Habis
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </article>
    </div>
<?php endforeach; ?>

<script>
    function muatStokKatalog() {
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
            .catch(err => console.error("Gagal memuat stok:", err));
    }
    muatStokKatalog();
    setInterval(muatStokKatalog, 10000);
</script>