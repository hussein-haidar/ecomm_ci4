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
    <!-- Bootstrap  -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- CSS -->
    <link rel="stylesheet" href="<?= base_url() ?>/template_admin/dist/css/toko.css?v=20260810c">
    <!-- Icon -->
    <link href="<?= base_url() ?>/icon/gudang.ico" rel="shortcut icon">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

    <?= view('layout_toko/v_csrf_script') ?>
</head>

<?php
$jumlahBarang = 0;
$jumlahBayar = 0;
$nama_pelanggan = session()->get('nama_pelanggan');
$sesiUserToko = session()->get('toko_sesi_user');
$user_logged_in = !empty($nama_pelanggan);
$jenis_produk_dropdown = $jenis_produk_dropdown ?? [];

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

<header>
    <div class="header-top">
        <h1><a href="<?= base_url('home_toko/index') ?>" style="text-decoration: none; color: inherit;"><?= get_data_toko()['nama_toko'] ?? 'Nama Toko Default'; ?></a></h1>
        <form method="GET" action="<?= base_url('home_toko/katalog') ?>">
            <div class="input-group mb-3">
                <input type="text" class="form-control search-input" placeholder="Cari produk..." name="keyword">
                <button class="btn btn-primary btn-search" type="submit">Cari</button>
            </div>
        </form>
        <!-- Ikon Pencarian untuk layar kecil -->
        <i class="fas fa-search search-icon" onclick="toggleSearchBar()"></i>
        <div class="navbar-toggler" onclick="toggleNavbar()">&#9776;</div>
        <nav>
            <ul>
                <li><a href="<?= base_url('home_toko/katalog') ?>">Katalog</a></li>
                <li class="dropdown">
                    <a class="dropdown-toggle" href="#">Jenis Produk</a>
                    <ul class="dropdown-content">
                        <?php foreach ($jenis_produk_dropdown as $jenis): ?>
                            <li>
                                <a href="<?= base_url('home_toko/jenis_produk/' . urlencode($jenis['jenis_produk'])) ?>">
                                    <?= esc($jenis['jenis_produk']) ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </li>
                <li>
                    <a href="#" data-bs-toggle="modal" data-bs-target="#filterModal">
                        <i class="fas fa-filter"></i> Filter
                    </a>
                </li>
                <?php if (!$user_logged_in): ?>
                <li><a href="<?= base_url('auth/pilih_toko') ?>"><i class="fas fa-store"></i> Pilih Toko</a></li>
                <?php endif; ?>
                <?php if ($user_logged_in): ?>
                    <li>
                        <a href="<?= base_url('pelanggan_kelola_data/keranjang') ?>" class="cart-icon-wrapper" style="position: relative;">
                            <i class="fas fa-cart-plus"></i>
                            <?php if (!empty($jumlahBarang) && $jumlahBarang > 0): ?>
                                <span class="cart-count"><?= $jumlahBarang ?></span>
                            <?php endif; ?>
                        </a>
                    </li>
                    <li><a href="<?= base_url('pelanggan_kelola_data/status_bayar') ?>" class="cart-icon-wrapper" style="position: relative;">
                            <i class="fas fa-hourglass-half"></i>
                            <?php if (!empty($jumlahBayar) && $jumlahBayar > 0): ?>
                                <span class="cart-count"><?= $jumlahBayar ?></span>
                            <?php endif; ?>
                        </a>
                    </li>
                    <li><a href="<?= base_url('pelanggan_kelola_data/riwayat_bayar') ?>"><i class="fas fa-history"></i></a></li>
                    <li>
                        <a href="#" onclick="bukaChatPopUp(event)" class="cart-icon-wrapper" style="position: relative;" title="Chat dengan Admin">
                            <i class="fas fa-comments"></i>
                            <span id="navbar-chat-count" class="cart-count<?= (isset($unreadChat) && $unreadChat > 0) ? '' : ' d-none' ?>"><?= $unreadChat ?? 0 ?></span>
                        </a>
                    </li>
                    <li><a href="<?= base_url('pelanggan_kelola_data/profil') ?>"><i class="fas fa-user"></i></a></li>
                    <li>
                        <a href="#" onclick="logoutPelanggan(event)">
                            <i id="logout-icon" class="fas fa-sign-out-alt"></i>
                        </a>
                    </li>
                <?php else: ?>
                    <li><a href="<?= base_url('home_toko/login') ?>"><i class="fas fa-sign-in-alt"></i></a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</header>

<!-- Modal Filter -->
<div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="filterModalLabel">Filter Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form id="product-filter-form" method="GET" action="<?= base_url('home_toko/katalog') ?>">
                    <!-- Cari Kata -->
                    <div class="mb-3">
                        <label for="keyword">Cari Kata</label>
                        <input type="text" name="keyword" id="keyword" class="form-control" placeholder="Cari..." value="<?= esc($filter_params['keyword'] ?? '') ?>">
                    </div>

                    <!-- Jenis Produk -->
                    <div class="mb-3">
                        <label for="jenis_produk">Jenis Produk</label>
                        <select name="jenis_produk" id="jenis_produk" class="form-control">
                            <option value="">Pilih Kategori</option>
                            <?php foreach ($jenis_produk_dropdown as $jenis): ?>
                                <option value="<?= esc($jenis['jenis_produk']) ?>" <?= (isset($filter_params['jenis_produk']) && $filter_params['jenis_produk'] == $jenis['jenis_produk']) ? 'selected' : '' ?>>
                                    <?= esc($jenis['jenis_produk']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Rentang Harga -->
                    <div class="mb-3">
                        <label>Rentang Harga</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp.</span>
                            <input type="number" name="harga_min" class="form-control" placeholder="Min" value="<?= esc($filter_params['harga_min'] ?? '') ?>">
                            &nbsp;
                            <span class="input-group-text">-</span>
                            &nbsp; <!-- Menambahkan jarak -->
                            <span class="input-group-text">Rp.</span>
                            <input type="number" name="harga_max" class="form-control" placeholder="Max" value="<?= esc($filter_params['harga_max'] ?? '') ?>">
                        </div>
                    </div>

                    <!-- Sorting Harga -->
                    <div class="mb-3">
                        <label>Urutkan Berdasarkan Harga</label>
                        <select name="sort_harga" class="form-control">
                            <option value="">Default</option>
                            <option value="asc" <?= ($filter_params['sort_harga'] ?? '') == 'asc' ? 'selected' : '' ?>>Harga Terendah</option>
                            <option value="desc" <?= ($filter_params['sort_harga'] ?? '') == 'desc' ? 'selected' : '' ?>>Harga Tertinggi</option>
                        </select>
                    </div>

                    <!-- Sorting Nama -->
                    <div class="mb-3">
                        <label>Sorting Nama</label>
                        <select name="sort_nama" class="form-control">
                            <option value="">Pilih Urutan</option>
                            <option value="a-z" <?= ($filter_params['sort_nama'] ?? '') == 'a-z' ? 'selected' : '' ?>>A - Z</option>
                            <option value="z-a" <?= ($filter_params['sort_nama'] ?? '') == 'z-a' ? 'selected' : '' ?>>Z - A</option>
                        </select>
                    </div>

                    <!-- Tombol Submit -->
                    <button type="submit" class="btn btn-primary w-100">Terapkan Filter</button>

                    <!-- Tombol Reset -->
                    <a href="<?= base_url('home_toko/katalog') ?>" class="btn btn-secondary w-100 mt-2">Reset Filter</a>

                </form>
            </div>
        </div>
    </div>
</div>