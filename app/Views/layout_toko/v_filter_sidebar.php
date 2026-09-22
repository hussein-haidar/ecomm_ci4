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
?>

<aside class="filter-sidebar <?= $is_desktop ? 'filter-sidebar-desktop' : '' ?>" id="filterSidebar" aria-label="Filter Produk">
    <div class="filter-sidebar-header">
        <h5 class="filter-sidebar-title"><i class="fas fa-filter"></i> Filter Produk</h5>
        <button type="button" class="filter-sidebar-close" id="closeFilterSidebar" aria-label="Tutup filter">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <form id="product-filter-form" method="GET" action="<?= base_url('home_toko/katalog') ?>">
        <!-- Cari Produk -->
        <div class="filter-group">
            <label for="keyword" class="filter-label">Cari Produk</label>
            <input type="text" name="keyword" id="keyword" class="filter-control" placeholder="Cari..." value="<?= esc($filter_params['keyword'] ?? '') ?>">
        </div>

        <!-- Kategori -->
        <div class="filter-group">
            <label for="jenis_produk" class="filter-label">Kategori</label>
            <select name="jenis_produk" id="jenis_produk" class="filter-control">
                <option value="">Semua Kategori</option>
                <?php foreach ($jenis_produk_dropdown as $jenis): ?>
                    <option value="<?= esc($jenis['jenis_produk']) ?>" <?= (isset($filter_params['jenis_produk']) && $filter_params['jenis_produk'] == $jenis['jenis_produk']) ? 'selected' : '' ?>>
                        <?= esc($jenis['jenis_produk']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Rentang Harga -->
        <div class="filter-group">
            <span class="filter-label">Rentang Harga</span>
            <div class="filter-price-row">
                <div class="filter-price-field">
                    <span class="filter-price-code">Rp</span>
                    <input type="number" name="harga_min" class="filter-price-input" placeholder="Min" value="<?= esc($filter_params['harga_min'] ?? '') ?>">
                </div>
                <span class="filter-price-sep">–</span>
                <div class="filter-price-field">
                    <span class="filter-price-code">Rp</span>
                    <input type="number" name="harga_max" class="filter-price-input" placeholder="Max" value="<?= esc($filter_params['harga_max'] ?? '') ?>">
                </div>
            </div>
        </div>

        <!-- Rating / Ulasan -->
        <div class="filter-group">
            <span class="filter-label">Rating / Ulasan</span>
            <div class="star-rating-filter">
                <?php for ($i = 5; $i >= 1; $i--): ?>
                    <button type="button" class="star-btn <?= (isset($filter_params['rating_min']) && $filter_params['rating_min'] == $i) ? 'active' : '' ?>"
                            data-rating="<?= $i ?>"
                            onclick="setRatingFilter(this, <?= $i ?>)"
                            aria-label="<?= $i ?> bintang">
                        <i class="fas fa-star"></i>
                    </button>
                <?php endfor; ?>
                <input type="hidden" name="rating_min" id="rating_min" value="<?= esc($filter_params['rating_min'] ?? '') ?>">
                <button type="button" class="filter-btn-clear" onclick="clearRatingFilter()">Hapus</button>
            </div>
            <small class="filter-hint">Klik bintang untuk set rating minimum (1-5)</small>
        </div>

        <!-- Urutkan -->
        <div class="filter-group">
            <label for="sort_by" class="filter-label">Urutkan</label>
            <select name="sort_by" id="sort_by" class="filter-control">
                <option value="">Default</option>
                <option value="asc" <?= ($filter_params['sort_by'] ?? '') == 'asc' ? 'selected' : '' ?>>Harga Terendah</option>
                <option value="desc" <?= ($filter_params['sort_by'] ?? '') == 'desc' ? 'selected' : '' ?>>Harga Tertinggi</option>
                <option value="a-z" <?= ($filter_params['sort_by'] ?? '') == 'a-z' ? 'selected' : '' ?>>Nama A-Z</option>
                <option value="z-a" <?= ($filter_params['sort_by'] ?? '') == 'z-a' ? 'selected' : '' ?>>Nama Z-A</option>
            </select>
        </div>

        <!-- Aksi -->
        <div class="filter-actions">
            <button type="submit" class="filter-btn filter-btn-primary"><i class="fas fa-check"></i> Terapkan</button>
            <a href="<?= base_url('home_toko/katalog') ?>" class="filter-btn filter-btn-secondary"><i class="fas fa-redo"></i> Reset</a>
        </div>
    </form>
</aside>