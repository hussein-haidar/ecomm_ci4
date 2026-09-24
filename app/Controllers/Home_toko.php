<?php

namespace App\Controllers;

use CodeIgniter\Controller;

use App\Models\M_home_toko;
use App\Models\M_pemilik_produk;
use App\Models\M_pemilik_banner;
use App\Models\M_pelanggan_bayar;
use App\Models\M_kebijakan_toko;

class Home_toko extends BaseController
{
    protected $M_home_toko;
    protected $M_pemilik_produk;
    protected $M_pelanggan_bayar;

    public function __construct()
    {
         $this->M_home_toko = new M_home_toko();
         $this->M_pemilik_produk = new M_pemilik_produk();
        $this->M_pelanggan_bayar = new M_pelanggan_bayar();
    }

    public function index()
    {
        $session = session();

        if (!$session->get('toko_sesi_user')) {
            return redirect()->to(base_url('auth/pilih_toko'));
        }

        $sesi_user = $session->get('toko_sesi_user');
        $keyword = $this->request->getVar('keyword') ?? '';

        $produkModel = new M_home_toko();
        $produk_data = $produkModel->getProduk($sesi_user, $keyword);

        foreach ($produk_data as &$produk) {
            $produk['ukuran_list'] = explode('-', $produk['ukuran_produk']);
        }

        // Fetch rating summaries for all products (batch)
        $reviewModel = new \App\Models\M_review();
        $namaProdukList = array_column($produk_data, 'nama_produk');
        $ratingMap = $reviewModel->get_rating_summary_batch($sesi_user, $namaProdukList);

        foreach ($produk_data as &$produk) {
            $produk['rating_summary'] = $ratingMap[$produk['nama_produk']] ?? null;
        }

        $data = [
            'produk_data' => $produk_data,
            'title2' => 'Katalog Produk',
            'jenis_produk_dropdown' => $produkModel->getJenisProdukDropdown($sesi_user),
            'data_banner' => (new M_pemilik_banner())->get_banner_aktif($sesi_user),
            'user_logged_in' => $session->get('user_logged_in') === true,
        ];

        return view('v_home_toko', $data);
    }

    public function katalog()
    {
        $session = session();

        if (!$session->get('toko_sesi_user')) {
            return redirect()->to(base_url('auth/pilih_toko'));
        }

        $sesi_user = $session->get('toko_sesi_user');
        $keyword = $this->request->getVar('keyword') ?? '';
        $harga_min = $this->request->getVar('harga_min');
        $harga_max = $this->request->getVar('harga_max');
        $jenis_produk = $this->request->getVar('jenis_produk');
        $rating_min = $this->request->getVar('rating_min');

        // Ambil sort berdasarkan jenis
        $sort_harga = $this->request->getVar('sort_harga');
        $sort_nama = $this->request->getVar('sort_nama');
        $sort_by = $this->request->getVar('sort_by');

        // Tentukan sort_by berdasarkan prioritas (sort_by takes precedence)
        if (empty($sort_by)) {
            $sort_by = $sort_harga ?: $sort_nama;
        }

        $produkModel = new \App\Models\M_home_toko();

        $produk_data = $produkModel->getProduk(
            $sesi_user,
            $keyword,
            $harga_min,
            $harga_max,
            $sort_by,
            $jenis_produk,
            $rating_min
        );

        foreach ($produk_data as &$produk) {
            $produk['ukuran_list'] = explode('-', $produk['ukuran_produk']);
        }

        // Fetch rating summaries for all products (batch)
        $reviewModel = new \App\Models\M_review();
        $namaProdukList = array_column($produk_data, 'nama_produk');
        $ratingMap = $reviewModel->get_rating_summary_batch($sesi_user, $namaProdukList);

        foreach ($produk_data as &$produk) {
            $produk['rating_summary'] = $ratingMap[$produk['nama_produk']] ?? null;
        }

        $nama_pelanggan = $session->get('nama_pelanggan');

        // Trigger hanya jika ada nama_pelanggan
        if ($nama_pelanggan) {
            $this->M_pelanggan_bayar->batalkanTransaksiExpiredByUser($nama_pelanggan);
        }

        $data = [
            'produk_data' => $produk_data,
            'title2' => 'Katalog Produk',
            'user_logged_in' => $session->get('user_logged_in') === true,
            'jenis_produk_dropdown' => $produkModel->getJenisProdukDropdown($sesi_user),
            'filter_params' => [
                'keyword' => $keyword,
                'harga_min' => $harga_min,
                'harga_max' => $harga_max,
                'sort_harga' => $sort_harga,
                'sort_nama' => $sort_nama,
                'sort_by' => $sort_by,
                'jenis_produk' => $jenis_produk,
                'rating_min' => $rating_min,
            ]
        ];

        return view('pelanggan/toko/v_katalog', $data);
    }

