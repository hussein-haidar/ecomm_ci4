-- =============================================================
-- Tabel Kebijakan Toko: FAQ (Bantuan) & Syarat Ketentuan
-- Data per toko (dipisahkan via kolom sesi_user)
-- =============================================================

CREATE TABLE IF NOT EXISTS tbl_faq (
  id_faq INT(11) NOT NULL AUTO_INCREMENT,
  sesi_user VARCHAR(255) NOT NULL,
  kategori VARCHAR(50) NOT NULL,
  pertanyaan TEXT NOT NULL,
  jawaban TEXT NOT NULL,
  urutan INT(11) DEFAULT 0,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id_faq),
  KEY idx_sesi_user (sesi_user),
  KEY idx_kategori (kategori)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS tbl_syarat_ketentuan (
  id_skt INT(11) NOT NULL AUTO_INCREMENT,
  sesi_user VARCHAR(255) NOT NULL,
  judul VARCHAR(255) NOT NULL,
  isi TEXT NOT NULL,
  urutan INT(11) DEFAULT 0,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id_skt),
  KEY idx_sesi_user (sesi_user)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- =============================================================
-- Seed default (disalin dari v_bantuan.php & v_syaket.php)
-- untuk toko yang sudah ada. Menjalankan file ini sekali saja
-- (bila tabel sudah berisi, seed tidak dijalankan lagi).
-- =============================================================

SET @user_agus = 'Agus';
SET @user_tantowi = 'Tantowi';

