 <?= view('layout_toko/v_navbar.php') ?>

 <body>
     <!-- Product Section -->
     <main class="product-section">
         <div class="container">
             <h3>Riwayat Pembayaran</h3>
             <div class="table-responsive mt-0 mb-3">
                 <table class="table table-bordered">
                     <thead class="table-light">
                         <tr>
                             <th>#</th>
                             <th>Tanggal Pembayaran</th>
                             <th>Nama Produk</th>
                             <th>Foto Produk</th>
                             <th>Status Pembayaran</th>
                             <th>Opsi</th>
                         </tr>
                     </thead>
                     <tbody>
                         <?php if (empty($pembayaran)): ?>
                             <tr>
                                 <td colspan="10" class="text-center">Pembayaran anda kosong, tidak ada yang dibayar</td>
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
                                            $tanggal = strtotime($value['waktu_pembayaran']);
                                            $formattedTanggal = date('d', $tanggal) . ' ' . $bulan[date('n', $tanggal)] . ' ' . date('Y H:i:s', $tanggal);
                                            ?>
                                         <?= $formattedTanggal ?>
                                     </td>
                                     <td><?= $value['nama_produk']; ?></td>
                                     <td><img src="<?= base_url('fotoproduk/' . $value['foto_produk']) ?>" class="img-circle" width="80px" height="80px"></td>
                                     <td>
                                         <span class="label-status <?= $value['status_bayar'] == 'Dibayar' ? 'label-success' : ($value['status_bayar'] == 'Dibatalkan' ? 'label-danger' : 'label-warning') ?>">
                                             <?= esc($value['status_bayar']) ?>
                                         </span>
                                     </td>
                                      <td>
                                          <a href="<?= base_url('pelanggan_kelola_data/nota_pembelian/' . $value['id_bayar']) ?>" class="btn btn-primary">Nota Belanja</a>
                                          <a href="<?= base_url('pelanggan_kelola_data/lihat_bayar/' . $value['id_bayar']) ?>" class="btn btn-success">Lihat Pembayaran</a>
                                          <?php
                                            $sudahReview = !empty($reviewedProducts) && in_array($value['nama_produk'], $reviewedProducts);
                                            if ($sudahReview):
                                          ?>
                                              <button type="button" class="btn btn-info btn-lihat-review" data-produk="<?= esc($value['nama_produk']) ?>">Lihat Review</button>
                                              <button type="button" class="btn btn-warning btn-edit-review" data-produk="<?= esc($value['nama_produk']) ?>">Edit Review</button>
                                          <?php else: ?>
                                              <button type="button" class="btn btn-warning btn-review" data-produk="<?= esc($value['nama_produk']) ?>">Beri Review</button>
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

  <!-- Modal Review -->
  <div class="modal fade" id="modalReview" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <form method="POST" id="formReview">
          <div class="modal-header">
            <h5 class="modal-title" id="modalReviewTitle">Beri Review</h5>
            <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
            <input type="hidden" name="nama_produk" id="review_produk">
            <div class="form-group">
              <label>Rating</label>
              <div id="rating_stars" class="mb-2">
                <i class="fa fa-star fa-2x text-muted" data-star="1" style="cursor:pointer;"></i>
                <i class="fa fa-star fa-2x text-muted" data-star="2" style="cursor:pointer;"></i>
                <i class="fa fa-star fa-2x text-muted" data-star="3" style="cursor:pointer;"></i>
                <i class="fa fa-star fa-2x text-muted" data-star="4" style="cursor:pointer;"></i>
                <i class="fa fa-star fa-2x text-muted" data-star="5" style="cursor:pointer;"></i>
              </div>
              <input type="hidden" name="rating" id="rating_value" value="0">
            </div>
            <div class="form-group">
              <label>Ulasan (opsional)</label>
              <textarea name="ulasan" id="review_ulasan" class="form-control" rows="3" maxlength="500" placeholder="Tulis ulasan Anda..."></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary" id="btnSubmitReview">Kirim Review</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
    // Modal review
    function setBintang(val) {
      document.getElementById('rating_value').value = val;
      document.querySelectorAll('#rating_stars i').forEach(s => {
        const sVal = parseInt(s.dataset.star);
        s.className = sVal <= val ? 'fa fa-star fa-2x text-warning' : 'fa fa-star fa-2x text-muted';
      });
    }

    document.querySelectorAll('#rating_stars i').forEach(star => {
      star.addEventListener('click', function() {
        setBintang(parseInt(this.dataset.star));
      });
    });

    // Muat data review milik pembeli untuk produk tertentu
    function muatReviewPembeli(produk, callback) {
      fetch('<?= base_url('pelanggan_kelola_data/review_my_review') ?>/' + encodeURIComponent(produk))
        .then(r => r.json())
        .then(data => callback(data.review))
        .catch(() => callback(null));
    }

    function aturModeReview(produk, mode) {
      document.getElementById('review_produk').value = produk;
      document.getElementById('review_ulasan').value = '';

      var modalEl = document.getElementById('modalReview');
      var modalTitle = document.getElementById('modalReviewTitle');
      var btnSubmit = document.getElementById('btnSubmitReview');
      var form = document.getElementById('formReview');

      if (mode === 'baru') {
        modalTitle.textContent = 'Beri Review';
        btnSubmit.textContent = 'Kirim Review';
        btnSubmit.style.display = 'inline-block';
        form.action = '<?= base_url('pelanggan_kelola_data/review_submit') ?>';
        setBintang(0);
        document.getElementById('review_ulasan').readOnly = false;
        document.querySelectorAll('#rating_stars i').forEach(s => s.style.cursor = 'pointer');
        muatReviewPembeli(produk, function(review) {
          if (review) {
            setBintang(parseInt(review.rating));
            document.getElementById('review_ulasan').value = review.ulasan || '';
          }
        });
      } else if (mode === 'edit') {
        modalTitle.textContent = 'Edit Review';
        btnSubmit.textContent = 'Simpan Review';
        btnSubmit.style.display = 'inline-block';
        form.action = '<?= base_url('pelanggan_kelola_data/review_update') ?>';
        document.getElementById('review_ulasan').readOnly = false;
        document.querySelectorAll('#rating_stars i').forEach(s => s.style.cursor = 'pointer');
        muatReviewPembeli(produk, function(review) {
          if (review) {
            setBintang(parseInt(review.rating));
            document.getElementById('review_ulasan').value = review.ulasan || '';
          } else {
            setBintang(0);
          }
        });
      } else if (mode === 'lihat') {
        modalTitle.textContent = 'Review Anda';
        btnSubmit.style.display = 'none';
        document.getElementById('review_ulasan').readOnly = true;
        document.querySelectorAll('#rating_stars i').forEach(s => s.style.cursor = 'default');
        muatReviewPembeli(produk, function(review) {
          if (review) {
            setBintang(parseInt(review.rating));
            document.getElementById('review_ulasan').value = review.ulasan || '';
          } else {
            setBintang(0);
            document.getElementById('review_ulasan').value = '';
          }
        });
      }

      var modal = bootstrap.Modal.getOrCreateInstance(modalEl);
      modal.show();
    }

    document.querySelectorAll('.btn-review').forEach(btn => {
      btn.addEventListener('click', function() {
        aturModeReview(this.dataset.produk, 'baru');
      });
    });

    document.querySelectorAll('.btn-edit-review').forEach(btn => {
      btn.addEventListener('click', function() {
        aturModeReview(this.dataset.produk, 'edit');
      });
    });

    document.querySelectorAll('.btn-lihat-review').forEach(btn => {
      btn.addEventListener('click', function() {
        aturModeReview(this.dataset.produk, 'lihat');
      });
    });
  </script>

<?= view('layout_toko/v_footer.php') ?>