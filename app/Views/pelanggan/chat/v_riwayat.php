<?= view('layout_toko/v_navbar.php') ?>

<body>
<main class="product-section">
    <div class="container my-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-bold">
                <i class="fas fa-comments text-primary"></i> Pesan Saya
            </div>
            <div class="card-body p-0">
                <?php if (empty($chat_list)): ?>
                    <div class="text-center text-muted py-5">
                        <i class="far fa-comment-dots fa-3x d-block mb-2"></i>
                        Belum ada percakapan. Buka halaman produk lalu klik <strong>Chat Penjual</strong>.
                    </div>
                <?php else: ?>
                    <ul class="list-group list-group-flush">
                        <?php foreach ($chat_list as $c): ?>
                        <li class="list-group-item d-flex align-items-center gap-3">
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold flex-shrink-0" style="width:42px;height:42px;">
                                <i class="fas fa-store"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-bold"><?= esc($c['nama_produk']) ?></div>
                                <div class="small text-muted">
                                    <?php $nama_terakhir = $c['nama_pengirim'] ?: ($c['pengirim_terakhir'] === 'admin' ? 'Admin' : 'Anda'); ?>
                                    <span class="<?= $c['pengirim_terakhir'] === 'admin' ? 'text-success' : 'text-primary' ?>"><?= esc($nama_terakhir) ?>:</span>
                                    <?= esc(mb_strimwidth($c['pesan_terakhir'], 0, 80, '...')) ?>
                                    <span class="text-secondary">· <?= esc($c['waktu_terakhir']) ?></span>
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2 flex-shrink-0">
                                <?php if ($c['unread'] > 0): ?>
                                    <span class="badge rounded-pill bg-danger"><?= $c['unread'] ?> baru</span>
                                <?php endif; ?>
                                <a href="#" onclick="bukaChatProduk(event, <?= esc(json_encode($c['nama_produk']), 'attr') ?>, <?= (int)($c['id_stok'] ?? 0) ?>)" class="btn btn-sm btn-primary">
                                    <i class="fas fa-comments"></i> Buka
                                </a>
                            </div>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<?= view('layout_toko/v_footer.php') ?>
