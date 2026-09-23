<footer class="footer-modern">
  <div class="footer-container">
    <?php
    $ft = get_data_toko();
    $ft_logo_path = 'logowebsite/' . ($ft['logo_website'] ?? '');
    $ft_default_logo = 'fotodefault/logofaaro.png';
    $ft_logo_to_use = file_exists(FCPATH . $ft_logo_path) && !empty($ft['logo_website']) ? $ft_logo_path : $ft_default_logo;
    $ft_ig = $ft['link_IG'] ?? '#';
    $ft_fb = $ft['link_FB'] ?? '#';
    $ft_tt = $ft['link_Tiktok'] ?? '#';
    $ft_wa = $ft['wa_pusat'] ?? '';
    $ft_wa_link = !empty($ft_wa) ? 'https://wa.me/' . preg_replace('/[^0-9]/', '', $ft_wa) : '#';
    ?>
    <div class="footer-col footer-brand">
      <img class="fb-logo" src="<?= base_url($ft_logo_to_use) ?>" alt="Logo <?= esc($ft['nama_toko'] ?? 'Toko') ?>">
      <span class="fb-name"><?= esc($ft['nama_toko'] ?? 'Nama Toko Default') ?></span>
      <p><?= esc($ft['footer_title'] ?? ($ft['nama_toko'] ?? 'Toko online kami')) ?></p>
    </div>

    <div class="footer-col footer-links">
      <h4>Link Cepat</h4>
      <ul>
        <li><a href="<?= base_url('home_toko/index') ?>"><i class="fas fa-chevron-right"></i> Beranda</a></li>
        <li><a href="<?= base_url('home_toko/katalog') ?>"><i class="fas fa-chevron-right"></i> Katalog Produk</a></li>
        <li><a href="<?= base_url('home_toko/syaket') ?>"><i class="fas fa-chevron-right"></i> Syarat &amp; Ketentuan</a></li>
        <li><a href="<?= base_url('home_toko/bantuan') ?>"><i class="fas fa-chevron-right"></i> Bantuan / FAQ</a></li>
      </ul>
    </div>

    <div class="footer-col footer-contact">
      <h4>Hubungi Kami</h4>
      <ul>
        <?php if (!empty($ft['alamat_pusat'])) : ?>
          <li>
            <span class="fc-icon"><i class="fas fa-map-marker-alt"></i></span>
            <span><?= esc($ft['alamat_pusat']) ?></span>
          </li>
        <?php endif; ?>
        <li>
          <span class="fc-icon"><i class="fas fa-phone-alt"></i></span>
          <span>
            <?php if ($ft_wa_link !== '#') : ?>
              <a href="<?= $ft_wa_link ?>" target="_blank"><?= esc($ft_wa) ?></a>
            <?php else : ?>
              -
            <?php endif; ?>
          </span>
        </li>
        <li>
          <span class="fc-icon"><i class="far fa-clock"></i></span>
          <span>Senin - Minggu<br>08.00 - 21.00 WIB</span>
        </li>
      </ul>
    </div>

    <div class="footer-col footer-social">
      <h4>Ikuti Kami</h4>
      <div class="fs-icons">
        <a class="fs-ig" href="<?= esc($ft_ig) ?>" target="_blank" title="Instagram"><i class="fab fa-instagram"></i></a>
        <a class="fs-fb" href="<?= esc($ft_fb) ?>" target="_blank" title="Facebook"><i class="fab fa-facebook-f"></i></a>
        <a class="fs-tt" href="<?= esc($ft_tt) ?>" target="_blank" title="TikTok"><i class="fab fa-tiktok"></i></a>
      </div>
      <div class="footer-extra">
        <?php if ($ft_wa_link !== '#') : ?>
          <a href="<?= $ft_wa_link ?>" target="_blank" style="display:inline-flex;align-items:center;gap:8px;background:rgba(37,211,102,0.15);border:1px solid rgba(37,211,102,0.4);color:#7ee2a6;border-radius:10px;padding:8px 14px;text-decoration:none;font-weight:600;font-size:14px;margin-top:12px;">
            <i class="fab fa-whatsapp" style="font-size:18px;"></i> Chat WhatsApp
          </a>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <div class="footer-bottom">
    &copy; <?= date('Y') ?> <?= esc($ft['nama_toko'] ?? 'Toko Online') ?> <span class="fb-sep">|</span> Dibuat dengan <i class="fas fa-heart"></i> di Platform Multi-Toko
  </div>
</footer>

<?= view('layout_toko/v_chat_popup.php') ?>

</body>

</html>

