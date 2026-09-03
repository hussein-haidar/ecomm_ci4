<?php

namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\M_pemilik_kelola_user;
use App\Models\M_pemilik_kurir;
use App\Models\M_profil_user;
use App\Models\M_profil_pelanggan;

class Pemilik_kelola_user extends BaseController
{
    protected $M_pemilik_kelola_user;
        protected $M_pemilik_kurir;
    protected $M_profil_user;
    protected $M_profil_pelanggan;

    public function __construct()
    {
        $this->M_pemilik_kelola_user = new M_pemilik_kelola_user();
        $this->M_pemilik_kurir = new M_pemilik_kurir();
        $this->M_profil_user = new M_profil_user();
        $this->M_profil_pelanggan = new M_profil_pelanggan();
    }

    public function user()
    {
        // Mengambil nilai 'tampilkan_varian' dan 'tampilkan_kriteria' dalam satu query
        $pengaturan_tampilan = $this->M_profil_user->getTampilanPengaturan();

        $data = [
            'title' => 'Data Pengguna',
            'title2' => 'Data Pengguna',
            'tampilkan_varian' => $pengaturan_tampilan['tampilkan_varian'],
            'user' => $this->M_pemilik_kelola_user->get_user(),
            'isi' => 'pemilik/data_user/v_user',
        ];
        return view('layout/v_template', $data);
    }

    public function add()
    {
        // Ambil nama_pelanggan dari session
        $sesi_user = session()->get('sesi_user');  // Menggunakan session() di CodeIgniter 4

        // Cek jika nama_pelanggan tidak ditemukan dalam session
        if (empty($sesi_user)) {
            // Redirect atau beri pesan error jika session nama_pelanggan tidak ada
            return redirect()->to('auth/login_user');  // Ganti dengan URL login atau halaman lainnya
        }
        // Ambil data pelanggan
        $data_pelanggan = $this->M_pemilik_kelola_user->get_user_by_id($sesi_user);

        $data = [
            'title' => 'Tambah Pengguna',
            'title2' => 'Data Pengguna',
            'sesi_user' => $data_pelanggan ? $data_pelanggan['sesi_user'] : 'Nama Lengkap Tidak Ditemukan',
            'isi' => 'pemilik/data_user/v_add',
        ];
        return view('layout/v_template', $data);
    }

