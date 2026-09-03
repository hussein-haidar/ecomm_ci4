<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\M_pemilik_varian_produk;
use App\Models\M_pemilik_jenis_produk;
use App\Models\M_pemilik_satuan_produk;
use App\Models\M_pemilik_produk;
use App\Models\M_pemilik_bank;


class Pemilik_kelola_data extends BaseController
{
    protected $M_pemilik_varian_produk;
    protected $M_pemilik_jenis_produk;
    protected $M_pemilik_satuan_produk;
    protected $M_pemilik_produk;
    protected $M_pemilik_bank;
    protected $db;

    public function __construct()
    {
        $this->M_pemilik_varian_produk = new M_pemilik_varian_produk();
        $this->M_pemilik_jenis_produk = new M_pemilik_jenis_produk();
        $this->M_pemilik_satuan_produk = new M_pemilik_satuan_produk();
        $this->M_pemilik_produk = new M_pemilik_produk();
        $this->M_pemilik_bank = new M_pemilik_bank();
        $this->db = \Config\Database::connect();
    }

    public function varian()
    {
        $data = [
            'title' => 'Daftar Varian Produk',
            'title2' => 'Data Varian Produk',
            'varian_produk' => $this->M_pemilik_varian_produk->get_varian(),
            'isi' => 'pemilik/varian_produk/v_varian_produk',
        ];
        return view('layout/v_template', $data);
    }

    public function add_varian()
    {
        // Ambil nama_pelanggan dari session
        $sesi_user = session()->get('sesi_user');  // Menggunakan session() di CodeIgniter 4

        // Cek jika nama_pelanggan tidak ditemukan dalam session
        if (empty($sesi_user)) {
            // Redirect atau beri pesan error jika session nama_pelanggan tidak ada
            return redirect()->to('auth/login_user');  // Ganti dengan URL login atau halaman lainnya
        }
        // Ambil data pelanggan
        $data_user = $this->M_pemilik_varian_produk->get_user_by_id($sesi_user);

        $data = [
            'title' => 'Tambah Varian Produk',
            'title2' => 'Data Varian Produk',
            'sesi_user' => $data_user ? $data_user['sesi_user'] : 'Nama Lengkap Tidak Ditemukan',
            'isi' => 'pemilik/varian_produk/v_add',
        ];
        return view('layout/v_template', $data);
    }

