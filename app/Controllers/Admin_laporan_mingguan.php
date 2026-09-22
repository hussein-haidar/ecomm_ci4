<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\M_admin_stok;
use App\Models\M_admin_laporan_stok_mingguan;

class Admin_laporan_mingguan extends BaseController
{
    protected $M_admin_stok;
    protected $M_admin_laporan_stok_mingguan;

    public function __construct()
    {
        $this->M_admin_stok = new M_admin_stok();
        $this->M_admin_laporan_stok_mingguan = new M_admin_laporan_stok_mingguan();
    }

    public function laporan_stok()
    {
        $start_date = $this->request->getGet('start_date');
        $end_date = $this->request->getGet('end_date');

        if ($start_date && $end_date) {
            $data_laporan_admin = $this->M_admin_laporan_stok_mingguan->get_laporan_stok_by_date_range($start_date, $end_date);
        } else {
            $data_laporan_admin = $this->M_admin_stok->get_stok();
            $start_date = '';
            $end_date = '';
        }

        $data = [
            'title' => 'Daftar Laporan Stok Produk Mingguan',
            'title2' => 'Data Laporan Stok Produk Mingguan',
            'data_laporan_admin' => $data_laporan_admin,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'isi' => 'admin/laporan_stok_admin_mingguan/v_laporan_admin',
        ];
        return view('layout/v_template', $data);
    }

    public function filter_stok_by_date()
    {
        if ($this->request->getMethod() === 'post') {
            $start_date = $this->request->getPost('start_date');
            $end_date = $this->request->getPost('end_date');

            return redirect()->to(base_url('admin_laporan_mingguan/laporan_stok?start_date=' . $start_date . '&end_date=' . $end_date));
        }

        return redirect()->to(base_url('admin_laporan_mingguan/laporan_stok'));
    }

    public function cetak_laporan_stok()
    {
        $start_date = $this->request->getGet('start_date');
        $end_date = $this->request->getGet('end_date');

        $data = [
            'title3' => 'Cetak Laporan Stok Produk Mingguan',
            'data_laporan_admin' => $this->M_admin_laporan_stok_mingguan->get_laporan_stok_by_date_range($start_date, $end_date),
            'start_date' => $start_date,
            'end_date' => $end_date,
        ];

        // Menambahkan pengalihan setelah cetak
        return view('admin/laporan_stok_admin_mingguan/v_cetak_laporan_admin', $data);
    }

    public function reset_filter_stok()
    {
        // Mengarahkan ulang ke halaman utama laporan
        return redirect()->to(base_url('admin_laporan_mingguan/laporan_stok'));
    }

    public function export_excel()
    {
        $start_date = $this->request->getGet('start_date');
        $end_date = $this->request->getGet('end_date');

        if ($start_date && $end_date) {
            $data_laporan = $this->M_admin_laporan_stok_mingguan->get_laporan_stok_by_date_range($start_date, $end_date);
        } else {
            $data_laporan = $this->M_admin_stok->get_stok();
        }

        $content = "\xEF\xBB\xBF"; // BOM UTF-8 agar karakter Indonesia terbaca di Excel
        $content .= '<table border="1"><thead><tr>';
        $content .= '<th>No</th>';
        $content .= '<th>Kode Stok Produk</th>';
        $content .= '<th>Tanggal Masuk</th>';
        $content .= '<th>Nama Produk</th>';
        $content .= '<th>Jumlah Stok Produk</th>';
        $content .= '<th>Harga Total Produk</th>';
        $content .= '</tr></thead><tbody>';

        $no = 1;
        foreach ($data_laporan as $value) {
            $content .= '<tr>';
            $content .= '<td>' . $no++ . '</td>';
            $content .= '<td>' . $value['kode_stok'] . '</td>';
            $content .= '<td>' . date('d-m-Y', strtotime($value['tanggal_masuk_produk'])) . '</td>';
            $content .= '<td>' . $value['nama_produk'] . '</td>';
            $content .= '<td>' . $value['jumlah_stok_produk'] . '</td>';
            $content .= '<td>' . number_format($value['total_harga'], 0, ',', '.') . '</td>';
            $content .= '</tr>';
        }

        $content .= '</tbody></table>';

        $filename = 'laporan_stok_mingguan_' . date('Y-m-d') . '.xls';

        return $this->response
            ->setHeader('Content-Type', 'application/vnd.ms-excel; charset=utf-8')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setHeader('Pragma', 'no-cache')
            ->setHeader('Expires', '0')
            ->setBody($content);
    }
    
}
