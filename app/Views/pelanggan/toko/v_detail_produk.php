<?= view('layout_toko/v_navbar.php') ?>

<body>
    <main class="product-section">
        <div class="container my-5">
            <div class="produk d-flex flex-wrap align-items-center">
                <!-- Gambar Produk -->
                <div class="col-md-6 mb-4 mb-md-0">
                    <?php
                        $gallery = [];
                        if (!empty($produk['gallery_images'])) {
                            $gallery = json_decode($produk['gallery_images'], true) ?? [];
                        }
                        $allImages = array_merge([$produk['foto_produk']], $gallery);
                    ?>
                    <div class="product-image-container rounded overflow-hidden" style="height: 400px; position: relative;">
                        <img id="mainProductImage" src="<?= base_url('fotoproduk/' . $produk['foto_produk']) ?>" alt="Gambar Produk"
                            class="img-fluid w-100 h-100 object-fit-contain">
                        
                        <?php if (count($allImages) > 1): ?>
                        <div class="product-thumbnails d-flex gap-2 justify-content-center mt-2 flex-wrap">
                            <?php foreach ($allImages as $index => $img): ?>
                                <img src="<?= base_url('fotoproduk/' . $img) ?>" alt="Thumbnail <?= $index + 1 ?>"
                                    class="product-thumb <?= $index === 0 ? 'active' : '' ?>"
                                    data-src="<?= base_url('fotoproduk/' . $img) ?>"
                                    style="width: 70px; height: 70px; object-fit: cover; cursor: pointer; border: 2px solid transparent; border-radius: 8px;">
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                    </div>

                    <?php if (!empty($produk['video_url'])): ?>
                    <div class="mt-3">
                        <button class="btn btn-outline-primary w-100" onclick="playVideo('<?= esc($produk['video_url']) ?>')">
                            <i class="fas fa-play-circle me-2"></i>Putar Video Produk
                        </button>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($produk['size_guide_image'])): ?>
                    <div class="mt-3">
                        <button class="btn btn-outline-success w-100" data-bs-toggle="modal" data-bs-target="#sizeGuideModal">
                            <i class="fas fa-ruler-combined me-2"></i>Lihat Panduan Ukuran
                        </button>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Detail Produk -->
                <div class="col-md-6">
                    <h3><strong><?= $produk['nama_produk']; ?></strong></h3>
                    <p class="text-muted">Kategori: <?= $produk['jenis_produk']; ?></p>

                    <!-- Ringkasan Rating -->
                    <div id="ringkasan_rating" style="margin-bottom:8px;">
                        <span id="rata_bintang"></span>
                        <span id="jumlah_ulasan_header"></span>
                    </div>

                    <p class="detail"><strong>Stok: </strong> <strong>Stock :</strong>
                        <span id="stok-<?= esc($produk['id_stok']) ?>">
                            <?= esc($produk['jumlah_stok_produk']) ?>
                        </span>
                        <?= esc($produk['satuan_produk']) ?>
                    </p>
                    <p class="detail"><strong>Harga: </strong>Rp.<?= number_format($produk['harga_produk'], 0, ',', '.') ?></p>
                    <p class="detail"><strong>Deskripsi: </strong><?= $produk['deskripsi_produk']; ?></p>

                    <!-- Form keranjang -->
                    <?php if ($user_logged_in): ?>
                        <?= form_open_multipart('pelanggan_kelola_data/add_to_cart'); ?>
                        <div class="form-detail-cart-wrapper d-flex flex-wrap gap-2 align-items-center">
                            <input type="hidden" name="id_stok" value="<?= $produk['id_stok']; ?>">
                            <input type="hidden" name="nama_produk" value="<?= $produk['nama_produk'] ?>">
                            <input type="hidden" name="satuan_produk" value="<?= $produk['satuan_produk']; ?>">
                            <input type="hidden" name="harga_produk" value="<?= $produk['harga_produk'] ?>">
                            <input type="hidden" name="berat_produk" value="<?= $produk['berat_produk'] ?>">

                            <select id="ukuran_produk" name="ukuran_produk" style="width: auto" class="form-control profile" required>
                                <option value="">--Pilih Ukuran--</option>
                                <?php foreach ($produk['ukuran_list'] as $ukuran): ?>
                                    <option value=" <?= esc($ukuran) ?>"><?= esc($ukuran) ?></option>
                                <?php endforeach; ?>
                            </select>

                            <input type="number" name="jumlah_produk" min="1" value="1" max="<?= $produk['total_stok']; ?>" style="width: auto" class="form-control profile">
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-shopping-cart"></i>&nbsp;Keranjang
                            </button>
                        </div>
                        <?= form_close(); ?>
                    <?php else: ?>
                        <div class="form-detail-cart-wrapper d-flex flex-wrap gap-2 align-items-center">
                            <?php if (isset($produk['ukuran_list'])): ?>
                                <select name="ukuran_produk" id="ukuran_produk" style="width: auto;" class="form-control profile" disabled>
                                    <option>--Pilih Ukuran--</option>
                                    <?php foreach ($produk['ukuran_list'] as $ukuran): ?>
                                        <option value="<?= esc($ukuran) ?>"><?= esc($ukuran) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            <?php endif; ?>
                            <input type="number" min="1" value="1" max="<?= $produk['total_stok']; ?>" style="width: 100px;" class="form-control profile" disabled>
                            <button type="button" class="btn btn-danger" disabled>
                                <i class="fas fa-shopping-cart"></i>&nbsp;Keranjang
                            </button>
                        </div>
                    <?php endif; ?>
                    <?php if ($user_logged_in): ?>
                        <div class="mt-3">
                            <a href="#" onclick="bukaChatProduk(event, <?= esc(json_encode($produk['nama_produk']), 'attr') ?>, <?= (int)$produk['id_stok'] ?>)" class="btn btn-outline-primary">
                                <i class="fas fa-comments"></i>&nbsp;Chat Penjual
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <?php if (!empty($produk['size_guide_image'])): ?>
            <div class="modal fade" id="sizeGuideModal" tabindex="-1" aria-labelledby="sizeGuideModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="sizeGuideModalLabel"><i class="fas fa-ruler-combined me-2"></i>Panduan Ukuran - <?= esc($produk['nama_produk']) ?></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-center p-0">
                            <img src="<?= base_url('fotoproduk/' . $produk['size_guide_image']) ?>" alt="Size Guide" class="img-fluid w-100" style="max-height: 70vh; object-fit: contain;">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Video Modal -->
            <div class="modal fade" id="videoModal" tabindex="-1" aria-labelledby="videoModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="videoModalLabel"><i class="fas fa-play-circle me-2"></i>Video Produk</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-0" id="videoModalBody">
                            <!-- Video will be inserted here -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- ===== Ulasan Pembeli ===== -->
            <div class="row mt-5" id="review_list">
                <div class="col-12">
                    <hr>
                    <h4 class="fw-bold"><i class="fa fa-star text-warning me-1"></i>Ulasan Pembeli</h4>

                    <!-- Ringkasan Rating -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body p-4">
                            <div class="row align-items-center g-4">
                                <div class="col-md-3 text-center border-end-md">
                                    <div id="review_avg" class="fw-bold" style="font-size:2.6rem;color:#f39c12;line-height:1;">0<span class="fs-5 text-muted">/5</span></div>
                                    <div id="ringkasan_stars" class="text-warning my-1"></div>
                                    <div id="jumlah_ulasan" class="text-muted small">Belum ada ulasan</div>
                                    <div id="review_recom" class="badge bg-success-subtle text-success-emphasis rounded-pill mt-2 d-none"></div>
                                </div>
                                <div class="col-md-9" id="rating_bars"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Filter Bintang -->
                    <div class="btn-group mb-3 flex-wrap" id="filter_rating">
                        <button type="button" class="btn btn-sm btn-outline-warning active" data-rating="0">Semua</button>
                        <button type="button" class="btn btn-sm btn-outline-warning" data-rating="5">5★</button>
                        <button type="button" class="btn btn-sm btn-outline-warning" data-rating="4">4★</button>
                        <button type="button" class="btn btn-sm btn-outline-warning" data-rating="3">3★</button>
                        <button type="button" class="btn btn-sm btn-outline-warning" data-rating="2">2★</button>
                        <button type="button" class="btn btn-sm btn-outline-warning" data-rating="1">1★</button>
                    </div>

                    <div id="review_items">
                        <p class="text-muted"><i class="fa fa-spinner fa-spin"></i> Memuat ulasan...</p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
    const namaProduk = <?= json_encode($produk['nama_produk']) ?>;

    function renderBintang(rating) {
        let s = '';
        const val = Math.round(rating * 2) / 2;
        for (let i = 1; i <= 5; i++) {
            if (val >= i) {
                s += '<i class="fas fa-star"></i>';
            } else if (val === i - 0.5) {
                s += '<i class="fas fa-star-half-alt"></i>';
            } else {
                s += '<i class="far fa-star text-muted"></i>';
            }
        }
        return s;
    }

    function renderAvatar(nama) {
        const parts = (nama || 'P').trim().split(/\s+/).filter(Boolean);
        const inisial = parts.map(w => w[0]).slice(0, 2).join('').toUpperCase();
        return '<div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold me-3 flex-shrink-0" style="width:46px;height:46px;font-size:16px;">'
            + escHtml(inisial) + '</div>';
    }

    function formatTanggal(waktu) {
        try {
            const tgl = String(waktu).replace(' ', 'T');
            return new Date(tgl).toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' });
        } catch (e) {
            return '';
        }
    }

    function escHtml(str) {
        const div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    }

    function renderBars(summary, total) {
        const wrap = document.getElementById('rating_bars');
        let html = '';
        for (let i = 5; i >= 1; i--) {
            const c = parseInt(summary.per_rating[i]) || 0;
            const pct = total > 0 ? Math.round((c / total) * 100) : 0;
            html += '<div class="d-flex align-items-center mb-1 bar-row" data-rating="' + i + '" style="cursor:pointer;" title="Tampilkan ulasan bintang ' + i + '">'
                + '<span class="me-2 small fw-bold text-muted" style="min-width:14px;">' + i + '</span>'
                + '<div class="progress flex-grow-1" style="height:10px;background:#eee;">'
                + '<div class="progress-bar" id="bar_' + i + '" role="progressbar" style="width:' + pct + '%;background:linear-gradient(90deg,#f6d365,#fda085);"></div>'
                + '</div>'
                + '<span class="ms-2 small text-muted" style="min-width:38px;text-align:right;">' + c + ' (' + pct + '%)</span>'
                + '</div>';
        }
        wrap.innerHTML = html;
    }

    function renderSummary(summary) {
        const total = parseInt(summary.total) || 0;
        const avg = parseFloat(summary.rata_rata) || 0;

        document.getElementById('review_avg').innerHTML = avg.toFixed(1) + '<span class="fs-5 text-muted">/5</span>';
        document.getElementById('ringkasan_stars').innerHTML = total > 0 ? renderBintang(avg) : '<span class="text-muted small">Belum ada ulasan</span>';
        document.getElementById('jumlah_ulasan').textContent = total > 0 ? total + ' ulasan pembeli' : 'Belum ada ulasan pembeli';

        document.getElementById('rata_bintang').innerHTML = total > 0
            ? renderBintang(avg) + ' <strong>' + avg.toFixed(1) + '</strong>'
            : '<span class="text-muted"><i class="far fa-star"></i> Belum ada ulasan</span>';
        document.getElementById('jumlah_ulasan_header').textContent = total > 0 ? ' (' + total + ' ulasan)' : '';

        const recom = document.getElementById('review_recom');
        const c5 = parseInt(summary.per_rating[5]) || 0;
        const c4 = parseInt(summary.per_rating[4]) || 0;
        if (total > 0) {
            const pct = Math.round(((c5 + c4) / total) * 100);
            recom.textContent = pct + '% pembeli merekomendasikan produk ini';
            recom.classList.remove('d-none');
        } else {
            recom.classList.add('d-none');
        }

        renderBars(summary, total);
    }

    function muatReview(rating) {
        const url = '<?= base_url('pelanggan_kelola_data/review_get') ?>/' + encodeURIComponent(namaProduk) + (rating ? '?rating=' + rating : '');
        fetch(url)
            .then(r => r.json())
            .then(data => {
                const summary = data.summary || {};
                const reviews = data.reviews || [];
                renderSummary(summary);

                let html = '';
                if (reviews.length === 0) {
                    html = '<div class="alert alert-light border text-center text-muted py-4"><i class="far fa-star fa-2x d-block mb-2"></i>Belum ada ulasan untuk filter ini.</div>';
                } else {
                    reviews.forEach(r => {
                        const nama = r.nama_pelanggan || 'Pembeli';
                        const rating = parseInt(r.rating) || 0;
                        const ulasan = r.ulasan || '';
                        html += '<div class="card border-0 shadow-sm mb-2">'
                            + '<div class="card-body d-flex">'
                            + renderAvatar(nama)
                            + '<div class="flex-grow-1">'
                            + '<div class="d-flex justify-content-between flex-wrap gap-1">'
                            + '<span class="fw-bold">' + escHtml(nama) + '</span>'
                            + '<small class="text-secondary">' + formatTanggal(r.waktu_review) + '</small>'
                            + '</div>'
                            + '<div class="text-warning mt-1">' + renderBintang(rating) + '<span class="ms-1 text-dark small fw-bold">' + rating.toFixed(1) + '</span></div>'
                            + (ulasan ? '<p class="mb-0 mt-1">' + escHtml(ulasan) + '</p>' : '<p class="mb-0 mt-1 fst-italic text-muted">Pembeli tidak menulis ulasan.</p>')
                            + '</div></div></div>';
                    });
                }
                document.getElementById('review_items').innerHTML = html;
            })
            .catch(function() {
                document.getElementById('review_items').innerHTML = '<div class="alert alert-danger">Gagal memuat ulasan.</div>';
            });
    }

    document.addEventListener('DOMContentLoaded', function() {
        muatReview(0);

        document.querySelectorAll('#filter_rating .btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('#filter_rating .btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                muatReview(parseInt(this.dataset.rating));
            });
        });

        // Klik bar rating untuk memfilter review sesuai bintang
        document.getElementById('rating_bars').addEventListener('click', function(e) {
            const row = e.target.closest('.bar-row');
            if (!row) return;
            const rating = parseInt(row.dataset.rating);
            document.querySelectorAll('#filter_rating .btn').forEach(b => {
                b.classList.toggle('active', parseInt(b.dataset.rating) === rating);
            });
            muatReview(rating);
        });
    });
    </script>

<?= view('layout_toko/v_footer.php') ?>
