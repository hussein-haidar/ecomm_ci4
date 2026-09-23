<?= view('layout_toko/v_navbar.php') ?>

<body>
    <!-- Promo Section (Banner) -->
    <?php if (!empty($data_banner)): ?>
    <main class="promo">
        <div class="promo-carousel-wrapper">
            <h3 class="promo-title">Banner Produk</h3>
            <div class="promo-carousel" id="promoCarousel">
                <div class="promo-track" id="promoTrack">
                    <?php foreach ($data_banner as $banner): ?>
                        <div class="promo-slide promo-slide-banner">
                            <div class="promo-slide-img-wrap">
                                <?php if (!empty($banner['link_banner'])): ?>
                                    <a href="<?= esc($banner['link_banner']) ?>" target="_blank" rel="noopener">
                                        <img src="<?= base_url('fotobanner/' . $banner['foto_banner']) ?>"
                                             alt="<?= esc($banner['judul_banner']) ?>"
                                             loading="lazy">
                                    </a>
                                <?php else: ?>
                                    <img src="<?= base_url('fotobanner/' . $banner['foto_banner']) ?>"
                                         alt="<?= esc($banner['judul_banner']) ?>"
                                         loading="lazy">
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <button class="promo-nav promo-prev" id="promoPrev" aria-label="Slide sebelumnya">&#10094;</button>
                <button class="promo-nav promo-next" id="promoNext" aria-label="Slide selanjutnya">&#10095;</button>
            </div>
            <div class="promo-dots" id="promoDots" aria-label="Navigasi slide"></div>
        </div>
    </main>
    <?php endif; ?>

    <main class="product-section">
        <h3 class="product-title">Katalog Produk</h3>
        <div class="product-grid">
            <?= view('layout_toko/v_produk.php') ?>
        </div>
    </main>

<?= view('layout_toko/v_footer.php') ?>

<script>
(function() {
    const track = document.getElementById('promoTrack');
    const prevBtn = document.getElementById('promoPrev');
    const nextBtn = document.getElementById('promoNext');
    const dotsContainer = document.getElementById('promoDots');

    if (!track || !prevBtn || !nextBtn) return;

    const slides = track.querySelectorAll('.promo-slide');
    const slideCount = slides.length;
    if (slideCount === 0) return;

    let currentIndex = 0;
    const maxIndex = slideCount - 1;

    // Buat indikator bulat (dots) sesuai jumlah slide
    function createDots() {
        dotsContainer.innerHTML = '';
        for (let i = 0; i < slideCount; i++) {
            const dot = document.createElement('button');
            dot.className = 'promo-dot' + (i === 0 ? ' active' : '');
            dot.setAttribute('aria-label', 'Slide ' + (i + 1));
            dot.addEventListener('click', () => goToSlide(i));
            dotsContainer.appendChild(dot);
        }
    }

    function updateDots() {
        dotsContainer.querySelectorAll('.promo-dot').forEach((dot, i) => {
            dot.classList.toggle('active', i === currentIndex);
        });
    }

    function goToSlide(index) {
        currentIndex = Math.max(0, Math.min(index, maxIndex));
        track.style.transform = 'translateX(-' + currentIndex * 100 + '%)';
        updateDots();
    }

    function nextSlide() {
        goToSlide(currentIndex < maxIndex ? currentIndex + 1 : 0);
    }

    function prevSlide() {
        goToSlide(currentIndex > 0 ? currentIndex - 1 : maxIndex);
    }

    prevBtn.addEventListener('click', prevSlide);
    nextBtn.addEventListener('click', nextSlide);

    // Auto-slide otomatis
    let autoSlideInterval = setInterval(nextSlide, 4000);
    track.addEventListener('mouseenter', () => clearInterval(autoSlideInterval));
    track.addEventListener('mouseleave', () => {
        autoSlideInterval = setInterval(nextSlide, 4000);
    });

    // Swipe layar sentuh
    let touchStartX = 0;
    track.addEventListener('touchstart', e => { touchStartX = e.touches[0].clientX; }, { passive: true });
    track.addEventListener('touchend', e => {
        const diff = touchStartX - e.changedTouches[0].clientX;
        if (Math.abs(diff) > 50) {
            diff > 0 ? nextSlide() : prevSlide();
        }
    }, { passive: true });

    // Init
    createDots();
    goToSlide(0);
})();
</script>