<!-- Bootstrap  -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Toggle menu mobile navbar
    function toggleNavbar() {
        document.querySelector("header nav").classList.toggle("active");
    }

    // Toggle dropdown saat diklik (untuk mobile)
    document.querySelectorAll('header nav li.dropdown').forEach(dropdown => {
        dropdown.addEventListener('click', function(e) {
            // Pastikan hanya jalankan di layar kecil
            if (window.innerWidth <= 768) {
                e.stopPropagation(); // Hindari propagasi event
                // Tutup semua dropdown dulu
                document.querySelectorAll('header nav li.dropdown').forEach(other => {
                    if (other !== this) {
                        other.classList.remove('active');
                    }
                });
                // Toggle dropdown yang diklik
                this.classList.toggle('active');
            }
        });
    });

    // Tutup dropdown jika klik di luar area menu
    document.addEventListener('click', function(e) {
        if (window.innerWidth <= 768) {
            const dropdowns = document.querySelectorAll('header nav li.dropdown');
            let targetIsDropdown = false;

            dropdowns.forEach(dd => {
                if (dd.contains(e.target)) {
                    targetIsDropdown = true;
                }
            });

            if (!targetIsDropdown) {
                dropdowns.forEach(dd => dd.classList.remove('active'));
            }
        }
    });

    // Optional: Tutup dropdown saat klik di dalam konten dropdown
    document.addEventListener('click', function(e) {
        const dropdownContents = document.querySelectorAll('.dropdown-content');
        dropdownContents.forEach(content => {
            if (content.contains(e.target)) {
                e.stopPropagation();
            }
        });
    });

    // Toggle input pencarian dan ikon pencarian
    function toggleSearchBar() {
        const searchInput = document.querySelector('.search-input');
        const searchIcon = document.querySelector('.search-icon');

        searchInput.classList.toggle('active');

        // Sembunyikan ikon jika input aktif, tampilkan kembali jika tidak
        searchIcon.style.display = searchInput.classList.contains('active') ? 'none' : 'block';
    }

    // Preview gambar saat upload
    function bacaGambar(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#gambar_load').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    $('#preview_gambar').change(function() {
        bacaGambar(this);
    });

    // Toggle show/hide password
    function myFunction() {
        var x = document.getElementById("ShowPass");
        if (x.type === "password") {
            x.type = "text";
        } else {
            x.type = "password";
        }
    }

    // Mencegah pengguna menekan tombol back (history.back())
    history.pushState(null, null, location.href);
    window.onpopstate = function() {
        history.pushState(null, null, location.href);
    };
</script>



<script>
    <?php if (session()->getFlashdata('pesan_welcome')): ?>
        Swal.fire({
            icon: 'success',
            title: '<?= session()->getFlashdata('pesan_welcome') ?>',
            showConfirmButton: false,
            timer: 3000,
            toast: true,
            position: 'top-start',
            background: '#fff',
            color: '#333',
            timerProgressBar: true,
            customClass: {
                popup: 'swal2-toast'
            }
        });
    <?php endif; ?>
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.checkout-btn').forEach(button => {
            button.addEventListener('click', function() {
                let id = this.getAttribute('data-id');
                let checkbox = document.getElementById('checkbox-' + id);
                checkbox.checked = !checkbox.checked; // toggle centang

                if (checkbox.checked) {
                    this.classList.remove('btn-outline-primary');
                    this.classList.add('btn-primary');
                } else {
                    this.classList.remove('btn-primary');
                    this.classList.add('btn-outline-primary');
                }
            });
        });
    });

    function checkoutSelected() {
        // Ambil semua checkbox yang dipilih
        let selectedProducts = [];
        document.querySelectorAll(".checkout-checkbox:checked").forEach((checkbox) => {
            selectedProducts.push(checkbox.value);
        });

        if (selectedProducts.length === 0) {
            alert("Silakan pilih produk yang ingin di-checkout.");
            return;
        }

        // Kirim data ke halaman checkout (gunakan AJAX atau form submit)
        let checkoutUrl = "<?= base_url('pelanggan_kelola_data/beli') ?>";
        let form = document.createElement("form");
        form.method = "POST";
        form.action = checkoutUrl;

        selectedProducts.forEach((id) => {
            let input = document.createElement("input");
            input.type = "hidden";
            input.name = "selected_products[]";
            input.value = id;
            form.appendChild(input);
        });

        document.body.appendChild(form);
        form.submit();
    }
</script>

<script>
    <?php if (session()->getFlashdata('pesan_cart')): ?>
        <?php if (session()->getFlashdata('pesan_cart') == 'Produk berhasil ditambahkan ke keranjang!'): ?>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '<?= session()->getFlashdata('pesan_cart') ?>',
                timer: 2000,
                showConfirmButton: false
            });
        <?php else: ?>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '<?= session()->getFlashdata('pesan_cart') ?>'
            });
        <?php endif; ?>
    <?php endif; ?>
</script>

