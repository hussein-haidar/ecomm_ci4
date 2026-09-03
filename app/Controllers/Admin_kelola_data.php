<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\M_admin_stok;
use App\Models\M_pelanggan_beli;
use App\Models\M_pelanggan_bayar;
use App\Models\M_pemilik_produk;

class Admin_kelola_data extends BaseController
{
    protected $M_admin_stok;
    protected $M_pelanggan_beli;
    protected $M_pelanggan_bayar;
    protected $M_pemilik_produk;
    protected $db;

    public function __construct()
    {
        $this->M_admin_stok = new M_admin_stok();
        $this->M_pelanggan_beli = new M_pelanggan_beli();
        $this->M_pelanggan_bayar = new M_pelanggan_bayar();
        $this->M_pemilik_produk = new M_pemilik_produk();
        $this->db = \Config\Database::connect();
    }

    public function view_produk()
    {
        $data = [
            'title' => 'Daftar Produk',
            'title2' => 'Data Produk',
            'data_produk' => $this->M_pemilik_produk->get_produk(),
            'isi' => 'admin/view_produk/v_produk',
        ];
        return view('layout/v_template', $data);
    }

    public function stok()
    {
        $data = [
            'title' => 'Daftar Stok Produk',
            'title2' => 'Data Stok Produk',
            'data_stok' => $this->M_admin_stok->get_stok(),
            'isi' => 'admin/stok_produk/v_stok',
        ];
        return view('layout/v_template', $data);
    }
    
    public function v_tot_stok()
    {
        $data = [
            'title' => 'Daftar Total Stok Produk',
            'title2' => 'Data Total Stok',
            'data_stok' => $this->M_admin_stok->get_tot_stok(),
            'isi' => 'admin/stok_produk/v_tot_stok',
        ];
        return view('layout/v_template', $data);
    }

