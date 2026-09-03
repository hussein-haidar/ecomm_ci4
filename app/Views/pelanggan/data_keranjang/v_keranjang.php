 <?= view('layout_toko/v_navbar.php') ?>

 <body>
     <!-- Product Section -->
     <main class="product-section">
         <div class="container my-4">
             <div class="d-flex flex-wrap align-items-center justify-content-between mb-3">
                 <h3 class="mb-0"><i class="fas fa-shopping-cart text-primary me-2"></i>Keranjang Belanja</h3>
                 <?php if (!empty($keranjang)): ?>
                     <span class="text-muted"><span id="jumlah-produk"><?= count($keranjang) ?></span> produk dalam keranjang</span>
                 <?php endif; ?>
             </div>

             <?php if (empty($keranjang)): ?>
                 <div class="text-center text-muted py-5 border rounded bg-light">
                     <i class="fas fa-cart-plus fa-3x d-block mb-3"></i>
                     Keranjang anda kosong, tidak ada barang untuk checkout.
                     <div class="mt-3">
                         <a href="<?= base_url('home_toko/index') ?>" class="btn btn-warning"><i class="fas fa-store"></i>&nbsp;Mulai Belanja</a>
                     </div>
                 </div>
             <?php else: ?>
                 <div class="table-responsive">
                     <table class="table table-bordered table-hover align-middle">
                         <thead class="table-light text-center">
                             <tr>
                                 <th width="4%">#</th>
                                 <th>Produk</th>
                                 <th>Ukuran</th>
                                 <th>Harga Satuan</th>
                                 <th>Jumlah</th>
                                 <th>Total Harga</th>
                                 <th>Ditambahkan</th>
                                 <th>Aksi</th>
                             </tr>
                         </thead>
                         <tbody>
                             <?php $no = 1;
                             $totalSemua = 0; ?>
                             <?php foreach ($keranjang as $value): ?>
                                 <?php $totalSemua += $value['total_harga']; ?>
                                 <tr>
                                     <td class="text-center"><?= $no++; ?></td>
                                     <td>
                                         <div class="d-flex align-items-center gap-2">
                                             <img src="<?= base_url('fotoproduk/' . $value['foto_produk']) ?>" class="rounded border" width="60" height="60" style="object-fit:cover;" alt="Produk">
                                             <div>
                                                 <div class="fw-bold"><?= esc($value['nama_produk']) ?></div>
                                                  <a href="#" onclick="bukaChatProduk(event, <?= esc(json_encode($value['nama_produk']), 'attr') ?>, <?= (int)$value['id_stok'] ?>)" class="btn btn-sm btn-outline-info mt-1">
                                                      <i class="fas fa-comments"></i>&nbsp;Chat Penjual
                                                  </a>
                                             </div>
                                         </div>
                                     </td>
                                     <td class="text-center">
                                         <form action="<?= base_url('pelanggan_kelola_data/update_cart') ?>" method="post">
                                             <?= csrf_field() ?>
                                             <input type="hidden" name="id_keranjang" value="<?= $value['id_keranjang'] ?>">
                                             <select name="ukuran_produk" class="form-select form-select-sm" onchange="this.form.submit()" style="width:auto;">
                                                 <option value="">--Pilih Ukuran--</option>
                                                 <?php foreach ($value['ukuran_list'] as $ukuran): ?>
                                                     <option value="<?= esc($ukuran) ?>" <?= ($ukuran == $value['ukuran_produk']) ? 'selected' : '' ?>>
                                                         <?= esc($ukuran) ?>
                                                     </option>
                                                 <?php endforeach; ?>
                                             </select>
                                         </form>
                                     </td>
                                     <td class="text-center">Rp. <?= number_format($value['harga_produk'], 0, ',', '.') ?></td>
                                     <td class="text-center">
                                         <form action="<?= base_url('pelanggan_kelola_data/update_cart') ?>" method="post" class="d-inline-flex align-items-center gap-1">
                                             <?= csrf_field() ?>
                                             <input type="hidden" name="id_keranjang" value="<?= $value['id_keranjang'] ?>">
                                             <div class="input-group input-group-sm" style="width:130px;">
                                                 <button type="button" class="btn btn-outline-secondary btn-qty" data-aksi="minus">&#8722;</button>
                                                 <input type="number" name="jumlah_produk" class="form-control text-center qty-input" min="1" max="<?= (int)$value['jumlah_stok_produk'] ?>" value="<?= $value['jumlah_produk'] ?>" data-max="<?= (int)$value['jumlah_stok_produk'] ?>" onchange="this.form.submit()">
                                                 <button type="button" class="btn btn-outline-secondary btn-qty" data-aksi="plus">&#43;</button>
                                             </div>
                                             <span class="text-muted small"><?= esc($value['satuan_produk']) ?></span>
                                         </form>
                                         <div class="small text-muted">Stok tersedia: <?= (int)$value['jumlah_stok_produk'] ?></div>
                                     </td>
                                     <td class="text-center fw-bold">Rp. <?= number_format($value['total_harga'], 0, ',', '.') ?></td>
                                     <td class="text-center small"><?= date('d-m-Y H:i', strtotime($value['waktu_ditambahkan'])) ?></td>
                                     <td class="text-center">
                                         <div class="d-flex flex-column align-items-stretch gap-1">
                                             <input type="checkbox" class="checkout-checkbox d-none" value="<?= $value['id_keranjang'] ?>" id="checkbox-<?= $value['id_keranjang'] ?>">
                                             <button type="button" class="btn btn-outline-primary btn-sm checkout-btn" data-id="<?= $value['id_keranjang'] ?>">
                                                 <i class="fa fa-fw fa-check"></i> Checkout
                                             </button>
                                             <button type="button" class="btn btn-danger btn-sm btn-delete" data-id="<?= $value['id_keranjang'] ?>" data-nama="<?= esc($value['nama_produk']) ?>">
                                                 <i class="fa fa-fw fa-trash"></i> Hapus
                                             </button>
                                         </div>
                                     </td>
                                 </tr>
                             <?php endforeach; ?>
                         </tbody>
                     </table>
                 </div>

                 <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mt-4 gap-3 p-3 bg-light border rounded">
                     <div>
                         <h4 class="mb-1">Total: <span class="text-success">Rp. <?= number_format($totalSemua, 0, ',', '.') ?></span></h4>
                         <span class="text-muted small"><span id="jumlah-terpilih">0</span> produk dipilih untuk checkout</span>
                     </div>
                     <div class="d-flex flex-wrap gap-2">
                         <button class="btn btn-warning" onclick="window.location.href='<?= base_url('home_toko/index') ?>'">
                             <i class="fas fa-cart-plus"></i>&nbsp;Kembali Belanja
                         </button>
                         <button class="btn btn-success" onclick="checkoutSelected()">
                             <i class="fas fa-cart-arrow-down"></i>&nbsp;Checkout
                         </button>
                     </div>
                 </div>
             <?php endif; ?>
         </div>
     </main>

     <script>
     // Stepper jumlah produk (+/-)
     document.querySelectorAll('.btn-qty').forEach(function(btn) {
         btn.addEventListener('click', function() {
             const form = btn.closest('form');
             const input = form.querySelector('.qty-input');
             let val = parseInt(input.value) || 1;
             const max = parseInt(input.dataset.max) || 9999;
             if (btn.dataset.aksi === 'plus' && val < max) {
                 input.value = val + 1;
                 form.submit();
             } else if (btn.dataset.aksi === 'minus' && val > 1) {
                 input.value = val - 1;
                 form.submit();
             }
         });
     });

     // Hitung produk terpilih untuk checkout
     function updateTerpilih() {
         const el = document.getElementById('jumlah-terpilih');
         if (el) {
             el.textContent = document.querySelectorAll('.checkout-checkbox:checked').length;
         }
     }

     document.querySelectorAll('.checkout-btn').forEach(function(btn) {
         btn.addEventListener('click', function() {
             setTimeout(updateTerpilih, 50);
         });
     });

     document.addEventListener('DOMContentLoaded', updateTerpilih);
     </script>

 </body>

 <?= view('layout_toko/v_footer.php') ?>
