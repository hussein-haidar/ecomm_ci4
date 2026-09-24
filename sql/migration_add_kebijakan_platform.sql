-- =============================================================
-- Tabel Kebijakan Platform: FAQ (Bantuan) & Syarat Ketentuan
-- Data lintas toko (platform), dikelola oleh Superadmin.
-- =============================================================

CREATE TABLE IF NOT EXISTS tbl_faq_platform (
  id_faq INT(11) NOT NULL AUTO_INCREMENT,
  kategori VARCHAR(50) NOT NULL,
  pertanyaan TEXT NOT NULL,
  jawaban TEXT NOT NULL,
  urutan INT(11) DEFAULT 0,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id_faq),
  KEY idx_kategori (kategori)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS tbl_syarat_platform (
  id_skt INT(11) NOT NULL AUTO_INCREMENT,
  judul VARCHAR(255) NOT NULL,
  isi TEXT NOT NULL,
  urutan INT(11) DEFAULT 0,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id_skt)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- =============================================================
-- Seed default (konten lintas toko / platform).
-- Menjalankan file ini sekali saja (seed di-skip bila tabel terisi).
-- =============================================================

INSERT INTO tbl_faq_platform (kategori, pertanyaan, jawaban, urutan)
SELECT * FROM (
  SELECT 'pemesanan' AS kategori, 'Bagaimana cara melakukan pemesanan di platform ini?' AS pertanyaan,
    'Pilih produk yang Anda inginkan melalui halaman toko, lalu klik tombol "Beli Sekarang" atau masukkan ke keranjang. Setelah itu lanjutkan ke halaman checkout, lengkapi data pemesanan, dan pilih metode pembayaran yang tersedia. Pesanan akan otomatis terbentuk setelah selesai checkout.' AS jawaban, 1 AS urutan
  UNION ALL SELECT 'pemesanan', 'Apakah saya harus membuat akun terlebih dahulu?', 'Ya, Anda disarankan membuat akun (register) agar dapat melacak status pesanan, riwayat pembelian, dan mempermudah proses retur/pengembalian. Pendaftaran gratis dan hanya membutuhkan nomor WhatsApp atau email yang aktif.', 2
  UNION ALL SELECT 'pemesanan', 'Bagaimana batas waktu pembayaran setelah pemesanan?', 'Batas waktu pembayaran adalah 10 menit untuk transfer manual, dan mengikuti timer yang ditentukan oleh Midtrans untuk metode pembayaran Instant (VA, E-Wallet, QRIS). Apabila melewati batas, pesanan otomatis dibatalkan oleh sistem.', 3
  UNION ALL SELECT 'pemesanan', 'Bagaimana cara memilih toko penjual?', 'Pada halaman toko Anda dapat memilih/membuka toko penjual yang diinginkan. Setiap toko dikelola oleh penjualnya masing-masing dan memiliki kebijakan sendiri. Platform menampilkan katalog serta memfasilitasi transaksi antar toko.', 4
  UNION ALL SELECT 'pembayaran', 'Metode pembayaran apa saja yang tersedia?', 'Pembayaran dapat dilakukan via Midtrans Payment Gateway: Transfer Bank (BCA, BRI, BNI, Mandiri, Permata), Virtual Account, E-Wallet (GoPay, ShopeePay, Dana, OVO, LinkAja), QRIS, serta COD (Bayar di Tempat) untuk area tertentu.', 1
  UNION ALL SELECT 'pembayaran', 'Apakah pembayaran di platform ini aman?', 'Sangat aman. Pembayaran yang melalui Midtrans diproses dengan standar keamanan PCI-DSS. Kami tidak pernah menyimpan data kartu Anda. Pembayaran COD juga baru dilakukan saat barang diterima.', 2
  UNION ALL SELECT 'pembayaran', 'Bagaimana verifikasi pembayaran transfer manual?', 'Setelah melakukan transfer, buka menu "Status Bayar", pilih pesanan yang dimaksud, lalu unggah bukti transfer (format gambar). Admin toko yang akan memverifikasi bukti tersebut dan mengonfirmasi pembayaran Anda.', 3
  UNION ALL SELECT 'pembayaran', 'Apakah bisa bayar dengan cicilan?', 'Ya, untuk transaksi yang mendukung, cicilan tersedia melalui Midtrans (Kartu Kredit / Akulaku). Ketersediaan dan syarat mengikuti kebijakan masing-masing penyedia pembayaran.', 4
  UNION ALL SELECT 'pengiriman', 'Bagaimana sistem pengiriman antar toko?', 'Setiap toko penjual mengelola pengirimannya sendiri dan dapat bekerja sama dengan berbagai ekspedisi (JNE, J&T, SiCepat, Ninja, atau Kurir Lokal). Estimasi ongkir dihitung otomatis berdasarkan berat dan tujuan pengiriman.', 1
  UNION ALL SELECT 'pengiriman', 'Bagaimana cara melacak pengiriman?', 'Nomor resi dan link tracking akan dikirimkan ke WhatsApp/email dan tersedia di menu "Pengiriman" pada toko terkait. Anda bisa memantau posisi paket melalui website ekspedisi yang digunakan.', 2
  UNION ALL SELECT 'pengiriman', 'Apa yang terjadi jika paket hilang atau rusak di perjalanan?', 'Pengajuan klaim dilakukan ke ekspedisi terkait. Platform dan toko akan membantu prosesnya. Sertakan bukti (nomor resi, foto paket, dokumentasi CCTV bila ada).', 3
  UNION ALL SELECT 'pengiriman', 'Bisakah digabung pesanan dari beberapa toko dalam satu kiriman?', 'Tidak. Setiap pesanan dikirim oleh toko masing-masing dengan ekspedisi dan nomor resi terpisah. Ongkir dihitung per toko.', 4
  UNION ALL SELECT 'retur', 'Apa kebijakan retur umum di platform ini?', 'Retur dapat diajukan maksimal 2x24 jam setelah barang diterima jika produk rusak/pecah, cacat pabrik, tidak sesuai pesanan, atau kedaluwarsa untuk produk konsumsi. Kebijakan detail dapat berbeda sesuai kebijakan toko penjual.', 1
  UNION ALL SELECT 'retur', 'Bagaimana alur pengajuan retur?', 'Ajukan retur melalui menu "Pengiriman" pada toko terkait atau hubungi Chat Penjual dengan menyertakan bukti kerusakan. Admin toko akan memverifikasi, lalu memberikan instruksi pengembalian paket.', 2
  UNION ALL SELECT 'retur', 'Siapa yang menanggung biaya retur?', 'Biaya pengiriman ulang ditanggung oleh toko apabila kesalahan berasal dari toko atau ekspedisi. Namun jika retur karena alasan pribadi (salah pilih, tidak suka dsb), biaya ditanggung pembeli.', 3
  UNION ALL SELECT 'retur', 'Produk apa saja yang tidak bisa diretur?', 'Produk custom/personalisasi, produk higiene (celana dalam, masker, dll), produk digital/voucher, serta produk promo/flash sale tidak dapat diretur kecuali dalam kondisi rusak atau keliru pengiriman.', 4
  UNION ALL SELECT 'akun', 'Bagaimana cara membuat akun di platform ini?', 'Klik menu "Daftar" atau "Register" pada halaman login, lengkapi data yang dibutuhkan, lalu konfirmasi. Anda akan langsung bisa berbelanja di semua toko yang terdaftar dan memantau pesanan.', 1
  UNION ALL SELECT 'akun', 'Bagaimana jika saya lupa password?', 'Klik "Lupa Password?" pada halaman login, masukkan email/nomor yang terdaftar, lalu ikuti instruksi reset yang dikirimkan ke kontak Anda.', 2
  UNION ALL SELECT 'akun', 'Dapatkah satu akun digunakan di semua toko?', 'Ya, satu akun pelanggan dapat digunakan untuk berbelanja di seluruh toko yang terdaftar di platform. Riwayat pesanan masing-masing toko tetap tersimpan terpisah.', 3
  UNION ALL SELECT 'akun', 'Bagaimana cara memperbarui data profil?', 'Masuk ke akun Anda, buka menu "Profil", lalu ubah data yang diinginkan (nama, alamat, nomor telepon, dsb). Simpan perubahan setelah selesai.', 4
) AS seed;

