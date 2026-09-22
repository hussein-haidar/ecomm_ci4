<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= $title2 ?? 'Toko' ?></title>
    <meta name="csrf-token" content="<?= csrf_token() ?>">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>/template_admin/dist/css/AdminLTE.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('themes/' . ($tokoData['tema_website'] ?? 'default') . '/css/toko.css?v=20260922b') ?>">
    <link href="<?= base_url() ?>/icon/gudang.ico" rel="shortcut icon">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    <?= view('layout_toko/v_csrf_script') ?>
</head>

<?php
$jumlahBarang = 0;
$jumlahBayar = 0;
$nama_pelanggan = session()->get('nama_pelanggan');
$nama_lengkap = session()->get('nama_lengkap');
$sesiUserToko = session()->get('toko_sesi_user');
$is_pelanggan = !empty($nama_pelanggan);
$user_logged_in = session()->get('user_logged_in') === true || $is_pelanggan || !empty($nama_lengkap);
$level_user = (int) session()->get('level');

$nama_tampil = '';
$avatar_src = '';
$avatar_alt = '';
if ($is_pelanggan) {
    $nama_tampil = $nama_pelanggan;
    $avatar_alt = $nama_pelanggan;
    $foto = session()->get('foto_pelanggan');
    if (!empty($foto) && file_exists(FCPATH . 'fotopelanggan/' . $foto)) {
        $avatar_src = base_url('fotopelanggan/' . $foto);
    }
} elseif (!empty($nama_lengkap)) {
    $nama_tampil = $nama_lengkap;
    $avatar_alt = $nama_lengkap;
    $foto = session()->get('foto_user');
    if (!empty($foto) && file_exists(FCPATH . 'fotouser/' . $foto)) {
        $avatar_src = base_url('fotouser/' . $foto);
    }
}
$ket_user = '';
if ($user_logged_in) {
    $ket_user = $level_user == 1 ? 'Pemilik' : ($level_user == 2 ? 'Admin' : 'Pelanggan');
}

// Data toko untuk branding
$tokoData = get_data_toko();
$namaToko = $tokoData['nama_toko'] ?? 'Toko Online';
$logoToko = !empty($tokoData['logo_website']) 
    ? base_url('logowebsite/' . $tokoData['logo_website'])
    : base_url('fotodefault/logofaaro.png');

// Ambil jenis_produk_dropdown
$jenis_produk_dropdown = [];
if (!empty($sesiUserToko)) {
    $produkModel = new \App\Models\M_home_toko();
    $jenis_produk_dropdown = $produkModel->getJenisProdukDropdown($sesiUserToko);
}

if (!empty($nama_pelanggan)) {
    $keranjangModel = new \App\Models\M_pelanggan_keranjang();
    $keranjang = $keranjangModel->get_keranjang($sesiUserToko);
    $jumlahBarang = count($keranjang);

    $pembayaranModel = new \App\Models\M_pelanggan_bayar();
    $status = $pembayaranModel->get_bayar_by_status($sesiUserToko);
    $jumlahBayar = count($status);

    $chatModel = new \App\Models\M_chat();
    $unreadChat = $chatModel->count_unread_pelanggan($sesiUserToko, $nama_pelanggan);
}
?>