    public function jenis_produk($jenis_produk = null)
    {
        $session = session();

        if (!$session->get('toko_sesi_user')) {
            return redirect()->to(base_url('auth/pilih_toko'));
        }

        $sesi_user = $session->get('toko_sesi_user');
        $keyword = $this->request->getVar('keyword') ?? '';

        $produkModel = new \App\Models\M_home_toko();
        $produk_data = $produkModel->getProdukByJenis($sesi_user, $jenis_produk, $keyword);

        // Fetch rating summaries for all products (batch)
        $reviewModel = new \App\Models\M_review();
        $namaProdukList = array_column($produk_data, 'nama_produk');
        $ratingMap = $reviewModel->get_rating_summary_batch($sesi_user, $namaProdukList);

        foreach ($produk_data as &$produk) {
            $produk['rating_summary'] = $ratingMap[$produk['nama_produk']] ?? null;
        }

        $data = [
            'produk_data' => $produk_data,
            'title2' => $jenis_produk,
            'user_logged_in' => $session->get('user_logged_in') === true,
            'jenis_produk_dropdown' => $produkModel->getJenisProdukDropdown($sesi_user),
        ];

        return view('pelanggan/toko/v_produk', $data);
    }

    // 🔁 Method baru untuk ambil stok dari server
    public function ambil_stok()
    {
        $session = session();
        $sesi_user = $session->get('toko_sesi_user') ?? $session->get('sesi_user');

        $model = new M_home_toko();
        $produk_data = $model->getProduk($sesi_user);

        $stokData = [];
        foreach ($produk_data as $row) {
            $stokData[] = [
                'id_stok' => $row['id_stok'],
                'jumlah_stok_produk' => $row['jumlah_stok_produk']
            ];
        }

        return $this->response->setJSON($stokData);
    }

    public function detail_produk($nama_produk)
    {
        $session = session();

        if (!$session->get('toko_sesi_user')) {
            return redirect()->to(base_url('auth/pilih_toko'));
        }

        $sesi_user = $session->get('toko_sesi_user');
        $keyword = $this->request->getVar('keyword') ?? '';

        $produkModel = new M_home_toko();
        $produk_data = $produkModel->getProduk($sesi_user, $keyword);

        foreach ($produk_data as &$produk) {
            $produk['ukuran_list'] = explode('-', $produk['ukuran_produk']);
        }

        $data = [
            'produk_data' => $produk_data,
            'title2' => 'Katalog Produk',
            'produk' => $produkModel->detailStok($nama_produk),
            'sesi_user' => $sesi_user,
            'jenis_produk_dropdown' => $produkModel->getJenisProdukDropdown($sesi_user),
            'user_logged_in' => $session->get('user_logged_in') === true,
        ];

        return view('pelanggan/toko/v_detail_produk', $data);
    }
    
