<?php
function katalogRendaBintang($rating)
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

<?php if (empty($produk_data ?? [])): ?>
    <div class="alert alert-light border text-center text-muted py-5">
        <i class="fas fa-box-open fa-2x d-block mb-2"></i>
        Belum ada produk tersedia.
    </div>
<?php endif; ?>

<?php foreach ($produk_data ?? [] as $produk) : ?>
    <?php
    $avgRating = 0;
    $totalReview = 0;
    $isFlash = false;
    if (!empty($produk['rating_summary']) && (int) $produk['rating_summary']['total'] > 0) {
        $avgRating = (float) $produk['rating_summary']['rata_rata'];
        $totalReview = (int) $produk['rating_summary']['total'];
    }
    $isFlash = !empty($produk['harga_flash']);
    $stok = (int) $produk['jumlah_stok_produk'];
    $stokClass = $stok <= 0 ? 'text-danger' : ($stok <= 5 ? 'text-warning' : 'text-success');
    $stokText = $stok <= 0 ? 'Stok Habis' : ($stok <= 5 ? 'Sisa ' . $stok . ' pcs' : 'Stok Tersedia');
    $hasMultipleSizes = count($produk['ukuran_list'] ?? []) > 1;
    ?>

    <article class="card-product shadow-sm" data-aos="fade-up"
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

            <!-- Product Image -->
            <div class="card-product-img">
                <div class="img-zoom-wrapper">
                    <a href="<?= base_url('home_toko/detail_produk/' . urlencode($produk['nama_produk'])) ?>" aria-label="Lihat detail <?= esc($produk['nama_produk']) ?>">
                        <img src="<?= base_url('fotoproduk/' . $produk['foto_produk']) ?>"
                             class="img-product"
                             alt="<?= esc($produk['nama_produk']) ?>"
                             loading="lazy"
                             data-zoom="1"
                             style="transform: scale(1);">
                    </a>
                    <div class="zoom-controls">
                        <button type="button" class="zoom-btn zoom-in" onclick="zoomImage(this, -1)" title="Kurangi ukuran">
                            <i class="fas fa-search-minus"></i>
                        </button>
                        <button type="button" class="zoom-btn zoom-out" onclick="zoomImage(this, 1)" title="Tambah ukuran">
                            <i class="fas fa-search-plus"></i>
                        </button>
                    </div>
                </div>

                <!-- Quick Actions Overlay -->
                <div class="product-quick-actions">
                    <div class="d-flex justify-content-center gap-2">
                        <?php if ($stok > 0): ?>
                            <?php if ($user_logged_in): ?>
                                <button type="button" class="btn btn-primary btn-sm rounded-pill px-3" title="Tambah ke Keranjang" onclick="window.location.href='<?= base_url('home_toko/detail_produk/' . urlencode($produk['nama_produk'])) ?>'">
                                    <i class="fas fa-shopping-cart"></i>
                                </button>
                                <button type="button" class="btn btn-outline-light btn-sm rounded-pill px-3"
                                        onclick="bukaChatProduk(event, '<?= esc(json_encode($produk['nama_produk'])) ?>', <?= (int) $produk['id_stok'] ?>, '<?= esc(json_encode($produk['foto_produk'] ?? '')) ?>')" title="Chat Penjual">
                                    <i class="fas fa-comments"></i>
                                </button>
                            <?php else: ?>
                                <a href="<?= base_url('home_toko/detail_produk/' . urlencode($produk['nama_produk'])) ?>" class="btn btn-light btn-sm rounded-pill px-3" title="Detail Produk">
                                    <i class="fas fa-info-circle"></i>
                                </a>
                            <?php endif; ?>
                        <?php else: ?>
                            <button type="button" class="btn btn-secondary btn-sm rounded-pill px-3" disabled title="Stok Habis">
                                <i class="fas fa-ban"></i>
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Product Info -->
            <div class="card-product-body p-3">
                <!-- Kategori -->
                <div class="card-product-meta mb-1">
                    <small class="text-muted"><i class="fas fa-tag me-1"></i><?= esc($produk['jenis_produk'] ?? 'Kategori') ?></small>
                </div>

                <!-- Product Name -->
                <h6 class="card-product-title mb-2">
                    <a href="<?= base_url('home_toko/detail_produk/' . urlencode($produk['nama_produk'])) ?>" class="text-dark text-decoration-none">
                        <?= esc($produk['nama_produk']) ?>
                    </a>
                </h6>

                <!-- Rating -->
                <div class="mb-2">
                    <div class="d-flex align-items-center gap-2 small text-warning">
                        <span><?= katalogRendaBintang($avgRating) ?></span>
                        <?php if ($totalReview > 0): ?>
                            <span class="text-muted"><?= number_format($avgRating, 1) ?> (<?= $totalReview ?> ulasan)</span>
                        <?php else: ?>
                            <span class="text-muted">Belum ada ulasan</span>
                        <?php endif; ?>
                        <?php if ($avgRating >= 4.5 && $totalReview > 0): ?>
                            <span class="badge bg-success rounded-pill"><i class="fa fa-thumbs-up"></i> Terbaik</span>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Stock Status -->
                <div class="mb-2">
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

                <!-- Price -->
                <div class="mt-auto">
                    <div class="d-flex align-items-center gap-2 flex-wrap mb-2">
                        <?php if ($isFlash): ?>
                            <span class="text-danger fw-bold fs-5">Rp<?= number_format($produk['harga_produk'], 0, ',', '.') ?></span>
                            <?php if (isset($produk['harga_normal'])): ?>
                                <span class="text-muted text-decoration-line-through small">Rp<?= number_format($produk['harga_normal'], 0, ',', '.') ?></span>
                                <span class="badge bg-danger rounded-pill"><?= $produk['diskon_persen'] ?? 0 ?>%</span>
                            <?php endif; ?>
                        <?php else: ?>
                            <span class="text-primary fw-bold fs-5">Rp<?= number_format($produk['harga_produk'], 0, ',', '.') ?></span>
                        <?php endif; ?>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex gap-2">
                        <?php if ($stok > 0): ?>
                            <?php if ($user_logged_in): ?>
                                <?= form_open_multipart('pelanggan_kelola_data/add_to_cart', ['class' => 'flex-grow-1', 'id' => 'product-form-' . $produk['id_stok']]) ?>
                                <input type="hidden" name="id_stok" value="<?= esc($produk['id_stok']) ?>">
                                <input type="hidden" name="nama_produk" value="<?= esc($produk['nama_produk']) ?>">
                                <input type="hidden" name="satuan_produk" value="<?= esc($produk['satuan_produk']) ?>">
                                <input type="hidden" name="harga_produk" value="<?= esc($produk['harga_produk']) ?>">
                                <input type="hidden" name="berat_produk" value="<?= esc($produk['berat_produk'] ?? 0) ?>">
                                <?php if ($hasMultipleSizes): ?>
                                    <div class="mb-2">
                                        <label for="ukuran_produk_<?= esc($produk['id_stok']) ?>" class="form-label small fw-medium">Ukuran:</label>
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
                                <div class="mb-2">
                                    <label for="jumlah_produk_<?= esc($produk['id_stok']) ?>" class="form-label small fw-medium">Qty:</label>
                                    <div class="input-group input-group-sm">
                                        <input type="number" id="jumlah_produk_<?= esc($produk['id_stok']) ?>" name="jumlah_produk"
                                               min="1" value="1" max="<?= $stok ?>" class="form-control text-center w-75">
                                    </div>
                                </div>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary flex-grow-1 rounded-pill py-2" title="Tambah ke Keranjang">
                                        <i class="fas fa-shopping-cart me-1"></i>Keranjang
                                    </button>
                                    <button type="submit" formaction="<?= base_url('pelanggan_kelola_data/beli_sekarang') ?>" class="btn btn-success rounded-pill py-2 px-3" title="Beli Sekarang">
                                        <i class="fas fa-bolt me-1"></i>Beli
                                    </button>
                                </div>
                                <?= form_close() ?>
                            <?php else: ?>
                                <div class="d-flex gap-2">
                                    <a href="<?= base_url('home_toko/login') ?>" class="btn btn-primary flex-grow-1 rounded-pill py-2" title="Login untuk beli">
                                        <i class="fas fa-sign-in-alt me-1"></i>Login
                                    </a>
                                </div>
                            <?php endif; ?>
                        <?php else: ?>
                            <button type="button" class="btn btn-secondary w-100 rounded-pill py-2" disabled>
                                <i class="fas fa-ban me-1"></i>Stok Habis
                            </button>
                         <?php endif; ?>
                    </div>
                </div>
            </div>
        </article>
<?php endforeach; ?>

<script>
    // Zoom image function
    function zoomImage(btn, delta) {
        const img = btn.closest('.card-product-img').querySelector('img');
        const currentZoom = parseFloat(img.getAttribute('data-zoom') || '1');
        const newZoom = Math.max(0.5, Math.min(3, currentZoom + (delta * 0.1)));
        img.setAttribute('data-zoom', newZoom);
        img.style.transform = `scale(${newZoom})`;
    }

    // Reset zoom when mouse leaves image container
    document.querySelectorAll('.card-product-img').forEach(container => {
        container.addEventListener('mouseleave', function() {
            const img = this.querySelector('img');
            img.style.transform = 'scale(1)';
            img.setAttribute('data-zoom', '1');
        });
    });

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