<header class="header-main">
    <div class="header-container">
        <!-- Branding / Logo -->
        <div class="header-brand">
            <a href="<?= base_url('home_toko/index') ?>" class="brand-link" aria-label="Beranda <?= esc($namaToko) ?>">
                <img src="<?= $logoToko ?>" alt="<?= esc($namaToko) ?>" class="brand-logo" onerror="this.src='<?= base_url('fotodefault/logofaaro.png') ?>'">
                <span class="brand-name"><?= esc($namaToko) ?></span>
            </a>
        </div>

        <!-- Search Bar (Desktop only) -->
        <div class="header-search d-none d-lg-block" id="headerSearch">
            <form method="GET" action="<?= base_url('home_toko/katalog') ?>" class="search-form">
                <label for="searchKeyword" class="sr-only">Cari produk</label>
                <input type="text" id="searchKeyword" name="keyword" class="search-input" placeholder="Cari produk..." value="<?= esc($_GET['keyword'] ?? '') ?>">
                <button type="submit" class="btn-search" aria-label="Cari">
                    <i class="fas fa-search"></i>
                </button>
            </form>
        </div>

        <!-- Mobile Search Toggle -->
        <button class="btn-search-toggle" id="btnSearchToggle" aria-label="Buka pencarian" aria-expanded="false">
            <i class="fas fa-search"></i>
        </button>

        <?php if ($user_logged_in): ?>
        <!-- Mobile User Chip -->
        <button class="btn-user-chip d-md-none" id="btnUserChip" aria-label="Menu pengguna" aria-expanded="false" onclick="toggleDrawer()">
            <?php if (!empty($avatar_src)): ?>
                <img src="<?= $avatar_src ?>" alt="<?= esc($avatar_alt) ?>" class="user-chip-img">
            <?php else: ?>
                <i class="fas fa-user"></i>
            <?php endif; ?>
        </button>
        <?php endif; ?>

        <!-- Hamburger Menu -->
        <button class="btn-menu-toggle" id="btnMenuToggle" aria-label="Buka menu" aria-expanded="false" aria-controls="mainNav">
            <span class="hamburger"></span>
        </button>

        <!-- User Actions (Desktop) -->
        <div class="header-actions d-none d-md-flex" id="headerActions">
            <?php if (!$user_logged_in): ?>
                <a href="<?= base_url('auth/pilih_toko') ?>" class="action-btn" title="Pilih Toko">
                    <i class="fas fa-store"></i> <span>Pilih Toko</span>
                </a>
                <a href="<?= base_url('auth/buka_toko') ?>" class="action-btn" title="Buka Toko Baru">
                    <i class="fas fa-store-alt"></i> <span>Buka Toko</span>
                </a>
                <a href="<?= base_url('home_toko/login') ?>" class="action-btn primary" title="Masuk">
                    <i class="fas fa-sign-in-alt"></i> <span>Masuk</span>
                </a>
            <?php elseif ($is_pelanggan): ?>
                <a href="<?= base_url('pelanggan_kelola_data/keranjang') ?>" class="action-btn cart-btn" title="Keranjang">
                    <i class="fas fa-shopping-cart"></i> <span>Keranjang</span>
                    <?php if ($jumlahBarang > 0): ?><span class="badge-count"><?= $jumlahBarang ?></span><?php endif; ?>
                </a>
                <a href="<?= base_url('pelanggan_kelola_data/nunggu_bayar') ?>" class="action-btn" title="Pesanan">
                    <i class="fas fa-box"></i> <span>Pesanan</span>
                    <?php if ($jumlahBayar > 0): ?><span class="badge-count"><?= $jumlahBayar ?></span><?php endif; ?>
                </a>
                <a href="#" class="action-btn" onclick="bukaChatPopUp(event)" title="Chat">
                    <i class="fas fa-comments"></i>
                    <span id="navbar-chat-count" class="badge-count<?= (isset($unreadChat) && $unreadChat > 0) ? '' : ' d-none' ?>"><?= $unreadChat ?? 0 ?></span>
                </a>
                <div class="user-menu">
                    <button class="user-btn" id="userMenuBtn" aria-label="Menu pengguna" aria-expanded="false">
                        <?php if (!empty($avatar_src)): ?>
                            <img src="<?= $avatar_src ?>" alt="<?= esc($avatar_alt) ?>" class="user-avatar-img">
                        <?php else: ?>
                            <i class="fas fa-user"></i>
                        <?php endif; ?>
                        <span><?= esc($nama_tampil) ?></span> <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="user-dropdown" id="userDropdown" role="menu">
                        <a href="<?= base_url('pelanggan_kelola_data/profil') ?>" class="dropdown-item" role="menuitem">
                            <i class="fas fa-user-circle"></i> Profil
                        </a>
                        <a href="<?= base_url('pelanggan_kelola_data/riwayat_pesanan') ?>" class="dropdown-item" role="menuitem">
                            <i class="fas fa-history"></i> Riwayat Pesanan
                        </a>
                        <a href="#" class="dropdown-item text-danger" onclick="logoutPelanggan(event)" role="menuitem">
                            <i class="fas fa-sign-out-alt" id="logout-icon"></i> Keluar
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <div class="user-menu">
                    <button class="user-btn" id="userMenuBtn" aria-label="Menu pengguna" aria-expanded="false">
                        <?php if (!empty($avatar_src)): ?>
                            <img src="<?= $avatar_src ?>" alt="<?= esc($avatar_alt) ?>" class="user-avatar-img">
                        <?php else: ?>
                            <i class="fas fa-user"></i>
                        <?php endif; ?>
                        <span><?= esc($nama_tampil) ?></span> <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="user-dropdown" id="userDropdown" role="menu">
                        <a href="<?= base_url($level_user == 1 ? 'home_pemilik' : 'home_admin') ?>" class="dropdown-item" role="menuitem">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                        <a href="<?= base_url('auth/logout_user') ?>" class="dropdown-item text-danger" role="menuitem">
                            <i class="fas fa-sign-out-alt"></i> Keluar
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Desktop Navigation Menu -->
    <nav class="header-nav" aria-label="Menu utama">
        <ul class="header-nav-list">
            <li class="header-nav-item">
                <a href="<?= base_url('home_toko/index') ?>" class="header-nav-link">
                    <i class="fas fa-home"></i> Beranda
                </a>
            </li>
            <li class="header-nav-item">
                <a href="<?= base_url('home_toko/katalog') ?>" class="header-nav-link">
                    <i class="fas fa-th-large"></i> Katalog Produk
                </a>
            </li>
            <li class="header-nav-item header-nav-dropdown">
                <button type="button" class="header-nav-link header-nav-toggle" aria-expanded="false" aria-label="Jenis Produk">
                    <i class="fas fa-tags"></i> Jenis Produk <i class="fas fa-chevron-down header-nav-caret"></i>
                </button>
                <ul class="header-nav-submenu" role="menu">
                    <?php foreach ($jenis_produk_dropdown as $jenis): ?>
                        <li>
                            <a href="<?= base_url('home_toko/jenis_produk/' . urlencode($jenis['jenis_produk'])) ?>" class="header-nav-sublink" role="menuitem">
                                <?= esc($jenis['jenis_produk']) ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                    <?php if (empty($jenis_produk_dropdown)): ?>
                        <li class="header-nav-empty">Belum ada kategori</li>
                    <?php endif; ?>
                </ul>
            </li>
        </ul>
    </nav>

    <!-- Mobile Drawer -->
    <nav id="mainNav" class="nav-drawer" role="navigation" aria-label="Menu utama">
        <div class="drawer-header">
            <div class="drawer-brand">
                <img src="<?= $logoToko ?>" alt="<?= esc($namaToko) ?>" class="brand-logo" onerror="this.src='<?= base_url('fotodefault/logofaaro.png') ?>'">
                <span class="brand-name"><?= esc($namaToko) ?></span>
            </div>
            <button class="btn-close-drawer" id="btnCloseDrawer" aria-label="Tutup menu">&times;</button>
        </div>

        <div class="drawer-content">
            <!-- Section: Navigasi Utama -->
            <div class="drawer-section">
                <h3 class="drawer-section-title">Navigasi</h3>
                <ul class="drawer-menu">
                    <li>
                        <a href="<?= base_url('home_toko/index') ?>" class="drawer-link">
                            <i class="fas fa-home"></i> <span>Beranda</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('home_toko/katalog') ?>" class="drawer-link">
                            <i class="fas fa-th-large"></i> <span>Katalog Produk</span>
                        </a>
                    </li>
                    <li class="drawer-dropdown">
                        <button class="drawer-link drawer-toggle" aria-expanded="false" aria-label="Jenis Produk">
                            <i class="fas fa-tags"></i> <span>Jenis Produk</span> <i class="fas fa-chevron-down"></i>
                        </button>
                        <ul class="drawer-submenu" role="menu">
                            <?php foreach ($jenis_produk_dropdown as $jenis): ?>
                                <li>
                                    <a href="<?= base_url('home_toko/jenis_produk/' . urlencode($jenis['jenis_produk'])) ?>" class="drawer-sublink" role="menuitem">
                                        <?= esc($jenis['jenis_produk']) ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                            <?php if (empty($jenis_produk_dropdown)): ?>
                                <li class="drawer-empty">Belum ada kategori</li>
                            <?php endif; ?>
                        </ul>
                    </li>
                </ul>
            </div>

            <!-- Section: Filter & Tools -->
            <div class="drawer-section">
                <h3 class="drawer-section-title">Filter & Pencarian</h3>
                <ul class="drawer-menu">
                    <li>
                        <a href="#" class="drawer-link" data-bs-toggle="modal" data-bs-target="#filterModal">
                            <i class="fas fa-filter"></i> <span>Filter Produk</span>
                        </a>
                    </li>
                </ul>
            </div>

            <?php if (!$user_logged_in): ?>
                <!-- Section: Guest -->
                <div class="drawer-section">
                    <h3 class="drawer-section-title">Masuk / Daftar</h3>
                    <ul class="drawer-menu">
                        <li>
                            <a href="<?= base_url('home_toko/login') ?>" class="drawer-link primary">
                                <i class="fas fa-sign-in-alt"></i> <span>Login</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?= base_url('auth/buka_toko') ?>" class="drawer-link">
                                <i class="fas fa-store-alt"></i> <span>Buka Toko</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?= base_url('auth/register_pelanggan') ?>" class="drawer-link">
                                <i class="fas fa-user-plus"></i> <span>Daftar</span>
                            </a>
                        </li>
                    </ul>
                </div>
            <?php else: ?>
                <!-- Section: User Account -->
                <div class="drawer-section">
                    <h3 class="drawer-section-title">Akun Saya</h3>
                    <div class="drawer-user-info">
                        <div class="user-avatar">
                            <?php if (!empty($avatar_src)): ?>
                                <img src="<?= $avatar_src ?>" alt="<?= esc($avatar_alt) ?>" class="user-avatar-img">
                            <?php else: ?>
                                <i class="fas fa-user-circle"></i>
                            <?php endif; ?>
                        </div>
                        <div class="user-detail">
                            <strong><?= esc($nama_tampil) ?></strong>
                            <small><?= esc($is_pelanggan ? (session()->get('email') ?? '') : ($ket_user ?: '')) ?></small>
                        </div>
                    </div>
                    <?php if ($is_pelanggan): ?>
                    <ul class="drawer-menu">
                        <li>
                            <a href="<?= base_url('pelanggan_kelola_data/keranjang') ?>" class="drawer-link">
                                <i class="fas fa-shopping-cart"></i> <span>Keranjang</span>
                                <?php if ($jumlahBarang > 0): ?><span class="badge-count"><?= $jumlahBarang ?></span><?php endif; ?>
                            </a>
                        </li>
                        <li>
                            <a href="<?= base_url('pelanggan_kelola_data/nunggu_bayar') ?>" class="drawer-link">
                                <i class="fas fa-box"></i> <span>Pesanan Aktif</span>
                                <?php if ($jumlahBayar > 0): ?><span class="badge-count"><?= $jumlahBayar ?></span><?php endif; ?>
                            </a>
                        </li>
                        <li>
                            <a href="<?= base_url('pelanggan_kelola_data/riwayat_pesanan') ?>" class="drawer-link">
                                <i class="fas fa-history"></i> <span>Riwayat Pemesanan</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="drawer-link" onclick="bukaChatPopUp(event)">
                                <i class="fas fa-comments"></i> <span>Chat Admin</span>
                                <span id="drawer-chat-count" class="badge-count<?= (isset($unreadChat) && $unreadChat > 0) ? '' : ' d-none' ?>"><?= $unreadChat ?? 0 ?></span>
                            </a>
                        </li>
                        <li>
                            <a href="<?= base_url('pelanggan_kelola_data/profil') ?>" class="drawer-link">
                                <i class="fas fa-user-cog"></i> <span>Profil & Pengaturan</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="drawer-link danger" onclick="logoutPelanggan(event)">
                                <i class="fas fa-sign-out-alt" id="logout-icon-drawer"></i> <span>Keluar</span>
                            </a>
                        </li>
                    </ul>
                    <?php else: ?>
                    <ul class="drawer-menu">
                        <li>
                            <a href="<?= base_url($level_user == 1 ? 'home_pemilik' : 'home_admin') ?>" class="drawer-link">
                                <i class="fas fa-tachometer-alt"></i> <span>Dashboard</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?= base_url('auth/logout_user') ?>" class="drawer-link danger">
                                <i class="fas fa-sign-out-alt"></i> <span>Keluar</span>
                            </a>
                        </li>
                    </ul>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <!-- Section: Toko -->
            <div class="drawer-section">
                <h3 class="drawer-section-title">Informasi Toko</h3>
                <ul class="drawer-menu">
                    <li>
                        <a href="<?= base_url('auth/pilih_toko') ?>" class="drawer-link">
                            <i class="fas fa-store"></i> <span>Ganti Toko</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Backdrop -->
    <div class="nav-backdrop" id="navBackdrop" aria-hidden="true"></div>

    <!-- Mobile Search Overlay -->
    <div class="search-overlay" id="searchOverlay" aria-hidden="true">
        <form method="GET" action="<?= base_url('home_toko/katalog') ?>" class="search-form-full">
            <label for="mobileSearchKeyword" class="sr-only">Cari produk</label>
            <div class="search-input-wrapper">
                <input type="text" id="mobileSearchKeyword" name="keyword" class="search-input-full" placeholder="Cari produk..." autofocus value="<?= esc($_GET['keyword'] ?? '') ?>">
                <button type="submit" class="btn-search-full" aria-label="Cari">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </form>
    </div>
