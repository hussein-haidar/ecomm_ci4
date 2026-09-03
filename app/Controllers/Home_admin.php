<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\M_home_admin;
use App\Models\M_profil_user;

class Home_admin extends BaseController
{
    protected $M_home_admin;
    protected $M_profil_user;

    public function __construct()
    {
        $this->M_home_admin = new M_home_admin();
        $this->M_profil_user = new M_profil_user();
    }

    public function index()
    {
        $user = session()->get();
        $title = "Dashboard " . ($user['nama_title']);

        $tahun = $this->request->getGet('tahun') ?? date('Y');
        $bulan = $this->request->getGet('bulan'); // Bisa null

        $data = [
            'title' => $title,
            'title2' => 'Dashboard',
            'tot_jenis' => $this->M_home_admin->tot_jenis_by_sesi(),
            'tot_varian' => $this->M_home_admin->tot_varian_by_sesi(),
            'tot_produk' => $this->M_home_admin->tot_produk_by_sesi(),
            'tot_stok' => $this->M_home_admin->tot_stok_by_sesi(),
            'tot_jumlah_stok' => $this->M_home_admin->tot_jumlah_stok_by_sesi(),
            'tot_harga_stok' => $this->M_home_admin->tot_harga_stok_by_sesi(),
            'tot_penjualan' => $this->M_home_admin->tot_penjualan_by_sesi(),
            'tot_nilai_penjualan' => $this->M_home_admin->tot_nilai_penjualan_by_sesi(),
            'tot_pembayaran_selesai' => $this->M_home_admin->tot_pembayaran_selesai(),
            'tot_pembayaran_batal' => $this->M_home_admin->tot_pembayaran_batal(),
            'tahun' => $tahun,
            'bulan' => $bulan,
            'grafik_penjualan' => $this->M_home_admin->getPenjualanPerBulan($tahun, $bulan),
            'grafik_nilai' => $this->M_home_admin->getNilaiPenjualanPerBulan($tahun, $bulan),
            'produk_terlaris' => $this->M_home_admin->getProdukTerlaris($tahun),
            'produk_tersedikit' => $this->M_home_admin->getProdukTersedikit($tahun),
            'produk_nilai_tertinggi' => $this->M_home_admin->getProdukNilaiTertinggi($tahun),
            'produk_nilai_terendah' => $this->M_home_admin->getProdukNilaiTerendah($tahun),
            'isi' => 'admin/v_home_admin',
        ];

        return view('layout/v_template', $data);
    }

    public function profil()
    {
        // Menyiapkan data untuk dikirim ke view
        $data = [
            'title' => 'Data Profil',
            'title2' => 'Profil Saya',
            'data_profil' => $this->M_profil_user->get_profile(),
            'isi' => 'admin/update_profile/v_profil',
        ];

        return view('layout/v_template', $data);
    }

    public function edit($id_user)
    {
        $data = [
            'title' => 'Data Profile',
            'title2' => 'Edit Profile',
            'data_profil' => $this->M_profil_user->detailProfile($id_user),
            'isi' => 'admin/update_profile/v_edit',
        ];
        return view('layout/v_template', $data);
    }

    public function update_profile()
    {
        $session_id_user = session()->get('id_user');

        if ($this->validate([
            'password' => [
                'label' => 'Password',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} Wajib Diisi !!!'
                ]
            ],
            'nama_lengkap' => [
                'label' => 'Nama Lengkap',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} Wajib Diisi !!!'
                ]
            ],
            'notelpon_user' => [
                'label' => 'No Telepon Pengguna',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} Wajib Diisi !!!'
                ]
            ],
            'foto_user' => [
                'label' => 'Foto User',
                'rules' => 'max_size[foto_user,1024]|mime_in[foto_user,image/png,image/jpg,image/jpeg,image/gif,image/ico]',
                'errors' => [
                    'max_size' => '{field} Max 1024 KB !!!',
                    'mime_in' => 'Format {field} Wajib PNG, JPG, JPEG, GIF, ICO!!!'
                ]
            ],
        ])) {
            // Mengambil file foto dari form input
            $foto = $this->request->getFile('foto_user');

            if ($foto->getError() == 4) {
                // Jika foto tidak diganti
                $data = [
                    'username' => $this->request->getPost('username'),
                    'password' => $this->request->getPost('password'),
                    'nama_lengkap' => $this->request->getPost('nama_lengkap'),
                    'notelpon_user' => $this->request->getPost('notelpon_user'),
                    'jobdesk_user' => $this->request->getPost('jobdesk_user'),
                ];
            } else {
                // Menghapus foto lama
                $data_profil = $this->M_profil_user->detailProfile($session_id_user);
                if ($data_profil['foto_user'] != "") {
                    unlink('fotouser/' . $data_profil['foto_user']);
                }
                // Mengganti nama file foto
                $nama_file = $foto->getRandomName();
                // Menambahkan data foto baru
                $data = [
                    'username' => $this->request->getPost('username'),
                    'password' => $this->request->getPost('password'),
                    'nama_lengkap' => $this->request->getPost('nama_lengkap'),
                    'notelpon_user' => $this->request->getPost('notelpon_user'),
                    'jobdesk_user' => $this->request->getPost('jobdesk_user'),
                    'foto_user' => $nama_file,
                ];
                // Pindahkan file ke folder tujuan
                $foto->move('fotouser', $nama_file);
            }

            $sukses = $this->M_profil_user->edit($session_id_user, $data);

            if ($sukses) {
                // Update session data
                session()->set('username', $data['username']);
                session()->set('password', $data['password']);
                session()->set('nama_lengkap', $data['nama_lengkap']);
                session()->set('notelpon_user', $data['notelpon_user']);
                session()->set('jobdesk_user', $data['jobdesk_user']);
                if (isset($data['foto_user'])) {
                    session()->set('foto_user', $data['foto_user']);
                }

                session()->setFlashdata('pesan', 'Data Profil Berhasil Diubah !!!');
                return redirect()->to(base_url('home_admin/profil'));
            } else {
                session()->setFlashdata('pesan', 'Gagal Mengubah Data Profil !!!');
                return redirect()->to(base_url('home_admin/edit/' . $session_id_user));
            }
        } else {
            // Jika tidak valid
            session()->setFlashdata('errors', \Config\Services::validation()->getErrors());
            return redirect()->to(base_url('home_admin/edit/' . $session_id_user));
        }
    }

  
}
