<?= view('layout_toko/v_navbar.php') ?>

<!-- Promo Section -->
<?php if (!empty($data_banner)): ?>
    <section class="py-5">
        <div class="container">
            <div class="promo-carousel position-relative promo-carousel-container">
                <div id="promoTrack" class="d-flex transition-transform"></div>
                <button id="promoPrev" class="position-absolute top-50 start-0 translate-middle-y btn btn-primary btn-circle">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button id="promoNext" class="position-absolute top-50 end-0 translate-middle-y btn btn-primary btn-circle">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
            <div id="promoDots" class="d-flex justify-content-center gap-2 mt-3"></div>
        </div>
    </section>
<?php endif; ?>

<!-- Products Section -->
<main class="catalog-section">
    <?= view('layout_toko/v_filter_sidebar.php', ['is_desktop' => true]) ?>

    <div class="flex-grow-1">
        <div class="catalog-header d-flex flex-wrap justify-content-between align-items-end gap-2 mb-4">
            <div>
                <h2 class="catalog-title mb-1">Katalog Produk</h2>
                <p class="catalog-subtitle text-muted mb-0">
                    <?= count($produk_data ?? []) ?> produk tersedia
                </p>
            </div>
            <div class="catalog-toolbar">
                <button type="button" class="btn btn-outline-primary btn-sm d-lg-none" onclick="document.getElementById('filterSidebar').scrollIntoView({behavior:'smooth',block:'start'})">
                    <i class="fas fa-filter me-1"></i>Filter
                </button>
            </div>
        </div>

        <div class="product-grid">
            <?= view('layout_toko/v_produk_grid.php', ['produk_data' => $produk_data ?? []]) ?>
        </div>
    </div>
</main>

<script>
(function() {
    const track = document.getElementById('promoTrack');
    const prevBtn = document.getElementById('promoPrev');
    const nextBtn = document.getElementById('promoNext');
    const dotsContainer = document.getElementById('promoDots');

    if (!track || !prevBtn || !nextBtn) return;

    const slides = <?= json_encode($data_banner ?? []) ?>;
    const slideCount = slides.length;
    if (slideCount === 0) return;

    const baseUrlFoto = <?= json_encode(base_url('fotobanner/')) ?>;

    let currentIndex = 0;

    // Create slides
    slides.forEach((banner, i) => {
        const slide = document.createElement('div');
        slide.className = 'flex-shrink-0 w-100';
        const imgTag = `<img src="${baseUrlFoto}${banner.foto_banner}" class="img-fluid promo-image">`;
        slide.innerHTML = `
            <div class="text-center p-4">
                ${banner.link_banner ?
                    `<a href="${banner.link_banner}" target="_blank">${imgTag}</a>` :
                    imgTag}
            </div>
        `;
        track.appendChild(slide);
    });

    // Create dots
    for (let i = 0; i < slideCount; i++) {
        const dot = document.createElement('button');
        dot.className = 'btn rounded-circle' + (i === 0 ? ' active' : '');
        dot.style.width = '10px';
        dot.style.height = '10px';
        dot.setAttribute('aria-label', 'Slide ' + (i + 1));
        dot.addEventListener('click', () => goToSlide(i));
        dotsContainer.appendChild(dot);
    }

    // Style active dot
    function styleDots() {
        document.querySelectorAll('#promoDots button').forEach((dot, i) => {
            dot.style.background = i === currentIndex ? '#0d6efd' : '#dee2e6';
            dot.style.border = 'none';
            dot.style.padding = '0';
            dot.style.margin = '0';
        });
    }
    styleDots();

    function goToSlide(index) {
        currentIndex = index;
        track.style.transform = `translateX(-${index * 100}%)`;
        styleDots();
    }

    function nextSlide() {
        goToSlide((currentIndex + 1) % slideCount);
    }

    prevBtn.addEventListener('click', () => goToSlide((currentIndex - 1 + slideCount) % slideCount));
    nextBtn.addEventListener('click', nextSlide);

    setInterval(nextSlide, 5000);
})();
</script>

<?= view('layout_toko/v_footer.php') ?>
