<?= view('layout_toko/v_navbar.php') ?>

<body>
    <!-- Promo Section -->
    <main class="promo">
        <div class="promo-carousel" id="carousel">
            <?php foreach ($data_produk as $produk): ?>
                <img src="<?= base_url('fotoproduk/' . $produk['foto_produk']) ?>" alt="<?= $produk['nama_produk'] ?>">
            <?php endforeach; ?>
            <button class="prev" onclick="prevImage()">&#10094;</button>
            <button class="next" onclick="nextImage()">&#10095;</button>
        </div>


        <!-- Product Section -->
        <section class="product-section">
            <h3 class="product-title">Katalog Produk</h3>
            <div class="product-grid">
                <?= view('layout_toko/v_produk.php') ?>
            </div>

        </section>
    </main>
</body>

<?= view('layout_toko/v_footer.php') ?>