<script>
    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault(); // Mencegah aksi default

            const id = this.getAttribute('data-id');
            const nama = this.getAttribute('data-nama');

            Swal.fire({
                title: 'Apakah Anda Yakin?',
                text: "Anda akan menghapus produk: " + nama,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '<?= base_url('pelanggan_kelola_data/delete_cart/') ?>' + id;
                }
            });
        });
    });

    <?php if (session()->getFlashdata('hapus_success')): ?>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '<?= session()->getFlashdata('hapus_success') ?>',
            timer: 2000,
            showConfirmButton: false
        });
    <?php endif; ?>
</script>

<script>
    <?php if (session()->getFlashdata('pesan_beli')): ?>
        Swal.fire({
            icon: 'success',
            title: 'Pembelian Berhasil!',
            text: '<?= session()->getFlashdata('pesan_beli') ?>',
            timer: 3000,
            showConfirmButton: false
        });
    <?php endif; ?>
</script>

<script>
    <?php if (session()->getFlashdata('pesan_profil')): ?>
        <?php if (session()->getFlashdata('pesan_profil') == 'Data Profil Berhasil Diubah !!!'): ?>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '<?= session()->getFlashdata('pesan') ?>',
                timer: 2000,
                showConfirmButton: false
            });
        <?php else: ?>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '<?= session()->getFlashdata('pesan') ?>'
            });
        <?php endif; ?>
    <?php endif; ?>
</script>

<script>
// ============================================================
// NAVBAR SCRIPTS (Drawer, Search, Dropdowns, Rating Filter)
// Harus jalan SETELAH v_chat_popup & logoutPelanggan didefinisikan
// ============================================================

// ====== Drawer Menu ======
const navDrawer = document.getElementById('mainNav');
const navBackdrop = document.getElementById('navBackdrop');
const btnMenuToggle = document.getElementById('btnMenuToggle');
const btnCloseDrawer = document.getElementById('btnCloseDrawer');

function openDrawer() {
    navDrawer.classList.add('active');
    navBackdrop.classList.add('active');
    btnMenuToggle.setAttribute('aria-expanded', 'true');
    document.body.style.overflow = 'hidden';
}

function closeDrawer() {
    navDrawer.classList.remove('active');
    navBackdrop.classList.remove('active');
    btnMenuToggle.setAttribute('aria-expanded', 'false');
    document.body.style.overflow = '';
}

function toggleDrawer() {
    if (navDrawer.classList.contains('active')) closeDrawer(); else openDrawer();
}

btnMenuToggle?.addEventListener('click', toggleDrawer);
btnCloseDrawer?.addEventListener('click', closeDrawer);
navBackdrop?.addEventListener('click', closeDrawer);

// Close drawer on link click (mobile)
document.addEventListener('click', function(e) {
    if (window.innerWidth <= 768) {
        const link = e.target.closest('#mainNav a');
        if (link && !link.closest('.drawer-toggle')) closeDrawer();
    }
});

// Close drawer on resize to desktop
window.addEventListener('resize', function() {
    if (window.innerWidth > 768) closeDrawer();
});

// ====== Drawer Submenu (Jenis Produk) ======
document.addEventListener('click', function(e) {
    const toggle = e.target.closest('.drawer-toggle');
    if (toggle) {
        e.preventDefault();
        const parent = toggle.closest('.drawer-dropdown');
        if (parent) {
            const isOpen = parent.classList.toggle('active');
            toggle.setAttribute('aria-expanded', isOpen);
        }
    }
    // Close other dropdowns when clicking outside
    if (!e.target.closest('.drawer-dropdown')) {
        document.querySelectorAll('.drawer-dropdown.active').forEach(d => {
            d.classList.remove('active');
            d.querySelector('.drawer-toggle')?.setAttribute('aria-expanded', 'false');
        });
    }
});

// ====== Mobile Search Overlay ======
const btnSearchToggle = document.getElementById('btnSearchToggle');
const searchOverlay = document.getElementById('searchOverlay');
const mobileSearchInput = document.getElementById('mobileSearchKeyword');

function openSearch() {
    searchOverlay.classList.add('active');
    btnSearchToggle.setAttribute('aria-expanded', 'true');
    document.body.style.overflow = 'hidden';
    setTimeout(() => mobileSearchInput?.focus(), 100);
}

function closeSearch() {
    searchOverlay.classList.remove('active');
    btnSearchToggle.setAttribute('aria-expanded', 'false');
    document.body.style.overflow = '';
}

function toggleSearch() {
    if (searchOverlay.classList.contains('active')) closeSearch(); else openSearch();
}

btnSearchToggle?.addEventListener('click', toggleSearch);
searchOverlay?.addEventListener('click', function(e) {
    if (e.target === searchOverlay) closeSearch();
});

// Close search on Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeSearch();
        closeDrawer();
    }
});

