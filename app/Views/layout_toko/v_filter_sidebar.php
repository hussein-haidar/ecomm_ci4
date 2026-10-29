<?php
// Filter sidebar untuk halaman katalog
$filter_params = $filter_params ?? [
    'keyword' => '',
    'harga_min' => '',
    'harga_max' => '',
    'sort_harga' => '',
    'sort_nama' => '',
    'jenis_produk' => '',
    'rating_min' => '',
    'sort_by' => '',
];
$jenis_produk_dropdown = $jenis_produk_dropdown ?? [];
$is_desktop = $is_desktop ?? false;

// Filter sidebar ini berada di dalam .catalog-section (grid CSS).
// Hapus col-* agar layout grid tidak tercampur dengan flex-column parent.
$wrapperClass = $is_desktop ? '' : 'col-12';
?>

<aside class="<?= $wrapperClass ?>" id="filterSidebarWrapper" aria-label="Filter Produk">
    <div class="filter-sidebar <?= $is_desktop ? '' : 'mobile-filter' ?>" id="filterSidebar">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0"><i class="fas fa-filter me-2"></i> Filter Produk</h5>
            <button type="button" class="btn btn-sm btn-outline-secondary" id="closeFilterSidebar" aria-label="Tutup filter">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form id="product-filter-form" method="GET" action="<?= base_url('home_toko/katalog') ?>">
            <!-- Cari Produk -->
            <div class="mb-3">
                <label for="filterKeyword" class="form-label">Cari Produk</label>
                <input type="text" name="keyword" id="filterKeyword" class="form-control" placeholder="Cari..." value="<?= esc($filter_params['keyword'] ?? '') ?>">
            </div>

            <!-- Kategori -->
            <div class="mb-3">
                <label for="filterJenisProduk" class="form-label">Kategori</label>
                <select name="jenis_produk" id="filterJenisProduk" class="form-select">
                    <option value="">Semua Kategori</option>
                    <?php if (!empty($jenis_produk_dropdown)): ?>
                        <?php foreach ($jenis_produk_dropdown as $jenis): ?>
                            <option value="<?= esc($jenis['jenis_produk']) ?>" <?= (isset($filter_params['jenis_produk']) && $filter_params['jenis_produk'] == $jenis['jenis_produk']) ? 'selected' : '' ?>>
                                <?= esc($jenis['jenis_produk']) ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>

            <!-- Rentang Harga -->
            <div class="mb-3">
                <label class="form-label">Rentang Harga</label>
                <div class="input-group">
                    <span class="input-group-text">Rp</span>
                    <input type="number" name="harga_min" class="form-control" placeholder="Min" value="<?= esc($filter_params['harga_min'] ?? '') ?>">
                    <span class="input-group-text">–</span>
                    <span class="input-group-text">Rp</span>
                    <input type="number" name="harga_max" class="form-control" placeholder="Max" value="<?= esc($filter_params['harga_max'] ?? '') ?>">
                </div>
            </div>

            <!-- Rating / Ulasan -->
            <div class="mb-4">
                <label class="form-label">Rating / Ulasan</label>
                <div class="d-flex flex-wrap align-items-center gap-2">
                    <?php for ($i = 5; $i >= 1; $i--): ?>
                        <button type="button" class="star-btn <?= (isset($filter_params['rating_min']) && $filter_params['rating_min'] == $i) ? 'active' : '' ?>"
                                data-rating="<?= $i ?>"
                                onclick="setRatingFilter(this, <?= $i ?>)"
                                aria-label="<?= $i ?> bintang">
                            <i class="fas fa-star"></i>
                        </button>
                    <?php endfor; ?>
                    <input type="hidden" name="rating_min" id="rating_min" value="<?= esc($filter_params['rating_min'] ?? '') ?>">
                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="clearRatingFilter()">Hapus</button>
                </div>
                <small class="text-muted d-block mt-1">Klik bintang untuk set rating minimum (1-5)</small>
            </div>

            <!-- Urutkan -->
            <div class="mb-3">
                <label for="filterSortBy" class="form-label">Urutkan</label>
                <select name="sort_by" id="filterSortBy" class="form-select">
                    <option value="" <?= empty($filter_params['sort_by'] ?? '') && empty($filter_params['sort_harga'] ?? '') && empty($filter_params['sort_nama'] ?? '') ? 'selected' : '' ?>>Default</option>
                    <option value="asc" <?= ($filter_params['sort_by'] ?? '') == 'asc' ? 'selected' : '' ?>>Harga Terendah</option>
                    <option value="desc" <?= ($filter_params['sort_by'] ?? '') == 'desc' ? 'selected' : '' ?>>Harga Tertinggi</option>
                    <option value="a-z" <?= ($filter_params['sort_by'] ?? '') == 'a-z' ? 'selected' : '' ?>>Nama A-Z</option>
                    <option value="z-a" <?= ($filter_params['sort_by'] ?? '') == 'z-a' ? 'selected' : '' ?>>Nama Z-A</option>
                </select>
            </div>

            <!-- Aksi -->
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-fill"><i class="fas fa-check me-1"></i> Terapkan</button>
                <a href="<?= base_url('home_toko/katalog') ?>" class="btn btn-outline-secondary flex-fill text-center">Reset</a>
            </div>
        </form>
    </div>
</aside>
