<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\M_admin_stok;
use App\Models\M_admin_laporan_stok_bulanan;

class Admin_laporan_bulanan extends BaseController
{
    protected $M_admin_stok;
    protected $M_admin_laporan_stok_bulanan;

    public function __construct()
    {
        $this->M_admin_stok = new M_admin_stok();
        $this->M_admin_laporan_stok_bulanan = new M_admin_laporan_stok_bulanan();
    }

    public function laporan_stok()
    {
        $data = [
            'title' => 'Daftar Laporan Stok Produk Bulanan',
            'title2' => 'Data Laporan Stok Produk Bulanan',
            'data_laporan_admin' => $this->M_admin_stok->get_stok(),
            'start_month' => null,
            'end_month' => null,
            'isi' => 'admin/laporan_stok_admin_bulanan/v_laporan_admin',
        ];
        return view('layout/v_template', $data);
    }

    public function filter_stok_by_month()
    {
        if ($this->request->getMethod() === 'post') {
            $start_month = $this->request->getPost('start_month');
            $end_month = $this->request->getPost('end_month');

            return redirect()->to(base_url("admin_laporan_bulanan/filter_stok_by_month?start_month=$start_month&end_month=$end_month"));
        }

        $start_month = $this->request->getGet('start_month') ?? null;
        $end_month = $this->request->getGet('end_month') ?? null;

        if ($start_month && $end_month) {
            $data_laporan_admin = $this->M_admin_laporan_stok_bulanan->get_laporan_stok_by_month_range($start_month, $end_month);
        } else {
            $data_laporan_admin = $this->M_admin_stok->get_stok();
        }

        $data = [
            'title' => 'Daftar Laporan Stok Produk Bulanan',
            'title2' => 'Data Laporan Stok Produk Bulanan',
            'data_laporan_admin' => $data_laporan_admin,
            'start_month' => $start_month,
            'end_month' => $end_month,
            'isi' => 'admin/laporan_stok_admin_bulanan/v_laporan_admin',
        ];

        return view('layout/v_template', $data);
    }

    public function cetak_laporan_stok()
    {
        $start_month = $this->request->getGet('start_month');
        $end_month = $this->request->getGet('end_month');

        if ($start_month && $end_month) {
            $data_laporan_pemilik = $this->M_admin_laporan_stok_bulanan->get_laporan_stok_by_month_range($start_month, $end_month);
        } else {
            $data_laporan_pemilik = $this->M_admin_stok->get_stok();
        }

        $data = [
            'title3' => 'Cetak Laporan Stok Produk Bulanan',
            'data_laporan_admin' => $data_laporan_pemilik,
            'start_month' => $start_month,
            'end_month' => $end_month,
        ];

        return view('admin/laporan_stok_admin_bulanan/v_cetak_laporan_admin', $data);
    }

    public function reset_filter_stok()
    {
        // Mengarahkan ulang ke halaman utama laporan
        return redirect()->to(base_url('admin_laporan_bulanan/laporan_stok'));
    }

}