INSERT INTO tbl_faq (sesi_user, kategori, pertanyaan, jawaban, urutan)
SELECT * FROM (
  SELECT @user_agus AS sesi_user, 'pemesanan' AS kategori, 'Bagaimana cara melakukan pemesanan produk?' AS pertanyaan,
    'Pilih produk yang Anda inginkan melalui halaman katalog, lalu klik tombol "Beli Sekarang" atau masukkan ke keranjang. Setelah itu lanjutkan ke halaman checkout, lengkapi data pemesanan, dan pilih metode pembayaran yang tersedia. Pesanan akan otomatis terbentuk setelah selesai checkout.' AS jawaban, 1 AS urutan
  UNION ALL SELECT @user_agus, 'pemesanan', 'Apakah saya harus membuat akun terlebih dahulu?', 'Ya, Anda disarankan membuat akun (register) agar dapat melacak status pesanan, riwayat pembelian, dan mempermudah proses retur/pengembalian. Pendaftaran gratis dan hanya membutuhkan nomor WhatsApp atau email yang aktif.', 2
  UNION ALL SELECT @user_agus, 'pemesanan', 'Berapa lama batas waktu pembayaran setelah pemesanan?', 'Batas waktu pembayaran adalah 10 menit untuk transfer manual, dan mengikuti timer yang ditentukan oleh Midtrans untuk metode pembayaran Instant (VA, E-Wallet, QRIS). Apabila melewati batas, pesanan otomatis dibatalkan oleh sistem.', 3
  UNION ALL SELECT @user_agus, 'pemesanan', 'Bagaimana jika pemesanan saya otomatis dibatalkan?', 'Pesanan yang batal otomatis tidak akan dihitung oleh sistem. Anda cukup membuat pesanan baru. Jika pembayaran sudah terlanjur ditransfer, segera hubungi kami melalui halaman kontak dengan menyertakan bukti transfer.', 4
  UNION ALL SELECT @user_agus, 'pembayaran', 'Metode pembayaran apa saja yang tersedia?', 'Pembayaran dapat dilakukan via Midtrans Payment Gateway: Transfer Bank (BCA, BRI, BNI, Mandiri, Permata), Virtual Account, E-Wallet (GoPay, ShopeePay, Dana, OVO, LinkAja), QRIS, serta COD (Bayar di Tempat) untuk area tertentu.', 1
  UNION ALL SELECT @user_agus, 'pembayaran', 'Bagaimana cara mengunggah bukti pembayaran transfer manual?', 'Setelah melakukan transfer, buka menu "Status Bayar", pilih pesanan yang dimaksud, lalu unggah bukti transfer (format gambar). Admin akan memverifikasi bukti tersebut dan mengonfirmasi pembayaran Anda.', 2
  UNION ALL SELECT @user_agus, 'pembayaran', 'Berapa lama verifikasi pembayaran?', 'Verifikasi bukti pembayaran dilakukan oleh admin dan biasanya selesai kurang dari 1x24 jam pada jam kerja. Setelah terverifikasi, status pesanan berubah menjadi "Dibayar" dan segera diproses pengirimannya.', 3
  UNION ALL SELECT @user_agus, 'pembayaran', 'Apakah pembayaran aman?', 'Sangat aman. Pembayaran yang melalui Midtrans diproses dengan standar keamanan PCI-DSS. Kami tidak pernah menyimpan data kartu Anda. Pembayaran COD juga baru dilakukan saat barang diterima.', 4
  UNION ALL SELECT @user_agus, 'pengiriman', 'Kapan pesanan saya dikirim?', 'Pesanan dikirim setelah pembayaran terverifikasi oleh admin. Proses pengemasan biasanya membutuhkan waktu 1-2 hari kerja, lalu dilanjutkan ke ekspedisi yang Anda pilih.', 1
  UNION ALL SELECT @user_agus, 'pengiriman', 'Bagaimana cara melacak pengiriman?', 'Nomor resi dan link tracking akan dikirimkan ke WhatsApp dan tersedia di menu "Pengiriman". Anda bisa memantau posisi paket melalui website ekspedisi terkait (JNE, J&T, SiCepat, Ninja, atau Kurir Lokal).', 2
  UNION ALL SELECT @user_agus, 'pengiriman', 'Apakah bisa memilih kurir sendiri?', 'Ya, pada halaman checkout Anda dapat memilih jasa ekspedisi beserta estimasi ongkir yang ditampilkan. Biaya pengiriman dihitung otomatis berdasarkan berat dan tujuan pengiriman.', 3
  UNION ALL SELECT @user_agus, 'pengiriman', 'Bagaimana jika alamat pengiriman salah?', 'Jika paket belum dikirim, segera hubungi kami untuk perbaikan alamat. Namun apabila status sudah "Dikirim", alamat tidak dapat diubah. Pastikan alamat Anda lengkap dan benar saat checkout.', 4
  UNION ALL SELECT @user_agus, 'retur', 'Kapan saya bisa mengajukan retur?', 'Retur dapat diajukan maksimal 2x24 jam setelah barang diterima, jika produk rusak/pecah, cacat pabrik, tidak sesuai pesanan, atau kedaluwarsa untuk produk konsumsi. Sertakan foto/video bukti saat mengajukan.', 1
  UNION ALL SELECT @user_agus, 'retur', 'Bagaimana alur pengajuan retur?', 'Ajukan retur melalui menu "Pengiriman" atau hubungi Chat Penjual dengan menyertakan bukti kerusakan. Admin akan memverifikasi, lalu memberikan instruksi pengembalian paket. Setelah barang diterima dan dicek, penggantian/pengembalian dana diproses.', 2
  UNION ALL SELECT @user_agus, 'retur', 'Siapa yang menanggung biaya retur?', 'Biaya pengiriman ulang ditanggung oleh toko apabila kesalahan berasal dari toko atau ekspedisi. Namun jika retur karena alasan pribadi (salah pilih, tidak suka dsb), biaya ditanggung pembeli.', 3
  UNION ALL SELECT @user_agus, 'retur', 'Produk apa saja yang tidak bisa diretur?', 'Produk custom/personalisasi, produk higiene (celana dalam, masker, dll), produk digital/voucher, serta produk promo/flash sale tidak dapat diretur kecuali dalam kondisi rusak atau keliru pengiriman.', 4
  UNION ALL SELECT @user_agus, 'akun', 'Bagaimana cara membuat akun?', 'Klik menu "Daftar" atau "Register" pada halaman login, lengkapi data yang dibutuhkan, lalu konfirmasi. Anda akan langsung bisa berbelanja dan memantau pesanan.', 1
  UNION ALL SELECT @user_agus, 'akun', 'Bagaimana jika saya lupa password?', 'Klik "Lupa Password?" pada halaman login, masukkan email/nomor yang terdaftar, lalu ikuti instruksi reset yang dikirimkan ke kontak Anda.', 2
  UNION ALL SELECT @user_agus, 'akun', 'Bagaimana cara memperbarui data profil?', 'Masuk ke akun Anda, buka menu "Profil", lalu ubah data yang diinginkan (nama, alamat, nomor telepon, dsb). Simpan perubahan setelah selesai.', 3
  UNION ALL SELECT @user_agus, 'akun', 'Apakah riwayat transaksi saya tersimpan?', 'Ya, semua riwayat pesanan, pembayaran, dan pengiriman tersimpan di akun Anda dan dapat dilihat kapan saja melalui menu terkait.', 4
) AS seed;

