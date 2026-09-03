<?php

namespace App\Controllers;

use CodeIgniter\Controller;

use App\Models\M_home_toko;
use App\Models\M_pemilik_produk;
use App\Models\M_pelanggan_bayar;

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

        // Jika belum pilih toko, redirect ke halaman pilih toko
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
            'jenis_produk_dropdown' => $produkModel->getJenisProdukDropdown($sesi_user),
            'data_produk' => $this->M_pemilik_produk->get_carousel(),
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

        // Ambil sort berdasarkan jenis
        $sort_harga = $this->request->getVar('sort_harga');
        $sort_nama = $this->request->getVar('sort_nama');

        // Tentukan sort_by berdasarkan prioritas
        $sort_by = $sort_harga ?: $sort_nama;

        $produkModel = new \App\Models\M_home_toko();

        $produk_data = $produkModel->getProduk(
            $sesi_user,
            $keyword,
            $harga_min,
            $harga_max,
            $sort_by,
            $jenis_produk
        );

        foreach ($produk_data as &$produk) {
            $produk['ukuran_list'] = explode('-', $produk['ukuran_produk']);
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
                'jenis_produk' => $jenis_produk,
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

        foreach ($produk_data as &$produk) {
            $produk['ukuran_list'] = explode('-', $produk['ukuran_produk']);
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

        $data = [
            'title' => 'Toko Online',
            'title2' => 'Toko Online',
            'produk_data' => $produk_data,
            'user_logged_in' => $session->get('user_logged_in') === true,
            'jenis_produk_dropdown' => $produkModel->getJenisProdukDropdown($sesi_user),
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

        $data = [
            'title' => 'Toko Online',
            'title2' => 'Toko Online',
            'produk_data' => $produk_data,
            'user_logged_in' => $session->get('user_logged_in') === true,
            'jenis_produk_dropdown' => $produkModel->getJenisProdukDropdown($sesi_user),
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
}