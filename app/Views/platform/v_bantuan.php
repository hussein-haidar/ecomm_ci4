<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bantuan / FAQ Platform</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css">
    <style>
        body { background: #f8f9fc; font-family: 'Segoe UI', system-ui, sans-serif; color: #333; }
        .plata-topbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            padding: 18px 0;
        }
        .plata-topbar .container { display: flex; align-items: center; justify-content: space-between; }
        .plata-brand { font-weight: 700; font-size: 1.15rem; color: #fff; text-decoration: none; }
        .plata-brand i { margin-right: 8px; }
        .plata-nav a { color: rgba(255,255,255,.85); text-decoration: none; margin-left: 18px; font-weight: 500; }
        .plata-nav a:hover { color: #fff; }
        .hero-icon {
            width: 80px; height: 80px; font-size: 2rem; display: inline-flex;
            align-items: center; justify-content: center; border-radius: 50%;
            background: rgba(102,126,234,.1); color: #667eea;
        }
        .accordion-button:not(.collapsed) {
            background: #eef0ff; color: #4c3fa8;
            box-shadow: none;
        }
        .faq-item .accordion-body { color: #555; }
        .plata-footer {
            background: #1e2340; color: rgba(255,255,255,.7); padding: 28px 0; margin-top: 60px;
        }
        .plata-footer a { color: rgba(255,255,255,.7); text-decoration: none; }
        .plata-footer a:hover { color: #fff; }
    </style>
</head>
<body>

<div class="plata-topbar">
    <div class="container">
        <a class="plata-brand" href="<?= base_url('home_toko/index') ?>"><i class="fas fa-store"></i> Platform Toko</a>
        <div class="plata-nav">
            <a href="<?= base_url('platform_kebijakan/bantuan') ?>">Bantuan / FAQ</a>
            <a href="<?= base_url('platform_kebijakan/syaket') ?>">Syarat &amp; Ketentuan</a>
        </div>
    </div>
</div>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="text-center mb-5" data-aos="fade-up">
                <div class="hero-icon mb-3"><i class="fas fa-question-circle"></i></div>
                <h1 class="fw-bold mb-2">Bantuan / FAQ Platform</h1>
                <p class="text-muted">Jawaban atas pertanyaan umum seputar penggunaan platform dan belanja lintas toko.</p>
            </div>

            <div class="card border-0 shadow-sm mb-4" data-aos="fade-up" data-aos-delay="100">
                <div class="card-body p-4">
                    <div class="position-relative">
                        <i class="fas fa-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                        <input type="text" id="faqSearch" class="form-control form-control-lg ps-5" placeholder="Cari pertanyaan...">
                    </div>
                </div>
            </div>

            <div class="d-flex flex-wrap gap-2 justify-content-center mb-5" data-aos="fade-up" data-aos-delay="200" id="faqCategories">
                <button class="btn btn-primary rounded-pill px-4 active" data-category="all">Semua</button>
                <button class="btn btn-outline-primary rounded-pill px-4" data-category="pemesanan">Pemesanan</button>
                <button class="btn btn-outline-primary rounded-pill px-4" data-category="pembayaran">Pembayaran</button>
                <button class="btn btn-outline-primary rounded-pill px-4" data-category="pengiriman">Pengiriman</button>
                <button class="btn btn-outline-primary rounded-pill px-4" data-category="retur">Retur &amp; Garansi</button>
                <button class="btn btn-outline-primary rounded-pill px-4" data-category="akun">Akun &amp; Profil</button>
            </div>

            <div class="accordion accordion-flush" id="faqAccordion">
                <?php
                $faq_groups = [];
                foreach ($faq_data as $faqItem) {
                    $faq_groups[$faqItem['kategori']][] = [
                        'q' => $faqItem['pertanyaan'],
                        'a' => nl2br($faqItem['jawaban']),
                    ];
                }
                ?>
                <?php foreach ($faq_groups as $category => $items): ?>
                    <?php foreach ($items as $index => $faq): ?>
                        <div class="accordion-item faq-item" data-category="<?= $category ?>">
                            <h2 class="accordion-header" id="heading<?= ucfirst($category) ?><?= $index + 1 ?>">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= ucfirst($category) ?><?= $index + 1 ?>" aria-expanded="false" aria-controls="collapse<?= ucfirst($category) ?><?= $index + 1 ?>">
                                    <?= esc($faq['q']) ?>
                                </button>
                            </h2>
                            <div id="collapse<?= ucfirst($category) ?><?= $index + 1 ?>" class="accordion-collapse collapse" aria-labelledby="heading<?= ucfirst($category) ?><?= $index + 1 ?>" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    <?= $faq['a'] ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </div>

            <div id="faqNoResults" class="text-center py-5 d-none">
                <i class="fas fa-search fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Tidak Ada Hasil</h5>
                <p class="text-muted">Coba kata kunci lain atau pilih kategori berbeda.</p>
            </div>

            <div class="card border-0 shadow-sm mt-5" data-aos="fade-up" data-aos-delay="300" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body p-5 text-white text-center">
                    <h4 class="fw-bold mb-3">Masih Butuh Bantuan?</h4>
                    <p class="mb-4 opacity-75">Hubungi toko penjual yang bersangkutan atau kirim email ke kami.</p>
                    <a href="<?= base_url('home_toko/index') ?>" class="btn btn-light rounded-pill px-4 fw-semibold">
                        <i class="fas fa-store me-2"></i>Jelajahi Toko
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="plata-footer">
    <div class="container text-center">
        <p class="mb-0">&copy; <?= date('Y') ?> Platform Toko. Semua hak dilindungi.</p>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof AOS !== 'undefined') { AOS.init({ duration: 800, easing: 'ease-out-cubic', once: true, offset: 100 }); }
    });

    const searchInput = document.getElementById('faqSearch');
    const categoryButtons = document.querySelectorAll('#faqCategories button');
    const faqItems = document.querySelectorAll('.faq-item');
    const noResults = document.getElementById('faqNoResults');

    let currentCategory = 'all';
    let searchTerm = '';

    function filterFAQs() {
        let visibleCount = 0;
        faqItems.forEach(item => {
            const category = item.dataset.category;
            const question = item.querySelector('.accordion-button').textContent.toLowerCase();
            const answer = item.querySelector('.accordion-body').textContent.toLowerCase();
            const text = question + ' ' + answer;

            const matchesCategory = currentCategory === 'all' || category === currentCategory;
            const matchesSearch = searchTerm === '' || text.includes(searchTerm);

            if (matchesCategory && matchesSearch) {
                item.style.display = '';
                visibleCount++;
            } else {
                item.style.display = 'none';
            }
        });

        noResults.style.display = visibleCount === 0 ? 'block' : 'none';
    }

    searchInput.addEventListener('input', function() {
        searchTerm = this.value.toLowerCase().trim();
        filterFAQs();
    });

    categoryButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            categoryButtons.forEach(button => {
                button.classList.remove('active');
                button.classList.remove('btn-primary');
                button.classList.add('btn-outline-primary');
            });
            this.classList.add('active');
            this.classList.remove('btn-outline-primary');
            this.classList.add('btn-primary');
            currentCategory = this.dataset.category;
            filterFAQs();
        });
    });
</script>

</body>
</html>