INSERT INTO tbl_faq (sesi_user, kategori, pertanyaan, jawaban, urutan)
SELECT * FROM (
  SELECT @user_tantowi AS sesi_user, 'pemesanan' AS kategori, 'Bagaimana cara melakukan pemesanan produk?' AS pertanyaan,
    'Pilih produk yang Anda inginkan melalui halaman katalog, lalu klik tombol "Beli Sekarang" atau masukkan ke keranjang. Setelah itu lanjutkan ke halaman checkout, lengkapi data pemesanan, dan pilih metode pembayaran yang tersedia. Pesanan akan otomatis terbentuk setelah selesai checkout.' AS jawaban, 1 AS urutan
  UNION ALL SELECT @user_tantowi, 'pemesanan', 'Apakah saya harus membuat akun terlebih dahulu?', 'Ya, Anda disarankan membuat akun (register) agar dapat melacak status pesanan, riwayat pembelian, dan mempermudah proses retur/pengembalian. Pendaftaran gratis dan hanya membutuhkan nomor WhatsApp atau email yang aktif.', 2
  UNION ALL SELECT @user_tantowi, 'pemesanan', 'Berapa lama batas waktu pembayaran setelah pemesanan?', 'Batas waktu pembayaran adalah 10 menit untuk transfer manual, dan mengikuti timer yang ditentukan oleh Midtrans untuk metode pembayaran Instant (VA, E-Wallet, QRIS). Apabila melewati batas, pesanan otomatis dibatalkan oleh sistem.', 3
  UNION ALL SELECT @user_tantowi, 'pemesanan', 'Bagaimana jika pemesanan saya otomatis dibatalkan?', 'Pesanan yang batal otomatis tidak akan dihitung oleh sistem. Anda cukup membuat pesanan baru. Jika pembayaran sudah terlanjur ditransfer, segera hubungi kami melalui halaman kontak dengan menyertakan bukti transfer.', 4
  UNION ALL SELECT @user_tantowi, 'pembayaran', 'Metode pembayaran apa saja yang tersedia?', 'Pembayaran dapat dilakukan via Midtrans Payment Gateway: Transfer Bank (BCA, BRI, BNI, Mandiri, Permata), Virtual Account, E-Wallet (GoPay, ShopeePay, Dana, OVO, LinkAja), QRIS, serta COD (Bayar di Tempat) untuk area tertentu.', 1
  UNION ALL SELECT @user_tantowi, 'pembayaran', 'Bagaimana cara mengunggah bukti pembayaran transfer manual?', 'Setelah melakukan transfer, buka menu "Status Bayar", pilih pesanan yang dimaksud, lalu unggah bukti transfer (format gambar). Admin akan memverifikasi bukti tersebut dan mengonfirmasi pembayaran Anda.', 2
  UNION ALL SELECT @user_tantowi, 'pembayaran', 'Berapa lama verifikasi pembayaran?', 'Verifikasi bukti pembayaran dilakukan oleh admin dan biasanya selesai kurang dari 1x24 jam pada jam kerja. Setelah terverifikasi, status pesanan berubah menjadi "Dibayar" dan segera diproses pengirimannya.', 3
  UNION ALL SELECT @user_tantowi, 'pembayaran', 'Apakah pembayaran aman?', 'Sangat aman. Pembayaran yang melalui Midtrans diproses dengan standar keamanan PCI-DSS. Kami tidak pernah menyimpan data kartu Anda. Pembayaran COD juga baru dilakukan saat barang diterima.', 4
  UNION ALL SELECT @user_tantowi, 'pengiriman', 'Kapan pesanan saya dikirim?', 'Pesanan dikirim setelah pembayaran terverifikasi oleh admin. Proses pengemasan biasanya membutuhkan waktu 1-2 hari kerja, lalu dilanjutkan ke ekspedisi yang Anda pilih.', 1
  UNION ALL SELECT @user_tantowi, 'pengiriman', 'Bagaimana cara melacak pengiriman?', 'Nomor resi dan link tracking akan dikirimkan ke WhatsApp dan tersedia di menu "Pengiriman". Anda bisa memantau posisi paket melalui website ekspedisi terkait (JNE, J&T, SiCepat, Ninja, atau Kurir Lokal).', 2
  UNION ALL SELECT @user_tantowi, 'pengiriman', 'Apakah bisa memilih kurir sendiri?', 'Ya, pada halaman checkout Anda dapat memilih jasa ekspedisi beserta estimasi ongkir yang ditampilkan. Biaya pengiriman dihitung otomatis berdasarkan berat dan tujuan pengiriman.', 3
  UNION ALL SELECT @user_tantowi, 'pengiriman', 'Bagaimana jika alamat pengiriman salah?', 'Jika paket belum dikirim, segera hubungi kami untuk perbaikan alamat. Namun apabila status sudah "Dikirim", alamat tidak dapat diubah. Pastikan alamat Anda lengkap dan benar saat checkout.', 4
  UNION ALL SELECT @user_tantowi, 'retur', 'Kapan saya bisa mengajukan retur?', 'Retur dapat diajukan maksimal 2x24 jam setelah barang diterima, jika produk rusak/pecah, cacat pabrik, tidak sesuai pesanan, atau kedaluwarsa untuk produk konsumsi. Sertakan foto/video bukti saat mengajukan.', 1
  UNION ALL SELECT @user_tantowi, 'retur', 'Bagaimana alur pengajuan retur?', 'Ajukan retur melalui menu "Pengiriman" atau hubungi Chat Penjual dengan menyertakan bukti kerusakan. Admin akan memverifikasi, lalu memberikan instruksi pengembalian paket. Setelah barang diterima dan dicek, penggantian/pengembalian dana diproses.', 2
  UNION ALL SELECT @user_tantowi, 'retur', 'Siapa yang menanggung biaya retur?', 'Biaya pengiriman ulang ditanggung oleh toko apabila kesalahan berasal dari toko atau ekspedisi. Namun jika retur karena alasan pribadi (salah pilih, tidak suka dsb), biaya ditanggung pembeli.', 3
  UNION ALL SELECT @user_tantowi, 'retur', 'Produk apa saja yang tidak bisa diretur?', 'Produk custom/personalisasi, produk higiene (celana dalam, masker, dll), produk digital/voucher, serta produk promo/flash sale tidak dapat diretur kecuali dalam kondisi rusak atau keliru pengiriman.', 4
  UNION ALL SELECT @user_tantowi, 'akun', 'Bagaimana cara membuat akun?', 'Klik menu "Daftar" atau "Register" pada halaman login, lengkapi data yang dibutuhkan, lalu konfirmasi. Anda akan langsung bisa berbelanja dan memantau pesanan.', 1
  UNION ALL SELECT @user_tantowi, 'akun', 'Bagaimana jika saya lupa password?', 'Klik "Lupa Password?" pada halaman login, masukkan email/nomor yang terdaftar, lalu ikuti instruksi reset yang dikirimkan ke kontak Anda.', 2
  UNION ALL SELECT @user_tantowi, 'akun', 'Bagaimana cara memperbarui data profil?', 'Masuk ke akun Anda, buka menu "Profil", lalu ubah data yang diinginkan (nama, alamat, nomor telepon, dsb). Simpan perubahan setelah selesai.', 3
  UNION ALL SELECT @user_tantowi, 'akun', 'Apakah riwayat transaksi saya tersimpan?', 'Ya, semua riwayat pesanan, pembayaran, dan pengiriman tersimpan di akun Anda dan dapat dilihat kapan saja melalui menu terkait.', 4
) AS seed;

