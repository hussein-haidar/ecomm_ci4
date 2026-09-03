<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\M_home_pemilik;
use App\Models\M_profil_user;
use App\Models\M_admin_stok;

class Home_pemilik extends BaseController
{
    protected $M_home_pemilik;
    protected $M_profil_user;
    protected $M_admin_stok;

    public function __construct()
    {
        $this->M_home_pemilik = new M_home_pemilik();
        $this->M_admin_stok = new M_admin_stok();
        $this->M_profil_user = new M_profil_user();
    }

    public function index()
    {
        // Ambil data stok
        $dataStok = $this->M_admin_stok->get_stok();

        // Data untuk dikirim ke view
        $data = [
            'title' => "Dashboard " . (session()->get('nama_title')),
            'title2' => 'Index',
            'tot_jenis' => $this->M_home_pemilik->tot_jenis_by_sesi(),
            'tot_varian' => $this->M_home_pemilik->tot_varian_by_sesi(),
            'tot_produk' => $this->M_home_pemilik->tot_produk_by_sesi(),
            'tot_stok' => $this->M_home_pemilik->tot_stok_by_sesi(),
            'data_stok' => $dataStok,
            'isi' => 'pemilik/v_home_pemilik',
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
            'isi' => 'pemilik/profil/v_profil',
        ];

        return view('layout/v_template', $data);
    }

}