    public function syaket()
    {
        $session = session();
        $sesi_user = $session->get('toko_sesi_user') ?? $session->get('sesi_user');
        $keyword = $this->request->getVar('keyword') ?? '';

        $produkModel = new M_home_toko();
        $produk_data = $produkModel->getProduk($sesi_user, $keyword);

        $sktModel = new M_kebijakan_toko();
        $skt_data = $sktModel->get_skt_by_sesi($sesi_user);
        if (empty($skt_data)) {
            $skt_data = $this->platform_syaket();
        }

        $data = [
            'title' => 'Toko Online',
            'title2' => 'Toko Online',
            'produk_data' => $produk_data,
            'user_logged_in' => $session->get('user_logged_in') === true,
            'jenis_produk_dropdown' => $produkModel->getJenisProdukDropdown($sesi_user),
            'skt_data' => $skt_data,
        ];

        return view('pelanggan/toko/v_syaket', $data);
    }

    public function bantuan()
    {
        $session = session();
        $sesi_user = $session->get('toko_sesi_user') ?? $session->get('sesi_user');
        $keyword = $this->request->getVar('keyword') ?? '';

        $produkModel = new M_home_toko();
        $produk_data = $produkModel->getProduk($sesi_user, $keyword);

        $sktModel = new M_kebijakan_toko();
        $faq_data = $sktModel->get_faq_by_sesi($sesi_user);
        if (empty($faq_data)) {
            $faq_data = $this->platform_bantuan();
        }

        $data = [
            'title' => 'Toko Online',
            'title2' => 'Toko Online',
            'produk_data' => $produk_data,
            'user_logged_in' => $session->get('user_logged_in') === true,
            'jenis_produk_dropdown' => $produkModel->getJenisProdukDropdown($sesi_user),
            'faq_data' => $faq_data,
        ];

        return view('pelanggan/toko/v_bantuan', $data);
    }

    public function login()
    {
        // Jika belum pilih toko, redirect ke pilih toko
        if (empty(session()->get('toko_sesi_user'))) {
            return redirect()->to(base_url('auth/pilih_toko'));
        }

        return redirect()->to(base_url('auth/login_pelanggan'));
    }

    // Fallback data kebijakan: ambil dari platform (lintas toko).
    private function platform_bantuan()
    {
        $platform = new \App\Models\M_platform_kebijakan();
        $faq = $platform->get_all_faq();
        if (!empty($faq)) {
            return $faq;
        }
        return $this->default_bantuan();
    }

    private function platform_syaket()
    {
        $platform = new \App\Models\M_platform_kebijakan();
        $skt = $platform->get_all_skt();
        if (!empty($skt)) {
            return $skt;
        }
        return $this->default_syaket();
    }