    public function save()
    {
        if ($this->validate([
            'username' => [
                'label' => 'Nama Pengguna',
                'rules' => 'required|is_unique[tbl_data_user.username]',
                'errors' => [
                    'required' => '{field} Wajib Diisi !!!',
                    'is_unique' => '{field} Sudah Ada, Input {field} Lain !!!'
                ]
            ],
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
            'nama_title' => [
                'label' => 'Nama Title',
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
            'jobdesk_user' => [
                'label' => 'Jobdesk User',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} Wajib Diisi !!!'
                ]
            ],
            'level' => [
                'label' => 'Level',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} Wajib Dipilih !!!'
                ]
            ],
            'foto_user' => [
                'label' => 'Foto User',
                'rules' => 'uploaded[foto_user]|max_size[foto_user,1024]|mime_in[foto_user,image/png,image/jpg,image/jpeg,image/gif,image/ico]',
                'errors' => [
                    'max_size' => '{field} Max 1024 KB !!!',
                    'mime_in' => 'Format {field} Wajib PNG, JPG, JPEG, GIF, ICO!!!',
                ]
            ],
        ])) {
            // Mengambil file foto dari form input
            $foto = $this->request->getFile('foto_user');
            // Mengganti nama file foto
            $nama_file = $foto->getRandomName();

            // Jika valid, menambahkan data pengguna baru
            $data = [
                'username' => $this->request->getPost('username'),
                'password' => $this->request->getPost('password'),
                'nama_lengkap' => $this->request->getPost('nama_lengkap'),
                'sesi_user' => $this->request->getPost('sesi_user'),
                'nama_title' => $this->request->getPost('nama_title'),
                'notelpon_user' => $this->request->getPost('notelpon_user'),
                'jobdesk_user' => $this->request->getPost('jobdesk_user'),
                'level' => $this->request->getPost('level'),
                'foto_user' => $nama_file,
                'last_login' => date('Y-m-d H:i:s'),  // Menambahkan last_login dengan waktu saat ini
            ];

            // File foto disimpan di folder foto_user
            $foto->move('fotouser', $nama_file);

            // Menambahkan data ke database
            $this->M_pemilik_kelola_user->add($data);

            session()->setFlashdata('pesan', 'Data Pengguna Berhasil Ditambahkan !');
            return redirect()->to(base_url('pemilik_kelola_user/user'));
        } else {
            // Jika tidak valid
            session()->setFlashdata('errors', \Config\Services::validation()->getErrors());
            return redirect()->to(base_url('pemilik_kelola_user/add'));
        }
    }

    public function edit($id_user)
    {
        // Ambil nama_pelanggan dari session
        $sesi_user = session()->get('sesi_user');  // Menggunakan session() di CodeIgniter 4

        // Cek jika nama_pelanggan tidak ditemukan dalam session
        if (empty($sesi_user)) {
            // Redirect atau beri pesan error jika session nama_pelanggan tidak ada
            return redirect()->to('auth/login_user');  // Ganti dengan URL login atau halaman lainnya
        }
        // Ambil data pelanggan
        $data_pelanggan = $this->M_pemilik_kelola_user->get_user_by_id($sesi_user);

        $data = [
            'title' => 'Edit Pengguna',
            'title2' => 'Data Pengguna',
            'user' => $this->M_pemilik_kelola_user->detailUser($id_user),
            'sesi_user' => $data_pelanggan ? $data_pelanggan['sesi_user'] : 'Nama Lengkap Tidak Ditemukan',
            'isi' => 'pemilik/data_user/v_edit',
        ];
        return view('layout/v_template', $data);
    }

    public function update($id_user)
    {
        if ($this->validate([
            'username' => [
                'label' => 'Nama Pengguna',
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
            'nama_lengkap' => [
                'label' => 'Nama Lengkap',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} Wajib Diisi !!!'
                ]
            ],
            'nama_title' => [
                'label' => 'Nama Title',
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
            'jobdesk_user' => [
                'label' => 'Jobdesk User',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} Wajib Diisi !!!'
                ]
            ],
            'level' => [
                'label' => 'Level',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} Wajib Dipilih !!!'
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
            //Mengambil file foto dari form input
            $foto = $this->request->getFile('foto_user');

            if ($foto->getError() == 4) {
                //Jika foto tidak diganti
                $data = [
                    'id_user' => $id_user,
                    'username' => $this->request->getPost('username'),
                    'password' => $this->request->getPost('password'),
                    'nama_lengkap' => $this->request->getPost('nama_lengkap'),
                    'sesi_user' => $this->request->getPost('sesi_user'),
                    'nama_title' => $this->request->getPost('nama_title'),
                    'notelpon_user' => $this->request->getPost('notelpon_user'),
                    'jobdesk_user' => $this->request->getPost('jobdesk_user'),
                    'level' => $this->request->getPost('level'),
                ];
                $this->M_pemilik_kelola_user->edit($data);
            } else {
                //Menghapus foto lama
                $data_user = $this->M_pemilik_kelola_user->detailUser($id_user);
                if ($data_user['foto_user'] != "") {
                    unlink('fotouser/' . $data_user['foto_user']);
                }
                //Mengganti nama file foto
                $nama_file = $foto->getRandomName();
                // Jika valid
                $data = [
                    'id_user' => $id_user,
                    'username' => $this->request->getPost('username'),
                    'password' => $this->request->getPost('password'),
                    'nama_lengkap' => $this->request->getPost('nama_lengkap'),
                    'sesi_user' => $this->request->getPost('sesi_user'),
                    'nama_title' => $this->request->getPost('nama_title'),
                    'notelpon_user' => $this->request->getPost('notelpon_user'),
                    'jobdesk_user' => $this->request->getPost('jobdesk_user'),
                    'level' => $this->request->getPost('level'),
                    'foto_user' => $nama_file,
                ];
                // File foto disimpan di folder foto_user
                $foto->move('fotouser', $nama_file);
                $this->M_pemilik_kelola_user->edit($data);
            }

            // Update session data
            session()->set('username', $data['username']);
            session()->set('password', $data['password']);
            session()->set('notelpon_user', $data['notelpon_user']);
            session()->set('jobdesk_user', $data['jobdesk_user']);
            if (isset($data['foto_user'])) {
                session()->set('foto_user', $data['foto_user']);
            }

            session()->setFlashdata('pesan', 'Data Pengguna Ini Berhasil Di Ganti !');
            return redirect()->to(base_url('pemilik_kelola_user/user'));
        } else {
            // Jika tidak valid
            session()->setFlashdata('errors', \Config\Services::validation()->getErrors());
            return redirect()->to(base_url('pemilik_kelola_user/edit/' . $id_user));
        }
    }

    public function delete_user($id_user)
    {
        //Menghapus foto lama
        $data_user = $this->M_pemilik_kelola_user->detailUser($id_user);
        if ($data_user['foto_user'] != "") {
            unlink('fotouser/' . $data_user['foto_user']);
        }
        $data = [
            'id_user' => $id_user,
        ];
        $this->M_pemilik_kelola_user->delete_data($data);
        session()->setFlashdata('pesan', 'Data Pengguna Ini Berhasil Di Hapus !');
        return redirect()->to(base_url('pemilik_kelola_user/user'));
    }

    public function pelanggan()
    {
        $data = [
            'title' => 'Data Pelanggan',
            'title2' => 'Data Pelanggan',
            'data_profil' => $this->M_profil_pelanggan->get_profile(),
            'isi' => 'pemilik/data_pelanggan/v_pelanggan',
        ];
        return view('layout/v_template', $data);
    }

    public function delete_pelanggan($id_pelanggan)
    {
        //Menghapus foto lama
        $data_profil = $this->M_profil_pelanggan->detailProfile($id_pelanggan);
        if ($data_profil['foto_pelanggan'] != "") {
            unlink('fotopelanggan/' . $data_profil['foto_pelanggan']);
        }
        $data = [
            'id_pelanggan' => $id_pelanggan,
        ];
        $this->M_profil_pelanggan->delete_data($data);
        session()->setFlashdata('pesan', 'Data Pelanggan Ini Berhasil Di Hapus !');
        return redirect()->to(base_url('pemilik_kelola_user/pelanggan'));
    }

    public function update_tampilan_varian()
    {
        // Ambil ID user target dari form POST
        $userId = $this->request->getPost('id_user');

        // Validasi minimal: pastikan ID user dikirim
        if (!$userId) {
            return redirect()->back()->with('error', 'ID user tidak ditemukan.');
        }

        // Ambil nilai checkbox
        $tampilkan_varian = $this->request->getPost('tampilkan_varian') ? 1 : 0;

        // Update data berdasarkan ID user target
        $this->M_profil_user->update($userId, [
            'tampilkan_varian' => $tampilkan_varian,
        ]);

        return redirect()->back()->with('success', 'Preferensi tampilan berhasil diperbarui.');
    }

    public function kurir()
    {
        $data = [
            'title' => 'Daftar Kurir Produk',
            'title2' => 'Data Kurir Produk',
            'data_kurir' => $this->M_pemilik_kurir->get_kurir(),
            'isi' => 'pemilik/data_kurir/v_kurir',
        ];
        return view('layout/v_template', $data);
    }

    public function add_kurir()
    {
        // Ambil nama_pelanggan dari session
        $sesi_user = session()->get('sesi_user');  // Menggunakan session() di CodeIgniter 4

        // Cek jika nama_pelanggan tidak ditemukan dalam session
        if (empty($sesi_user)) {
            // Redirect atau beri pesan error jika session nama_pelanggan tidak ada
            return redirect()->to('auth/login_user');  // Ganti dengan URL login atau halaman lainnya
        }
        // Ambil data pelanggan
        $data_user = $this->M_pemilik_kurir->get_user_by_id($sesi_user);

        $data = [
            'title' => 'Tambah Kurir',
            'title2' => 'Data Kurir',
            'sesi_user' => $data_user ? $data_user['sesi_user'] : 'Nama Lengkap Tidak Ditemukan',
            'isi' => 'pemilik/data_kurir/v_add',
        ];
        return view('layout/v_template', $data);
    }

    public function save_kurir()
    {
        if ($this->validate([
            'jenis_kurir' => [
                'label' => 'Jenis Kurir',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} Wajib Diisi !!!'
                ]
            ],
        ])) {
            // Jika valid
            $data = [
                'sesi_user' => $this->request->getPost('sesi_user'),
                'jenis_kurir' => $this->request->getPost('jenis_kurir'),
                'ongkir' => $this->request->getPost('ongkir'),
            ];
            $this->M_pemilik_kurir->add($data);

            session()->setFlashdata('pesan', 'Data Kurir Produk Berhasil Ditambahkan !');
            return redirect()->to(base_url('pemilik_kelola_user/kurir'));
        } else {
            // JIka tidak valid
            session()->setFlashdata('errors', \Config\Services::validation()->getErrors());
            return redirect()->to(base_url('pemilik_kelola_user/add_kurir'));
        }
    }

    public function edit_kurir($id_kurir)
    {
        // Ambil nama_pelanggan dari session
        $sesi_user = session()->get('sesi_user');  // Menggunakan session() di CodeIgniter 4

        // Cek jika nama_pelanggan tidak ditemukan dalam session
        if (empty($sesi_user)) {
            // Redirect atau beri pesan error jika session nama_pelanggan tidak ada
            return redirect()->to('auth/login_user');  // Ganti dengan URL login atau halaman lainnya
        }
        // Ambil data pelanggan
        $data_user = $this->M_pemilik_kurir->get_user_by_id($sesi_user);

        $data = [
            'title' => 'Edit varian Produk',
            'title2' => 'Data varian Produk',
            'data_kurir' => $this->M_pemilik_kurir->detailKurir($id_kurir),
            'sesi_user' => $data_user ? $data_user['sesi_user'] : 'Nama Lengkap Tidak Ditemukan',
            'isi' => 'pemilik/data_kurir/v_edit',
        ];
        return view('layout/v_template', $data);
    }

    public function update_kurir($id_kurir)
    {
        if ($this->validate([
            'jenis_kurir' => [
                'label' => 'Jenis Kurir',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} Wajib Diisi !!!'
                ]
            ],
        ])) {
            // Jika valid
            $data = [
                'id_kurir' => $id_kurir,
                'jenis_kurir' => $this->request->getPost('jenis_kurir'),
                'ongkir' => $this->request->getPost('ongkir'),
            ];
            $this->M_pemilik_kurir->edit($data);

            session()->setFlashdata('pesan', 'Data Kurir Produk Ini Berhasil Di Ganti !');
            return redirect()->to(base_url('pemilik_kelola_user/kurir'));
        } else {
            // JIka tidak valid
            session()->setFlashdata('errors', \Config\Services::validation()->getErrors());
            return redirect()->to(base_url('pemilik_kelola_user/edit_kurir/' . $id_kurir));
        }
    }

    public function delete_kurir($id_kurir)
    {
        $data = [
            'id_kurir' => $id_kurir,
        ];
        $this->M_pemilik_kurir->delete_data($data);
        session()->setFlashdata('pesan', 'Data Kurir Ini Berhasil Di Hapus !');
        return redirect()->to(base_url('pemilik_kelola_user/kurir'));
    }
}
