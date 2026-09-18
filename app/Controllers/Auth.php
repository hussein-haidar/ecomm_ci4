<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\M_profil_user;
use App\Models\M_profil_pelanggan;
use App\Models\M_pelanggan_bayar;

class Auth extends BaseController
{
    protected $M_profil_user;
    protected $M_profil_pelanggan;
    protected $M_pelanggan_bayar;

    public function __construct()
    {
        $this->M_profil_user = new M_profil_user();
        $this->M_profil_pelanggan = new M_profil_pelanggan();
        $this->M_pelanggan_bayar = new M_pelanggan_bayar();
    }

    public function register_user()
    {
        // Ambil nama_pelanggan dari session
        $nama_lengkap = session()->get('nama_lengkap');  // Menggunakan session() di CodeIgniter 4

        // Cek jika nama_pelanggan tidak ditemukan dalam session
        if (empty($nama_lengkap)) {
            // Redirect atau beri pesan error jika session nama_pelanggan tidak ada
            return redirect()->to('auth/login_user');  // Ganti dengan URL login atau halaman lainnya
        }
        // Ambil data pelanggan
        $data_pelanggan = $this->M_profil_user->get_user_by_id($nama_lengkap);

        $data = [
            'title' => 'Register',
            'title2' => 'Register',
            'nama_lengkap' => $data_pelanggan ? $data_pelanggan['nama_lengkap'] : 'Nama Lengkap Tidak Ditemukan',
        ];
        return view('auth_login/v_register', $data);
    }

