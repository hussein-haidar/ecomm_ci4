 <?= view('layout_toko/v_navbar.php') ?>

 <body>
     <main class="product-section">
         <div class="container my-5">
             <div class="row">

                 <div class="col-md-6">
                     <h3><strong><?= $pembayaran['nama_pelanggan']; ?></strong></h3>
                     <p class="detail"><strong>Bank:</strong> <?= $pembayaran['bank_tujuan']; ?></p>
                     <?php
                        $bulan = [
                            1 => 'Januari',
                            'Februari',
                            'Maret',
                            'April',
                            'Mei',
                            'Juni',
                            'Juli',
                            'Agustus',
                            'September',
                            'Oktober',
                            'November',
                            'Desember'
                        ];
                        ?>
                     <p class="detail"><strong>Waktu Bayar:</strong> <?php
                                                                    $tanggal = strtotime($pembayaran['waktu_pembayaran']);
                                                                    $formattedTanggal = date('d', $tanggal) . ' ' . $bulan[date('n', $tanggal)] . ' ' . date('Y H:i:s', $tanggal);
                                                                    ?>
                         <?= $formattedTanggal ?></p>
                     <p class="detail"><strong>Total Bayar:</strong> Rp. <?= number_format($pembayaran['total_bayar'], 0, ',', '.') ?></p>
                 </div>

                 <div class="col-md-6">
                     <img src="<?= base_url('fotobayar/' . $pembayaran['foto_bayar']) ?>" alt="Gambar Produk" class="img-fluid product-image rounded">
                 </div>
             </div>
         </div>
     </main>

 </body>

 <?= view('layout_toko/v_footer.php') ?>