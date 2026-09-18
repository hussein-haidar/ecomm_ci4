  <?php
      function renderBintangServer($rating)
      {
          $s = '';
          $val = round($rating * 2) / 2;
          for ($i = 1; $i <= 5; $i++) {
              if ($val >= $i) {
                  $s .= '<i class="fa fa-star text-warning" style="font-size:14px;"></i>';
              } elseif ($val === $i - 0.5) {
                  $s .= '<i class="fas fa-star-half-alt text-warning" style="font-size:14px;"></i>';
              } else {
                  $s .= '<i class="far fa-star text-muted" style="font-size:14px;"></i>';
              }
          }
          return $s;
      }
      ?>
      <?php foreach ($produk_data as $produk) : ?>
      <div class="product-item" data-nama-produk="<?= esc($produk['nama_produk']) ?>">
          <img src="<?= base_url('fotoproduk/' . $produk['foto_produk']) ?>" alt="<?= $produk['foto_produk']; ?>">
          <h4><?= $produk['nama_produk']; ?></h4>
          <p class="produk"><strong>Size :</strong><?= $produk['ukuran_produk']; ?></p>
          <p class="produk">
              <strong>Stock :</strong>
              <span id="stok-<?= esc($produk['id_stok']) ?>">
                  <?= esc($produk['jumlah_stok_produk']) ?>
              </span>
              <?= esc($produk['satuan_produk']) ?>
          </p>
          <p class="produk"><strong>Price :</strong>Rp. <?= number_format($produk['harga_produk'], 0, ',', '.') ?></p>
          <div class="rating-katalog mb-1">
              <?php if (!empty($produk['rating_summary']) && $produk['rating_summary']['total'] > 0): ?>
                  <?php $avg = (float)$produk['rating_summary']['rata_rata']; ?>
                  <?= renderBintangServer($avg) ?>
                  <strong><?= number_format($avg, 1) ?></strong>
                  <small class="text-muted">(<?= (int)$produk['rating_summary']['total'] ?> ulasan)</small>
                  <?php if ($avg >= 4.5): ?>
                      <span class="rating-badge-top"><i class="fa fa-thumbs-up"></i> Terbaik</span>
                  <?php endif; ?>
              <?php else: ?>
                  <small class="text-muted"><i class="far fa-star"></i> Belum ada ulasan</small>
              <?php endif; ?>
          </div>
          <a href="<?= base_url('home_toko/detail_produk/' . $produk['nama_produk']) ?>" class="btn btn-success"><i class="fa fa-fw fa-eye"></i>&nbsp;Detail</a>

          <?php if ($user_logged_in): ?>
              <?= form_open_multipart('pelanggan_kelola_data/add_to_cart'); ?>
              <div class="form-cart-wrapper">
                  <input type="hidden" name="id_stok" value="<?= $produk['id_stok']; ?>">
                  <input type="hidden" name="nama_produk" value="<?= $produk['nama_produk'] ?>">
                  <input type="hidden" name="satuan_produk" value="<?= $produk['satuan_produk']; ?>">
                  <input type="hidden" name="harga_produk" value="<?= $produk['harga_produk'] ?>">
                  <input type="hidden" name="berat_produk" value="<?= $produk['berat_produk'] ?>">

                  <select id="ukuran_produk_<?= esc($produk['id_stok']) ?>" name="ukuran_produk" class="form-control profile" style="width: auto" required>
                      <option value="">--Pilih Ukuran--</option>
                      <?php foreach ($produk['ukuran_list'] as $ukuran): ?>
                          <option value=" <?= esc($ukuran) ?>"><?= esc($ukuran) ?></option>
                      <?php endforeach; ?>
                  </select>

                  <div class="form-cart-row">
                      <input type="number" name="jumlah_produk" min="1" value="1" max="<?= $produk['jumlah_stok_produk']; ?>" style="width: auto" class="form-control profile">
                      <button type="submit" class="btn btn-danger">
                          <i class="fas fa-shopping-cart"></i>&nbsp;Keranjang
                      </button>
                      <button type="submit" formaction="<?= base_url('pelanggan_kelola_data/beli_sekarang') ?>" class="btn btn-primary">
                          <i class="fas fa-bolt"></i>&nbsp;Beli Sekarang
                      </button>
                  </div>
              </div>
              <?= form_close(); ?>

          <?php else: ?>
              <div class="form-cart-wrapper">
                  <input type="hidden" name="id_stok" value="<?= $produk['id_stok']; ?>">
                  <input type="hidden" name="nama_produk" value="<?= $produk['nama_produk'] ?>">
                  <input type="hidden" name="satuan_produk" value="<?= $produk['satuan_produk']; ?>">
                  <input type="hidden" name="harga_produk" value="<?= $produk['harga_produk'] ?>">

                  <select id="ukuran_produk_<?= esc($produk['id_stok']) ?>" name="ukuran_produk" class="form-control profile" style="width: auto" disabled>
                      <option value="">--Pilih Ukuran--</option>
                      <?php foreach ($produk['ukuran_list'] as $ukuran): ?>
                          <option value=" <?= esc($ukuran) ?>"><?= esc($ukuran) ?></option>
                      <?php endforeach; ?>
                  </select>

                  <div class="form-cart-row">
                      <input type="number" name="jumlah_produk" min="1" value="1" max="<?= $produk['jumlah_stok_produk']; ?>" style="width: auto" class="form-control profile" disabled>
                      <button type="submit" class="btn btn-danger" disabled>
                          <i class="fas fa-shopping-cart"></i>&nbsp;Keranjang
                      </button>
                      <button type="submit" class="btn btn-primary" disabled>
                          <i class="fas fa-bolt"></i>&nbsp;Beli Sekarang
                      </button>
                  </div>
              </div>
          <?php endif; ?>

          <?php if ($user_logged_in): ?>
              <a href="#" onclick="bukaChatProduk(event, <?= esc(json_encode($produk['nama_produk']), 'attr') ?>, <?= (int)$produk['id_stok'] ?>)" class="btn btn-outline-primary mt-2">
                  <i class="fas fa-comments"></i>&nbsp;Chat Penjual
              </a>
          <?php endif; ?>

      </div>
  <?php endforeach; ?>

  <script>
      function muatStok() {
          fetch('<?= site_url('home_toko/ambil_stok') ?>')
              .then(response => response.json())
              .then(data => {
                  data.forEach(produk => {
                      const stokElement = document.getElementById('stok-' + produk.id_stok);
                      if (stokElement && stokElement.textContent != produk.jumlah_stok_produk) {
                          stokElement.textContent = produk.jumlah_stok_produk;
                      }
                  });
              })
              .catch(err => {
                  console.error("Gagal memuat stok:", err);
              });
      }

      function renderBintangKatalog(rating) {
          let s = '';
          const val = Math.round(rating * 2) / 2;
          for (let i = 1; i <= 5; i++) {
              if (val >= i) {
                  s += '<i class="fa fa-star text-warning" style="font-size:14px;"></i>';
              } else if (val === i - 0.5) {
                  s += '<i class="fas fa-star-half-alt text-warning" style="font-size:14px;"></i>';
              } else {
                  s += '<i class="far fa-star text-muted" style="font-size:14px;"></i>';
              }
          }
          return s;
      }

      function muatRatingKatalog() {
          document.querySelectorAll('.rating-katalog').forEach(function(el) {
              // Skip jika sudah di-render server-side (punya child elements)
              if (el.children.length > 0) return;
              const card = el.closest('[data-nama-produk]');
              if (!card) return;
              const nama = card.getAttribute('data-nama-produk');
              if (!nama) return;
              fetch('<?= base_url('pelanggan_kelola_data/review_get') ?>/' + encodeURIComponent(nama))
                  .then(r => r.json())
                  .then(data => {
                      const summary = data.summary;
                      if (summary && summary.total > 0) {
                          const avg = parseFloat(summary.rata_rata);
                          const badge = avg >= 4.5 ? '<span class="rating-badge-top"><i class="fa fa-thumbs-up"></i> Terbaik</span>' : '';
                          el.innerHTML = renderBintangKatalog(avg)
                              + ' <strong>' + avg.toFixed(1) + '</strong>'
                              + ' <small class="text-muted">(' + summary.total + ' ulasan)</small>'
                              + badge;
                      } else {
                          el.innerHTML = '<small class="text-muted"><i class="far fa-star"></i> Belum ada ulasan</small>';
                      }
                  })
                  .catch(function() {
                      el.innerHTML = '<small class="text-muted">-</small>';
                  });
          });
      }

      // Jalankan saat halaman pertama kali dimuat
      muatStok();
      muatRatingKatalog();

      // Auto-refresh setiap 10 detik
      setInterval(muatStok, 10000);
  </script>
