<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\RESTful\ResourceController;
use App\Models\M_home_toko;
use App\Models\M_profil_user;
use App\Models\M_profil_pelanggan;
use App\Models\M_pelanggan_keranjang;
use App\Models\M_admin_stok;
use App\Models\M_pelanggan_beli;
use App\Models\M_pelanggan_bayar;
use App\Models\M_pelanggan_ekspedisi;
use App\Models\M_review;
use CodeIgniter\API\ResponseTrait;
use Endroid\QrCode\Builder\Builder; // Library QR Code

require_once ROOTPATH . 'vendor/autoload.php';
require_once APPPATH . 'Libraries/phpqrcode/qrlib.php'; // ← wajib ditambahkan

class pelanggan_kelola_data extends BaseController
{
    use ResponseTrait;
    protected $M_home_toko;
    protected $M_profil_user;
    protected $M_profil_pelanggan;
    protected $M_pelanggan_keranjang;
    protected $M_admin_stok;
    protected $M_pelanggan_beli;
    protected $M_pelanggan_bayar;
    protected $M_pelanggan_ekspedisi;
    protected $db;

    public function __construct()
    {
        $this->M_home_toko = new M_home_toko();
        $this->M_profil_user = new M_profil_user();
        $this->M_profil_pelanggan = new M_profil_pelanggan();
        $this->M_pelanggan_keranjang = new M_pelanggan_keranjang();
        $this->M_admin_stok = new M_admin_stok();
        $this->M_pelanggan_beli = new M_pelanggan_beli();
        $this->M_pelanggan_bayar = new M_pelanggan_bayar();
        $this->M_pelanggan_ekspedisi = new M_pelanggan_ekspedisi();
        $this->db = \Config\Database::connect();
    }

    public function profil()
    {
        $session = session();
        $sesi_user = $session->get('toko_sesi_user') ?? $session->get('sesi_user');
        $keyword = $this->request->getVar('keyword') ?? '';

        $produkModel = new M_home_toko();
        $produk_data = $produkModel->getProduk($sesi_user, $keyword);

        foreach ($produk_data as &$produk) {
            $produk['ukuran_list'] = explode('-', $produk['ukuran_produk']);
        }

        $data = [
            'produk_data' => $produk_data,
            'title' => 'Data Profil',
            'title2' => 'Profil Saya',
            'data_profil' => $this->M_profil_pelanggan->get_profile(),
            'jenis_produk_dropdown' => $produkModel->getJenisProdukDropdown($sesi_user),
            'user_logged_in' => $session->get('user_logged_in') === true,
        ];

        return view('pelanggan/update_profile/v_profil', $data);
    }

    public function edit($id_pelanggan)
    {
        $session = session();
        $tokoUser = $session->get('toko_sesi_user') ?? $session->get('sesi_user');
        $keyword = $this->request->getVar('keyword') ?? '';

        $produkModel = new M_home_toko();
        $produk_data = $produkModel->getProduk($tokoUser, $keyword);

        foreach ($produk_data as &$produk) {
            $produk['ukuran_list'] = explode('-', $produk['ukuran_produk']);
        }

        // Ambil data pelanggan (pakai sesi_user milik pelanggan sendiri)
        $data_pelanggan = $this->M_profil_pelanggan->detailProfile($id_pelanggan);

        $data = [
            'title' => 'Data Profile',
            'title2' => 'Edit Profile',
            'produk_data' => $produk_data,
            'data_profil' => $data_pelanggan,
            'jenis_produk_dropdown' => $produkModel->getJenisProdukDropdown($tokoUser),
            'sesi_user' => $data_pelanggan ? $data_pelanggan['sesi_user'] : 'Nama Lengkap Tidak Ditemukan',
            'user_logged_in' => $session->get('user_logged_in') === true,
        ];

        return view('pelanggan/update_profile/v_edit', $data);
    }