    public function save_user()
    {
        if ($this->validate([
            'username' => [
                'label' => 'Username',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} Wajib Diisi !!!'
                ]
            ],
            'password' => [
                'label' => 'Kata Sandi',
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
            'level' => [
                'label' => 'Level',
                'rules' => 'required|in_list[1]',
                'errors' => [
                    'required' => '{field} Wajib Dipilih!',
                    'in_list' => '{field} Harus Pemilik!',
                ]
            ],
            'nama_title' => [
                'label' => 'Title Dashboard',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} Wajib Diisi !!!'
                ]
            ],
            'foto_user' => [
                'label' => 'Foto User',
                'rules' => 'uploaded[foto_user]|max_size[foto_user,1024]|mime_in[foto_user,image/png,image/jpg,image/jpeg]',
                'errors' => [
                    'uploaded' => '{field} Wajib Diunggah!',
                    'max_size' => '{field} Max 1024 KB!',
                    'mime_in' => '{field} Format Harus PNG, JPG, JPEG!',
                ],
            ],
        ])) {
            // Mengambil file foto dari form input
            $foto = $this->request->getFile('foto_user');
            // Mengganti nama file foto
            $nama_file = $foto->getRandomName();

            // Jika valid, menambahkan data pengguna baru
            $data = [
                'sesi_user' => $this->request->getPost('nama_lengkap'),
                'username' => $this->request->getPost('username'),
                'password' => $this->request->getPost('password'),
                'nama_lengkap' => $this->request->getPost('nama_lengkap'),
                'nama_title' => $this->request->getPost('nama_title'),
                'notelpon_user' => $this->request->getPost('notelpon_user'),
                'jobdesk_user' => $this->request->getPost('jobdesk_user'),
                'level' => $this->request->getPost('level'),
                'foto_user' => $nama_file,
                'last_login' => date('Y-m-d H:i:s'),  // Menambahkan last_login dengan waktu saat ini
            ];

            // File foto disimpan di folder
            $foto->move('fotouser', $nama_file);

            // Menambahkan data ke database
            $this->M_profil_user->add($data);

            session()->setFlashdata('pesan_success', 'Data Pelanggan Berhasil Ditambahkan !');
            return redirect()->to(base_url('auth/login_user'));
        } else {
            // Jika tidak valid
            session()->setFlashdata('errors', \Config\Services::validation()->getErrors());
            return redirect()->to(base_url('auth/register_user'));
        }
    }

    public function buka_toko()
    {
        $data = [
            'title' => 'Buka Toko',
            'title2' => 'Buka Toko',
        ];
        return view('pelanggan/auth_login/v_buka_toko', $data);
    }

    public function save_buka_toko()
    {
        if ($this->validate([
            'username' => [
                'label' => 'Username',
                'rules' => 'required|min_length[4]',
                'errors' => [
                    'required' => '{field} Wajib Diisi !!!',
                    'min_length' => '{field} Minimal 4 Karakter !'
                ]
            ],
            'password' => [
                'label' => 'Kata Sandi',
                'rules' => 'required|min_length[4]',
                'errors' => [
                    'required' => '{field} Wajib Diisi !!!',
                    'min_length' => '{field} Minimal 4 Karakter !'
                ]
            ],
            'nama_lengkap' => [
                'label' => 'Nama Lengkap Pemilik',
                'rules' => 'required',
                'errors' => ['required' => '{field} Wajib Diisi !!!']
            ],
            'nama_toko' => [
                'label' => 'Nama Toko',
                'rules' => 'required',
                'errors' => ['required' => '{field} Wajib Diisi !!!']
            ],
        ])) {
            $username = $this->request->getPost('username');
            $namaLengkap = trim($this->request->getPost('nama_lengkap'));

            // Cek duplikasi username
            if ($this->M_profil_user->findByUsername($username)) {
                session()->setFlashdata('errors', ['Username "' . $username . '" sudah terdaftar!']);
                return redirect()->to(base_url('auth/buka_toko'));
            }

            // Cek duplikasi sesi_user (nama_lengkap dipakai sebagai penanda toko)
            if ($this->M_profil_user->get_user_by_id($namaLengkap)) {
                session()->setFlashdata('errors', ['Nama lengkap "' . $namaLengkap . '" sudah terdaftar. Gunakan nama lain.']);
                return redirect()->to(base_url('auth/buka_toko'));
            }

            // Foto user bersifat opsional
            $foto = $this->request->getFile('foto_user');
            $nama_file = '';
            if ($foto && $foto->isValid() && !$foto->hasMoved()) {
                $nama_file = $foto->getRandomName();
                $foto->move('fotouser', $nama_file);
            }

            // 1) Buat akun user pemilik (level 1)
            $idUser = $this->M_profil_user->insert([
                'sesi_user' => $namaLengkap,
                'username' => $username,
                'password' => $this->request->getPost('password'),
                'nama_lengkap' => $namaLengkap,
                'nama_title' => $this->request->getPost('nama_title') ?: 'Dashboard Pemilik',
                'notelpon_user' => $this->request->getPost('notelpon_user'),
                'jobdesk_user' => $this->request->getPost('jobdesk_user') ?: 'Pemilik',
                'level' => 1,
                'foto_user' => $nama_file,
                'last_login' => date('Y-m-d H:i:s'),
            ]);

            // 2) Auto-buat data toko (tema default, langsung aktif / is_checked=2)
            $websiteModel = new \App\Models\M_pemilik_website();
            $websiteModel->add([
                'sesi_user' => $namaLengkap,
                'level' => 1,
                'nama_toko' => $this->request->getPost('nama_toko'),
                'alamat_pusat' => $this->request->getPost('alamat_pusat') ?? '',
                'wa_pusat' => $this->request->getPost('wa_pusat') ?? '',
                'footer_title' => $this->request->getPost('nama_toko'),
                'tema_website' => 'default',
                'is_checked' => 2,
            ]);

            // Bersihkan sisa session pelanggan agar tidak tercampur
            session()->remove([
                'nama_pelanggan', 'id_pelanggan', 'email', 'jenis_kelamin',
                'no_telpon', 'tanggal_lahir', 'longitude', 'latitude',
                'alamat', 'foto_pelanggan', 'kode_kota', 'nama_kota'
            ]);

            // 3) Langsung login sebagai pemilik ke toko barunya
            session()->set([
                'id_user' => $idUser,
                'username' => $username,
                'nama_lengkap' => $namaLengkap,
                'sesi_user' => $namaLengkap,
                'nama_title' => $this->request->getPost('nama_title') ?: 'Dashboard Pemilik',
                'notelpon_user' => $this->request->getPost('notelpon_user'),
                'jobdesk_user' => $this->request->getPost('jobdesk_user') ?: 'Pemilik',
                'foto_user' => $nama_file,
                'level' => 1,
                'user_logged_in' => true,
                'log' => true,
                'toko_sesi_user' => $namaLengkap,
            ]);

            session()->setFlashdata('pesan_welcome', 'Selamat Datang, ' . $namaLengkap . '! Toko Anda aktif dan siap dikelola.');
            return redirect()->to(base_url('home_pemilik'));
        } else {
            // Jika tidak valid
            session()->setFlashdata('errors', \Config\Services::validation()->getErrors());
            return redirect()->to(base_url('auth/buka_toko'));
        }
    }

    public function login_user()
    {
        // Jika belum pilih toko, redirect ke pilih toko
        if (empty(session()->get('toko_sesi_user'))) {
            return redirect()->to(base_url('auth/pilih_toko_user'));
        }

        $data = [
            'title' => 'Login',
            'title2' => 'Login',
        ];
        return view('auth_login/v_login', $data);
    }

    public function cek_login_user()
    {
        if ($this->validate([
            'username' => [
                'label' => 'Nama Pengguna',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} Wajib Diisi !!!'
                ]
            ],
            'password' => [
                'label' => 'Kata Sandi',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} Wajib Diisi !!!'
                ]
            ],
        ])) {
            $username = $this->request->getPost('username');
            $password = $this->request->getPost('password');

            // Cek apakah username ada
            $user = $this->M_profil_user->findByUsername($username);
            if ($user) {
                // Jika username ada, cek password
                if ($user['password'] === $password) {
                    // Cek apakah sudah memilih toko
                    $tokoDipilih = session()->get('toko_sesi_user');
                    if (empty($tokoDipilih)) {
                        session()->setFlashdata('pesan_warning', 'Silakan pilih toko terlebih dahulu!');
                        return redirect()->to(base_url('auth/pilih_toko_user'));
                    }

                    // Cek apakah user adalah anggota toko yang dipilih
                    if ($user['sesi_user'] !== $tokoDipilih) {
                        session()->setFlashdata('pesan_warning', 'Anda bukan anggota toko ini! Silakan pilih toko yang benar.');
                        session()->remove('toko_sesi_user');
                        return redirect()->to(base_url('auth/pilih_toko_user'));
                    }

                    // Bersihkan sisa session pelanggan agar tidak tercampur
                    session()->remove([
                        'nama_pelanggan', 'id_pelanggan', 'email', 'jenis_kelamin',
                        'no_telpon', 'tanggal_lahir', 'longitude', 'latitude',
                        'alamat', 'foto_pelanggan', 'kode_kota', 'nama_kota'
                    ]);

                    // Login session
                    $userData = [
                        'id_user' => $user['id_user'],
                        'username' => $user['username'],
                        'password' => $user['password'],
                        'nama_lengkap' => $user['nama_lengkap'],
                        'sesi_user' => $user['sesi_user'],
                        'nama_title' => $user['nama_title'],
                        'notelpon_user' => $user['notelpon_user'],
                        'jobdesk_user' => $user['jobdesk_user'],
                        'foto_user' => $user['foto_user'],
                        'level' => $user['level'],
                        'user_logged_in'     => true,
                        'last_login' => $user['last_login'],
                    ];

                    session()->set('log', true);
                    session()->set($userData);

                    // Update waktu login terakhir
                    $this->M_profil_user->updateLastLogin($user['id_user']);
                    session()->setFlashdata('pesan_welcome', 'Selamat Datang, ' . $user['nama_lengkap'] . '!');

                    // Redirect berdasarkan level
                    switch ($user['level']) {
                        case 1:
                            return redirect()->to(base_url('home_pemilik'));
                        case 2:
                            return redirect()->to(base_url('home_admin'));
                        default:
                            return redirect()->to(base_url('auth/login_user'));
                    }
                } else {
                    // Password salah
                    session()->setFlashdata('pesan_warning', 'Login Gagal, Password Salah!');
                    return redirect()->to(base_url('auth/login_user'));
                }
            } else {
                // Username tidak ditemukan
                session()->setFlashdata('pesan_warning', 'Login Gagal, Username Tidak Ditemukan!');
                return redirect()->to(base_url('auth/login_user'));
            }
        } else {
            // Validasi form gagal
            session()->setFlashdata('errors', \Config\Services::validation()->getErrors());
            return redirect()->to(base_url('auth/login_user'));
        }
    }

    public function logout_user()
    {
        // Hapus semua session login (admin/pemilik & sisa pelanggan)
        session()->remove([
            'username', 'id_user', 'level', 'user_logged_in', 'nama_lengkap', 'sesi_user',
            'nama_pelanggan', 'id_pelanggan', 'email', 'jenis_kelamin', 'no_telpon',
            'tanggal_lahir', 'longitude', 'latitude', 'alamat', 'foto_pelanggan',
            'kode_kota', 'nama_kota'
        ]);

        // Set flashdata pesan_success setelah menghapus sesi
        session()->setFlashdata('pesan_success', 'Logout Berhasil !');

        // Redirect ke halaman login
        return redirect()->to(base_url('auth/login_user'));
    }

    public function register_pelanggan()
    {
        // Jika belum pilih toko, redirect ke pilih toko
        if (empty(session()->get('toko_sesi_user'))) {
            return redirect()->to(base_url('auth/pilih_toko'));
        }

        $data = [
            'title' => 'Register',
            'title2' => 'Register',
        ];
        return view('pelanggan/auth_login/v_register', $data);
    }

    public function save_pelanggan()
    {
        if ($this->validate([
            'email' => [
                'label' => 'Email',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} Wajib Diisi !!!'
                ]
            ],
            'password' => [
                'label' => 'Kata Sandi',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} Wajib Diisi !!!'
                ]
            ],
            'nama_pelanggan' => [
                'label' => 'Nama Pelanggan',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} Wajib Diisi !!!'
                ]
            ],
            'jenis_kelamin' => [
                'label' => 'Jenis Kelamin',
                'rules' => 'required|in_list[L,P]',
                'errors' => [
                    'required' => '{field} Wajib Dipilih!',
                    'in_list' => '{field} Harus Laki-Laki atau Perempuan!',
                ]
            ],
            'tanggal_lahir' => [
                'label' => 'Tanggal Lahir',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} Wajib Diisi !!!'
                ]
            ],
            'no_telpon' => [
                'label' => 'No Telpon',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} Wajib Dipilih !!!'
                ]
            ],
            'alamat' => [
                'label' => 'Alamat',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} Wajib Dipilih !!!'
                ]
            ],
            'foto_pelanggan' => [
                'label' => 'Foto Pelanggan',
                'rules' => 'max_size[foto_pelanggan,1024]|mime_in[foto_pelanggan,image/png,image/jpg,image/jpeg]',
                'errors' => [
                    'max_size' => '{field} Max 1024 KB!',
                    'mime_in' => '{field} Format Harus PNG, JPG, JPEG!',
                ],
            ],
        ])) {
            // Mengambil file foto dari form input
            $foto = $this->request->getFile('foto_pelanggan');
            $nama_file = 'profil_default.png';

            if ($foto && $foto->isValid() && !$foto->hasMoved()) {
                $nama_file = $foto->getRandomName();
                $foto->move('fotopelanggan', $nama_file);
            }

            // Jika valid, menambahkan data pengguna baru
            $data = [
                'sesi_user' => $this->request->getPost('sesi_user'),
                'email' => $this->request->getPost('email'),
                'password' => $this->request->getPost('password'),
                'nama_pelanggan' => $this->request->getPost('nama_pelanggan'),
                'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),
                'tanggal_lahir' => $this->request->getPost('tanggal_lahir'),
                'no_telpon' => $this->request->getPost('no_telpon'),
                'alamat' => $this->request->getPost('alamat'),
                'foto_pelanggan' => $nama_file,
                'last_login' => date('Y-m-d H:i:s'),
            ];

            // Menambahkan data ke database
            $this->M_profil_pelanggan->add($data);

            session()->setFlashdata('pesan_success', 'Data Pelanggan Berhasil Ditambahkan !');
            return redirect()->to(base_url('auth/register_pelanggan'));
        } else {
            // Jika tidak valid
            session()->setFlashdata('errors', \Config\Services::validation()->getErrors());
            return redirect()->to(base_url('auth/register_pelanggan'));
        }
    }

    public function login_pelanggan()
    {
        // Jika belum pilih toko, redirect ke pilih toko
        if (empty(session()->get('toko_sesi_user'))) {
            return redirect()->to(base_url('auth/pilih_toko'));
        }

        $data = [
            'title' => 'Login',
            'title2' => 'Login',
        ];
        return view('pelanggan/auth_login/v_login', $data);
    }

    public function cek_login_pelanggan()
    {
        if ($this->validate([
            'email' => [
                'label' => 'Email',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} Wajib Diisi !!!'
                ]
            ],
            'password' => [
                'label' => 'Kata Sandi',
                'rules' => 'required',
                'errors' => [
                    'required' => '{field} Wajib Diisi !!!'
                ]
            ],
        ])) {
            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');

            // Cek apakah username ada
            $user = $this->M_profil_pelanggan->findByEmail($email);
            if ($user) {
                // Jika username ada, cek password
                if ($user['password'] === $password) {
                    // Login berhasil

                    // Bersihkan sisa session admin/pemilik agar tidak tercampur
                    session()->remove([
                        'nama_lengkap', 'id_user', 'username', 'nama_title',
                        'notelpon_user', 'jobdesk_user', 'foto_user'
                    ]);

                    $userData = [
                        'id_pelanggan' => $user['id_pelanggan'],
                        'sesi_user' => $user['sesi_user'],
                        'email' => $user['email'],
                        'password' => $user['password'],
                        'jenis_kelamin' => $user['jenis_kelamin'],
                        'nama_pelanggan' => $user['nama_pelanggan'],
                        'no_telpon' => $user['no_telpon'],
                        'tanggal_lahir' => $user['tanggal_lahir'],
                        'longitude' => $user['longitude'],
                        'latitude' => $user['latitude'],
                        'alamat' => $user['alamat'],
                        'foto_pelanggan' => $user['foto_pelanggan'],
                        'level' => $user['level'],
                        'kode_kota' => $user['kode_kota'] ?? '',
                        'nama_kota' => $user['nama_kota'] ?? '',
                        'user_logged_in'     => true,
                        'last_login' => $user['last_login'],
                    ];

                    session()->set('log', true);
                    session()->set($userData);

                    // ✅ Tambahkan trigger pembatalan otomatis di sini
                    $this->M_pelanggan_bayar->batalkanTransaksiExpiredByUser($user['nama_pelanggan']);

                    // Update waktu login terakhir
                    $this->M_profil_pelanggan->updateLastLogin($user['id_pelanggan']);

session()->setFlashdata('pesan_welcome', 'Selamat Datang, ' . $user['nama_pelanggan'] . '!');

        // Redirect ke toko yang sudah dipilih, atau pilih toko jika belum
        $redirectUrl = session()->get('toko_sesi_user')
            ? base_url('home_toko/index')
            : base_url('auth/pilih_toko');

        return redirect()->to($redirectUrl);
                } else {
                    // Password salah
                    session()->setFlashdata('pesan_warning', 'Login Gagal, Password Salah!');
                    return redirect()->to(base_url('auth/login_pelanggan'));
                }
            } else {
                // Username tidak ditemukan
                session()->setFlashdata('pesan_warning', 'Login Gagal, Email Tidak Ditemukan!');
                return redirect()->to(base_url('auth/login_pelanggan'));
            }
        } else {
            // Validasi form gagal
            session()->setFlashdata('errors', \Config\Services::validation()->getErrors());
            return redirect()->to(base_url('auth/login_pelanggan'));
        }
    }

    public function lupa_password_pelanggan()
    {
        $data = [
            'title' => 'Lupa Password',
            'title2' => 'Lupa Password',
        ];
        return view('pelanggan/auth_login/v_lupa_password', $data);
    }

    public function cek_proses()
    {
        $email = $this->request->getPost('email');

        $user = $this->M_profil_pelanggan->findByEmail($email);

        if ($user) {
            return redirect()->to(base_url('auth/reset_password/' . $user['id_pelanggan']));
        } else {
            return redirect()->back()->with('error_email', 'Email tidak ditemukan.');
        }
    }

    public function reset_password($id_pelanggan)
    {
        $user = $this->M_profil_pelanggan->find($id_pelanggan);

        if (!$user) {
            return redirect()->to(base_url('auth/lupa_password_pelanggan'))->with('pesan_warning', 'Akun tidak ditemukan.');
        }

        $data = [
            'title' => 'Reset Password',
            'user' => $user,
        ];

        return view('pelanggan/auth_login/v_reset_password', $data);
    }

    public function ganti_password($id_pelanggan)
    {
        $new_password = $this->request->getPost('new_password');
        $confirm_password = $this->request->getPost('confirm_password');

        if ($new_password !== $confirm_password) {
            return redirect()->back()->with('error_password', 'Password tidak cocok.');
        }

        // Simpan password langsung tanpa hashing
        $this->M_profil_pelanggan->update($id_pelanggan, ['password' => $new_password]);

        return redirect()->to(base_url('auth/login_pelanggan'))->with('pesan_success', 'Password berhasil diperbarui.');
    }

    public function logout_pelanggan()
    {
        // Hapus semua session pelanggan & sisa session admin/pemilik
        session()->remove([
            'username', 'id_user', 'level', 'user_logged_in', 'toko_sesi_user',
            'nama_lengkap', 'sesi_user', 'nama_pelanggan', 'id_pelanggan', 'email',
            'jenis_kelamin', 'no_telpon', 'tanggal_lahir', 'longitude', 'latitude',
            'alamat', 'foto_pelanggan', 'kode_kota', 'nama_kota'
        ]);

        // Set flashdata pesan_success setelah menghapus sesi
        session()->setFlashdata('pesan_logout', 'Logout Berhasil !');

        // Redirect ke halaman login
        return redirect()->to(base_url('home_toko/index'));
    }

    public function login_google()
    {
        $clientId = env('google.clientId', '') ?? '';
        $redirectUri = base_url('auth/google/callback');
        $scope = 'email profile';

        $url = 'https://accounts.google.com/o/oauth2/v2/auth'
            . '?client_id=' . urlencode($clientId)
            . '&redirect_uri=' . urlencode($redirectUri)
            . '&response_type=code'
            . '&scope=' . urlencode($scope)
            . '&access_type=offline';

        return redirect()->to($url);
    }

    public function google_callback()
    {
        $code = $this->request->getGet('code');

        if (empty($code)) {
            session()->setFlashdata('pesan_warning', 'Login Google Gagal: Kode otorisasi tidak diterima.');
            return redirect()->to(base_url('auth/login_pelanggan'));
        }

        $clientId     = env('google.clientId', '') ?? '';
        $clientSecret = env('google.clientSecret', '') ?? '';
        $redirectUri  = base_url('auth/google/callback');

        // Tukar authorization code dengan access token
        $tokenData = $this->exchangeCodeForToken($code, $clientId, $clientSecret, $redirectUri);

        if (!isset($tokenData['access_token'])) {
            session()->setFlashdata('pesan_warning', 'Login Google Gagal: Tidak bisa mendapatkan access token.');
            return redirect()->to(base_url('auth/login_pelanggan'));
        }

        // Ambil info user dari Google
        $userInfo = $this->getGoogleUserInfo($tokenData['access_token']);

        if (!isset($userInfo['email']) || empty($userInfo['email'])) {
            session()->setFlashdata('pesan_warning', 'Login Google Gagal: Tidak bisa mendapatkan data pengguna.');
            return redirect()->to(base_url('auth/login_pelanggan'));
        }

        $googleId    = $userInfo['id'] ?? '';
        $email       = $userInfo['email'];
        $nama        = $userInfo['name'] ?? 'Google User';
        $foto        = $userInfo['picture'] ?? '';
        $defaultFoto = 'profil_default.png';

        // Cek apakah user sudah pernah login pakai Google
        $pelanggan = $this->M_profil_pelanggan->findByGoogleId($googleId);

        if (!$pelanggan) {
            // Cek apakah email sudah ada di database
            $pelanggan = $this->M_profil_pelanggan->findByEmail($email);
        }

        if ($pelanggan) {
            // Update google_id jika kosong
            if (empty($pelanggan['google_id'])) {
                $this->M_profil_pelanggan->update($pelanggan['id_pelanggan'], ['google_id' => $googleId]);
            }
            // Update foto jika kosong
            if (empty($pelanggan['foto_pelanggan']) && !empty($foto)) {
                $this->M_profil_pelanggan->update($pelanggan['id_pelanggan'], ['foto_pelanggan' => $foto]);
            } elseif (empty($pelanggan['foto_pelanggan'])) {
                $this->M_profil_pelanggan->update($pelanggan['id_pelanggan'], ['foto_pelanggan' => $defaultFoto]);
            }
        } else {
            // Buat akun baru otomatis
            $newData = [
                'sesi_user'      => $nama,
                'email'          => $email,
                'google_id'      => $googleId,
                'password'       => '',
                'nama_pelanggan' => $nama,
                'jenis_kelamin'  => 'L',
                'tanggal_lahir'  => '2000-01-01',
                'no_telpon'      => '',
                'longitude'      => 0,
                'latitude'       => 0,
                'alamat'         => '',
                'foto_pelanggan' => !empty($foto) ? $foto : $defaultFoto,
                'level'          => '3',
                'last_login'     => date('Y-m-d H:i:s'),
            ];

            $this->M_profil_pelanggan->add($newData);
            $pelanggan = $this->M_profil_pelanggan->findByEmail($email);
        }

        // Bersihkan sisa session admin/pemilik agar tidak tercampur
        session()->remove([
            'nama_lengkap', 'id_user', 'username', 'nama_title',
            'notelpon_user', 'jobdesk_user', 'foto_user'
        ]);

        // Set session
        $userData = [
            'id_pelanggan'    => $pelanggan['id_pelanggan'],
            'sesi_user'       => $pelanggan['sesi_user'],
            'email'           => $pelanggan['email'],
            'password'        => $pelanggan['password'],
            'jenis_kelamin'   => $pelanggan['jenis_kelamin'],
            'nama_pelanggan'  => $pelanggan['nama_pelanggan'],
            'no_telpon'       => $pelanggan['no_telpon'],
            'tanggal_lahir'   => $pelanggan['tanggal_lahir'],
            'longitude'       => $pelanggan['longitude'] ?? 0,
            'latitude'        => $pelanggan['latitude'] ?? 0,
            'alamat'          => $pelanggan['alamat'],
            'foto_pelanggan'  => $pelanggan['foto_pelanggan'],
            'level'           => $pelanggan['level'],
            'kode_kota'       => $pelanggan['kode_kota'] ?? '',
            'nama_kota'       => $pelanggan['nama_kota'] ?? '',
            'user_logged_in'  => true,
            'last_login'      => $pelanggan['last_login'],
        ];

        session()->set('log', true);
        session()->set($userData);

        $this->M_pelanggan_bayar->batalkanTransaksiExpiredByUser($pelanggan['nama_pelanggan']);
        $this->M_profil_pelanggan->updateLastLogin($pelanggan['id_pelanggan']);

        session()->setFlashdata('pesan_welcome', 'Selamat Datang, ' . $pelanggan['nama_pelanggan'] . '!');

        // Redirect ke toko yang sudah dipilih, atau pilih toko jika belum
        $redirectUrl = session()->get('toko_sesi_user')
            ? base_url('home_toko/index')
            : base_url('auth/pilih_toko');

        return redirect()->to($redirectUrl);
    }

    public function csrf()
    {
        return $this->response
            ->setStatusCode(200)
            ->setContentType('application/json')
            ->setBody(json_encode(['token' => csrf_hash()]));
    }

    public function pilih_toko()
    {
        $model = new \App\Models\M_pemilik_website();
        $semuaToko = $model->get_website_aktif();

        $toko_list = [];
        foreach ($semuaToko as $toko) {
            $logo_path = 'logowebsite/' . ($toko['logo_website'] ?? '');
            $default_logo = 'fotodefault/logofaaro.png';
            $logo = file_exists(FCPATH . $logo_path) && !empty($toko['logo_website']) ? $logo_path : $default_logo;

            $bgd_path = 'bgdweb/' . ($toko['bgd_web'] ?? '');
            $default_bgd = 'fotodefault/bgdefault.jpeg';
            $bgd = file_exists(FCPATH . $bgd_path) && !empty($toko['bgd_web']) ? $bgd_path : $default_bgd;

            $toko_list[] = [
                'id_website'  => $toko['id_website'],
                'sesi_user'   => $toko['sesi_user'],
                'nama_toko'   => $toko['nama_toko'],
                'logo'        => $logo,
                'bgd'         => $bgd,
                'alamat'      => $toko['alamat_pusat'] ?? '',
            ];
        }

        $data = [
            'title'     => 'Pilih Toko',
            'title2'    => 'Pilih Toko',
            'toko_list' => $toko_list,
        ];
        return view('pelanggan/auth_login/v_pilih_toko', $data);
    }

    public function set_toko()
    {
        $sesiUserToko = $this->request->getPost('sesi_user_toko');

        if (empty($sesiUserToko)) {
            session()->setFlashdata('pesan_warning', 'Pilih toko terlebih dahulu!');
            return redirect()->to(base_url('auth/pilih_toko'));
        }

        session()->set('toko_sesi_user', $sesiUserToko);

        return redirect()->to(base_url('home_toko/index'));
    }

    public function pilih_toko_user()
    {
        $model = new \App\Models\M_pemilik_website();
        $semuaToko = $model->get_website_aktif();

        $toko_list = [];
        foreach ($semuaToko as $toko) {
            $logo_path = 'logowebsite/' . ($toko['logo_website'] ?? '');
            $default_logo = 'fotodefault/logofaaro.png';
            $logo = file_exists(FCPATH . $logo_path) && !empty($toko['logo_website']) ? $logo_path : $default_logo;

            $toko_list[] = [
                'id_website'  => $toko['id_website'],
                'sesi_user'   => $toko['sesi_user'],
                'nama_toko'   => $toko['nama_toko'],
                'logo'        => $logo,
                'alamat'      => $toko['alamat_pusat'] ?? '',
            ];
        }

        $data = [
            'title'     => 'Pilih Toko',
            'title2'    => 'Pilih Toko',
            'toko_list' => $toko_list,
        ];
        return view('auth_login/v_pilih_toko_user', $data);
    }

    public function set_toko_user()
    {
        $sesiUserToko = $this->request->getPost('sesi_user_toko');

        if (empty($sesiUserToko)) {
            session()->setFlashdata('pesan_warning', 'Pilih toko terlebih dahulu!');
            return redirect()->to(base_url('auth/pilih_toko_user'));
        }

        session()->set('toko_sesi_user', $sesiUserToko);

        return redirect()->to(base_url('auth/login_user'));
    }

    private function exchangeCodeForToken($code, $clientId, $clientSecret, $redirectUri)
    {
        $url = 'https://oauth2.googleapis.com/token';

        $postData = http_build_query([
            'code'          => $code,
            'client_id'     => $clientId,
            'client_secret' => $clientSecret,
            'redirect_uri'  => $redirectUri,
            'grant_type'    => 'authorization_code',
        ]);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }

    private function getGoogleUserInfo($accessToken)
    {
        $url = 'https://www.googleapis.com/oauth2/v2/userinfo';

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $accessToken]);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }
}