</header>

<!-- Modal Filter -->
<div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content filter-panel">
            <div class="modal-header filter-panel-header">
                <h5 class="modal-title" id="filterModalLabel"><i class="fas fa-filter"></i> Filter Produk</h5>
                <button type="button" class="filter-close" data-bs-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
            </div>

            <div class="modal-body filter-panel-body">
                <form id="product-filter-form" method="GET" action="<?= base_url('home_toko/katalog') ?>">

                    <!-- Cari Kata -->
                    <div class="filter-group">
                        <label for="keyword" class="filter-label">Cari Kata</label>
                        <input type="text" name="keyword" id="keyword" class="filter-control" placeholder="Cari produk..." value="<?= esc($filter_params['keyword'] ?? '') ?>">
                    </div>

                    <!-- Jenis Produk -->
                    <div class="filter-group">
                        <label for="jenis_produk" class="filter-label">Jenis Produk</label>
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

                    <!-- Rating Minimum -->
                    <div class="filter-group">
                        <span class="filter-label">Rating Minimum</span>
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

                    <!-- Aksi -->
                    <div class="filter-actions">
                        <button type="submit" class="filter-btn filter-btn-primary"><i class="fas fa-check"></i> Terapkan Filter</button>
                        <a href="<?= base_url('home_toko/katalog') ?>" class="filter-btn filter-btn-secondary"><i class="fas fa-redo"></i> Reset Filter</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



<script>
// Ensure search buttons submit form on click
document.addEventListener('DOMContentLoaded', function() {
    // Desktop search button
    document.querySelectorAll('.search-form .btn-search, .search-form-full .btn-search-full').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            this.closest('form').submit();
        });
    });
});
</script>