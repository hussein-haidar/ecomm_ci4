 <?= view('layout_toko/v_navbar.php') ?>

 <body>
     <!-- Product Section -->
     <main class="product-section">
         <div class="container">
             <h3>Upload Bukti</h3>

             <!-- Form Pembayaran -->
             <?php echo form_open_multipart('pelanggan_kelola_data/save_bayar/' . $pembayaran['id_bayar']); ?>

             <input type="hidden" name="sesi_user" value="<?= esc($sesi_user) ?>" readonly>

             <input type="hidden" name="id_beli" value="<?= esc($pembayaran['id_beli']) ?>">
             
             <div class="mb-3">
                 <label for="nama_pelanggan" class="form-label">Pelanggan</label>
                 <input type="text" id="nama_pelanggan" name="nama_pelanggan" value="<?= $pembayaran['nama_pelanggan'] ?>" class="form-control" readonly>
             </div>

             <div class="mb-3">
                 <label for="bank_tujuan" class="form-label">Bank Penerima</label>
                 <input type="text" id="bank_tujuan" name="bank_tujuan" value="<?= $pembayaran['bank_tujuan'] ?>" class="form-control" readonly>
             </div>

             <div class="mb-3">
                 <label for="no_rek" class="form-label">No Rekening</label>
                 <input type="text" id="no_rek" name="no_rek" value="<?= $pembayaran['no_rek'] ?>" class="form-control" readonly>
             </div>

             <div class="mb-3">
                 <label for="total_bayar" class="form-label">Total Bayar</label>
                 <input type="text" class="form-control" id="total_bayar" name="total_bayar"
                     value="Rp. <?= number_format($pembayaran['total_bayar'], 0, ',', '.') ?>" readonly>
             </div>

             <div class="mb-3">
                 <label>Upload Bukti Bayar</label>
                 <input type="file" class="form-control" name="foto_bayar" id="preview_gambar">
             </div>

             <button type="submit" class="btn btn-primary">Konfirmasi Pembayaran</button>
             <?php echo form_close() ?>

         </div>

     </main>

 </body>

 <?= view('layout_toko/v_footer.php') ?>