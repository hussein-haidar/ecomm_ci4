<footer>
    <div class="footer-content">
        <div>
            <h4>Tentang Kami</h4>
            <p><?= get_data_toko()['footer_title'] ?? 'Nama Toko Default'; ?></p>
        </div>
        <div>
            <h4>Link Cepat</h4>
            <ul>
                <li><a href="<?= base_url('home_toko/syaket') ?>">Syarat & Ketentuan</a></li>
                <li><a href="<?= base_url('home_toko/bantuan') ?>">Bantuan / FAQ</a></li>
            </ul>
        </div>
        <div class="marketplace">
            <h4>Temukan Kami di Sosial Media</h4>
            <ul>

                <li>
                    <a href="<?= get_data_toko()['link_IG'] ?? '#' ?>" target="_blank">
                        <img src="https://img.icons8.com/fluency/24/instagram-new.png" alt="Instagram" style="vertical-align: middle; margin-right: 8px;">
                        Instagram
                    </a>
                </li>
                <li>
                    <a href="<?= get_data_toko()['link_FB'] ?? '#' ?>" target="_blank">
                        <img src="https://img.icons8.com/fluency/24/facebook-new.png" alt="Facebook" style="vertical-align: middle; margin-right: 8px;">
                        Facebook
                    </a>
                </li>
                <li>
                    <a href="<?= get_data_toko()['link_Tiktok'] ?? '#' ?>" target="_blank">
                        <img src="https://img.icons8.com/fluency/24/tiktok.png" alt="TikTok" style="vertical-align: middle; margin-right: 8px;">
                        Tiktok
                    </a>
                </li>
            </ul>
        </div>
    </div>
</footer>

<?= view('layout_toko/v_chat_popup.php') ?>

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
    let currentIndex = 0;
    const images = document.querySelectorAll('#carousel img');

    function showImage(index) {
        images.forEach(img => img.classList.remove('active'));
        images[index].classList.add('active');
    }

    function nextImage() {
        currentIndex = (currentIndex + 1) % images.length;
        showImage(currentIndex);
    }

    function prevImage() {
        currentIndex = (currentIndex - 1 + images.length) % images.length;
        showImage(currentIndex);
    }

    // Auto-slide setiap 3 detik
    setInterval(nextImage, 3000);

    // Tampilkan gambar pertama pas awal load
    showImage(currentIndex);
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
    function logoutPelanggan(event) {
        const icon = document.getElementById('logout-icon');

        // Simpan warna asli
        const originalColor = icon.style.color;

        // Ubah warna sementara (jika perlu)
        icon.style.color = '#3085d6'; // warna biru

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
            } else {
                // Kembalikan warna ikon ke semula jika dibatalkan
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