// ====== User Dropdown (Desktop) ======
const userMenuBtn = document.getElementById('userMenuBtn');
const userDropdown = document.getElementById('userDropdown');

userMenuBtn?.addEventListener('click', function(e) {
    e.stopPropagation();
    const isOpen = userDropdown.classList.toggle('active');
    userMenuBtn.setAttribute('aria-expanded', isOpen);
});

document.addEventListener('click', function(e) {
    if (!e.target.closest('.user-menu')) {
        userDropdown?.classList.remove('active');
        userMenuBtn?.setAttribute('aria-expanded', 'false');
    }
});

// ====== Desktop Nav Dropdown (Jenis Produk) ======
document.addEventListener('click', function(e) {
    const toggle = e.target.closest('.header-nav-toggle');
    if (toggle) {
        e.preventDefault();
        const parent = toggle.closest('.header-nav-dropdown');
        if (parent) {
            const isOpen = parent.classList.toggle('active');
            toggle.setAttribute('aria-expanded', isOpen);
        }
        return;
    }
    if (!e.target.closest('.header-nav-dropdown')) {
        document.querySelectorAll('.header-nav-dropdown.active').forEach(function(d) {
            d.classList.remove('active');
            d.querySelector('.header-nav-toggle')?.setAttribute('aria-expanded', 'false');
        });
    }
});

// ====== Rating Filter (Modal) ======
function setRatingFilter(btn, rating) {
    document.querySelectorAll('.star-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    document.getElementById('rating_min').value = rating;
}

function clearRatingFilter() {
    document.querySelectorAll('.star-btn').forEach(b => b.classList.remove('active'));
    document.getElementById('rating_min').value = '';
}
</script>

<script>
    function logoutPelanggan(event) {
        event?.preventDefault();
        const icon = document.getElementById('logout-icon') || document.getElementById('logout-icon-drawer');
        let originalColor = '';
        if (icon) {
            originalColor = icon.style.color;
            icon.style.color = '#3085d6';
        }

        Swal.fire({
            title: 'Yakin ingin logout?',
            text: 'Anda akan keluar dari akun pelanggan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, logout',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "<?= base_url('auth/logout_pelanggan') ?>";
            } else if (icon) {
                icon.style.color = originalColor;
            }
        });
    }
</script>

<script>
    <?php if (session()->getFlashdata('pesan_logout')): ?>
        Swal.fire({
            icon: 'success',
            title: 'Logout Berhasil!',
            text: '<?= session()->getFlashdata('pesan_logout') ?> Anda telah berhasil keluar dari akun pelanggan.',
            timer: 2000,
            showConfirmButton: false
        }).then(() => {
            window.location.href = "<?= base_url('home_toko/index') ?>";
        });
    <?php endif; ?>
</script>

<script>
    <?php if (session()->get('user_logged_in')): ?>
    (function() {
        var timeoutMinutes = 15;
        var warningMinutes = 1;
        var logoutUrl = "<?= base_url('auth/logout_pelanggan') ?>";
        var timeout, warningTimeout, countdownInterval;
        var countdown = warningMinutes * 60;

        function resetTimer() {
            clearTimeout(timeout);
            clearTimeout(warningTimeout);
            clearInterval(countdownInterval);
            countdown = warningMinutes * 60;
            timeout = setTimeout(function() {
                showWarning();
            }, timeoutMinutes * 60 * 1000);
        }

        function showWarning() {
            countdown = warningMinutes * 60;
            Swal.fire({
                title: 'Sesi Akan Berakhir!',
                html: 'Anda akan logout otomatis dalam <strong id="countdown-timer">1:00</strong> menit.<br>Klik tombol di bawah untuk tetap login.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Tetap Login',
                cancelButtonText: 'Logout',
                allowOutsideClick: false,
                allowEscapeKey: false,
                timer: warningMinutes * 60 * 1000,
                timerProgressBar: true,
                willOpen: function() {
                    var timerEl = document.getElementById('countdown-timer');
                    countdownInterval = setInterval(function() {
                        countdown--;
                        var mins = Math.floor(countdown / 60);
                        var secs = countdown % 60;
                        timerEl.textContent = mins + ':' + (secs < 10 ? '0' : '') + secs;
                    }, 1000);
                },
                preConfirm: function() {
                    clearInterval(countdownInterval);
                }
            }).then(function(result) {
                if (result.dismiss === Swal.DismissReason.timer || result.isDismissed) {
                    window.location.href = logoutUrl;
                } else if (result.isConfirmed) {
                    resetTimer();
                }
            });
        }

        ['mousemove', 'keypress', 'click', 'scroll', 'touchstart'].forEach(function(evt) {
            document.addEventListener(evt, resetTimer, { passive: true });
        });

        resetTimer();
    })();
    <?php endif; ?>
</script>