    private function default_bantuan()
    {
        return [
            ['kategori' => 'pemesanan', 'pertanyaan' => 'Bagaimana cara melakukan pemesanan?', 'jawaban' => '1) Pilih produk yang diinginkan di halaman Katalog atau Toko.<br>2) Pilih ukuran (jika ada) dan jumlah, lalu klik "Tambah ke Keranjang".<br>3) Buka Keranjang (ikon keranjang di navbar), centang produk, klik "Checkout".<br>4) Isi alamat pengiriman, pilih ekspedisi & metode pembayaran.<br>5) Klik "Buat Pesanan" lalu lakukan pembayaran sesuai instruksi.', 'urutan' => 1],
            ['kategori' => 'pemesanan', 'pertanyaan' => 'Apakah saya harus login untuk memesan?', 'jawaban' => 'Ya. Anda harus mendaftar & login untuk checkout, melihat riwayat, melacak pengiriman, dan mengelola profil. Login juga memungkinkan fitur Chat Penjual & Wishlist.', 'urutan' => 2],
            ['kategori' => 'pemesanan', 'pertanyaan' => 'Bisa ganti alamat/ukuran setelah pesanan dibuat?', 'jawaban' => 'Tidak bisa diubah lewat sistem setelah checkout. Segera hubungi toko via <strong>Chat Penjual</strong> (di halaman Detail Produk / Keranjang / Riwayat) sebelum status berubah jadi "Dikemas/Dikirim".', 'urutan' => 3],
            ['kategori' => 'pemesanan', 'pertanyaan' => 'Produk yang saya inginkan stoknya habis. Bisa notif kalau stok masuk?', 'jawaban' => 'Fitur notif stok belum tersedia. Saran: cek berkala atau chat penjual via tombol <strong>Chat Penjual</strong> di halaman produk untuk tanya kapan restock.', 'urutan' => 4],
            ['kategori' => 'pembayaran', 'pertanyaan' => 'Metode pembayaran apa saja yang tersedia?', 'jawaban' => '<strong>Via Midtrans Payment Gateway:</strong><br>• Virtual Account (BCA, BRI, BNI, Mandiri, Permata, CIMB, dll)<br>• E-Wallet (GoPay, ShopeePay, Dana, OVO, LinkAja, Sakuku)<br>• Retail/Minimarket (Alfamart, Indomaret via VA)<br>• Kartu Kredit/Debit (Visa, Mastercard, JCB)<br>• QRIS<br><br><strong>Manual Transfer:</strong> Rekening toko (BCA/BRI/Mandiri) - bukti bayar wajib diupload.<br><strong>COD:</strong> Bayar di tempat (area & nominal terbatas).', 'urutan' => 1],
            ['kategori' => 'pembayaran', 'pertanyaan' => 'Berapa batas waktu pembayaran?', 'jawaban' => '<strong>Midtrans (VA/E-Wallet/QRIS/Kartu):</strong> Ada timer real-time (biasanya 15-24 jam).<br><strong>Transfer Manual:</strong> 10 menit setelah checkout (timer di halaman Status Bayar).<br><strong>COD:</strong> Bayar saat kurir sampai.', 'urutan' => 2],
            ['kategori' => 'pembayaran', 'pertanyaan' => 'Sudah bayar tapi status belum berubah jadi "Dibayar".', 'jawaban' => '<strong>Midtrans:</strong> Biasanya otomatis &lt; 5 menit. Jika &gt; 1 jam, cek email Midtrans/notif WhatsApp.<br><strong>Transfer Manual:</strong> Admin verifikasi manual (jam kerja 08-22 WIB). Pastikan bukti JPG/PNG &lt; 2MB, nominal & nomor rekening terlihat jelas.<br><strong>COD:</strong> Status update setelah kurir konfirmasi pembayaran.', 'urutan' => 3],
            ['kategori' => 'pembayaran', 'pertanyaan' => 'Bisa bayar cicilan?', 'jawaban' => 'Ya, via Midtrans dengan Kartu Kredit (biasanya 3/6/12 bln) atau Akulaku/Kredivo (pilih di halaman pembayaran Midtrans). Syarat & bunga mengikuti kebijakan masing-masing penyedia.', 'urutan' => 4],
            ['kategori' => 'pengiriman', 'pertanyaan' => 'Berapa lama estimasi pengiriman?', 'jawaban' => 'Tergantung asal toko & kota tujuan:<br>• <strong>Jawa & Bali:</strong> 1-3 hari kerja<br>• <strong>Sumatra:</strong> 2-4 hari kerja<br>• <strong>Kalimantan/Sulawesi/NTB/NTT:</strong> 3-6 hari kerja<br>• <strong>Papua/Maluku:</strong> 5-10 hari kerja<br><br><em>Estimasi ini tidak mengikat & tidak termasuk hari libur/force majeure.</em>', 'urutan' => 1],
            ['kategori' => 'pengiriman', 'pertanyaan' => 'Cara cek nomor resi & tracking?', 'jawaban' => '1) Buka menu <strong>Pengiriman</strong> di navbar (ikon truk).<br>2) Klik "Lacak" pada pesanan.<br>3) Atau klik link WhatsApp/Email notif pengiriman.<br>Nomor resi juga terlihat di detail pesanan (menu Riwayat Beli).', 'urutan' => 2],
            ['kategori' => 'pengiriman', 'pertanyaan' => 'Paket tertulis "Diterima" tapi saya belum terima.', 'jawaban' => '1) Cek ke tetangga/RT/security/lokasi penitipan kurir.<br>2) Tanya ke kurir via nomor telepon di detail tracking.<br>3) Jika 1x24 jam tidak ketemu: hubungi kami via Chat/WA dengan nomor resi & bukti tidak terima (foto CCTV/skck RT). Kami akan klaim ke ekspedisi.', 'urutan' => 3],
            ['kategori' => 'pengiriman', 'pertanyaan' => 'Bisa ganti alamat pengiriman setelah dikirim?', 'jawaban' => 'Tidak bisa via sistem. Hubungi <strong>langsung kurir</strong> via nomor telepon di tracking. Beberapa ekspedisi izinkan ganti alamat (bisa ada biaya tambahan).', 'urutan' => 4],
            ['kategori' => 'retur', 'pertanyaan' => 'Syarat retur/produk diganti?', 'jawaban' => '<strong>Diterima jika:</strong> Rusak/pecah, cacat pabrik, salah kirim (warna/ukuran/model), kedaluwarsa.<br><strong>Batas waktu:</strong> Maksimal <strong>2 hari</strong> setelah terima (berdasarkan bukti terima ekspedisi).<br><strong>Kondisi:</strong> Asli, tidak dipakai, tag/packaging utuh, lengkap aksesoris.', 'urutan' => 1],
            ['kategori' => 'retur', 'pertanyaan' => 'Cara ajukan retur?', 'jawaban' => '1) Foto/video bukti kerusakan/ketidaksesuaian (wajib jelas).<br>2) Buka menu <strong>Pengiriman</strong> → Klik "Ajukan Retur" pada pesanan.<br>3) Atau Chat Penjual langsung dari halaman Detail Produk/Riwayat.<br>4) Tunggu verifikasi (max 1x24 jam) → Dapatkan instruksi & alamat pengembalian.', 'urutan' => 2],
            ['kategori' => 'retur', 'pertanyaan' => 'Siapa bayar ongkir retur?', 'jawaban' => '<strong>Kesalahan toko/ekspedisi</strong> (rusak, salah kirim, cacat): <strong>Toko bayar</strong> (kita kirim label return/transfer ongkir).<br><strong>Alasan pribadi</strong> (salah pilih, tidak suka, ukuran tidak pas): <strong>Pembeli bayar</strong> ongkir bolak-balik.', 'urutan' => 3],
            ['kategori' => 'retur', 'pertanyaan' => 'Produk Flash Sale / Promo bisa diretur?', 'jawaban' => 'Hanya jika <strong>rusak/cacat/salah kirim</strong>. Retur alasan pribadi (tidak suka, salah ukuran) <strong>tidak diterima</strong> untuk produk Flash Sale/Promo/Bundle. Cek deskripsi & tabel ukuran sebelum beli.', 'urutan' => 4],
            ['kategori' => 'akun', 'pertanyaan' => 'Lupa password, gimana reset?', 'jawaban' => 'Klik <strong>"Lupa Password"</strong> di halaman Login → Masukkan email terdaftar → Cek email (cek folder Spam) → Klik link reset (berlaku 60 menit) → Buat password baru.', 'urutan' => 1],
            ['kategori' => 'akun', 'pertanyaan' => 'Gimana ganti email/nomor HP?', 'jawaban' => 'Login → Menu <strong>Profil Saya</strong> → Edit Profil → Ganti email/nomor HP → Simpan. Verifikasi OTP akan dikirim ke kontak baru.', 'urutan' => 2],
            ['kategori' => 'akun', 'pertanyaan' => 'Bisa hapus akun?', 'jawaban' => 'Hubungi admin via WA/Email dengan permintaan hapus akun. Data transaksi (riwayat, nota, garansi) akan diarsipkan sesuai regulasi (min 5 tahun).', 'urutan' => 3],
            ['kategori' => 'akun', 'pertanyaan' => 'Notifikasi WA/Email tidak masuk.', 'jawaban' => '• Cek folder Spam/Promotions (email).<br>• Pastikan nomor WA aktif & tidak block nomor kami.<br>• Cek pengaturan notifikasi di aplikasi WA.<br>• Data kontak di Profil sudah benar?', 'urutan' => 4],
        ];
    }

