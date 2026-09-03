<?= view('layout_toko/v_navbar.php') ?>

<style>
    .nota-beli {
        display: block;
        margin-bottom: 0;
    }

    .nota-header {
        background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
    }

    .nota-header .tracking-wide {
        letter-spacing: 2px;
    }

    .nota-info-box {
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 10px;
        padding: 14px 16px;
    }

    .nota-info-title {
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #1e3c72;
        margin-bottom: 8px;
    }

    .nota-info-title i {
        margin-right: 4px;
    }

    @media (max-width: 768px) {
        .product-section {
            padding-left: 16px !important;
            padding-right: 16px !important;
        }
    }

    @page {
        size: A4;
        margin: 10mm;
    }

    @media print {
        * {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        header,
        footer,
        .no-print,
        #chatPopLauncher,
        #chatPopBadge {
            display: none !important;
        }

        body {
            background: #fff !important;
        }

        main.product-section {
            padding: 0 !important;
        }

        .nota-beli {
            box-shadow: none !important;
            border: 1px solid #dee2e6;
        }

        .nota-info-box {
            background: #f8f9fa !important;
        }
    }
</style>

<body>
    <main class="product-section">
        <div class="container my-4">

            <!-- Toolbar -->
            <div class="no-print d-flex flex-wrap gap-2 align-items-center mb-3">
                <a href="<?= base_url('pelanggan_kelola_data/riwayat_bayar') ?>" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
                <div class="ms-auto d-flex flex-wrap gap-2">
                    <button type="button" onclick="window.print()" class="btn btn-outline-secondary">
                        <i class="fas fa-print me-1"></i> Cetak
                    </button>
                    <a href="<?= base_url('pelanggan_kelola_data/unduh_nota_pdf/' . ($pembayaran['id_bayar'] ?? '')) ?>" class="btn btn-primary">
                        <i class="fas fa-file-pdf me-1"></i> Unduh Nota (PDF)
                    </a>
                </div>
            </div>

            <?php if (!empty($pembayaran)): ?>
                <?php
                $toko = get_data_toko();
                $namaToko = $toko['nama_toko'] ?? 'Toko Default';
                $alamatToko = $toko['alamat_pusat'] ?? '-';
                $waToko = $toko['wa_pusat'] ?? '-';
                $logoToko = $toko['logo_website'] ?? 'fotodefault/gudang.png';

                $bulan = [1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                $ts = strtotime($pembayaran['waktu_pembayaran'] ?? 'now');
                $tanggalNota = date('d', $ts) . ' ' . $bulan[(int) date('n', $ts)] . ' ' . date('Y', $ts);
                $jamNota = date('H:i', $ts);

                $hargaSatuan = (float) ($pembayaran['harga_produk'] ?? 0);
                $jumlah = (int) ($pembayaran['jumlah_produk'] ?? 1);
                $subtotal = $hargaSatuan * $jumlah;
                $ongkir = (float) ($pembayaran['ongkir'] ?? 0);
                $totalBayar = (float) ($pembayaran['total_bayar'] ?? ($subtotal + $ongkir));
                if ($totalBayar <= 0) {
                    $totalBayar = $subtotal + $ongkir;
                }
                $beratSatuan = (float) ($pembayaran['berat_produk'] ?? 0);
                $satuanBerat = $pembayaran['satuan_berat'] ?? 'gr';

                $status = $pembayaran['status_bayar'] ?? '-';
                $statusClass = 'bg-secondary';
                if ($status === 'Dibayar') {
                    $statusClass = 'bg-success';
                } elseif ($status === 'Belum Bayar') {
                    $statusClass = 'bg-warning text-dark';
                } elseif ($status === 'Dibatalkan') {
                    $statusClass = 'bg-danger';
                }
                ?>

                <div class="nota-beli rounded-3 shadow-sm bg-white overflow-hidden">
                    <!-- Kepala Nota -->
                    <div class="nota-header text-white d-flex flex-wrap justify-content-between align-items-center px-4 py-4">
                        <div class="d-flex align-items-center gap-3">
                            <img src="<?= base_url('logowebsite/' . $logoToko) ?>" alt="Logo Toko" class="rounded-circle bg-white p-1" style="width:64px;height:64px;object-fit:cover;">
                            <div>
                                <div class="fw-bold fs-5"><?= esc($namaToko) ?></div>
                                <div class="small opacity-75"><i class="fas fa-map-marker-alt"></i> <?= esc($alamatToko) ?></div>
                                <div class="small opacity-75"><i class="fas fa-phone-alt"></i> <?= esc($waToko) ?></div>
                            </div>
                        </div>
                        <div class="text-md-end mt-3 mt-md-0">
                            <div class="fs-4 fw-bold tracking-wide">Nota Pembelian</div>
                            <div class="small opacity-75">Kode: <span class="fw-bold"><?= esc($pembayaran['kode_beli'] ?? '-') ?></span></div>
                            <span class="badge <?= $statusClass ?> fs-6 mt-1"><i class="fas fa-circle me-1" style="font-size:8px;"></i><?= esc($status) ?></span>
                        </div>
                    </div>

                    <div class="px-4 py-4">
                        <!-- Info Pembeli / Pengiriman / Pembayaran -->
                        <div class="row g-4">
                            <div class="col-md-4">
                                <div class="nota-info-box h-100">
                                    <div class="nota-info-title"><i class="fas fa-user"></i> Diterima Oleh</div>
                                    <div class="fw-bold"><?= esc($pembayaran['nama_pelanggan'] ?? '-') ?></div>
                                    <div class="small text-muted"><i class="fas fa-phone-alt"></i> <?= esc($pembayaran['no_telpon'] ?? '-') ?></div>
                                    <div class="small text-muted"><i class="fas fa-envelope"></i> <?= esc($pembayaran['email'] ?? '-') ?></div>
                                    <div class="small text-muted"><i class="fas fa-map-marker-alt"></i> <?= esc($pembayaran['alamat'] ?? '-') ?></div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="nota-info-box h-100">
                                    <div class="nota-info-title"><i class="fas fa-truck"></i> Pengiriman</div>
                                    <div class="fw-bold"><?= esc($pembayaran['jenis_kurir'] ?? '-') ?></div>
                                    <div class="small text-muted"><i class="fas fa-box-open"></i> Ongkir: Rp <?= number_format($ongkir, 0, ',', '.') ?></div>
                                    <div class="small text-muted"><i class="fas fa-clock"></i> Estimasi: <?= esc($pembayaran['estimasi_waktu'] ?? '-') ?></div>
                                    <div class="small text-muted"><i class="fas fa-calendar-alt"></i> <?= $tanggalNota ?> <?= $jamNota ?> WIB</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="nota-info-box h-100">
                                    <div class="nota-info-title"><i class="fas fa-credit-card"></i> Pembayaran</div>
                                    <div class="fw-bold"><?= esc($pembayaran['bank_tujuan'] ?? '-') ?></div>
                                    <div class="small text-muted"><i class="fas fa-credit-card"></i> No. Rek: <?= esc($pembayaran['no_rek'] ?? '-') ?></div>
                                    <div class="small text-muted"><i class="fas fa-user-tag"></i> A/N: <?= esc($pembayaran['a_n'] ?? '-') ?></div>
                                    <div class="small text-muted"><i class="fas fa-hashtag"></i> ID Bayar: <?= esc($pembayaran['id_bayar'] ?? '-') ?></div>
                                </div>
                            </div>
                        </div>

                        <!-- Tabel Produk -->
                        <div class="table-responsive mt-4">
                            <table class="table table-bordered align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center" style="width:5%;">No</th>
                                        <th>Produk</th>
                                        <th class="text-center" style="width:12%;">Ukuran</th>
                                        <th class="text-center" style="width:12%;">Jumlah</th>
                                        <th class="text-end" style="width:17%;">Harga Satuan</th>
                                        <th class="text-end" style="width:17%;">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-center">1</td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <img src="<?= base_url('fotoproduk/' . ($pembayaran['foto_produk'] ?? 'fotodefault.png')) ?>" alt="Foto Produk" class="rounded border" width="50" height="50" style="object-fit:cover;">
                                                <div>
                                                    <div class="fw-bold"><?= esc($pembayaran['nama_produk'] ?? '-') ?></div>
                                                    <div class="small text-muted">Berat: <?= number_format($beratSatuan, 0, ',', '.') ?> <?= esc($satuanBerat) ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center"><?= esc($pembayaran['ukuran_produk'] ?? '-') ?></td>
                                        <td class="text-center"><?= $jumlah ?> <?= esc($pembayaran['satuan_produk'] ?? '') ?></td>
                                        <td class="text-end">Rp <?= number_format($hargaSatuan, 0, ',', '.') ?></td>
                                        <td class="text-end fw-bold">Rp <?= number_format($subtotal, 0, ',', '.') ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Ringkasan Total -->
                        <div class="row justify-content-end mt-4">
                            <div class="col-md-5 col-12">
                                <div class="card border-0 shadow-none" style="background:#f8f9fa;">
                                    <div class="card-body p-3">
                                        <div class="d-flex justify-content-between mb-1">
                                            <span class="text-muted">Subtotal Produk</span>
                                            <span>Rp <?= number_format($subtotal, 0, ',', '.') ?></span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-1">
                                            <span class="text-muted">Ongkir</span>
                                            <span>Rp <?= number_format($ongkir, 0, ',', '.') ?></span>
                                        </div>
                                        <hr>
                                        <div class="d-flex justify-content-between fw-bold fs-5">
                                            <span>Total Bayar</span>
                                            <span class="text-primary">Rp <?= number_format($totalBayar, 0, ',', '.') ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tanda Tangan -->
                        <div class="row mt-5">
                            <div class="col-6 text-center">
                                <div class="small text-muted">Pembeli</div>
                                <div class="my-5">&nbsp;</div>
                                <div class="fw-bold border-top pt-2" style="border-color:#000 !important;"><?= esc($pembayaran['nama_pelanggan'] ?? '-') ?></div>
                            </div>
                            <div class="col-6 text-center">
                                <div class="small text-muted"><?= esc($namaToko) ?></div>
                                <div class="my-5">&nbsp;</div>
                                <div class="fw-bold border-top pt-2" style="border-color:#000 !important;"><?= esc(session()->get('nama_lengkap') ?? 'Admin Toko') ?></div>
                            </div>
                        </div>

                        <!-- Ucapan Terima Kasih -->
                        <div class="text-center mt-4 pt-3 border-top">
                            <div class="fw-bold">Terima kasih telah berbelanja di <?= esc($namaToko) ?></div>
                            <div class="small text-muted">Untuk pertanyaan lebih lanjut, hubungi kami di <?= esc($waToko) ?></div>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="alert alert-info"><i class="fas fa-info-circle me-1"></i> Data pembayaran tidak tersedia.</div>
            <?php endif; ?>

        </div>
    </main>
</body>

<?= view('layout_toko/v_footer.php') ?>