    public function generateKodeStok()
    {
        // Ambil nama_produk dari request
        $nama_produk = $this->request->getVar('nama_produk');

        if (!$nama_produk) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Nama produk tidak diberikan'
            ]);
        }

        // Cek apakah nama_produk ada di tabel tbl_data_produk
        $produk = $this->db->table('tbl_data_produk')
            ->where('nama_produk', $nama_produk)
            ->get()
            ->getRowArray();

        if (!$produk) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Nama produk tidak ditemukan di database'
            ]);
        }

        // Generate kode produk: ambil 2 huruf awal dari kata pertama dan kedua
        $kata_produk = explode(' ', $nama_produk);
        $kode_produk = '';

        if (isset($kata_produk[0])) {
            $kode_produk .= strtoupper(substr($kata_produk[0], 0, 1)); // Huruf pertama dari kata pertama
        }
        if (isset($kata_produk[1])) {
            $kode_produk .= strtoupper(substr($kata_produk[1], 0, 1)); // Huruf pertama dari kata kedua
        }

        // Inisialisasi nomor urut
        $nextNumber = '001';
        $found = false;

        do {
            // Buat kode stok dengan format: SXX-001
            $newKodeStok = 'S' . $kode_produk . '-' . $nextNumber;

            // Cek apakah kode stok ini sudah ada di database
            $exists = $this->db->table('tbl_stok_produk')
                ->where('kode_stok', $newKodeStok)
                ->countAllResults();

            if ($exists == 0) {
                $found = true;
            } else {
                $nextNumber = str_pad(intval($nextNumber) + 1, 3, '0', STR_PAD_LEFT);
            }
        } while (!$found);

        // Kembalikan hasil
        return $this->response->setJSON([
            'status' => 'success',
            'kode_stok' => $newKodeStok
        ]);
    }

    public function add_stok()
    {
        // Ambil nama_pelanggan dari session
        $sesi_user = session()->get('sesi_user');  // Menggunakan session() di CodeIgniter 4

        // Cek jika nama_pelanggan tidak ditemukan dalam session
        if (empty($sesi_user)) {
            // Redirect atau beri pesan error jika session nama_pelanggan tidak ada
            return redirect()->to('auth/login_user');  // Ganti dengan URL login atau halaman lainnya
        }
        // Ambil data pelanggan
        $data_user = $this->M_admin_stok->get_user_by_id($sesi_user);

        $data = [
            'title' => 'Tambah Stok Produk',
            'title2' => 'Data Stok Produk',
            'data_stok' => $this->M_admin_stok->get_stok(),
            'data_produk' => $this->M_admin_stok->get_produk(),
            'data_satuan' => $this->M_admin_stok->get_satuan(),
            'sesi_user' => $data_user ? $data_user['sesi_user'] : 'Nama Lengkap Tidak Ditemukan',
            'isi' => 'admin/stok_produk/v_add',
        ];
        return view('layout/v_template', $data);
    }

    public function save_stok()
    {
        if ($this->validate([
            'nama_produk' => [
                'label' => 'Nama Produk',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} Wajib Dipilih !!!'
                ]
            ],
            'jumlah_stok_produk' => [
                'label' => 'Jumlah Stok Produk',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} Wajib Diisi !!!'
                ]
            ],
            'tanggal_masuk_produk' => [
                'label' => 'Tanggal Masuk Produk',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} Wajib Diisi !!!'
                ]
            ],
          
        ])) {
            // Mengambil data dari request
            $data = [
                'kode_stok' => $this->request->getPost('kode_stok'),
                'sesi_user' => $this->request->getPost('sesi_user'),
                'jumlah_stok_produk' => $this->request->getPost('jumlah_stok_produk'),
                'satuan_produk' => $this->request->getPost('satuan_produk'),
                'tanggal_masuk_produk' => $this->request->getPost('tanggal_masuk_produk'),
                'nama_produk' => $this->request->getPost('nama_produk'),
                'jenis_produk' => $this->request->getPost('jenis_produk'),
                'harga_produk' => $this->request->getPost('harga_produk'),
                'total_harga' => $this->request->getPost('total_harga'),
                'ukuran_produk' => $this->request->getPost('ukuran_produk'),
                'berat_produk' => $this->request->getPost('berat_produk'),
                'satuan_berat' => $this->request->getPost('satuan_berat'),
                'total_berat' => $this->request->getPost('total_berat'),
            ];

            // Simpan data ke database
            $this->M_admin_stok->add($data);

            // Pesan sukses
            session()->setFlashdata('pesan', 'Data Stok Produk Ini Berhasil Ditambahkan !');
            return redirect()->to(base_url('admin_kelola_data/stok'));
        } else {
            // Kirim error jika validasi gagal
            session()->setFlashdata('errors', \Config\Services::validation()->getErrors());
            return redirect()->to(base_url('admin_kelola_data/add_stok'))->withInput();
        }
    }

    public function edit_stok($id_stok)
    {
        // Ambil nama_pelanggan dari session
        $sesi_user = session()->get('sesi_user');  // Menggunakan session() di CodeIgniter 4

        // Cek jika nama_pelanggan tidak ditemukan dalam session
        if (empty($sesi_user)) {
            // Redirect atau beri pesan error jika session nama_pelanggan tidak ada
            return redirect()->to('auth/login_user');  // Ganti dengan URL login atau halaman lainnya
        }
        // Ambil data pelanggan
        $data_user = $this->M_admin_stok->get_user_by_id($sesi_user);

        $data = [
            'title' => 'Edit Stok Produk',
            'title2' => 'Data Stok Produk',
            'data_stok' => $this->M_admin_stok->detailStok($id_stok),
            'data_produk' => $this->M_admin_stok->get_produk(),
            'data_satuan' => $this->M_admin_stok->get_satuan(),
            'sesi_user' => $data_user ? $data_user['sesi_user'] : 'Nama Lengkap Tidak Ditemukan',
            'isi' => 'admin/stok_produk/v_edit',
        ];
        return view('layout/v_template', $data);
    }

    public function update_stok($id_stok)
    {
        if ($this->validate([
            'nama_produk' => [
                'label' => 'Nama Produk',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} Wajib Dipilih !!!'
                ]
            ],
            'jumlah_stok_produk' => [
                'label' => 'Jumlah Stok Produk',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} Wajib Diisi !!!'
                ]
            ],
            'tanggal_masuk_produk' => [
                'label' => 'Tanggal Masuk Produk',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} Wajib Diisi !!!'
                ]
            ],
        ])) {
            // Mengambil data dari request
            $data = [
                'id_stok' => $id_stok,
                'sesi_user' => $this->request->getPost('sesi_user'),
                'kode_stok' => $this->request->getPost('kode_stok'),
                'jumlah_stok_produk' => $this->request->getPost('jumlah_stok_produk'),
                'satuan_produk' => $this->request->getPost('satuan_produk'),
                'tanggal_masuk_produk' => $this->request->getPost('tanggal_masuk_produk'),
                'nama_produk' => $this->request->getPost('nama_produk'),
                'jenis_produk' => $this->request->getPost('jenis_produk'),
                'harga_produk' => $this->request->getPost('harga_produk'),
                'total_harga' => $this->request->getPost('total_harga'),
                'ukuran_produk' => $this->request->getPost('ukuran_produk'),
                'berat_produk' => $this->request->getPost('berat_produk'),
                'satuan_berat' => $this->request->getPost('satuan_berat'),
                'total_berat' => $this->request->getPost('total_berat'),
            ];
            // Jika valid
            $this->M_admin_stok->edit($data);
            session()->setFlashdata('pesan', 'Data Stok Produk Berhasil Di Ganti !');
            return redirect()->to(base_url('admin_kelola_data/stok'));
        } else {
            // JIka tidak valid
            session()->setFlashdata('errors', \Config\Services::validation()->getErrors());
            return redirect()->to(base_url('admin_kelola_data/edit_stok/' . $id_stok))->withInput();
        }
    }

    public function delete_stok($id_stok)
    {
        $data = [
            'id_stok' => $id_stok,
        ];
        $this->M_admin_stok->delete_data($data);
        session()->setFlashdata('pesan', 'Data Stok Produk Ini Berhasil Di Hapus !');
        return redirect()->to(base_url('admin_kelola_data/stok'));
    }

    public function beli()
    {
        $data = [
            'title' => 'Daftar Pembelian Produk',
            'title2' => 'Data Pembelian Produk',
            'pembelian' => $this->M_pelanggan_beli->get_beli(),
            'isi' => 'admin/pembelian/v_beli',
        ];
        return view('layout/v_template', $data);
    }

    public function bayar()
    {
        // Ambil nama_pelanggan dari session
        $sesi_user = session()->get('sesi_user');  // Menggunakan session() di CodeIgniter 4

        // Cek jika nama_pelanggan tidak ditemukan dalam session
        if (empty($sesi_user)) {
            // Redirect atau beri pesan error jika session nama_pelanggan tidak ada
            return redirect()->to('auth/login_user');  // Ganti dengan URL login atau halaman lainnya
        }
        // Ambil data pelanggan
        $data_user = $this->M_admin_stok->get_user_by_id($sesi_user);

        $data = [
            'title' => 'Daftar Pembayaran Produk',
            'title2' => 'Data Pembayaran Produk',
            'pembayaran' => $this->M_pelanggan_bayar->get_status_bayar_by_status(),
            'sesi_user' => $data_user ? $data_user['sesi_user'] : 'Nama Lengkap Tidak Ditemukan',
            'isi' => 'admin/pembayaran/v_bayar',
        ];
        return view('layout/v_template', $data);
    }

   public function konfirm_status_bayar()
{
    // Ambil data dari form
    $id_bayar = $this->request->getPost('id_bayar');
        $sesi_user = $this->request->getPost('sesi_user');
    $status_bayar = $this->request->getPost('status_bayar');
    $alasan_batal = $this->request->getPost('alasan_batal'); // bisa null jika bukan dibatalkan

    // Jika bukan dibatalkan, bersihkan alasan pembatalan sebelumnya
    if ($status_bayar != 'Dibatalkan') {
        $alasan_batal = null;
    }

    // Update ke database
    $this->M_pelanggan_bayar->konfirm_status_bayar($id_bayar, $sesi_user, $status_bayar, $alasan_batal);

    // Redirect kembali ke halaman
    return redirect()->to(base_url('admin_kelola_data/bayar'));
}

    public function view_foto($id_bayar)
    {
        $data = [
            'title' => 'View Foto Bayar',
            'title2' => 'Data Foto',
            'data_bayar' => $this->M_pelanggan_bayar->detailBayar($id_bayar),
            'isi' => 'admin/pembayaran/v_foto',
        ];
        return view('layout/v_template', $data);
    }
}
