<?php $tokoData = get_data_toko(); ?>
<?= view('layout_toko/v_navbar.php') ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            {{-- Header --}}
            <div class="text-center mb-5" data-aos="fade-up">
                <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3" style="width: 80px; height: 80px; font-size: 2rem; background: rgba(102, 126, 234, 0.1); color: #667eea;">
                    <i class="fas fa-file-contract"></i>
                </div>
                <h1 class="fw-bold mb-2">Syarat &amp; Ketentuan</h1>
                <p class="text-muted">Terakhir diperbarui: <?= date('d F Y') ?></p>
            </div>

            {{-- Intro --}}
            <div class="card border-0 shadow-sm mb-5" data-aos="fade-up" data-aos-delay="100">
                <div class="card-body p-4 p-lg-5">
                    <p class="lead text-muted mb-0">
                        Selamat datang di <strong><?= esc($tokoData['nama_toko'] ?? 'Nama Toko Default') ?></strong>.
                        Dengan mengakses atau menggunakan layanan kami, Anda dianggap telah membaca, memahami, dan menyetujui semua Syarat dan Ketentuan berikut.
                        Mohon baca dengan seksama sebelum melakukan transaksi.
                    </p>
                </div>
            </div>

            {{-- Terms Content --}}
            <div class="card border-0 shadow-sm" data-aos="fade-up" data-aos-delay="200">
                <div class="card-body p-4 p-lg-5">
                    <div class="terms-content">
                        <?php $term_no = 1; ?>
                        <?php foreach ($skt_data as $term): ?>
                            <section id="term-<?= $term_no ?>" class="<?= $term_no < count($skt_data) ? 'mb-5 pb-4 border-bottom' : 'mb-0' ?>">
                                <div class="d-flex align-items-center gap-3 mb-3">
                                    <span class="badge bg-primary rounded-pill px-4 py-2 fs-6"><?= $term_no++ ?></span>
                                    <h3 class="fw-bold mb-0"><?= esc($term['judul']) ?></h3>
                                </div>
                                <?= $term['isi'] ?>
                            </section>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            {{-- Contact CTA --}}
            <div class="text-center mt-5" data-aos="fade-up" data-aos-delay="300">
                <div class="card border-0 shadow-sm" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="card-body p-5 text-white">
                        <h4 class="fw-bold mb-3">Masih Punya Pertanyaan?</h4>
                        <p class="mb-4 opacity-75">Tim layanan pelanggan kami siap membantu Anda kapan saja.</p>
                        <div class="d-flex justify-content-center gap-3 flex-wrap">
                            <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $tokoData['wa_pusat'] ?? '6281234567890') ?>" target="_blank" class="btn btn-light rounded-pill px-4 fw-semibold">
                                <i class="fab fa-whatsapp me-2"></i>Chat WhatsApp
                            </a>
                            <a href="<?= base_url('home_toko/bantuan') ?>" class="btn btn-outline-light rounded-pill px-4 fw-semibold">
                                <i class="fas fa-question-circle me-2"></i>Bantuan / FAQ
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });
    });
</script>

<?= view('layout_toko/v_footer.php') ?>