    public function update_profile()
    {
        $session_id_user = session()->get('id_pelanggan');

        if ($this->validate([
            'email' => [
                'label' => 'Email',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} Wajib Diisi !!!',
                ]
            ],
            'password' => [
                'label' => 'Password',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} Wajib Diisi !!!'
                ]
            ],
            'nama_pelanggan' => [
                'label' => 'Nama Lengkap',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} Wajib Diisi !!!'
                ]
            ],
            'no_telpon' => [
                'label' => 'No Telepon Pengguna',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} Wajib Diisi !!!'
                ]
            ],
            'foto_pelanggan' => [
                'label' => 'Foto',
                'rules' => 'max_size[foto_pelanggan,1024]|mime_in[foto_pelanggan,image/png,image/jpg,image/jpeg,image/gif,image/ico]',
                'errors' => [
                    'max_size' => '{field} Max 1024 KB !!!',
                    'mime_in' => 'Format {field} Wajib PNG, JPG, JPEG, GIF, ICO!!!'
                ]
            ],
        ])) {
            // Mengambil file foto dari form input
            $foto = $this->request->getFile('foto_pelanggan');

            if ($foto->getError() == 4) {
                // Jika foto tidak diganti
                $data = [
                    'sesi_user' => $this->request->getPost('sesi_user'),
                    'email' => $this->request->getPost('email'),
                    'password' => $this->request->getPost('password'),
                    'nama_pelanggan' => $this->request->getPost('nama_pelanggan'),
                    'no_telpon' => $this->request->getPost('no_telpon'),
                    'longitude' => $this->request->getPost('longitude') ?? 0,
                    'latitude' => $this->request->getPost('latitude') ?? 0,
                    'alamat' => $this->request->getPost('alamat') ?? '',
                    'kode_kota' => $this->request->getPost('kode_kota') ?? session()->get('kode_kota') ?? '',
                    'nama_kota' => $this->request->getPost('nama_kota') ?? session()->get('nama_kota') ?? '',
                    'tanggal_lahir' => $this->request->getPost('tanggal_lahir'),
                ];
            } else {
                // Menghapus foto lama (hanya jika file lokal, bukan URL)
                $data_profil = $this->M_profil_pelanggan->detailProfile($session_id_user);
                if ($data_profil['foto_pelanggan'] != "" && !filter_var($data_profil['foto_pelanggan'], FILTER_VALIDATE_URL)) {
                    $path = 'fotopelanggan/' . $data_profil['foto_pelanggan'];
                    if (file_exists($path)) {
                        unlink($path);
                    }
                }
                // Mengganti nama file foto
                $nama_file = $foto->getRandomName();
                // Menambahkan data foto baru
                $data = [
                    'sesi_user' => $this->request->getPost('sesi_user'),
                    'email' => $this->request->getPost('email'),
                    'password' => $this->request->getPost('password'),
                    'nama_pelanggan' => $this->request->getPost('nama_pelanggan'),
                    'no_telpon' => $this->request->getPost('no_telpon'),
                    'longitude' => $this->request->getPost('longitude') ?? 0,
                    'latitude' => $this->request->getPost('latitude') ?? 0,
                    'alamat' => $this->request->getPost('alamat') ?? '',
                    'kode_kota' => $this->request->getPost('kode_kota') ?? session()->get('kode_kota') ?? '',
                    'nama_kota' => $this->request->getPost('nama_kota') ?? session()->get('nama_kota') ?? '',
                    'tanggal_lahir' => $this->request->getPost('tanggal_lahir'),
                    'foto_pelanggan' => $nama_file,
                ];
                // Pindahkan file ke folder tujuan
                $foto->move('fotopelanggan', $nama_file);
            }

            $sukses = $this->M_profil_pelanggan->edit($session_id_user, $data);

            if ($sukses) {
                // Update session data
                session()->set('email', $data['email']);
                session()->set('password', $data['password']);
                session()->set('nama_pelanggan', $data['nama_pelanggan']);
                session()->set('no_telpon', $data['no_telpon']);
                session()->set('longitude', $data['longitude']);
                session()->set('latitude', $data['latitude']);
                session()->set('alamat', $data['alamat']);
                session()->set('kode_kota', $data['kode_kota'] ?? '');
                session()->set('nama_kota', $data['nama_kota'] ?? '');
                session()->set('tanggal_lahir', $data['tanggal_lahir']);
                if (isset($data['foto_pelanggan'])) {
                    session()->set('foto_pelanggan', $data['foto_pelanggan']);
                }

                session()->setFlashdata('pesan_profil', 'Data Profil Berhasil Diubah !!!');
                return redirect()->to(base_url('pelanggan_kelola_data/edit/' . $session_id_user));
            } else {
                session()->setFlashdata('pesan_profil', 'Gagal Mengubah Data Profil !!!');
                return redirect()->to(base_url('pelanggan_kelola_data/edit/' . $session_id_user));
            }
        } else {
            // Jika tidak valid
            session()->setFlashdata('errors', \Config\Services::validation()->getErrors());
            return redirect()->to(base_url('pelanggan_kelola_data/edit/' . $session_id_user));
        }
    }

    public function keranjang()
    {
        // Inisialisasi session & model
        $session = session();
        $produkModel = new M_home_toko();

        // Ambil sesi user & keyword pencarian
        $sesi_user = $session->get('toko_sesi_user') ?? $session->get('sesi_user');
        $keyword = $this->request->getVar('keyword') ?? '';

        // Ambil semua produk dan tambahkan ukuran_list
        $produk_data = $produkModel->getProduk($sesi_user, $keyword);
        $produk_map = [];

        foreach ($produk_data as &$produk) {
            $produk['ukuran_list'] = explode('-', $produk['ukuran_produk']);
            $produk_map[$produk['nama_produk']] = $produk;
        }

        // Ambil data keranjang dari model (filter by toko)
        $keranjang = $this->M_pelanggan_keranjang->get_keranjang($sesi_user);

        // Tambahkan detail produk ke setiap item keranjang
        foreach ($keranjang as &$item) {
            $nama_produk = $item['nama_produk'];
            if (isset($produk_map[$nama_produk])) {
                $item['ukuran_list']   = $produk_map[$nama_produk]['ukuran_list'];
                $item['nama_produk']   = $produk_map[$nama_produk]['nama_produk'];
                $item['foto_produk']   = $produk_map[$nama_produk]['foto_produk'];
            } else {
                $item['ukuran_list'] = [];
            }
        }

        // Kirim data ke view
        $data = [
            'title'                 => 'Data Keranjang',
            'title2'                => 'Data Keranjang',
            'keranjang'             => $keranjang,
            'produk_data'           => $produk_data,
            'jenis_produk_dropdown' => $produkModel->getJenisProdukDropdown($sesi_user),
            'user_logged_in'        => $session->get('user_logged_in') === true,
        ];

        return view('pelanggan/data_keranjang/v_keranjang', $data);
    }

    public function add_to_cart()
    {
        // Ambil data dari form
        $id_stok = $this->request->getPost('id_stok');
        $nama_produk = $this->request->getPost('nama_produk');
        $ukuran_produk_full = $this->request->getPost('ukuran_produk');
        $ukuran_parts = explode('-', $ukuran_produk_full);
        $ukuran_produk = trim($ukuran_parts[0]); // Ambil bagian pertama, misalnya "M"
        $jumlah_produk = $this->request->getPost('jumlah_produk');
        $satuan_produk = $this->request->getPost('satuan_produk');
        $harga_produk = $this->request->getPost('harga_produk');
        $berat_produk = $this->request->getPost('berat_produk');
        $status_keranjang = $this->request->getPost('status_keranjang') ?? 'proses';

        $total_harga = $jumlah_produk * $harga_produk;

        // Cek apakah item sudah ada di keranjang
        $existing_cart = $this->M_pelanggan_keranjang->get_cart_item(
            session()->get('nama_pelanggan'),
            $nama_produk,
            $id_stok
        );

        if ($existing_cart) {
            // Jika status sama, update jumlah dan harga
            if ($existing_cart['status_keranjang'] === $status_keranjang) {
                $new_jumlah = $existing_cart['jumlah_produk'] + $jumlah_produk;
                $new_total_harga = $new_jumlah * $harga_produk;

                $update_cart = $this->M_pelanggan_keranjang->update_cart(
                    session()->get('nama_pelanggan'),
                    $id_stok,
                    $nama_produk,
                    $new_jumlah,
                    $new_total_harga
                );
            } else {
                // Status berbeda → tambahkan entri baru
                $data_keranjang = [
                    'nama_pelanggan' => session()->get('nama_pelanggan'),
                    'longitude' => session()->get('longitude'),
                    'latitude' => session()->get('latitude'),
                    'alamat' => session()->get('alamat'),
                    'id_stok' => $id_stok,
                    'nama_produk' => $nama_produk,
                    'ukuran_produk' => $ukuran_produk,
                    'jumlah_produk' => $jumlah_produk,
                    'satuan_produk' => $satuan_produk,
                    'harga_produk' => $harga_produk,
                    'berat_produk' => $berat_produk,
                    'total_harga' => $total_harga,
                    'status_keranjang' => $status_keranjang,
                    'waktu_ditambahkan' => date('Y-m-d H:i:s'),
                ];
                $update_cart = $this->M_pelanggan_keranjang->insert($data_keranjang);
            }
        } else {
            // Produk belum ada → tambahkan entri baru
            $data_keranjang = [
                'nama_pelanggan' => session()->get('nama_pelanggan'),
                'longitude' => session()->get('longitude'),
                'latitude' => session()->get('latitude'),
                'alamat' => session()->get('alamat'),
                'id_stok' => $id_stok,
                'nama_produk' => $nama_produk,
                'ukuran_produk' => $ukuran_produk,
                'jumlah_produk' => $jumlah_produk,
                'satuan_produk' => $satuan_produk,
                'harga_produk' => $harga_produk,
                'berat_produk' => $berat_produk,
                'total_harga' => $total_harga,
                'status_keranjang' => $status_keranjang,
                'waktu_ditambahkan' => date('Y-m-d H:i:s'),
            ];
            $update_cart = $this->M_pelanggan_keranjang->insert($data_keranjang);
        }

        if ($update_cart) {
            session()->setFlashdata('pesan_cart', 'Produk berhasil ditambahkan ke keranjang!');
            return redirect()->to(base_url('pelanggan_kelola_data/keranjang'));    
        } else {
            session()->setFlashdata('pesan_cart', 'Gagal menambahkan produk ke keranjang!');
            return redirect()->back();
        }
    }

    public function beli_sekarang()
    {
        // Ambil data dari form (sama seperti add_to_cart)
        $id_stok = $this->request->getPost('id_stok');
        $nama_produk = $this->request->getPost('nama_produk');
        $ukuran_produk_full = $this->request->getPost('ukuran_produk');
        $ukuran_parts = explode('-', $ukuran_produk_full);
        $ukuran_produk = trim($ukuran_parts[0]);
        $jumlah_produk = $this->request->getPost('jumlah_produk');
        $satuan_produk = $this->request->getPost('satuan_produk');
        $harga_produk = $this->request->getPost('harga_produk');
        $berat_produk = $this->request->getPost('berat_produk');
        $status_keranjang = 'proses';

        $total_harga = $jumlah_produk * $harga_produk;

        // Cek apakah item sudah ada di keranjang
        $existing_cart = $this->M_pelanggan_keranjang->get_cart_item(
            session()->get('nama_pelanggan'),
            $nama_produk,
            $id_stok
        );

        if ($existing_cart) {
            if ($existing_cart['status_keranjang'] === $status_keranjang) {
                $new_jumlah = $existing_cart['jumlah_produk'] + $jumlah_produk;
                $new_total_harga = $new_jumlah * $harga_produk;

                $this->M_pelanggan_keranjang->update_cart(
                    session()->get('nama_pelanggan'),
                    $id_stok,
                    $nama_produk,
                    $new_jumlah,
                    $new_total_harga
                );
                $id_keranjang = $existing_cart['id_keranjang'];
            } else {
                $data_keranjang = [
                    'nama_pelanggan' => session()->get('nama_pelanggan'),
                    'longitude' => session()->get('longitude'),
                    'latitude' => session()->get('latitude'),
                    'alamat' => session()->get('alamat'),
                    'id_stok' => $id_stok,
                    'nama_produk' => $nama_produk,
                    'ukuran_produk' => $ukuran_produk,
                    'jumlah_produk' => $jumlah_produk,
                    'satuan_produk' => $satuan_produk,
                    'harga_produk' => $harga_produk,
                    'berat_produk' => $berat_produk,
                    'total_harga' => $total_harga,
                    'status_keranjang' => $status_keranjang,
                    'waktu_ditambahkan' => date('Y-m-d H:i:s'),
                ];
                $this->M_pelanggan_keranjang->insert($data_keranjang);
                $id_keranjang = $this->db->insertID();
            }
        } else {
            $data_keranjang = [
                'nama_pelanggan' => session()->get('nama_pelanggan'),
                'longitude' => session()->get('longitude'),
                'latitude' => session()->get('latitude'),
                'alamat' => session()->get('alamat'),
                'id_stok' => $id_stok,
                'nama_produk' => $nama_produk,
                'ukuran_produk' => $ukuran_produk,
                'jumlah_produk' => $jumlah_produk,
                'satuan_produk' => $satuan_produk,
                'harga_produk' => $harga_produk,
                'berat_produk' => $berat_produk,
                'total_harga' => $total_harga,
                'status_keranjang' => $status_keranjang,
                'waktu_ditambahkan' => date('Y-m-d H:i:s'),
            ];
            $this->M_pelanggan_keranjang->insert($data_keranjang);
            $id_keranjang = $this->db->insertID();
        }

        // Arahkan langsung ke halaman beli (checkout) dengan item ini
        session()->setFlashdata('selected_products', [$id_keranjang]);
        return redirect()->to(base_url('pelanggan_kelola_data/beli'));
    }

    public function update_cart()
    {
        $id_keranjang = $this->request->getPost('id_keranjang');

        // Ambil data keranjang
        $keranjang = $this->M_pelanggan_keranjang->find($id_keranjang);
        if (!$keranjang) {
            return redirect()->back()->with('error', 'Data keranjang tidak ditemukan.');
        }

        // Cek apakah ada request untuk ganti jumlah
        $jumlah_baru = $this->request->getPost('jumlah_produk');
        if ($jumlah_baru && $jumlah_baru != $keranjang['jumlah_produk']) {

            // Validasi stok berdasarkan id_stok dan toko
            $tokoSesi = session()->get('toko_sesi_user');
            $stok = $this->db->table('tbl_stok_produk')
                ->where('id_stok', $keranjang['id_stok'])
                ->where('sesi_user', $tokoSesi)
                ->get()
                ->getRowArray();

            if (!$stok) {
                return redirect()->back()->with('error', 'Stok produk tidak ditemukan.');
            }

            if ($jumlah_baru > $stok['jumlah_stok_produk']) {
                return redirect()->back()->with('error', 'Jumlah melebihi stok yang tersedia.');
            }

            // Hitung ulang total harga
            $total_harga_baru = $jumlah_baru * $keranjang['harga_produk'];

            // Update jumlah produk
            $this->M_pelanggan_keranjang->update($id_keranjang, [
                'jumlah_produk' => $jumlah_baru,
                'total_harga' => $total_harga_baru
            ]);

            return redirect()->back()->with('success', 'Jumlah produk berhasil diperbarui.');
        }

        // Cek apakah ada request untuk ganti ukuran
        $ukuran_baru = $this->request->getPost('ukuran_produk');
        if ($ukuran_baru && $ukuran_baru != $keranjang['ukuran_produk']) {

            // Ambil data stok berdasarkan id_produk dan ukuran baru
            $tokoSesi = session()->get('toko_sesi_user');
            $stok_baru = $this->db->table('tbl_stok_produk')
                ->where('id_stok', $keranjang['id_stok'])
                ->where('sesi_user', $tokoSesi)
                ->get()
                ->getRowArray();

            if (!$stok_baru) {
                return redirect()->back()->with('error', 'Ukuran produk tidak tersedia.');
            }

            // Ambil jumlah produk saat ini
            $jumlah_sekarang = $keranjang['jumlah_produk'];

            // Validasi stok
            if ($jumlah_sekarang > $stok_baru['jumlah_stok_produk']) {
                return redirect()->back()->with('error', 'Jumlah pesanan melebihi stok ukuran baru.');
            }

            // Hitung ulang harga
            $total_harga_baru = $jumlah_sekarang * $stok_baru['harga_produk'];

            // Update ukuran dan harga
            $this->M_pelanggan_keranjang->update($id_keranjang, [
                'ukuran_produk' => $ukuran_baru,
                'harga_produk' => $stok_baru['harga_produk'],
                'id_stok' => $stok_baru['id_stok'], // jika pakai id_stok
                'total_harga' => $total_harga_baru
            ]);

            return redirect()->back()->with('success', 'Ukuran produk berhasil diperbarui.');
        }

        // Tidak ada perubahan
        return redirect()->back();
    }

    public function delete_cart($id_keranjang)
    {
        $data = [
            'id_keranjang' => $id_keranjang,
        ];
        $this->M_pelanggan_keranjang->delete_data($data);
        // Set flashdata untuk SweetAlert
        session()->setFlashdata('hapus_success', 'Produk berhasil dihapus dari keranjang.');
        return redirect()->to(base_url('pelanggan_kelola_data/keranjang'));
    }

    public function beli()
    {
        $session = session();
        $tokoUser = $session->get('toko_sesi_user') ?? $session->get('sesi_user');
        $keyword = $this->request->getVar('keyword') ?? '';

        $produkModel = new M_home_toko();
        $produk_data = $produkModel->getProduk($tokoUser, $keyword);

        foreach ($produk_data as &$produk) {
            $produk['ukuran_list'] = explode('-', $produk['ukuran_produk']);
        }
     
        // Ambil nama_pelanggan dari session
        $nama_pelanggan = session()->get('nama_pelanggan');
       
        // Ambil data pelanggan
        $data_pelanggan = $this->M_pelanggan_keranjang->get_user_by_id($nama_pelanggan);

        // Ambil produk yang dipilih dari POST request
        $selected_products = $this->request->getPost('selected_products');

        // Jika dari redirect validasi gagal, ambil dari flashdata
        if (empty($selected_products)) {
            $selected_products = session()->getFlashdata('selected_products');
        }

        if (empty($selected_products)) {
            session()->setFlashdata('error', 'Silakan pilih produk terlebih dahulu.');
            return redirect()->to('pelanggan_kelola_data/keranjang');
        }

        $ids = $selected_products;

        // 🔥 Ambil data alamat toko dari database
        $websiteModel = new \App\Models\M_pemilik_website();
        $dataToko = $websiteModel->get_website_by_nama($tokoUser);
        $dataToko = !empty($dataToko) ? $dataToko[0] : null;

        $latToko = $dataToko ? $dataToko['latitude_pusat'] : -6.9175;
        $lngToko = $dataToko ? $dataToko['longitude_pusat'] : 107.6191;

        // Hitung total harga
        $totalSemua = 0;
        $keranjang_terpilih = $this->M_pelanggan_keranjang->get_keranjang_by_ids($ids, $tokoUser);
        foreach ($keranjang_terpilih as $item) {
            $totalSemua += $item['harga_produk'] * $item['jumlah_produk'];
        }

        // Siapkan semua data untuk dikirim ke view
        $data = [
            'title' => 'Checkout',
            'title2' => 'Checkout',
            'produk_data' => $produk_data,
            'keranjang' => $data_pelanggan,
            'bank' => $this->M_pelanggan_beli->get_bank($tokoUser),
            'kurir' => $this->M_pelanggan_beli->get_kurir($tokoUser),
            'keranjang_terpilih' => $keranjang_terpilih,
            'selected_products' => $selected_products,
            'jenis_produk_dropdown' => $produkModel->getJenisProdukDropdown($tokoUser),
            'sesi_user' => $session->get('sesi_user', ''),
            'user_logged_in' => $session->get('user_logged_in') === true,

            // 📍 Data alamat pelanggan dari session (profil terbaru)
            'alamat' => session()->get('alamat') ?? $data_pelanggan['alamat'] ?? '',
            'latitude' => session()->get('latitude') ?? $data_pelanggan['latitude'] ?? '',
            'longitude' => session()->get('longitude') ?? $data_pelanggan['longitude'] ?? '',
            'kode_kota_pelanggan' => session()->get('kode_kota') ?? '',
            'nama_kota_pelanggan' => session()->get('nama_kota') ?? '',

            // 💡 Data lokasi toko
            'latitude_pusat' => $latToko,
            'longitude_pusat' => $lngToko,
            'kode_kota_toko' => $dataToko['kode_kota'] ?? '',
            'nama_kota_toko' => $dataToko['nama_kota'] ?? '',
            'nama_toko' => $dataToko['nama_toko'] ?? '',
            'alamat_toko' => $dataToko['alamat_pusat'] ?? '',

            // 💰 Total Belanja
            'totalSemua' => $totalSemua,
        ];

        return view('pelanggan/data_beli/v_beli', $data);
    }

    public function generateKodeTransaksi()
    {
        $nama_produk = $this->request->getVar('nama_produk');

        if (!$nama_produk) {
            return $this->response->setJSON(['error' => 'Nama produk tidak ditemukan']);
        }

        // Ambil huruf pertama dari setiap kata dan ubah ke huruf kapital
        $initials = implode('', array_map(fn($word) => strtoupper($word[0]), explode(' ', $nama_produk)));

        $date = date('Ymd');
        $prefix = 'T' . $date . '-' . $initials;

        $db = \Config\Database::connect();
        $table = $db->table('tbl_data_pembelian');

        $found = false;
        $tries = 0;
        $maxTries = 10;

        do {
            $random = strtoupper(bin2hex(random_bytes(2))); // Lebih kuat dan unik daripada md5
            $kode = $prefix . '-' . $random;

            $exists = $table->where('kode_beli', $kode)->countAllResults();

            if ($exists == 0) {
                $found = true;
            }

            $tries++;
        } while (!$found && $tries < $maxTries);

        if ($found) {
            return $this->response->setJSON(['kode_beli' => $kode]);
        } else {
            return $this->response->setJSON(['error' => 'Gagal menghasilkan kode beli']);
        }
    }

    public function save_beli()
    {
        $waktu_sekarang = date('Y-m-d H:i:s');
        $batas_waktu_bayar = date('Y-m-d H:i:s', strtotime('+24 hours', strtotime($waktu_sekarang)));

        $kode_beli = $this->request->getPost('kode_beli');
        $nama_pelanggan = $this->request->getPost('nama_pelanggan');
        $sesi_user = session()->get('toko_sesi_user') ?? $this->request->getPost('sesi_user');
        $longitude = $this->request->getPost('longitude');
        $latitude = $this->request->getPost('latitude');
        $alamat = $this->request->getPost('alamat');
        $bank_tujuan = $this->request->getPost('bank_tujuan');
        $no_rek = $this->request->getPost('no_rek');
        $a_n = $this->request->getPost('a_n');
        $jenis_kurir = $this->request->getPost('jenis_kurir');
        $ongkir = $this->request->getPost('ongkir');
        $estimasi_waktu = $this->request->getPost('estimasi_waktu');

        // Ambil produk yang dipilih (array id_keranjang)
        $selected_products = $this->request->getPost('selected_products'); // Pastikan ini ada di form

        // Simpan selected_products di flashdata agar bisa dipakai ulang jika redirect
        session()->setFlashdata('selected_products', $selected_products);

        if (!$this->validate([
            'nama_pelanggan' => ['label' => 'Nama Pelanggan', 'rules' => 'required'],
            'alamat' => ['label' => 'Alamat', 'rules' => 'required'],
            'bank_tujuan' => ['label' => 'Bank Tujuan', 'rules' => 'required'],
            'no_rek' => ['label' => 'Nomor Rekening', 'rules' => 'required|numeric'],
        ])) {
            session()->setFlashdata('errors', \Config\Services::validation()->getErrors());
            return redirect()->to(base_url('pelanggan_kelola_data/beli'));
        }

        $keranjangModel = new \App\Models\M_pelanggan_keranjang();

        // Ambil hanya keranjang yang dipilih dan sesuai toko
        $tokoSesi = session()->get('toko_sesi_user');
        $keranjang = $keranjangModel->get_keranjang_by_ids($selected_products, $tokoSesi);

        $total_harga = 0;
        $total_berat = 0;
        $pesan_produk = "";

        foreach ($keranjang as $item) {
            // ... proses tiap produk yang dipilih seperti sebelumnya

            $id_stok = $item['id_stok'];
            $nama_produk = $item['nama_produk'];
            $ukuran_produk = $item['ukuran_produk'];
            $jumlah_produk = $item['jumlah_produk'];
            $satuan_produk = $item['satuan_produk'];
            $waktu_ditambahkan = $item['waktu_ditambahkan'];
            $harga_produk = $item['harga_produk'];
            $berat_produk = $item['berat_produk'];

            $subtotal_harga = $harga_produk * $jumlah_produk;
            $subtotal_berat = $berat_produk * $jumlah_produk;

            $total_harga += $subtotal_harga;
            $total_berat += $subtotal_berat;

            $result = $this->M_admin_stok->kurangi_stok_fifo($nama_produk, $jumlah_produk);

            if (!$result['success']) {
                session()->setFlashdata('errors', $result['message']);
                return redirect()->to(base_url('pelanggan_kelola_data/beli'));
            }

            $data_pembelian = [
                'kode_beli' => $kode_beli,
                'sesi_user' => $sesi_user,
                'nama_pelanggan' => $nama_pelanggan,
                'id_stok' => $id_stok,
                'nama_produk' => $nama_produk,
                'ukuran_produk' => $ukuran_produk,
                'jumlah_produk' => $jumlah_produk,
                'satuan_produk' => $satuan_produk,
                'total_harga' => $subtotal_harga,
                'total_berat' => $subtotal_berat,
                'waktu_pembelian' => date('Y-m-d H:i:s'),
            ];

            $this->M_pelanggan_beli->add($data_pembelian);

            // Simpan data pembayaran sekali, setelah loop
            $data_pembayaran = [
                'sesi_user' => $sesi_user,
                'nama_pelanggan' => $nama_pelanggan,
                'nama_produk' => $nama_produk,
                'ukuran_produk' => $ukuran_produk,
                'jumlah_produk' => $jumlah_produk,
                'satuan_produk' => $satuan_produk,
                'total_harga' => $subtotal_harga,
                'total_bayar' => $subtotal_harga,
                'bank_tujuan' => $bank_tujuan,
                'no_rek' => $no_rek,
                'a_n' => $a_n,
                'batas_waktu_bayar' => $batas_waktu_bayar,
            ];
            $this->M_pelanggan_bayar->add($data_pembayaran);

            // Simpan data pembayaran per produk? Jika hanya sekali simpan di luar foreach.
            // Kalau mau sekali simpan, pindahkan keluar foreach.
            // Saat ini simpan di foreach, berarti akan banyak data pembayaran.
            // Kamu bisa kumpulkan total harga dulu baru simpan pembayaran di luar foreach.

            $pesan_produk .= "🛒 *Produk:* $nama_produk\n";
            $pesan_produk .= "🔢 *Jumlah:* $jumlah_produk $satuan_produk\n";
            $pesan_produk .= "💰 *Subtotal:* Rp. " . number_format($subtotal_harga, 0, ',', '.') . "\n\n";

            // Update status keranjang untuk produk yang diproses
            $this->M_pelanggan_keranjang->updateStatusKeranjang($nama_pelanggan, $waktu_ditambahkan);
        }

        // Simpan data ekspedisi
        $data_ekspedisi = [
            'nama_pelanggan' => $nama_pelanggan,
            'longitude' => $longitude,
            'latitude' => $latitude,
            'alamat' => $alamat,
            'jenis_kurir' => $jenis_kurir,
            'ongkir' => $ongkir,
            'estimasi_waktu' => $estimasi_waktu,
        ];
        $this->M_pelanggan_ekspedisi->add($data_ekspedisi);

        // Ambil nomor WA admin dan bersihkan agar hanya angka
        $nomor_wa_admin = get_data_toko()['wa_pusat'] ?? '08xxxx';
        $nomor_wa_admin = preg_replace('/[^0-9]/', '', $nomor_wa_admin);

        // Ganti awalan 0 ke 62
        if (substr($nomor_wa_admin, 0, 1) === '0') {
            $nomor_wa_admin = '62' . substr($nomor_wa_admin, 1);
        }

        $pesan = "Assalamu'alaikum, Halo Admin 👋\n";
        $pesan .= "Saya atas nama *$nama_pelanggan* ingin menyampaikan rincian pembelian dengan kode transaksi *$kode_beli* sebagai berikut:\n\n";
        $pesan .= $pesan_produk;
        $pesan .= "💳 *Total Bayar:* Rp. " . number_format($total_harga, 0, ',', '.') . "\n";
        $pesan .= "📌 *Status Pembayaran:* Belum Bayar\n";
        $pesan .= "🚚 *Kurir:* $jenis_kurir\n";
        $pesan .= "📍 *Alamat:* $alamat\n";

        // Encode pesan
        $pesan_encoded = rawurlencode($pesan);

        // Buat link
        $link_wa = "https://wa.me/$nomor_wa_admin?text=$pesan_encoded";

        // Debug (opsional): tampilkan link dulu
        // echo "<a href='$link_wa' target='_blank'>$link_wa</a>"; exit;

        session()->setFlashdata('pesan_beli', 'Pembelian berhasil!');

        // Buka di tab baru
        echo "<script> window.open('$link_wa', '_blank');
        window.location.href = '" . base_url('pelanggan_kelola_data/status_bayar') . "';
        </script>";
        exit;
        
    }

    public function status_bayar()
    {
        $session = session();
        $sesi_user = $session->get('toko_sesi_user') ?? $session->get('sesi_user');
        $keyword = $this->request->getVar('keyword') ?? '';

        $produkModel = new M_home_toko();

        $produk_data = $produkModel->getProduk($sesi_user, $keyword);

        foreach ($produk_data as &$produk) {
            $produk['ukuran_list'] = explode('-', $produk['ukuran_produk']);
        }

          $bayarModel = new M_pelanggan_bayar();
        $pembayaran = $bayarModel->get_bayar_by_status(session()->get('toko_sesi_user'));

        // Lokasi folder QR
        $folderPath = FCPATH . 'qr';
        if (!is_dir($folderPath)) {
            mkdir($folderPath, 0777, true);
        }

        // === Generate QR Pembayaran (Midtrans Snap URL) ===
        $midtrans = config('Midtrans');
        \Midtrans\Config::$serverKey = $midtrans->serverKey;
        \Midtrans\Config::$isProduction = $midtrans->isProduction;
        \Midtrans\Config::$isSanitized = $midtrans->isSanitized;
        \Midtrans\Config::$is3ds = $midtrans->is3ds;

        foreach ($pembayaran as &$value) {
            $orderId     = $value['id_bayar'];
            $total_harga = (int)$value['total_harga'];

            $qrFile  = $folderPath . '/qr_' . $orderId . '.png';
            $urlFile = $folderPath . '/qr_' . $orderId . '_url.txt';

            // Jika QR sudah ada, baca snap_url dari file teks
            if (file_exists($qrFile)) {
                $value['qr_url']   = base_url("qr/qr_{$orderId}.png");
                $value['snap_url'] = file_exists($urlFile) ? file_get_contents($urlFile) : null;
                continue;
            }

            $params = [
                'transaction_details' => [
                    'order_id'      => $orderId,
                    'gross_amount'  => $total_harga,
                ],
                'customer_details' => [
                    'first_name' => $value['nama_pelanggan'] ?? '',
                ],
                'enabled_payments' => [
                    'gopay', 'qris',
                    'bca_va', 'bni_va', 'mandiri_va', 'bri_va', 'other_va',
                    'danamon_online', 'shopeepay',
                ],
            ];

            try {
                $snapUrl = \Midtrans\Snap::getSnapUrl($params);
                if (!$snapUrl) {
                    throw new \Exception("Snap URL kosong");
                }

                \QRcode::png($snapUrl, $qrFile, QR_ECLEVEL_L, 4);
                file_put_contents($urlFile, $snapUrl);

                $value['qr_url']   = base_url("qr/qr_{$orderId}.png");
                $value['snap_url'] = $snapUrl;
            } catch (\Exception $e) {
                log_message('error', 'QRIS Gagal: ' . $e->getMessage());
                $value['qr_url']   = null;
                $value['snap_url'] = null;
            }
        }

        $data = [
            'title' => 'Riwayat Pembayaran',
            'title2' => 'Riwayat Pembayaran',
            'produk_data' => $produk_data,
            'pembayaran' => $pembayaran,
            'jenis_produk_dropdown' => $produkModel->getJenisProdukDropdown($sesi_user),
            'user_logged_in' => $session->get('user_logged_in') === true,
        ];

        return view('pelanggan/riwayat_pembayaran/v_status_bayar', $data);
    }

    public function riwayat_bayar()
    {
        $session = session();
        $tokoSesi = $session->get('toko_sesi_user') ?? $session->get('sesi_user');
        $namaPelanggan = $session->get('nama_pelanggan');
        $keyword = $this->request->getVar('keyword') ?? '';

        $produkModel = new M_home_toko();
        $produk_data = $produkModel->getProduk($tokoSesi, $keyword);

        foreach ($produk_data as &$produk) {
            $produk['ukuran_list'] = explode('-', $produk['ukuran_produk']);
        }

        // Data riwayat hanya untuk pembeli ini di toko yang sedang aktif
        $pembayaran = $this->M_pelanggan_bayar->get_riwayat_bayar_pelanggan($tokoSesi, $namaPelanggan);

        // Produk yang sudah direview oleh pembeli ini di toko aktif
        $reviewModel = new \App\Models\M_review();
        $userReviews = $reviewModel->get_user_reviews($tokoSesi, $namaPelanggan);
        $reviewedProducts = array_column($userReviews, 'nama_produk');

        $data = [
            'title' => 'Riwayat Pembayaran',
            'title2' => 'Riwayat Pembayaran',
            'produk_data' => $produk_data,
            'pembayaran' => $pembayaran,
            'reviewedProducts' => $reviewedProducts,
            'jenis_produk_dropdown' => $produkModel->getJenisProdukDropdown($tokoSesi),
            'user_logged_in' => $session->get('user_logged_in') === true,
        ];

        return view('pelanggan/riwayat_pembayaran/v_riwayat_bayar', $data);
    }

    public function nota_pembelian($id_bayar)
    {
        $session = session();
        $sesi_user = $session->get('toko_sesi_user') ?? $session->get('sesi_user');
        $keyword = $this->request->getVar('keyword') ?? '';

        $produkModel = new M_home_toko();
        $produk_data = $produkModel->getProduk($sesi_user, $keyword);

        foreach ($produk_data as &$produk) {
            $produk['ukuran_list'] = explode('-', $produk['ukuran_produk']);
        }

        $data = [
            'title' => 'Nota Pembelian',
            'title2' => 'Nota Pembelian',
            'produk_data' => $produk_data,
            'pembayaran' => $this->M_pelanggan_bayar->detailBayar($id_bayar),
            'jenis_produk_dropdown' => $produkModel->getJenisProdukDropdown($sesi_user),
            'user_logged_in' => $session->get('user_logged_in') === true,
        ];

        return view('pelanggan/riwayat_pembayaran/v_nota_beli', $data);
    }

    public function unduh_nota_pdf($id_bayar)
    {
        $session = session();
        if (!$session->get('nama_pelanggan')) {
            return redirect()->to(base_url('auth/login_user'));
        }

        $pembayaran = $this->M_pelanggan_bayar->detailBayar($id_bayar);
        if (empty($pembayaran)) {
            session()->setFlashdata('pesan', 'Data pembayaran tidak ditemukan.');
            return redirect()->to(base_url('pelanggan_kelola_data/riwayat_bayar'));
        }

        // Hanya pemilik nota yang boleh mengunduh
        if (strcasecmp($pembayaran['nama_pelanggan'] ?? '', $session->get('nama_pelanggan')) !== 0) {
            return redirect()->to(base_url('pelanggan_kelola_data/riwayat_bayar'));
        }

        // ---- Siapkan data ----
        $toko = get_data_toko();
        $namaToko = $toko['nama_toko'] ?? 'Toko Default';
        $alamatToko = $toko['alamat_pusat'] ?? '-';
        $waToko = $toko['wa_pusat'] ?? '-';
        $logoToko = $toko['logo_website'] ?? '';

        $bulan = [1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $ts = strtotime($pembayaran['waktu_pembayaran'] ?? 'now');
        $tanggalNota = date('d', $ts) . ' ' . $bulan[(int) date('n', $ts)] . ' ' . date('Y', $ts) . ' ' . date('H:i', $ts) . ' WIB';

        $hargaSatuan = (float) ($pembayaran['harga_produk'] ?? 0);
        $jumlah = (int) ($pembayaran['jumlah_produk'] ?? 1);
        $subtotal = $hargaSatuan * $jumlah;
        $ongkir = (float) ($pembayaran['ongkir'] ?? 0);
        $totalBayar = (float) ($pembayaran['total_bayar'] ?? ($subtotal + $ongkir));
        if ($totalBayar <= 0) {
            $totalBayar = $subtotal + $ongkir;
        }
        $beratSatuan = (float) ($pembayaran['berat_produk'] ?? 0);
        $satuanBerat = $pembayaran['satuan_berat'] ?? 'gr';
        $namaProduk = $pembayaran['nama_produk'] ?? '-';
        $kodeBeli = $pembayaran['kode_beli'] ?? ('NOTA-' . $id_bayar);
        $status = $pembayaran['status_bayar'] ?? '-';

        // ---- TCPDF ----
        $oldLevel = error_reporting();
        error_reporting($oldLevel & ~E_DEPRECATED & ~E_NOTICE & ~E_WARNING);

        require_once ROOTPATH . 'vendor/tecnickcom/tcpdf/tcpdf.php';

        $pdf = new \TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator('ecomm_ci4');
        $pdf->SetAuthor($namaToko);
        $pdf->SetTitle('Nota ' . $kodeBeli);
        $pdf->SetSubject('Nota Pembelian');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetMargins(12, 12, 12);
        $pdf->SetAutoPageBreak(false, 12);
        $pdf->AddPage();

        $blue = [30, 60, 114];
        $dark = [33, 37, 41];
        $gray = [108, 117, 125];
        $light = [248, 249, 250];
        $lineColor = [222, 226, 230];
        $w = 186; // lebar konten

        // ===== Header band =====
        $pdf->SetFillColor($blue[0], $blue[1], $blue[2]);
        $pdf->Rect(12, 12, $w, 36, 'F');

        // Logo
        $logoPath = FCPATH . 'logowebsite/' . $logoToko;
        $logoW = 0;
        if (!empty($logoToko) && is_file($logoPath)) {
            $pdf->SetFillColor(255, 255, 255);
            $pdf->Rect(16, 16, 28, 28, 'F');
            $pdf->Image($logoPath, 17, 17, 26, 26, '', '', '', true, 300, '', false, false, false, false, false, true);
            $logoW = 34;
        }

        // Blok kanan (judul, kode, status)
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetXY(138, 15);
        $pdf->SetFont('helvetica', 'B', 17);
        $pdf->Cell(60, 8, 'NOTA PEMBELIAN', 0, 1, 'R');
        $pdf->SetX(138);
        $pdf->SetFont('helvetica', '', 10);
        $pdf->Cell(60, 5, 'Kode: ' . $kodeBeli, 0, 1, 'R');
        $statusColor = [108, 117, 125];
        if ($status === 'Dibayar') {
            $statusColor = [40, 167, 69];
        } elseif ($status === 'Belum Bayar') {
            $statusColor = [255, 193, 7];
        } elseif ($status === 'Dibatalkan') {
            $statusColor = [220, 53, 69];
        }
        $statusW = $pdf->GetStringWidth($status) + 8;
        $statusX = 198 - $statusW;
        $pdf->SetFillColor($statusColor[0], $statusColor[1], $statusColor[2]);
        $pdf->RoundedRect($statusX, 36, $statusW, 7, 1, 'F');
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->SetXY($statusX, 36.5);
        $pdf->Cell($statusW, 5, $status, 0, 0, 'C');

        // Blok kiri (nama toko, alamat, telepon)
        $leftX = 16 + $logoW;
        $leftW = 138 - $leftX;
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFont('helvetica', 'B', 15);
        $pdf->SetXY($leftX, 16);
        $pdf->MultiCell($leftW, 7, $namaToko, 0, 'L', false, 1);
        $pdf->SetFont('helvetica', '', 9);
        $pdf->SetX($leftX);
        $pdf->MultiCell($leftW, 4.5, 'Alamat: ' . $alamatToko, 0, 'L', false, 1);
        $pdf->SetX($leftX);
        $pdf->MultiCell($leftW, 4.5, 'Telepon/WA: ' . $waToko, 0, 'L', false, 1);

        $pdf->SetTextColor(0, 0, 0);
        $Y = 12 + 36 + 8; // = 56

        // ===== Info boxes =====
        $boxH = 46;
        $boxes = [
            [12, 'PENERIMA / PEMBELI',
                "Nama   : " . ($pembayaran['nama_pelanggan'] ?? '-') . "\n"
                . "Telp    : " . ($pembayaran['no_telpon'] ?? '-') . "\n"
                . "Email   : " . ($pembayaran['email'] ?? '-') . "\n"
                . "Alamat : " . ($pembayaran['alamat'] ?? '-')],
            [74.5, 'PENGIRIMAN',
                "Kurir     : " . ($pembayaran['jenis_kurir'] ?? '-') . "\n"
                . "Ongkir   : Rp " . number_format($ongkir, 0, ',', '.') . "\n"
                . "Estimasi : " . ($pembayaran['estimasi_waktu'] ?? '-') . "\n"
                . "Waktu   : " . $tanggalNota],
            [137, 'PEMBAYARAN',
                "Bank    : " . ($pembayaran['bank_tujuan'] ?? '-') . "\n"
                . "No. Rek : " . ($pembayaran['no_rek'] ?? '-') . "\n"
                . "A/N      : " . ($pembayaran['a_n'] ?? '-') . "\n"
                . "ID Bayar: " . ($pembayaran['id_bayar'] ?? '-')],
        ];

        foreach ($boxes as $b) {
            $x = $b[0];
            $pdf->SetFillColor($light[0], $light[1], $light[2]);
            $pdf->SetDrawColor($lineColor[0], $lineColor[1], $lineColor[2]);
            $pdf->Rect($x, $Y, 60.5, $boxH, 'DF');
            $pdf->SetTextColor($blue[0], $blue[1], $blue[2]);
            $pdf->SetFont('helvetica', 'B', 9);
            $pdf->SetXY($x + 3, $Y + 3);
            $pdf->Cell(54, 5, $b[1], 0, 1, 'L');
            $pdf->SetTextColor($dark[0], $dark[1], $dark[2]);
            $pdf->SetFont('helvetica', '', 8.5);
            $pdf->SetXY($x + 3, $Y + 10);
            $pdf->MultiCell(54, 4.5, $b[2], 0, 'L', false, 1);
        }

        $Y += $boxH + 8;

        // ===== Tabel item =====
        $colW = [10, 66, 24, 26, 30, 30];
        $rowH = 8;
        $pdf->SetFillColor($blue[0], $blue[1], $blue[2]);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->SetXY(12, $Y);
        foreach (['No', 'Produk', 'Ukuran', 'Jumlah', 'Harga Satuan', 'Subtotal'] as $i => $h) {
            $align = ($i === 0) ? 'C' : (($i >= 2) ? 'C' : 'L');
            $pdf->Cell($colW[$i], $rowH, $h, 1, 0, $align, true);
        }
        $Y = $pdf->GetY();

        // Baris isi
        $rowH = 20;
        $pdf->SetTextColor($dark[0], $dark[1], $dark[2]);
        $pdf->SetFillColor(255, 255, 255);
        $pdf->SetXY(12, $Y);
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell($colW[0], $rowH, '1', 1, 0, 'C');

        $prodX = 22;
        $imgPath = FCPATH . 'fotoproduk/' . ($pembayaran['foto_produk'] ?? '');
        $imgW = 0;
        if (!empty($pembayaran['foto_produk']) && is_file($imgPath)) {
            $pdf->Image($imgPath, $prodX + 2, $Y + 2, 16, 16, '', '', '', true, 300, '', false, false, false, false, false, true);
            $imgW = 20;
        }
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->SetXY($prodX + 2 + $imgW, $Y + 3);
        $pdf->MultiCell($colW[1] - 4 - $imgW, 4.5, $namaProduk, 0, 'L', false, 1);
        $pdf->SetFont('helvetica', '', 8);
        $pdf->SetX($prodX + 2 + $imgW);
        $pdf->MultiCell($colW[1] - 4 - $imgW, 4, 'Berat: ' . number_format($beratSatuan, 0, ',', '.') . ' ' . $satuanBerat, 0, 'L', false, 1);

        $pdf->SetXY(88, $Y);
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell($colW[2], $rowH, ($pembayaran['ukuran_produk'] ?? '-'), 1, 0, 'C');
        $pdf->Cell($colW[3], $rowH, $jumlah . ' ' . ($pembayaran['satuan_produk'] ?? ''), 1, 0, 'C');
        $pdf->Cell($colW[4], $rowH, 'Rp ' . number_format($hargaSatuan, 0, ',', '.'), 1, 0, 'R');
        $pdf->Cell($colW[5], $rowH, 'Rp ' . number_format($subtotal, 0, ',', '.'), 1, 0, 'R');

        $Y = $pdf->GetY() + $rowH;

        // ===== Ringkasan total =====
        $totW = 70;
        $totX = 12 + $w - $totW;
        $pdf->SetY($Y + 6);
        $pdf->SetTextColor($dark[0], $dark[1], $dark[2]);
        $pdf->SetFont('helvetica', '', 9.5);
        $pdf->SetX($totX);
        $pdf->Cell($totW, 6, 'Subtotal Produk   Rp ' . number_format($subtotal, 0, ',', '.'), 0, 1, 'L');
        $pdf->SetX($totX);
        $pdf->Cell($totW, 6, 'Ongkir                    Rp ' . number_format($ongkir, 0, ',', '.'), 0, 1, 'L');
        $pdf->SetDrawColor($lineColor[0], $lineColor[1], $lineColor[2]);
        $pdf->Line($totX, $pdf->GetY(), $totX + $totW, $pdf->GetY());
        $pdf->SetFont('helvetica', 'B', 12);
        $pdf->SetTextColor($blue[0], $blue[1], $blue[2]);
        $pdf->SetX($totX);
        $pdf->Cell($totW, 8, 'TOTAL BAYAR  Rp ' . number_format($totalBayar, 0, ',', '.'), 0, 1, 'L');

        $Y = $pdf->GetY() + 16;

        // ===== Tanda tangan =====
        $pdf->SetTextColor($dark[0], $dark[1], $dark[2]);
        $pdf->SetFont('helvetica', '', 9);
        $pdf->SetXY(12, $Y);
        $pdf->Cell(80, 5, 'Pembeli', 0, 0, 'C');
        $pdf->SetXY(106, $Y);
        $pdf->Cell(80, 5, $namaToko, 0, 0, 'C');
        $pdf->SetXY(12, $Y + 32);
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->Cell(80, 5, $pembayaran['nama_pelanggan'] ?? '-', 0, 0, 'C');
        $pdf->SetXY(106, $Y + 32);
        $pdf->Cell(80, 5, 'Admin Toko', 0, 0, 'C');

        $Y += 42;

        // ===== Ucapan terima kasih =====
        $pdf->SetY($Y);
        $pdf->SetTextColor($gray[0], $gray[1], $gray[2]);
        $pdf->SetFont('helvetica', '', 9);
        $pdf->Cell($w, 5, 'Terima kasih telah berbelanja di ' . $namaToko, 0, 1, 'C');
        $pdf->Cell($w, 5, 'Untuk pertanyaan lebih lanjut hubungi ' . $waToko, 0, 1, 'C');

        $pdf->lastPage();

        error_reporting($oldLevel);

        $namaFile = 'Nota_' . preg_replace('/[^A-Za-z0-9_-]/', '', $kodeBeli) . '.pdf';
        $pdf->Output($namaFile, 'D');
        exit;
    }

    public function lihat_bayar($id_bayar)
    {
        $session = session();
        $sesi_user = $session->get('toko_sesi_user') ?? $session->get('sesi_user');
        $keyword = $this->request->getVar('keyword') ?? '';

        $produkModel = new M_home_toko();
        $produk_data = $produkModel->getProduk($sesi_user, $keyword);

        foreach ($produk_data as &$produk) {
            $produk['ukuran_list'] = explode('-', $produk['ukuran_produk']);
        }

        $data = [
            'title' => 'Pembayaran',
            'title2' => 'Pembayaran',
            'produk_data' => $produk_data,
            'pembayaran' => $this->M_pelanggan_bayar->detailBayar($id_bayar),
            'jenis_produk_dropdown' => $produkModel->getJenisProdukDropdown($sesi_user),
            'user_logged_in' => $session->get('user_logged_in') === true,
        ];

        return view('pelanggan/data_bayar/v_lihat_bayar', $data);
    }

    public function add_bayar($id_bayar)
    {
        $session = session();
        $sesi_user = $session->get('toko_sesi_user') ?? $session->get('sesi_user');
        $keyword = $this->request->getVar('keyword') ?? '';

        $produkModel = new M_home_toko();
        $produk_data = $produkModel->getProduk($sesi_user, $keyword);

        foreach ($produk_data as &$produk) {
            $produk['ukuran_list'] = explode('-', $produk['ukuran_produk']);
        }

        // Cek jika nama_pelanggan tidak ditemukan dalam session
        if (empty($session->get('sesi_user'))) {
            return redirect()->to('auth/login_user');
        }

        $id_beli = session()->get('id_beli'); // Ambil id_unit dari session

        // Cari nama unit berdasarkan id_unit
        $data_beli = $this->M_pelanggan_bayar->get_beli_by_id($id_beli);

        $data = [
            'title' => 'Pembayaran',
            'title2' => 'Pembayaran',
            'produk_data' => $produk_data,
            'pembayaran' => $this->M_pelanggan_bayar->detailBayar($id_bayar),
            'jenis_produk_dropdown' => $produkModel->getJenisProdukDropdown($sesi_user),
            'sesi_user' => $session->get('sesi_user', ''),
            'id_beli' => $data_beli ? $data_beli['id_beli'] : 'ID Tidak Ditemukan',
            'user_logged_in' => $session->get('user_logged_in') === true,
        ];
        return view('pelanggan/data_bayar/v_bayar', $data);
    }

    public function save_bayar($id_bayar)
    {
        if ($this->validate([
            'foto_bayar' => [
                'label' => 'Foto Bayar',
                'rules' => 'uploaded[foto_bayar]|max_size[foto_bayar,1792]|mime_in[foto_bayar,image/png,image/jpg,image/jpeg,image/gif,image/ico]',
                'errors' => [
                    'uploaded' => '{field} Wajib Diisi !!!',
                    'max_size' => '{field} Max 1792 KB !!!',
                    'mime_in' => 'Format {field} Wajib PNG, JPG, JPEG, GIF, ICO !!!'
                ]
            ],
        ])) {
            $sesi_user = $this->request->getPost('sesi_user');
            $nama_pelanggan = $this->request->getPost('nama_pelanggan');
            $id_beli = $this->request->getPost('id_beli');
            $foto = $this->request->getFile('foto_bayar');
            $nama_file = $foto->getRandomName();

            $data = [
                'id_bayar' => $id_bayar,
                'nama_pelanggan' => $nama_pelanggan,
                'id_beli' => $id_beli,
                'sesi_user' => $sesi_user,
                'foto_bayar' => $nama_file
            ];

            $foto->move('fotobayar', $nama_file);

            // Konfirmasi pembayaran
            $this->M_pelanggan_bayar->konfirmasi_pembayaran($id_bayar, $id_beli, $nama_pelanggan);

            // Simpan bukti bayar
            $this->M_pelanggan_bayar->edit($data);

            session()->setFlashdata('pesan', 'Data Bayar Berhasil Ditambahkan !');
            return redirect()->to(base_url('home_toko/index'));
        } else {
            session()->setFlashdata('errors', \Config\Services::validation()->getErrors());
            return redirect()->to(base_url('pelanggan_kelola_data/add_bayar/' . $id_bayar));
        }
    }

    public function midtrans_notification()
    {
        $json = file_get_contents('php://input');
        $notification = json_decode($json, true);

        if (!is_array($notification) || empty($notification['order_id'])) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Invalid payload']);
        }

        $midtrans = config('Midtrans');
        \Midtrans\Config::$serverKey = $midtrans->serverKey;
        \Midtrans\Config::$isProduction = $midtrans->isProduction;
        \Midtrans\Config::$isSanitized = $midtrans->isSanitized;
        \Midtrans\Config::$is3ds = $midtrans->is3ds;

        $orderId    = $notification['order_id'];
        $statusCode = $notification['status_code'];
        $grossAmount = $notification['gross_amount'];
        $serverKey  = $midtrans->serverKey;

        $expectedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);
        $givenSignature    = $notification['signature_key'] ?? '';

        if (!hash_equals($expectedSignature, $givenSignature)) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'Invalid signature']);
        }

        $transactionStatus = $notification['transaction_status'];
        $fraudStatus       = $notification['fraud_status'] ?? null;

        if ($transactionStatus == 'capture') {
            if ($fraudStatus == 'accept') {
                $this->M_pelanggan_bayar->konfirmasi_via_midtrans($orderId, 'Dibayar');
            }
        } else if ($transactionStatus == 'settlement') {
            $this->M_pelanggan_bayar->konfirmasi_via_midtrans($orderId, 'Dibayar');
        } else if (in_array($transactionStatus, ['cancel', 'expire', 'deny'])) {
            $this->M_pelanggan_bayar->konfirmasi_via_midtrans($orderId, 'Dibatalkan');
        }

        return $this->response->setStatusCode(200)->setJSON(['status' => 'ok']);
    }

    public function search_destination()
    {
        $keyword = $this->request->getGet('search');
        if (empty($keyword) || strlen($keyword) < 2) {
            return $this->response->setJSON([]);
        }

        $lat = (float) ($this->request->getGet('lat') ?? 0);
        $lng = (float) ($this->request->getGet('lng') ?? 0);

        $ongkirModel = new \App\Models\M_ongkir_api();
        $results = $ongkirModel->searchDestination($keyword, $lat, $lng);

        if (isset($results['error'])) {
            return $this->response->setJSON(['error' => $results['error']]);
        }

        return $this->response->setJSON(['data' => $results]);
    }

    public function refine_alamat()
    {
        $desa     = trim((string) ($this->request->getGet('desa') ?? ''));
        $kota     = trim((string) ($this->request->getGet('kota') ?? ''));
        $provinsi = trim((string) ($this->request->getGet('provinsi') ?? ''));
        $lat      = (float) ($this->request->getGet('lat') ?? 0);
        $lng      = (float) ($this->request->getGet('lng') ?? 0);

        $ongkirModel = new \App\Models\M_ongkir_api();
        $result = $ongkirModel->refineAddress($desa, $kota, $provinsi, $lat, $lng);

        return $this->response->setJSON($result);
    }

    public function hitung_ongkir()
    {
        $origin      = (int) $this->request->getPost('origin');
        $destination = (int) $this->request->getPost('destination');
        $weight      = (int) $this->request->getPost('weight');
        $courier     = $this->request->getPost('courier') ?: 'jne:jnt:sicepat';

        if (!$origin || !$destination || !$weight) {
            return $this->response->setJSON(['error' => 'Parameter tidak lengkap']);
        }

        $ongkirModel = new \App\Models\M_ongkir_api();
        $results = $ongkirModel->calculateCost($origin, $destination, $weight, $courier);

        if (isset($results['error'])) {
            return $this->response->setJSON(['error' => $results['error']]);
        }

        return $this->response->setJSON(['data' => $results]);
    }

    public function hitung_ongkir_lokal()
    {
        $jarakKm = (float) $this->request->getPost('jarak');
        $tokoUser = session()->get('toko_sesi_user') ?? session()->get('sesi_user');

        if ($jarakKm <= 0) {
            return $this->response->setJSON(['data' => []]);
        }

        $kurirModel = new \App\Models\M_pelanggan_beli();
        $kurirList = $kurirModel->get_kurir($tokoUser);

        $hasil = [];
        foreach ($kurirList as $kurir) {
            $biaya = round($kurir['ongkir'] * $jarakKm);
            $estimasiMenit = ceil($jarakKm * 3);
            $estimasi = $estimasiMenit < 60
                ? $estimasiMenit . ' menit'
                : round($estimasiMenit / 60, 1) . ' jam';

            $hasil[] = [
                'name'    => $kurir['jenis_kurir'],
                'service' => 'Lokal',
                'cost'    => $biaya,
                'etd'     => $estimasi,
            ];
        }

        return $this->response->setJSON(['data' => $hasil]);
    }

    public function review_submit()
    {
        $session = session();
        $nama_pelanggan = $session->get('nama_pelanggan');
        if (!$nama_pelanggan) {
            return redirect()->to('auth/login_pelanggan');
        }

        if (!$this->validate([
            'nama_produk' => 'required',
            'rating'      => 'required|numeric|greater_than[0]|less_than[6]',
            'ulasan'      => 'permit_empty|max_length[500]',
        ])) {
            return redirect()->back()->with('errors', \Config\Services::validation()->getErrors());
        }

        $nama_produk = $this->request->getPost('nama_produk');
        $rating      = (int)$this->request->getPost('rating');
        $ulasan      = $this->request->getPost('ulasan');
        $tokoSesi    = $session->get('toko_sesi_user');

        $reviewModel = new M_review();

        if ($reviewModel->has_reviewed($nama_pelanggan, $nama_produk, $tokoSesi)) {
            return redirect()->back()->with('pesan_warning', 'Anda sudah pernah mereview produk ini.');
        }

        $reviewModel->insert([
            'sesi_user'       => $tokoSesi,
            'nama_pelanggan'  => $nama_pelanggan,
            'nama_produk'     => $nama_produk,
            'rating'          => $rating,
            'ulasan'          => $ulasan,
            'waktu_review'    => date('Y-m-d H:i:s'),
        ]);

        return redirect()->back()->with('pesan_success', 'Review berhasil dikirim.');
    }

    public function review_update()
    {
        $session = session();
        $nama_pelanggan = $session->get('nama_pelanggan');
        if (!$nama_pelanggan) {
            return redirect()->to('auth/login_pelanggan');
        }

        if (!$this->validate([
            'nama_produk' => 'required',
            'rating'      => 'required|numeric|greater_than[0]|less_than[6]',
            'ulasan'      => 'permit_empty|max_length[500]',
        ])) {
            return redirect()->back()->with('errors', \Config\Services::validation()->getErrors());
        }

        $nama_produk = $this->request->getPost('nama_produk');
        $rating      = (int)$this->request->getPost('rating');
        $ulasan      = $this->request->getPost('ulasan');
        $tokoSesi    = $session->get('toko_sesi_user');

        $reviewModel = new M_review();
        $review = $reviewModel->get_user_review($tokoSesi, $nama_pelanggan, $nama_produk);

        if (!$review) {
            return redirect()->back()->with('pesan_warning', 'Review tidak ditemukan.');
        }

        $reviewModel->update($review['id_review'], [
            'rating'       => $rating,
            'ulasan'       => $ulasan,
            'waktu_review' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->back()->with('pesan_success', 'Review berhasil diperbarui.');
    }

    public function review_my_review($nama_produk)
    {
        $session = session();
        $nama_pelanggan = $session->get('nama_pelanggan');
        $tokoSesi = $session->get('toko_sesi_user');

        if (!$nama_pelanggan || !$tokoSesi) {
            return $this->response->setJSON(['review' => null]);
        }

        $reviewModel = new M_review();
        $review = $reviewModel->get_user_review($tokoSesi, $nama_pelanggan, $nama_produk);

        return $this->response->setJSON(['review' => $review]);
    }

    public function review_get($nama_produk)
    {
        $session = session();
        $tokoSesi = $session->get('toko_sesi_user');
        $rating = $this->request->getGet('rating');

        $reviewModel = new M_review();
        $reviews = $reviewModel->get_reviews($tokoSesi, $nama_produk, $rating);
        $summary = $reviewModel->get_rating_summary($tokoSesi, $nama_produk);

        return $this->response->setJSON([
            'reviews' => $reviews,
            'summary' => $summary,
        ]);
    }
}