-- Syarat Ketentuan (10 section, sama untuk Agus & Tantowi)
INSERT INTO tbl_syarat_ketentuan (sesi_user, judul, isi, urutan)
SELECT * FROM (
  SELECT @user_agus AS sesi_user, 'Informasi Produk' AS judul,
    'Kami berusaha untuk menampilkan informasi produk (deskripsi, harga, gambar, spesifikasi) seakurat mungkin. Namun, kesalahan pengetikan, ketidaksesuaian warna akibat tampilan layar, atau perubahan spesifikasi tanpa pemberitahuan sebelumnya dapat terjadi.' AS isi, 1 AS urutan
  UNION ALL SELECT @user_agus, 'Pemesanan', 'Pemesanan dilakukan melalui sistem keranjang belanja di website. Pembeli wajib mengisi data pemesanan (nama, alamat, nomor telepon) dengan benar, lengkap, dan dapat dihubungi. Data pemesanan yang tidak valid dapat menyebabkan pembatalan pesanan. Pesanan otomatis dibatalkan jika pembayaran tidak dikonfirmasi dalam batas waktu yang ditentukan.', 2
  UNION ALL SELECT @user_agus, 'Pembayaran', 'Pembayaran dapat dilakukan melalui metode yang tersedia di halaman checkout: Transfer Bank / Virtual Account / E-Wallet melalui Midtrans Payment Gateway (BCA, BRI, BNI, Mandiri, Permata, dll + GoPay, ShopeePay, Dana, OVO, LinkAja), serta COD (Bayar di Tempat) untuk area tertentu dengan batasan nilai transaksi. Bukti pembayaran transfer manual wajib diunggah ke halaman Status Bayar untuk verifikasi admin. Biaya administrasi/transfer (jika ada) ditanggung pembeli.', 3
  UNION ALL SELECT @user_agus, 'Pengiriman', 'Pengiriman dilakukan setelah pembayaran terverifikasi dan dikonfirmasi oleh admin/toko. Waktu pengiriman tergantung lokasi tujuan dan jasa ekspedisi (JNE, J&T, SiCepat, Ninja, Kurir Lokal). Estimasi waktu (ETD) yang ditampilkan hanyalah perkiraan. Nomor resi dan link tracking akan dikirimkan ke WhatsApp/email dan tersedia di menu Pengiriman. Jika paket rusak/hilang di jalan, klaim diajukan ke ekspedisi terkait. Alamat pengiriman tidak dapat diubah setelah status Dikirim.', 4
  UNION ALL SELECT @user_agus, 'Retur, Pengembalian & Penggantian', 'Retur diterima maksimal 2x24 jam setelah produk diterima jika produk rusak/pecah/cacat pabrik, tidak sesuai pesanan, atau kedaluwarsa. Produk harus dalam kondisi asli, tag/label/packaging utuh. Proses: foto/video bukti, ajukan lewat menu Pengiriman / Chat Penjual, verifikasi, lalu instruksi pengembalian. Biaya retur dibatalkan oleh toko jika kesalahan dari toko/ekspedisi, ditanggung pembeli jika alasan pribadi. Penggantian prioritas pengiriman ulang; jika stok habis, pengembalian dana full. Produk custom/higiene/digital tidak dapat diretur.', 5
  UNION ALL SELECT @user_agus, 'Garansi Produk', 'Garansi resmi mengikuti kebijakan pabrikan/distributor resmi masing-masing brand. Klaim garansi memerlukan nota/resi pembelian asli dan barang dalam kondisi sesuai ketentuan. Garansi tidak berlaku untuk kerusakan akibat salah penggunaan, jatuh, cairan, modifikasi, bencana alam, atau pemakaian normal.', 6
  UNION ALL SELECT @user_agus, 'Privasi & Data Pribadi', 'Data pribadi Anda (nama, alamat, telepon, email, koordinat lokasi) hanya digunakan untuk proses pemesanan, pembayaran, pengiriman, komunikasi terkait transaksi, peningkatan layanan, analitik, keamanan, dan kepatuhan hukum. Kami tidak menjual data pribadi ke pihak ketiga.', 7
  UNION ALL SELECT @user_agus, 'Hak Kekayaan Intelektual', 'Semua konten di website (logo, nama brand, desain UI, foto produk, deskripsi, kode program) adalah hak milik rumah toko/penjual atau mitra reseller yang berhak. Dilarang menyalin, mendistribusikan, atau memodifikasi tanpa izin tertulis.', 8
  UNION ALL SELECT @user_agus, 'Batas Tanggung Jawab', 'Kami tidak bertanggung jawab atas keterlambatan/pengiriman gagal akibat force majeure atau kebijakan pemerintah, kerusakan akibat penggunaan produk tidak sesuai petunjuk, serta ketersediaan/performa website 100% uptime.', 9
  UNION ALL SELECT @user_agus, 'Perubahan Syarat & Ketentuan', 'Kami berhak mengubah Syarat & Ketentuan ini sewaktu-waktu tanpa pemberitahuan sebelumnya. Versi terbaru akan selalu ditampilkan di halaman ini dengan tanggal pembaruan. Penggunaan berkelanjutan setelah perubahan berarti Anda menyetujui revisi tersebut.', 10
) AS seed;

