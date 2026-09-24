<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Syarat &amp; Ketentuan Platform</title>
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
        .terms-content .badge { min-width: 38px; }
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
                <div class="hero-icon mb-3"><i class="fas fa-file-contract"></i></div>
                <h1 class="fw-bold mb-2">Syarat &amp; Ketentuan Platform</h1>
                <p class="text-muted">Terakhir diperbarui: <?= date('d F Y') ?></p>
            </div>

            <div class="card border-0 shadow-sm mb-5" data-aos="fade-up" data-aos-delay="100">
                <div class="card-body p-4 p-lg-5">
                    <p class="lead text-muted mb-0">
                        Selamat datang di <strong>Platform Toko</strong>. Dengan mengakses atau menggunakan layanan platform, Anda dianggap telah membaca, memahami, dan menyetujui Syarat dan Ketentuan berikut yang berlaku lintas toko.
                        Kebijakan spesifik (ongkir, garansi, retur) masing-masing toko tetap mengikuti ketentuan yang ditetapkan penjual.
                    </p>
                </div>
            </div>

            <div class="card border-0 shadow-sm" data-aos="fade-up" data-aos-delay="200">
                <div class="card-body p-4 p-lg-5">
                    <div class="terms-content">
                        <?php $term_no = 1; ?>
                        <?php foreach ($skt_data as $term): ?>
                            <section id="term-<?= $term_no ?>" class="<?= $term_no < count($skt_data) ? 'mb-5 pb-4 border-bottom' : 'mb-0' ?>">
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <span class="badge bg-primary rounded-pill px-3 py-2 fs-6"><?= $term_no++ ?></span>
                                    <h3 class="fw-bold mb-0"><?= esc($term['judul']) ?></h3>
                                </div>
                                <?= $term['isi'] ?>
                            </section>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <div class="text-center mt-5" data-aos="fade-up" data-aos-delay="300">
                <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="card-body p-5 text-white">
                        <h4 class="fw-bold mb-3">Masih Punya Pertanyaan?</h4>
                        <p class="mb-4 opacity-75">Kunjungi halaman Bantuan / FAQ atau jelajahi toko penjual untuk kebijakan spesifik.</p>
                        <div class="d-flex justify-content-center gap-3 flex-wrap">
                            <a href="<?= base_url('platform_kebijakan/bantuan') ?>" class="btn btn-light rounded-pill px-4 fw-semibold">
                                <i class="fas fa-question-circle me-2"></i>Bantuan / FAQ
                            </a>
                            <a href="<?= base_url('home_toko/index') ?>" class="btn btn-outline-light rounded-pill px-4 fw-semibold">
                                <i class="fas fa-store me-2"></i>Jelajahi Toko
                            </a>
                        </div>
                    </div>
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
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) { target.scrollIntoView({ behavior: 'smooth', block: 'start' }); }
            });
        });
    });
</script>

</body>
</html>