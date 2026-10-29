<?= view('layout_toko/v_navbar.php') ?>

<!-- Products Section -->
<main class="catalog-section">
    <?= view('layout_toko/v_filter_sidebar.php', ['is_desktop' => true]) ?>

    <div class="flex-grow-1">
        <div class="catalog-header d-flex flex-wrap justify-content-between align-items-end gap-2 mb-4">
            <div>
                <h2 class="catalog-title mb-1">Katalog Produk</h2>
                <p class="catalog-subtitle text-muted mb-0">
                    <?= count($produk_data ?? []) ?> produk tersedia
                    <?php if (!empty($filter_params['keyword'] ?? '')): ?>
                        · hasil pencarian &ldquo;<?= esc($filter_params['keyword']) ?>&rdquo;
                    <?php endif; ?>
                </p>
            </div>
            <div class="catalog-toolbar">
                <button type="button" class="btn btn-outline-primary btn-sm d-lg-none" onclick="document.getElementById('filterSidebar').scrollIntoView({behavior:'smooth',block:'start'})">
                    <i class="fas fa-filter me-1"></i>Filter
                </button>
            </div>
        </div>

        <div class="product-grid">
            <?= view('layout_toko/v_produk_grid.php', ['produk_data' => $produk_data ?? []]) ?>
        </div>
    </div>
</main>

<?= view('layout_toko/v_footer.php') ?>