INSERT INTO tbl_syarat_platform (judul, isi, urutan)
SELECT * FROM (
  SELECT 'Informasi Produk' AS judul,
    'Platform menampilkan produk dari berbagai toko penjual. Setiap toko bertanggung jawab atas keakuratan informasi produknya (deskripsi, harga, gambar, spesifikasi). Kesalahan pengetikan, ketidaksesuaian warna akibat tampilan layar, atau perubahan spesifikasi tanpa pemberitahuan sebelumnya dapat terjadi.' AS isi, 1 AS urutan
  UNION ALL SELECT 'Pemesanan', 'Pemesanan dilakukan melalui sistem keranjang belanja di website toko masing-masing. Pembeli wajib mengisi data pemesanan (nama, alamat, nomor telepon) dengan benar, lengkap, dan dapat dihubungi. Data pemesanan yang tidak valid dapat menyebabkan pembatalan pesanan. Pesanan otomatis dibatalkan jika pembayaran tidak dikonfirmasi dalam batas waktu yang ditentukan.', 2
  UNION ALL SELECT 'Pembayaran', 'Pembayaran dapat dilakukan melalui metode yang tersedia di halaman checkout: Transfer Bank / Virtual Account / E-Wallet melalui Midtrans Payment Gateway (BCA, BRI, BNI, Mandiri, Permata, dll + GoPay, ShopeePay, Dana, OVO, LinkAja), serta COD (Bayar di Tempat) untuk area tertentu dengan batasan nilai transaksi. Bukti pembayaran transfer manual wajib diunggah ke halaman Status Bayar untuk verifikasi admin toko. Biaya administrasi/transfer (jika ada) ditanggung pembeli.', 3
  UNION ALL SELECT 'Pengiriman', 'Pengiriman dilakukan setelah pembayaran terverifikasi dan dikonfirmasi oleh admin/toko. Waktu pengiriman tergantung lokasi tujuan dan jasa ekspedisi (JNE, J&T, SiCepat, Ninja, Kurir Lokal). Estimasi waktu (ETD) yang ditampilkan hanyalah perkiraan. Nomor resi dan link tracking dikirimkan ke WhatsApp/email dan tersedia di menu Pengiriman. Jika paket rusak/hilang di jalan, klaim diajukan ke ekspedisi terkait dan toko membantu prosesnya. Alamat pengiriman tidak dapat diubah setelah status Dikirim.', 4
  UNION ALL SELECT 'Retur, Pengembalian & Penggantian', 'Retur diterima maksimal 2x24 jam setelah produk diterima jika produk rusak/pecah/cacat pabrik, tidak sesuai pesanan, atau kedaluwarsa. Produk harus dalam kondisi asli, tag/label/packaging utuh. Proses: foto/video bukti, ajukan lewat menu Pengiriman / Chat Penjual, verifikasi, lalu instruksi pengembalian. Biaya retur dibatalkan oleh toko jika kesalahan dari toko/ekspedisi, ditanggung pembeli jika alasan pribadi. Penggantian prioritas pengiriman ulang; jika stok habis, pengembalian dana full. Produk custom/higiene/digital tidak dapat diretur.', 5
  UNION ALL SELECT 'Garansi Produk', 'Garansi resmi mengikuti kebijakan pabrikan/distributor resmi masing-masing brand. Klaim garansi memerlukan nota/resi pembelian asli dan barang dalam kondisi sesuai ketentuan. Garansi tidak berlaku untuk kerusakan akibat salah penggunaan, jatuh, cairan, modifikasi, bencana alam, atau pemakaian normal.', 6
  UNION ALL SELECT 'Privasi & Data Pribadi', 'Data pribadi Anda (nama, alamat, telepon, email, koordinat lokasi) hanya digunakan untuk proses pemesanan, pembayaran, pengiriman, komunikasi terkait transaksi, peningkatan layanan, analitik, keamanan, dan kepatuhan hukum. Kami tidak menjual data pribadi ke pihak ketiga.', 7
  UNION ALL SELECT 'Hak Kekayaan Intelektual', 'Semua konten di platform (logo, nama brand, desain UI, foto produk, deskripsi, kode program) adalah hak milik platform atau toko penjual/mitra reseller yang berhak. Dilarang menyalin, mendistribusikan, atau memodifikasi tanpa izin tertulis.', 8
  UNION ALL SELECT 'Batas Tanggung Jawab', 'Platform tidak bertanggung jawab atas keterlambatan/pengiriman gagal akibat force majeure atau kebijakan pemerintah, serta kerusakan akibat penggunaan produk tidak sesuai petunjuk. Setiap toko penjual bertanggung jawab atas mutu, harga, dan ketersediaan produknya, serta layanan purna jual masing-masing.', 9
  UNION ALL SELECT 'Perubahan Syarat & Ketentuan', 'Kami berhak mengubah Syarat & Ketentuan ini sewaktu-waktu tanpa pemberitahuan sebelumnya. Versi terbaru akan selalu ditampilkan di halaman ini dengan tanggal pembaruan. Penggunaan berkelanjutan setelah perubahan berarti Anda menyetujui revisi tersebut.', 10
) AS seed;