<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\M_superadmin;

class Home_superadmin extends BaseController
{
    protected $M_superadmin;

    public function __construct()
    {
        $this->M_superadmin = new M_superadmin();
    }

    public function index()
    {
        $data = [
            'title' => 'Dashboard ' . (session()->get('nama_title') ?: 'Superadmin'),
            'title2' => 'Dashboard',
            'tot_toko' => $this->M_superadmin->tot_toko(),
            'tot_toko_aktif' => $this->M_superadmin->tot_toko_aktif(),
            'tot_toko_nonaktif' => $this->M_superadmin->tot_toko_nonaktif(),
            'tot_toko_menunggu' => $this->M_superadmin->tot_toko_menunggu(),
            'tot_pemilik' => $this->M_superadmin->tot_pemilik(),
            'tot_admin' => $this->M_superadmin->tot_admin(),
            'tot_pelanggan' => $this->M_superadmin->tot_pelanggan(),
            'tot_produk' => $this->M_superadmin->tot_produk(),
            'tot_stok' => $this->M_superadmin->tot_stok(),
            'tot_penjualan' => $this->M_superadmin->tot_penjualan(),
            'tot_nilai_penjualan' => $this->M_superadmin->tot_nilai_penjualan(),
            'isi' => 'superadmin/v_home_superadmin',
        ];

        return view('layout/v_template', $data);
    }

    public function profil()
    {
        $data = [
            'title' => 'Data Profil',
            'title2' => 'Profil Saya',
            'isi' => 'superadmin/profil/v_profil',
        ];

        return view('layout/v_template', $data);
    }
}