    private function default_syaket()
    {
        return [
            ['judul' => 'Informasi Produk', 'isi' => 'Kami berusaha untuk menampilkan informasi produk (deskripsi, harga, gambar, spesifikasi) seakurat mungkin. Namun, kesalahan pengetikan, ketidaksesuaian warna akibat tampilan layar, atau perubahan spesifikasi tanpa pemberitahuan sebelumnya dapat terjadi.<ul><li>Gambar produk bersifat ilustrasi, warna &amp; detail bisa berbeda nyata.</li><li>Harga &amp; ketersediaan stok dapat berubah sewaktu-waktu.</li><li>Spesifikasi teknis mengacu pada data pabrikan/resmi.</li></ul>', 'urutan' => 1],
            ['judul' => 'Pemesanan', 'isi' => '<ul><li>Pemesanan dilakukan melalui sistem keranjang belanja di website.</li><li>Pembeli wajib mengisi data pemesanan (nama, alamat, nomor telepon) dengan benar, lengkap, dan dapat dihubungi.</li><li>Data pemesanan yang tidak valid/menyesatkan dapat menyebabkan pembatalan pesanan oleh sistem.</li><li>Kami berhak membatalkan pesanan apabila terjadi pelanggaran, penyalahgunaan sistem, atau kecurangan.</li><li>Pesanan otomatis dibatalkan jika pembayaran tidak dikonfirmasi dalam batas waktu (10 menit untuk transfer manual, sesuai timer Midtrans untuk payment gateway).</li></ul>', 'urutan' => 2],
            ['judul' => 'Pembayaran', 'isi' => '<p>Pembayaran dapat dilakukan melalui metode yang tersedia di halaman checkout:</p><ul><li><strong>Transfer Bank / Virtual Account / E-Wallet:</strong> Via Midtrans Payment Gateway (BCA, BRI, BNI, Mandiri, Permata, dll + GoPay, ShopeePay, Dana, OVO, LinkAja).</li><li><strong>COD (Bayar di Tempat):</strong> Tersedia untuk area tertentu dengan batasan nilai transaksi.</li><li>Bukti pembayaran (transfer manual) wajib diunggah ke halaman <em>Status Bayar</em> untuk verifikasi admin.</li><li>Pembayaran harus dilakukan dalam mata uang Rupiah (IDR).</li><li>Biaya administrasi/transfer (jika ada) ditanggung pembeli.</li></ul>', 'urutan' => 3],
            ['judul' => 'Pengiriman', 'isi' => '<ul><li>Pengiriman dilakukan setelah pembayaran <strong>terverifikasi &amp; dikonfirmasi</strong> oleh admin/toko.</li><li>Waktu pengiriman tergantung lokasi tujuan, jasa ekspedisi (JNE, J&amp;T, SiCepat, Ninja, Lokal), dan kondisi cuaca/keamanan.</li><li>Estimasi waktu (ETD) yang ditampilkan hanyalah perkiraan, bukan jaminan pasti.</li><li>Nomor resi &amp; link tracking akan dikirimkan ke WhatsApp/email &amp; tersedia di menu <em>Pengiriman</em>.</li><li>Jika paket rusak/hilang di jalan, klaim diajukan ke ekspedisi terkait (kami bantu prosesnya).</li><li>Alamat pengiriman tidak bisa diubah setelah status <strong>Dikirim</strong>.</li></ul>', 'urutan' => 4],
            ['judul' => 'Retur, Pengembalian &amp; Penggantian', 'isi' => '<ul><li><strong>Retur Diterima Jika:</strong> Produk rusak/pecah/cacat pabrik, tidak sesuai pesanan (warna/ukuran/model salah dikirim), atau kedaluwarsa (untuk produk konsumsi).</li><li><strong>Batas Waktu:</strong> Maksimal <strong>2 x 24 jam</strong> setelah produk diterima (berdasarkan bukti terima ekspedisi).</li><li><strong>Syarat Produk:</strong> Dalam kondisi asli, tidak digunakan, tag/label/packaging utuh, lengkap aksesorisnya.</li><li><strong>Proses:</strong> Foto/video bukti kerusakan/ketidaksesuaian → Ajukan via menu <em>Pengiriman</em> / Chat Penjual → Verifikasi → Instruksi pengembalian.</li><li><strong>Biaya Retur:</strong> Dibatalkan oleh toko jika kesalahan dari toko/ekspedisi; ditanggung pembeli jika alasan pribadi (salah pilih, tidak suka, dll).</li><li><strong>Penggantian:</strong> Prioritas pengiriman ulang barang yang sama; jika stok habis → pengembalian dana full.</li><li>Produk <strong>tidak dapat diretur</strong>: Produk custom/personalisasi, produk higiene (celana dalam, masker, dll), produk digital/voucher, produk promo/flash sale (kecuali rusak/keliru).</li></ul>', 'urutan' => 5],
            ['judul' => 'Garansi Produk', 'isi' => '<ul><li>Garansi resmi mengikuti kebijakan pabrikan/distributor resmi masing-masing brand.</li><li>Klaim garansi memerlukan nota/resi pembelian asli &amp; barang dalam kondisi sesuai ketentuan garansi.</li><li>Garansi tidak berlaku untuk kerusakan akibat salah penggunaan, jatuh, cairan, modifikasi, bencana alam, atau normal wear &amp; tear.</li></ul>', 'urutan' => 6],
            ['judul' => 'Privasi &amp; Data Pribadi', 'isi' => '<p>Data pribadi Anda (nama, alamat, telepon, email, koordinat lokasi) hanya digunakan untuk:</p><ul><li>Proses pemesanan, pembayaran, pengiriman, &amp; komunikasi terkait transaksi.</li><li>Peningkatan layanan, analitik, &amp; keamanan (fraud prevention).</li><li>Kepatuhan hukum &amp; peraturan perundang-undangan.</li></ul><p>Kami tidak menjual data pribadi ke pihak ketiga.</p>', 'urutan' => 7],
            ['judul' => 'Hak Kekayaan Intelektual', 'isi' => 'Semua konten di website (logo, nama brand, desain UI, foto produk, deskripsi, kode program) adalah hak milik toko atau mitra/reseller yang berhak. Dilarang menyalin, mendistribusikan, atau memodifikasi tanpa izin tertulis.', 'urutan' => 8],
            ['judul' => 'Batas Tanggung Jawab', 'isi' => 'Kami tidak bertanggung jawab atas:<ul><li>Keterlambatan/pengiriman gagal akibat force majeure, kebijakan pemerintah, gangguan jaringan ekspedisi.</li><li>Kerusakan/kerugian akibat penggunaan produk tidak sesuai manual/petunjuk.</li><li>Ketersediaan/performa website 100% uptime (meski kami berusaha maksimal).</li></ul>', 'urutan' => 9],
            ['judul' => 'Perubahan Syarat &amp; Ketentuan', 'isi' => 'Kami berhak mengubah Syarat &amp; Ketentuan ini sewaktu-waktu tanpa pemberitahuan sebelumnya. Versi terbaru akan selalu ditampilkan di halaman ini dengan tanggal pembaruan. Penggunaan berkelanjutan setelah perubahan berarti Anda menyetujui revisi tersebut.', 'urutan' => 10],
        ];
    }
}