    public function save_varian()
    {
        if ($this->validate([
            'varian_produk' => [
                'label' => 'varian Produk',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} Wajib Diisi !!!'
                ]
            ],
        ])) {
            // Jika valid
            $data = [
                'sesi_user' => $this->request->getPost('sesi_user'),
                'varian_produk' => $this->request->getPost('varian_produk'),
            ];
            $this->M_pemilik_varian_produk->add($data);

            session()->setFlashdata('pesan', 'Data Varian Produk Berhasil Ditambahkan !');
            return redirect()->to(base_url('pemilik_kelola_data/varian'));
        } else {
            // JIka tidak valid
            session()->setFlashdata('errors', \Config\Services::validation()->getErrors());
            return redirect()->to(base_url('pemilik_kelola_data/add_varian'));
        }
    }

    public function edit_varian($id_varian)
    {
        // Ambil nama_pelanggan dari session
        $sesi_user = session()->get('sesi_user');  // Menggunakan session() di CodeIgniter 4

        // Cek jika nama_pelanggan tidak ditemukan dalam session
        if (empty($sesi_user)) {
            // Redirect atau beri pesan error jika session nama_pelanggan tidak ada
            return redirect()->to('auth/login_user');  // Ganti dengan URL login atau halaman lainnya
        }
        // Ambil data pelanggan
        $data_user = $this->M_pemilik_varian_produk->get_user_by_id($sesi_user);

        $data = [
            'title' => 'Edit varian Produk',
            'title2' => 'Data varian Produk',
            'varian_produk' => $this->M_pemilik_varian_produk->detailVarian($id_varian),
            'sesi_user' => $data_user ? $data_user['sesi_user'] : 'Nama Lengkap Tidak Ditemukan',
            'isi' => 'pemilik/varian_produk/v_edit',
        ];
        return view('layout/v_template', $data);
    }

    public function update_varian($id_varian)
    {
        if ($this->validate([
            'varian_produk' => [
                'label' => 'Varian Produk',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} Wajib Diisi !!!'
                ]
            ],
        ])) {
            // Jika valid
            $data = [
                'id_varian' => $id_varian,
                'sesi_user' => $this->request->getPost('sesi_user'),
                'varian_produk' => $this->request->getPost('varian_produk'),
            ];
            $this->M_pemilik_varian_produk->edit($data);

            session()->setFlashdata('pesan', 'Data Varian Produk Ini Berhasil Di Ganti !');
            return redirect()->to(base_url('pemilik_kelola_data/varian'));
        } else {
            // JIka tidak valid
            session()->setFlashdata('errors', \Config\Services::validation()->getErrors());
            return redirect()->to(base_url('pemilik_kelola_data/edit_varian/' . $id_varian));
        }
    }

    public function delete_varian($id_varian)
    {
        // Soft delete produk, menandai produk sebagai terhapus
        $this->M_pemilik_varian_produk->update($id_varian, ['deleted_at' => 1]);
        return redirect()->to(base_url('pemilik_kelola_data/varian'))->with('pesan', 'Data Varian Ini berhasil dihapus !');
    }

    public function data_varian_dihapus()
    {
        $data = [
            'title' => 'Data Varian Dihapus',
            'title2' => 'Data Varian Dihapus',
            'data_varian_dihapus' => $this->M_pemilik_varian_produk->get_varian_dihapus(), // Ambil data produk yang sudah dihapus
            'isi' => 'pemilik/varian_produk/v_data_dihapus', // Ganti dengan view yang sesuai
        ];
        return view('layout/v_template', $data);
    }

    public function restore_varian($id_varian)
    {
        // Restore produk yang telah dihapus
        $this->M_pemilik_varian_produk->update($id_varian, ['deleted_at' => 0]);
        return redirect()->to(base_url('pemilik_kelola_data/varian'))->with('pesan', 'Data Varian Ini berhasil direstore !');
    }

    public function delete_hard_varian($id_varian)
    {
        $data = [
            'id_varian' => $id_varian,
        ];
        $this->M_pemilik_varian_produk->delete_hard($data);
        session()->setFlashdata('pesan', 'Data Varian Ini Berhasil Di Hapus !');
        return redirect()->to(base_url('pemilik_kelola_data/varian'));
    }

    public function jenis()
    {
        $data = [
            'title' => 'Daftar Jenis Produk',
            'title2' => 'Data Jenis Produk',
            'jenis_produk' => $this->M_pemilik_jenis_produk->get_jenis(),
            'isi' => 'pemilik/jenis_produk/v_jenis_produk',
        ];
        return view('layout/v_template', $data);
    }

    public function add_jenis()
    {
        // Ambil nama_pelanggan dari session
        $sesi_user = session()->get('sesi_user');  // Menggunakan session() di CodeIgniter 4

        // Cek jika nama_pelanggan tidak ditemukan dalam session
        if (empty($sesi_user)) {
            // Redirect atau beri pesan error jika session nama_pelanggan tidak ada
            return redirect()->to('auth/login_user');  // Ganti dengan URL login atau halaman lainnya
        }
        // Ambil data pelanggan
        $data_user = $this->M_pemilik_jenis_produk->get_user_by_id($sesi_user);

        $data = [
            'title' => 'Tambah Jenis Produk',
            'title2' => 'Data Jenis Produk',
            'sesi_user' => $data_user ? $data_user['sesi_user'] : 'Nama Lengkap Tidak Ditemukan',
            'isi' => 'pemilik/jenis_produk/v_add',
        ];
        return view('layout/v_template', $data);
    }

    public function save_jenis()
    {
        if ($this->validate([
            'jenis_produk' => [
                'label' => 'Jenis Produk',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} Wajib Diisi !!!'
                ]
            ],
        ])) {
            // Jika valid
            $data = [
                'sesi_user' => $this->request->getPost('sesi_user'),
                'jenis_produk' => $this->request->getPost('jenis_produk'),
            ];

            $this->M_pemilik_jenis_produk->add($data);

            session()->setFlashdata('pesan', 'Data Jenis Produk Berhasil Ditambahkan !');
            return redirect()->to(base_url('pemilik_kelola_data/jenis'));
        } else {
            // Jika tidak valid
            session()->setFlashdata('errors', \Config\Services::validation()->getErrors());
            return redirect()->to(base_url('pemilik_kelola_data/add_jenis'));
        }
    }

    public function edit_jenis($id_jenis)
    {
        // Ambil nama_pelanggan dari session
        $sesi_user = session()->get('sesi_user');  // Menggunakan session() di CodeIgniter 4

        // Cek jika nama_pelanggan tidak ditemukan dalam session
        if (empty($sesi_user)) {
            // Redirect atau beri pesan error jika session nama_pelanggan tidak ada
            return redirect()->to('auth/login_user');  // Ganti dengan URL login atau halaman lainnya
        }
        // Ambil data pelanggan
        $data_user = $this->M_pemilik_jenis_produk->get_user_by_id($sesi_user);

        $data = [
            'title' => 'Edit Jenis Produk',
            'title2' => 'Data Jenis Produk',
            'jenis_produk' => $this->M_pemilik_jenis_produk->detailJenis($id_jenis),
            'sesi_user' => $data_user ? $data_user['sesi_user'] : 'Nama Lengkap Tidak Ditemukan',
            'isi' => 'pemilik/jenis_produk/v_edit',
        ];
        return view('layout/v_template', $data);
    }

    public function update_jenis($id_jenis)
    {
        if ($this->validate([
            'jenis_produk' => [
                'label' => 'Jenis Produk',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} Wajib Diisi !!!'
                ]
            ],
        ])) {
            // Jika valid
            $data = [
                'id_jenis' => $id_jenis,
                'sesi_user' => $this->request->getPost('sesi_user'),
                'jenis_produk' => $this->request->getPost('jenis_produk'),
            ];
            $this->M_pemilik_jenis_produk->edit($data);

            session()->setFlashdata('pesan', 'Data Jenis Produk Ini Berhasil Di Ganti !');
            return redirect()->to(base_url('pemilik_kelola_data/jenis'));
        } else {
            // Jika tidak valid
            session()->setFlashdata('errors', \Config\Services::validation()->getErrors());
            return redirect()->to(base_url('pemilik_kelola_data/edit_jenis/' . $id_jenis));
        }
    }
    public function delete_jenis($id_jenis)
    {
        // Soft delete produk, menandai produk sebagai terhapus
        $this->M_pemilik_jenis_produk->update($id_jenis, ['deleted_at' => 1]);
        return redirect()->to(base_url('pemilik_kelola_data/jenis'))->with('pesan', 'Data Jenis berhasil dihapus !');
    }

    public function data_jenis_dihapus()
    {
        $data = [
            'title' => 'Data Jenis Produk Dihapus',
            'title2' => 'Data Jenis Produk Dihapus',
            'data_jenis_dihapus' => $this->M_pemilik_jenis_produk->get_jenis_dihapus(), // Ambil data produk yang sudah dihapus
            'isi' => 'pemilik/jenis_produk/v_data_dihapus', // Ganti dengan view yang sesuai
        ];
        return view('layout/v_template', $data);
    }

    public function restore_jenis($id_jenis)
    {
        // Restore produk yang telah dihapus
        $this->M_pemilik_jenis_produk->update($id_jenis, ['deleted_at' => 0]);
        return redirect()->to(base_url('pemilik_kelola_data/jenis'))->with('pesan', 'Data BerHasil Di Restore !');
    }

    public function delete_hard_jenis($id_jenis)
    {
        $data = [
            'id_jenis' => $id_jenis,
        ];
        $this->M_pemilik_jenis_produk->delete_hard($data);
        session()->setFlashdata('pesan', 'Data Jenis Ini Berhasil Di Hapus !');
        return redirect()->to(base_url('pemilik_kelola_data/data_jenis_dihapus'));
    }

    public function satuan_produk()
    {
        $data = [
            'title' => 'Daftar Satuan Produk',
            'title2' => 'Data Satuan Produk',
            'satuan_produk' => $this->M_pemilik_satuan_produk->get_satuan(),
            'isi' => 'pemilik/satuan_produk/v_satuan',
        ];
        return view('layout/v_template', $data);
    }

    public function add_satuan()
    {
        // Ambil nama_pelanggan dari session
        $sesi_user = session()->get('sesi_user');  // Menggunakan session() di CodeIgniter 4

        // Cek jika nama_pelanggan tidak ditemukan dalam session
        if (empty($sesi_user)) {
            // Redirect atau beri pesan error jika session nama_pelanggan tidak ada
            return redirect()->to('auth/login_user');  // Ganti dengan URL login atau halaman lainnya
        }
        // Ambil data pelanggan
        $data_user = $this->M_pemilik_satuan_produk->get_user_by_id($sesi_user);

        $data = [
            'title' => 'Tambah Satuan Produk',
            'title2' => 'Data Satuan Produk',
            'sesi_user' => $data_user ? $data_user['sesi_user'] : 'Nama Lengkap Tidak Ditemukan',
            'isi' => 'pemilik/satuan_produk/v_add',
        ];
        return view('layout/v_template', $data);
    }

    public function save_satuan()
    {
        if ($this->validate([
            'satuan_produk' => [
                'label' => 'varian Produk',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} Wajib Diisi !!!'
                ]
            ],
        ])) {
            // Jika valid
            $data = [
                'sesi_user' => $this->request->getPost('sesi_user'),
                'satuan_produk' => $this->request->getPost('satuan_produk'),
            ];
            $this->M_pemilik_satuan_produk->add($data);

            session()->setFlashdata('pesan', 'Data Satuan Produk Berhasil Ditambahkan !');
            return redirect()->to(base_url('pemilik_kelola_data/satuan_produk'));
        } else {
            // JIka tidak valid
            session()->setFlashdata('errors', \Config\Services::validation()->getErrors());
            return redirect()->to(base_url('pemilik_kelola_data/add_satuan'));
        }
    }

    public function edit_satuan($id_satuan)
    {
        // Ambil nama_pelanggan dari session
        $sesi_user = session()->get('sesi_user');  // Menggunakan session() di CodeIgniter 4

        // Cek jika nama_pelanggan tidak ditemukan dalam session
        if (empty($sesi_user)) {
            // Redirect atau beri pesan error jika session nama_pelanggan tidak ada
            return redirect()->to('auth/login_user');  // Ganti dengan URL login atau halaman lainnya
        }
        // Ambil data pelanggan
        $data_user = $this->M_pemilik_satuan_produk->get_user_by_id($sesi_user);

        $data = [
            'title' => 'Edit varian Produk',
            'title2' => 'Data varian Produk',
            'satuan_produk' => $this->M_pemilik_satuan_produk->detailSatuan($id_satuan),
            'sesi_user' => $data_user ? $data_user['sesi_user'] : 'Nama Lengkap Tidak Ditemukan',
            'isi' => 'pemilik/satuan_produk/v_edit',
        ];
        return view('layout/v_template', $data);
    }

    public function update_satuan($id_satuan)
    {
        if ($this->validate([
            'satuan_produk' => [
                'label' => 'varian Produk',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} Wajib Diisi !!!'
                ]
            ],
        ])) {
            // Jika valid
            $data = [
                'id_satuan' => $id_satuan,
                'sesi_user' => $this->request->getPost('sesi_user'),
                'satuan_produk' => $this->request->getPost('satuan_produk'),
            ];
            $this->M_pemilik_satuan_produk->edit($data);

            session()->setFlashdata('pesan', 'Data Satuan Produk Ini Berhasil Di Ganti !');
            return redirect()->to(base_url('pemilik_kelola_data/satuan_produk'));
        } else {
            // JIka tidak valid
            session()->setFlashdata('errors', \Config\Services::validation()->getErrors());
            return redirect()->to(base_url('pemilik_kelola_data/edit_satuan/' . $id_satuan));
        }
    }

    public function delete_satuan($id_satuan)
    {
        // Soft delete produk, menandai produk sebagai terhapus
        $this->M_pemilik_satuan_produk->update($id_satuan, ['deleted_at' => 1]);
        return redirect()->to(base_url('pemilik_kelola_data/satuan_produk'))->with('pesan', 'Data varian Ini berhasil dihapus !');
    }

    public function data_satuan_dihapus()
    {
        $data = [
            'title' => 'Data varian Dihapus',
            'title2' => 'Data varian Dihapus',
            'data_satuan_dihapus' => $this->M_pemilik_satuan_produk->get_satuan_dihapus(), // Ambil data produk yang sudah dihapus
            'isi' => 'pemilik/satuan_produk/v_data_dihapus', // Ganti dengan view yang sesuai
        ];
        return view('layout/v_template', $data);
    }

    public function restore_satuan($id_satuan)
    {
        // Restore produk yang telah dihapus
        $this->M_pemilik_satuan_produk->update($id_satuan, ['deleted_at' => 0]);
        return redirect()->to(base_url('pemilik_kelola_data/satuan_produk'))->with('pesan', 'Data varian Ini berhasil direstore !');
    }

    public function delete_hard_satuan($id_satuan)
    {
        $data = [
            'id_satuan' => $id_satuan,
        ];
        $this->M_pemilik_satuan_produk->delete_hard($data);
        session()->setFlashdata('pesan', 'Data varian Ini Berhasil Di Hapus !');
        return redirect()->to(base_url('pemilik_kelola_data/satuan_produk'));
    }

    public function produk()
    {
        $data = [
            'title' => 'Data Produk',
            'title2' => 'Data Produk',
            'data_produk' => $this->M_pemilik_produk->get_produk(), // Mengambil semua data produk termasuk yang soft delete
            'isi' => 'pemilik/data_produk/v_produk',
        ];
        return view('layout/v_template', $data);
    }

    public function update_carousel()
    {
        $idProduk = $this->request->getPost('id_produk');
        $checked  = $this->request->getPost('checked') ? 1 : 0;

        $model = new M_pemilik_produk();

        // Update status checked di database
        $model->update($idProduk, [
            'checked' => $checked
        ]);

        // Redirect kembali ke halaman produk
        return redirect()->to(base_url('pemilik_kelola_data/produk'));
    }

    public function generateKodeProduk()
    {
        // Mendapatkan data dari request
        $id_jenis = $this->request->getVar('id_jenis');
        $id_varian = $this->request->getVar('id_varian');

        // Ambil nama jenis produk
        $jenis = $this->M_pemilik_produk->getJenisById($id_jenis);
        $nama_jenis = $jenis ? $jenis['jenis_produk'] : '';

        // Ambil nama varian produk (jika tersedia)
        $nama_varian = '';
        if (!empty($id_varian)) {
            $varian = $this->M_pemilik_produk->getVarianById($id_varian);
            $nama_varian = $varian ? $varian['varian_produk'] : '';
        }

        // Generate kode jenis
        $kode_jenis = '';
        if (!empty($nama_jenis)) {
            $kata_jenis = explode(' ', $nama_jenis);
            $kode_jenis .= strtoupper(substr($kata_jenis[0], 0, 1));
            if (isset($kata_jenis[1])) {
                $kode_jenis .= strtoupper(substr($kata_jenis[1], 0, 1));
            }
        }

        // Generate kode varian (jika tersedia)
        $kode_varian = '';
        if (!empty($nama_varian)) {
            $kata_varian = explode(' ', $nama_varian);
            $kode_varian .= strtoupper(substr($kata_varian[0], 0, 1));
            if (isset($kata_varian[1])) {
                $kode_varian .= strtoupper(substr($kata_varian[1], 0, 1));
            }
        }

        // Gabungkan kode jenis dan varian
        $kode_awal = $kode_jenis . $kode_varian;

        // Inisialisasi nomor urut
        $nextNumber = '001';
        $found = false;

        do {
            $newKodeProduk = $kode_awal . '-' . $nextNumber;

            $exists = $this->db->table('tbl_data_produk')
                ->where('kode_produk', $newKodeProduk)
                ->countAllResults();

            if ($exists == 0) {
                $found = true;
            } else {
                $nextNumber = str_pad(intval($nextNumber) + 1, 3, '0', STR_PAD_LEFT);
            }
        } while (!$found);

        return $this->response->setJSON(['kode_produk' => $newKodeProduk]);
    }

    public function generateNamaProduk()
    {
        $id_jenis = $this->request->getGet('id_jenis');
        $id_varian = $this->request->getGet('id_varian');

        // Ambil data jenis
        $jenis = $this->M_pemilik_produk->getJenisById($id_jenis);

        // Jika id_varian dikirim dan varian ditemukan, gabungkan
        if ($id_varian) {
            $varian = $this->M_pemilik_produk->getVarianById($id_varian);
            if ($varian) {
                $nama_produk = ($jenis['jenis_produk'] ?? '') . ' ' . $varian['varian_produk'];
            } else {
                $nama_produk = $jenis['jenis_produk'] ?? '';
            }
        } else {
            // Jika id_varian tidak dikirim, pakai jenis saja
            $nama_produk = $jenis['jenis_produk'] ?? '';
        }

        return $this->response->setJSON(['nama_produk' => $nama_produk]);
    }

    public function add_produk()
    {
        // Ambil nama_pelanggan dari session
        $sesi_user = session()->get('sesi_user');  // Menggunakan session() di CodeIgniter 4

        // Cek jika nama_pelanggan tidak ditemukan dalam session
        if (empty($sesi_user)) {
            // Redirect atau beri pesan error jika session nama_pelanggan tidak ada
            return redirect()->to('auth/login_user');  // Ganti dengan URL login atau halaman lainnya
        }
        // Ambil data pelanggan
        $data_user = $this->M_pemilik_produk->get_user_by_id($sesi_user);

        $data = [
            'title' => 'Tambah Produk',
            'title2' => 'Data Produk',
            'data_jenis' => $this->M_pemilik_produk->all_jenis(),
            'data_varian' => $this->M_pemilik_produk->all_varian(),
            'data_satuan' => $this->M_pemilik_produk->all_satuan(),
            'sesi_user' => $data_user ? $data_user['sesi_user'] : 'Nama Lengkap Tidak Ditemukan',
            'isi' => 'pemilik/data_produk/v_add',
        ];
        return view('layout/v_template', $data);
    }

    public function save_produk()
    {
        if ($this->validate([
            'nama_produk' => [
                'label' => 'Nama Produk',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} Wajib Diisi !!!'
                ]
            ],
            'harga_produk' => [
                'label' => 'Harga Produk',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} Wajib Diisi !!!'
                ]
            ],
            'id_jenis' => [
                'label' => 'Jenis Produk',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} Wajib Dipilih !!!'
                ]
            ],
            'deskripsi_produk' => [
                'label' => 'Deskripsi Produk',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} Wajib Diisi !!!'
                ]
            ],
            'foto_produk' => [
                'label' => 'Foto Produk',
                'rules' => 'uploaded[foto_produk]|max_size[foto_produk,1792]|mime_in[foto_produk,image/png,image/jpg,image/jpeg,image/gif,image/ico]',
                'errors' => [
                    'uploaded' => '{field} Wajib Diisi !!!',
                    'max_size' => '{field} Max 1792 KB !!!',
                    'mime_in' => 'Format {field} Wajib PNG, JPG, JPEG, GIF, ICO !!!'
                ]
            ],
            'gallery_images.*' => [
                'label' => 'Gallery Foto Produk',
                'rules' => 'max_size[gallery_images.*,1792]|mime_in[gallery_images.*,image/png,image/jpg,image/jpeg,image/gif,image/ico]',
                'errors' => [
                    'max_size' => '{field} Max 1792 KB !!!',
                    'mime_in' => 'Format {field} Wajib PNG, JPG, JPEG, GIF, ICO !!!'
                ]
            ],
            'size_guide_image' => [
                'label' => 'Foto Panduan Ukuran',
                'rules' => 'max_size[size_guide_image,1792]|mime_in[size_guide_image,image/png,image/jpg,image/jpeg,image/gif,image/ico]',
                'errors' => [
                    'max_size' => '{field} Max 1792 KB !!!',
                    'mime_in' => 'Format {field} Wajib PNG, JPG, JPEG, GIF, ICO !!!'
                ]
            ],
            'video_url' => [
                'label' => 'URL Video Produk',
                'rules' => 'valid_url[video_url]',
                'errors' => [
                    'valid_url' => 'Format {field} tidak valid !!!'
                ]
            ],
        ])) {
            // Mengambil data dari request
            $id_varian = $this->request->getPost('id_varian');
            $id_jenis = $this->request->getPost('id_jenis');

            //Mengambil file foto utama dari form input
            $foto = $this->request->getFile('foto_produk');
            //Mengganti nama file foto
            $nama_file = $foto->getRandomName();
            
            // Upload gallery images
            $galleryImages = [];
            $galleryFiles = $this->request->getFileMultiple('gallery_images');
            if ($galleryFiles) {
                foreach ($galleryFiles as $file) {
                    if ($file->isValid() && !$file->hasMoved()) {
                        $galleryFileName = $file->getRandomName();
                        $file->move('fotoproduk', $galleryFileName);
                        $galleryImages[] = $galleryFileName;
                    }
                }
            }
            
            // Upload size guide image
            $sizeGuideImage = null;
            $sizeGuideFile = $this->request->getFile('size_guide_image');
            if ($sizeGuideFile && $sizeGuideFile->isValid() && !$sizeGuideFile->hasMoved()) {
                $sizeGuideImage = $sizeGuideFile->getRandomName();
                $sizeGuideFile->move('fotoproduk', $sizeGuideImage);
            }

            // Jika diisi semua
            $data = [
                'kode_produk' => $this->request->getPost('kode_produk'),
                'sesi_user' => $this->request->getPost('sesi_user'),
                'nama_produk' => $this->request->getPost('nama_produk'),
                'ukuran_produk' => $this->request->getPost('ukuran_produk'),
                'berat_produk' => $this->request->getPost('berat_produk'),
                'satuan_berat' => $this->request->getPost('satuan_berat'),
                'harga_produk' => $this->request->getPost('harga_produk'),
                'id_varian' => $id_varian,
                'id_jenis' => $id_jenis,
                'deskripsi_produk' => $this->request->getPost('deskripsi_produk'),
                'foto_produk' => $nama_file,
                'gallery_images' => $galleryImages ? json_encode($galleryImages) : null,
                'size_guide_image' => $sizeGuideImage,
                'video_url' => $this->request->getPost('video_url'),
            ];
            // File foto disimpan di folder foto_produk
            $foto->move('fotoproduk', $nama_file);
            // Jika valid
            $this->M_pemilik_produk->add($data);
            session()->setFlashdata('pesan', 'Data Produk Berhasil Ditambahkan !');
            return redirect()->to(base_url('pemilik_kelola_data/produk'));
        } else {
            // Jika tidak valid
            session()->setFlashdata('errors', \Config\Services::validation()->getErrors());
            return redirect()->to(base_url('pemilik_kelola_data/add_produk'));
        }
    }

    public function edit_produk($id_produk)
    {
        // Ambil nama_pelanggan dari session
        $sesi_user = session()->get('sesi_user');  // Menggunakan session() di CodeIgniter 4

        // Cek jika nama_pelanggan tidak ditemukan dalam session
        if (empty($sesi_user)) {
            // Redirect atau beri pesan error jika session nama_pelanggan tidak ada
            return redirect()->to('auth/login_user');  // Ganti dengan URL login atau halaman lainnya
        }
        // Ambil data pelanggan
        $data_user = $this->M_pemilik_produk->get_user_by_id($sesi_user);

        $data = [
            'title' => 'Edit Produk',
            'title2' => 'Data Produk',
            'data_produk' => $this->M_pemilik_produk->detailProduk($id_produk),
            'data_jenis' => $this->M_pemilik_produk->all_jenis(),
            'data_varian' => $this->M_pemilik_produk->all_varian(),
            'data_satuan' => $this->M_pemilik_produk->all_satuan(),
            'sesi_user' => $data_user ? $data_user['sesi_user'] : 'Nama Lengkap Tidak Ditemukan',
            'isi' => 'pemilik/data_produk/v_edit',
        ];
        return view('layout/v_template', $data);
    }

    public function update_produk($id_produk)
    {
        if ($this->validate([
            'nama_produk' => [
                'label' => 'Nama Produk',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} Wajib Diisi !!!'
                ]
            ],
            'harga_produk' => [
                'label' => 'Harga Produk',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} Wajib Diisi !!!'
                ]
            ],
            'id_jenis' => [
                'label' => 'Jenis Produk',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} Wajib Diisi !!!'
                ]
            ],
            'deskripsi_produk' => [
                'label' => 'Deskripsi Produk',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} Wajib Diisi !!!'
                ]
            ],
            'foto_produk' => [
                'label' => 'Foto Produk',
                'rules' => 'max_size[foto_produk,1792]|mime_in[foto_produk,image/png,image/jpg,image/jpeg,image/gif,image/ico]',
                'errors' => [
                    'max_size' => '{field} Max 1792 KB !!!',
                    'mime_in' => 'Format {field} Wajib PNG, JPG, JPEG, GIF, ICO !!!',
                ]
            ],
            'gallery_images.*' => [
                'label' => 'Gallery Foto Produk',
                'rules' => 'max_size[gallery_images.*,1792]|mime_in[gallery_images.*,image/png,image/jpg,image/jpeg,image/gif,image/ico]',
                'errors' => [
                    'max_size' => '{field} Max 1792 KB !!!',
                    'mime_in' => 'Format {field} Wajib PNG, JPG, JPEG, GIF, ICO !!!'
                ]
            ],
            'size_guide_image' => [
                'label' => 'Foto Panduan Ukuran',
                'rules' => 'max_size[size_guide_image,1792]|mime_in[size_guide_image,image/png,image/jpg,image/jpeg,image/gif,image/ico]',
                'errors' => [
                    'max_size' => '{field} Max 1792 KB !!!',
                    'mime_in' => 'Format {field} Wajib PNG, JPG, JPEG, GIF, ICO !!!'
                ]
            ],
            'video_url' => [
                'label' => 'URL Video Produk',
                'rules' => 'valid_url[video_url]',
                'errors' => [
                    'valid_url' => 'Format {field} tidak valid !!!'
                ]
            ],
        ])) {

            //Mengambil file foto dari form input
            $foto = $this->request->getFile('foto_produk');
            
            // Ambil data produk existing untuk gallery & size guide
            $produk = $this->M_pemilik_produk->detailProduk($id_produk);
            $existingGallery = $produk['gallery_images'] ? json_decode($produk['gallery_images'], true) : [];
            
            // Upload gallery images (tambahan)
            $galleryFiles = $this->request->getFileMultiple('gallery_images');
            if ($galleryFiles) {
                foreach ($galleryFiles as $file) {
                    if ($file->isValid() && !$file->hasMoved()) {
                        $galleryFileName = $file->getRandomName();
                        $file->move('fotoproduk', $galleryFileName);
                        $existingGallery[] = $galleryFileName;
                    }
                }
            }
            
            // Upload size guide image
            $sizeGuideImage = $produk['size_guide_image'] ?? null;
            $sizeGuideFile = $this->request->getFile('size_guide_image');
            if ($sizeGuideFile && $sizeGuideFile->isValid() && !$sizeGuideFile->hasMoved()) {
                // Hapus lama
                if ($sizeGuideImage && file_exists('fotoproduk/' . $sizeGuideImage)) {
                    unlink('fotoproduk/' . $sizeGuideImage);
                }
                $sizeGuideImage = $sizeGuideFile->getRandomName();
                $sizeGuideFile->move('fotoproduk', $sizeGuideImage);
            }
            
            // Video URL
            $videoUrl = $this->request->getPost('video_url');

            if ($foto->getError() == 4) {
                //Jika foto utama tidak diganti
                $data = [
                    'id_produk' => $id_produk,
                    'kode_produk' => $this->request->getPost('kode_produk'),
                    'sesi_user' => $this->request->getPost('sesi_user'),
                    'nama_produk' => $this->request->getPost('nama_produk'),
                    'ukuran_produk' => $this->request->getPost('ukuran_produk'),
                    'berat_produk' => $this->request->getPost('berat_produk'),
                    'satuan_berat' => $this->request->getPost('satuan_berat'),
                    'harga_produk' => $this->request->getPost('harga_produk'),
                    'id_varian' => $this->request->getPost('id_varian'),
                    'id_jenis' => $this->request->getPost('id_jenis'),
                    'deskripsi_produk' => $this->request->getPost('deskripsi_produk'),
                    'gallery_images' => $existingGallery ? json_encode($existingGallery) : null,
                    'size_guide_image' => $sizeGuideImage,
                    'video_url' => $videoUrl,
                ];
                $this->M_pemilik_produk->edit($data);
            } else {
                //Menghapus foto lama
                if ($produk['foto_produk'] != "") {
                    unlink('fotoproduk/' . $produk['foto_produk']);
                }
                //Mengganti nama file foto
                $nama_file = $foto->getRandomName();
                // Jika valid
                $data = [
                    'id_produk' => $id_produk,
                    'kode_produk' => $this->request->getPost('kode_produk'),
                    'sesi_user' => $this->request->getPost('sesi_user'),
                    'nama_produk' => $this->request->getPost('nama_produk'),
                    'ukuran_produk' => $this->request->getPost('ukuran_produk'),
                    'berat_produk' => $this->request->getPost('berat_produk'),
                    'satuan_berat' => $this->request->getPost('satuan_berat'),
                    'harga_produk' => $this->request->getPost('harga_produk'),
                    'id_varian' => $this->request->getPost('id_varian'),
                    'id_bank' => $this->request->getPost('id_bank'),
                    'deskripsi_produk' => $this->request->getPost('deskripsi_produk'),
                    'foto_produk' => $nama_file,
                    'gallery_images' => $existingGallery ? json_encode($existingGallery) : null,
                    'size_guide_image' => $sizeGuideImage,
                    'video_url' => $videoUrl,
                ];
                // File foto disimpan di folder foto_produk
                $foto->move('fotoproduk', $nama_file);
                $this->M_pemilik_produk->edit($data);
            }
            session()->setFlashdata('pesan', 'Data Produk Ini Berhasil Di Ganti !');
            return redirect()->to(base_url('pemilik_kelola_data/produk'));
        } else {
            // Jika tidak valid
            session()->setFlashdata('errors', \Config\Services::validation()->getErrors());
            return redirect()->to(base_url('pemilik_kelola_data/edit_produk/' . $id_produk));
        }
    }

    public function delete_produk($id_produk)
    {
        // Soft delete produk, menandai produk sebagai terhapus
        $this->M_pemilik_produk->update($id_produk, ['deleted_at' => 1]);
        return redirect()->to(base_url('pemilik_kelola_data/produk'))->with('pesan', 'Data Produk berhasil dihapus !');
    }

    // Fungsi delete untuk soft delete produk
    public function data_dihapus_produk()
    {
        $data = [
            'title' => 'Data Produk Dihapus',
            'title2' => 'Data Produk Dihapus',
            'data_produk_dihapus' => $this->M_pemilik_produk->get_produk_dihapus(), // Ambil data produk yang sudah dihapus
            'isi' => 'pemilik/data_produk/v_data_dihapus', // Ganti dengan view yang sesuai
        ];
        return view('layout/v_template', $data);
    }

    public function restore_produk($id_produk)
    {
        // Restore produk yang telah dihapus
        $this->M_pemilik_produk->update($id_produk, ['deleted_at' => 0]);
        return redirect()->to(base_url('pemilik_kelola_data/produk'))->with('pesan', 'Data berhasil direstore !');
    }

    public function delete_hard_produk($id_produk)
    {
        //Menghapus foto lama
        $data_produk = $this->M_pemilik_produk->detailProduk($id_produk);
        if ($data_produk['foto_produk'] != "") {
            unlink('fotoproduk/' . $data_produk['foto_produk']);
        }
        $data = [
            'id_produk' => $id_produk,
        ];
        $this->M_pemilik_produk->delete_hard($data);
        session()->setFlashdata('pesan', 'Data Produk Ini Berhasil Di Hapus !');
        return redirect()->to(base_url('pemilik_kelola_data/data_dihapus_produk'));
    }

    public function bank()
    {
        $data = [
            'title' => 'Daftar Bank',
            'title2' => 'Data Bank',
            'bank' => $this->M_pemilik_bank->get_bank(),
            'isi' => 'pemilik/bank/v_bank',
        ];
        return view('layout/v_template', $data);
    }

    public function add_bank()
    {
        // Ambil nama_pelanggan dari session
        $sesi_user = session()->get('sesi_user');  // Menggunakan session() di CodeIgniter 4

        // Cek jika nama_pelanggan tidak ditemukan dalam session
        if (empty($sesi_user)) {
            // Redirect atau beri pesan error jika session nama_pelanggan tidak ada
            return redirect()->to('auth/login_user');  // Ganti dengan URL login atau halaman lainnya
        }
        // Ambil data pelanggan
        $data_user = $this->M_pemilik_bank->get_user_by_id($sesi_user);

        $data = [
            'title' => 'Tambah Bank',
            'title2' => 'Data Bank',
            'sesi_user' => $data_user ? $data_user['sesi_user'] : 'Nama Lengkap Tidak Ditemukan',
            'isi' => 'pemilik/bank/v_add',
        ];
        return view('layout/v_template', $data);
    }

    public function save_bank()
    {
        if ($this->validate([
            'a_n' => [
                'label' => 'Nama Rekening',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} Wajib Diisi !!!'
                ]
            ],
            'no_rek' => [
                'label' => 'No Rekening',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} Wajib Diisi !!!'
                ]
            ],
            'nama_bank' => [
                'label' => 'Nama Bank',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} Wajib Diisi !!!'
                ]
            ],
        ])) {
            // Jika valid
            $data = [
                'sesi_user' => $this->request->getPost('sesi_user'),
                'a_n' => $this->request->getPost('a_n'),
                'no_rek' => $this->request->getPost('no_rek'),
                'nama_bank' => $this->request->getPost('nama_bank'),
            ];

            $this->M_pemilik_bank->add($data);

            session()->setFlashdata('pesan', 'Data Bank Berhasil Ditambahkan !');
            return redirect()->to(base_url('pemilik_kelola_data/bank'));
        } else {
            // Jika tidak valid
            session()->setFlashdata('errors', \Config\Services::validation()->getErrors());
            return redirect()->to(base_url('pemilik_kelola_data/add_bank'));
        }
    }

    public function edit_bank($id_bank)
    {
        // Ambil nama_pelanggan dari session
        $sesi_user = session()->get('sesi_user');  // Menggunakan session() di CodeIgniter 4

        // Cek jika nama_pelanggan tidak ditemukan dalam session
        if (empty($sesi_user)) {
            // Redirect atau beri pesan error jika session nama_pelanggan tidak ada
            return redirect()->to('auth/login_user');  // Ganti dengan URL login atau halaman lainnya
        }
        // Ambil data pelanggan
        $data_user = $this->M_pemilik_bank->get_user_by_id($sesi_user);

        $data = [
            'title' => 'Edit Bank',
            'title2' => 'Data Bank',
            'bank' => $this->M_pemilik_bank->detailBank($id_bank),
            'sesi_user' => $data_user ? $data_user['sesi_user'] : 'Nama Lengkap Tidak Ditemukan',
            'isi' => 'pemilik/bank/v_edit',
        ];
        return view('layout/v_template', $data);
    }

    public function update_bank($id_bank)
    {
        if ($this->validate([
            'a_n' => [
                'label' => 'Nama Rekening',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} Wajib Diisi !!!'
                ]
            ],
            'no_rek' => [
                'label' => 'No Rekening',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} Wajib Diisi !!!'
                ]
            ],
            'nama_bank' => [
                'label' => 'Nama Bank',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} Wajib Diisi !!!'
                ]
            ],
        ])) {
            // Jika valid
            $data = [
                'id_bank' => $id_bank,
                'sesi_user' => $this->request->getPost('sesi_user'),
                'a_n' => $this->request->getPost('a_n'),
                'no_rek' => $this->request->getPost('no_rek'),
                'nama_bank' => $this->request->getPost('nama_bank'),
            ];
            $this->M_pemilik_bank->edit($data);

            session()->setFlashdata('pesan', 'Data Bank Ini Berhasil Di Ganti !');
            return redirect()->to(base_url('pemilik_kelola_data/bank'));
        } else {
            // Jika tidak valid
            session()->setFlashdata('errors', \Config\Services::validation()->getErrors());
            return redirect()->to(base_url('pemilik_kelola_data/edit_bank/' . $id_bank));
        }
    }

    public function delete_bank($id_bank)
    {
        // Soft delete produk, menandai produk sebagai terhapus
        $this->M_pemilik_bank->update($id_bank, ['deleted_at' => 1]);
        return redirect()->to(base_url('pemilik_kelola_data/bank'))->with('pesan', 'Data Bank berhasil dihapus !');
    }

    public function data_dihapus_bank()
    {
        $data = [
            'title' => 'Data Bank Dihapus',
            'title2' => 'Data Bank Dihapus',
            'data_bank_dihapus' => $this->M_pemilik_bank->get_bank_dihapus(), // Ambil data produk yang sudah dihapus
            'isi' => 'pemilik/bank/v_data_dihapus', // Ganti dengan view yang sesuai
        ];
        return view('layout/v_template', $data);
    }

    public function restore_bank($id_bank)
    {
        // Restore produk yang telah dihapus
        $this->M_pemilik_bank->update($id_bank, ['deleted_at' => 0]);
        return redirect()->to(base_url('pemilik_kelola_data/bank'))->with('pesan', 'Data BerHasil Di Restore !');
    }

    public function delete_hard_bank($id_bank)
    {
        $data = [
            'id_bank' => $id_bank,
        ];
        $this->M_pemilik_bank->delete_hard($data);
        session()->setFlashdata('pesan', 'Data Bank Ini Berhasil Di Hapus !');
        return redirect()->to(base_url('pemilik_kelola_data/data_dihapus_bank'));
    }
}