INSERT INTO tbl_syarat_ketentuan (sesi_user, judul, isi, urutan)
SELECT * FROM (
  SELECT @user_tantowi AS sesi_user, 'Informasi Produk' AS judul,
    'Kami berusaha untuk menampilkan informasi produk (deskripsi, harga, gambar, spesifikasi) seakurat mungkin. Namun, kesalahan pengetikan, ketidaksesuaian warna akibat tampilan layar, atau perubahan spesifikasi tanpa pemberitahuan sebelumnya dapat terjadi.' AS isi, 1 AS urutan
  UNION ALL SELECT @user_tantowi, 'Pemesanan', 'Pemesanan dilakukan melalui sistem keranjang belanja di website. Pembeli wajib mengisi data pemesanan (nama, alamat, nomor telepon) dengan benar, lengkap, dan dapat dihubungi. Data pemesanan yang tidak valid dapat menyebabkan pembatalan pesanan. Pesanan otomatis dibatalkan jika pembayaran tidak dikonfirmasi dalam batas waktu yang ditentukan.', 2
  UNION ALL SELECT @user_tantowi, 'Pembayaran', 'Pembayaran dapat dilakukan melalui metode yang tersedia di halaman checkout: Transfer Bank / Virtual Account / E-Wallet melalui Midtrans Payment Gateway (BCA, BRI, BNI, Mandiri, Permata, dll + GoPay, ShopeePay, Dana, OVO, LinkAja), serta COD (Bayar di Tempat) untuk area tertentu dengan batasan nilai transaksi. Bukti pembayaran transfer manual wajib diunggah ke halaman Status Bayar untuk verifikasi admin. Biaya administrasi/transfer (jika ada) ditanggung pembeli.', 3
  UNION ALL SELECT @user_tantowi, 'Pengiriman', 'Pengiriman dilakukan setelah pembayaran terverifikasi dan dikonfirmasi oleh admin/toko. Waktu pengiriman tergantung lokasi tujuan dan jasa ekspedisi (JNE, J&T, SiCepat, Ninja, Kurir Lokal). Estimasi waktu (ETD) yang ditampilkan hanyalah perkiraan. Nomor resi dan link tracking akan dikirimkan ke WhatsApp/email dan tersedia di menu Pengiriman. Jika paket rusak/hilang di jalan, klaim diajukan ke ekspedisi terkait. Alamat pengiriman tidak dapat diubah setelah status Dikirim.', 4
  UNION ALL SELECT @user_tantowi, 'Retur, Pengembalian & Penggantian', 'Retur diterima maksimal 2x24 jam setelah produk diterima jika produk rusak/pecah/cacat pabrik, tidak sesuai pesanan, atau kedaluwarsa. Produk harus dalam kondisi asli, tag/label/packaging utuh. Proses: foto/video bukti, ajukan lewat menu Pengiriman / Chat Penjual, verifikasi, lalu instruksi pengembalian. Biaya retur dibatalkan oleh toko jika kesalahan dari toko/ekspedisi, ditanggung pembeli jika alasan pribadi. Penggantian prioritas pengiriman ulang; jika stok habis, pengembalian dana full. Produk custom/higiene/digital tidak dapat diretur.', 5
  UNION ALL SELECT @user_tantowi, 'Garansi Produk', 'Garansi resmi mengikuti kebijakan pabrikan/distributor resmi masing-masing brand. Klaim garansi memerlukan nota/resi pembelian asli dan barang dalam kondisi sesuai ketentuan. Garansi tidak berlaku untuk kerusakan akibat salah penggunaan, jatuh, cairan, modifikasi, bencana alam, atau pemakaian normal.', 6
  UNION ALL SELECT @user_tantowi, 'Privasi & Data Pribadi', 'Data pribadi Anda (nama, alamat, telepon, email, koordinat lokasi) hanya digunakan untuk proses pemesanan, pembayaran, pengiriman, komunikasi terkait transaksi, peningkatan layanan, analitik, keamanan, dan kepatuhan hukum. Kami tidak menjual data pribadi ke pihak ketiga.', 7
  UNION ALL SELECT @user_tantowi, 'Hak Kekayaan Intelektual', 'Semua konten di website (logo, nama brand, desain UI, foto produk, deskripsi, kode program) adalah hak milik rumah toko/penjual atau mitra reseller yang berhak. Dilarang menyalin, mendistribusikan, atau memodifikasi tanpa izin tertulis.', 8
  UNION ALL SELECT @user_tantowi, 'Batas Tanggung Jawab', 'Kami tidak bertanggung jawab atas keterlambatan/pengiriman gagal akibat force majeure atau kebijakan pemerintah, kerusakan akibat penggunaan produk tidak sesuai petunjuk, serta ketersediaan/performa website 100% uptime.', 9
  UNION ALL SELECT @user_tantowi, 'Perubahan Syarat & Ketentuan', 'Kami berhak mengubah Syarat & Ketentuan ini sewaktu-waktu tanpa pemberitahuan sebelumnya. Versi terbaru akan selalu ditampilkan di halaman ini dengan tanggal pembaruan. Penggunaan berkelanjutan setelah perubahan berarti Anda menyetujui revisi tersebut.', 10
) AS seed;