<?= view('layout_toko/v_navbar.php') ?>

<body>
    <!-- Product Section with Sidebar -->
    <main class="product-section katalog-page">
        <div class="katalog-container">
            <!-- Sidebar Filter (Desktop: always visible, Mobile: slide-in) -->
            <aside class="filter-sidebar-desktop" id="filterSidebar">
                <?= view('layout_toko/v_filter_sidebar', [
                    'filter_params' => $filter_params ?? [],
                    'jenis_produk_dropdown' => $jenis_produk_dropdown ?? [],
                    'is_desktop' => true
                ]) ?>
            </aside>

            <!-- Mobile Overlay -->
            <div class="filter-overlay" id="filterOverlay" aria-hidden="true"></div>

            <!-- Product Grid -->
            <div class="katalog-content">
                <div class="katalog-header">
                    <h3 class="katalog-title">Katalog Produk</h3>
                </div>
                <div class="product-grid">
                    <?= view('layout_toko/v_produk.php') ?>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Mobile filter sidebar toggle
        (function() {
            const filterSidebar = document.getElementById('filterSidebar');
            const filterOverlay = document.getElementById('filterOverlay');
            const closeFilterSidebar = document.getElementById('closeFilterSidebar');

            function openFilterSidebar() {
                filterSidebar.classList.add('active');
                filterOverlay.classList.add('active');
                document.body.style.overflow = 'hidden';
            }

            function closeFilterSidebarFunc() {
                filterSidebar.classList.remove('active');
                filterOverlay.classList.remove('active');
                document.body.style.overflow = '';
            }

            // Open from toggle button
            document.addEventListener('click', function(e) {
                const btn = e.target.closest('[data-filter-toggle]');
                if (btn) {
                    e.preventDefault();
                    openFilterSidebar();
                }
            });

            if (closeFilterSidebar) {
                closeFilterSidebar.addEventListener('click', closeFilterSidebarFunc);
            }
            if (filterOverlay) {
                filterOverlay.addEventListener('click', closeFilterSidebarFunc);
            }

            // Rating filter
            window.setRatingFilter = function(btn, rating) {
                document.querySelectorAll('.star-btn').forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                document.getElementById('rating_min').value = rating;
            };

            window.clearRatingFilter = function() {
                document.querySelectorAll('.star-btn').forEach(b => b.classList.remove('active'));
                document.getElementById('rating_min').value = '';
            };
        })();
    </script>
<?= view('layout_toko/v_footer.php') ?>