 <?= view('layout_toko/v_navbar.php') ?>

 <body>

     <!-- Product Section -->
     <main class="product-section">
         <div class="container">
             <h3>Menunggu Pembayaran</h3>

             <div class="row">
                 <div class="col-md-5 col-12">
                     <div class="notif-pembayaran alert-info p-2">
                         <p class="notif-title">
                             ℹ️ <strong>Silahkan lakukan pembayaran sebelum jatuh tempo.</strong>
                         </p>
                     </div>
                 </div>
             </div>

             <div class="table-responsive mt-0 mb-3">

                 <table class="table table-bordered">
                     <thead class="table-light">
                         <tr>
                             <th>#</th>
                              <th>Tanggal Pembayaran</th>
                              <th>Nama Produk</th>
                              <th>Jumlah Produk</th>
                              <th>Total Dibayar</th>
                              <th>QR Pembayaran</th>
                              <th>Foto Produk</th>
                              <th>Batas Waktu Bayar</th>
                              <th>Status Pembayaran</th>
                              <th>Aksi</th>
                         </tr>
                     </thead>
                     <tbody>
                         <?php if (empty($pembayaran)): ?>
                             <tr>
                                 <td colspan="10" class="text-center">Pembayaran anda kosong, tidak ada pembayaran ditunda</td>
                             </tr>
                         <?php else: ?>
                             <?php $no = 1; ?>
                             <?php foreach ($pembayaran as $value): ?>
                                 <tr>
                                     <td><?= $no++; ?></td>
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
                                     <td>
                                         <?php
                                            $tanggal_db = $value['waktu_pembayaran'];
                                            if ($tanggal_db && $tanggal_db != '0000-00-00 00:00:00'):
                                                $timestamp = strtotime($tanggal_db);
                                                $formattedTanggal = date('d', $timestamp) . ' ' . $bulan[date('n', $timestamp)] . ' ' . date('Y H:i:s', $timestamp);
                                                echo $formattedTanggal;
                                            else:
                                            ?>
                                             <span class="text-danger">Silahkan lakukan pembayaran</span>
                                         <?php endif; ?>
                                     </td>

                                     <td><?= $value['nama_produk']; ?></td>
                                     <td><?= $value['jumlah_produk']; ?>
                                         <?= $value['satuan_produk']; ?></p>
                                     </td>
                                      <td>Rp. <?= number_format($value['total_bayar'], 0, ',', '.'); ?></td>
                                      <td class="text-center">
                                          <?php if ($value['qr_url']): ?>
                                          <img src="<?= esc($value['qr_url']) ?>" width="150" alt="QR Pembayaran">
                                          <div class="mt-2">
                                              <a href="<?= esc($value['qr_url']) ?>" download="qr_<?= esc($value['id_bayar']) ?>.png" class="btn btn-sm btn-primary">
                                                  Unduh QR
                                              </a>
                                              <?php if ($value['snap_url']): ?>
                                              <a href="<?= esc($value['snap_url']) ?>" target="_blank" class="btn btn-sm btn-success">
                                                  Bayar Sekarang
                                              </a>
                                              <?php endif; ?>
                                          </div>
                                          <?php else: ?>
                                          <span class="text-muted">QR tidak tersedia</span>
                                          <?php endif; ?>
                                      </td>
                                     <td><img src="<?= base_url('fotoproduk/' . $value['foto_produk']) ?>" class="img-circle" width="80px" height="80px"></td>
                                     <td>
                                         <?php
                                            $batas_waktu = $value['batas_waktu_bayar'];
                                            if ($batas_waktu && $batas_waktu != '0000-00-00 00:00:00'):
                                                $timestamp_batas = strtotime($batas_waktu);
                                                $formatted_batas = date('d', $timestamp_batas) . ' ' . $bulan[date('n', $timestamp_batas)] . ' ' . date('Y H:i:s', $timestamp_batas);
                                                echo '<span class="text-danger fw-bold">' . $formatted_batas . '</span>';
                                            else:
                                                echo '-';
                                            endif;
                                            ?>
                                     </td>
                                     <td>
                                         <span class="label-status <?= $value['status_bayar'] == 'Dibayar' ? 'label-success' : ($value['status_bayar'] == 'Dibatalkan' ? 'label-danger' : 'label-warning') ?>">
                                             <?= esc($value['status_bayar']) ?>
                                         </span>
                                     </td>
                                     <td>
                                         <?php if ($value['status_bayar'] == 'Belum Bayar' || $value['status_bayar'] == 'Dibatalkan'): ?>
                                             <a href="<?= base_url('pelanggan_kelola_data/add_bayar/' . $value['id_bayar']) ?>" class="btn btn-warning">Pembayaran</a>
                                         <?php endif; ?>
                                     </td>
                                 </tr>
                             <?php endforeach; ?>
                         <?php endif; ?>
                     </tbody>
                 </table>
             </div>

         </div>
     </main>

 </body>

 <?= view('layout_toko/v_footer.php') ?>