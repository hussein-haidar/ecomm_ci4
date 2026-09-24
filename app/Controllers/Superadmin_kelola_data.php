<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\M_superadmin;

class Superadmin_kelola_data extends BaseController
{
    protected $M_superadmin;

    public function __construct()
    {
        $this->M_superadmin = new M_superadmin();
    }

    public function toko()
    {
        $data = [
            'title' => 'Data Toko',
            'title2' => 'Data Toko',
            'semua_toko' => $this->M_superadmin->get_semua_toko(),
            'isi' => 'superadmin/data_website/v_toko',
        ];

        return view('layout/v_template', $data);
    }

    public function detail($id_website)
    {
        $data = [
            'title' => 'Detail Toko',
            'title2' => 'Data Toko',
            'toko' => $this->M_superadmin->detail_toko($id_website),
            'isi' => 'superadmin/data_website/v_detail',
        ];

        return view('layout/v_template', $data);
    }

    public function aktifkan($id_website)
    {
        $this->M_superadmin->update_checked($id_website, 2);
        session()->setFlashdata('pesan', 'Toko Berhasil Diaktifkan !!!');
        return redirect()->to(base_url('superadmin_kelola_data/toko'));
    }

    public function nonaktifkan($id_website)
    {
        $this->M_superadmin->update_checked($id_website, 1);
        session()->setFlashdata('pesan', 'Toko Berhasil Dinonaktifkan !!!');
        return redirect()->to(base_url('superadmin_kelola_data/toko'));
    }
}