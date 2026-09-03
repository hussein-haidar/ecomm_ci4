<?php

namespace App\Models;

use CodeIgniter\Model;

class M_home_admin extends Model
{
    // Fungsi total per sesi user
    public function tot_jenis_by_sesi()
    {
        $sesi_user = session()->get('sesi_user');
        return $this->db->table('tbl_jenis_produk')
            ->where('sesi_user', $sesi_user)
            ->get()
            ->getResultArray();
    }

    public function tot_varian_by_sesi()
    {
        $sesi_user = session()->get('sesi_user');
        return $this->db->table('tbl_varian_produk')
            ->where('sesi_user', $sesi_user)
            ->get()
            ->getResultArray();
    }

    public function tot_produk_by_sesi()
    {
        $sesi_user = session()->get('sesi_user');
        return $this->db->table('tbl_data_produk')
            ->where('sesi_user', $sesi_user)
            ->get()
            ->getResultArray();
    }

    public function tot_stok_by_sesi()
    {
        $sesi_user = session()->get('sesi_user');
        return $this->db->table('tbl_stok_produk')
            ->where('sesi_user', $sesi_user)
            ->get()
            ->getResultArray();
    }

    public function tot_jumlah_stok_by_sesi()
    {
        $sesi_user = session()->get('sesi_user');
        return $this->db->table('tbl_stok_produk')
            ->selectSum('jumlah_stok_produk')
            ->where('sesi_user', $sesi_user)
            ->get()
            ->getRow()
            ->jumlah_stok_produk ?? 0;
    }

    public function tot_harga_stok_by_sesi()
    {
        $sesi_user = session()->get('sesi_user');
        return $this->db->table('tbl_stok_produk')
            ->select('SUM(jumlah_stok_produk * harga_produk) AS total_harga')
            ->where('sesi_user', $sesi_user)
            ->get()
            ->getRow()
            ->total_harga ?? 0;
    }

    public function tot_penjualan_by_sesi()
    {
        $sesi_user = session()->get('sesi_user');
        return $this->db->table('tbl_data_pembelian')
            ->selectSum('jumlah_produk')
            ->where('sesi_user', $sesi_user)
            ->where('status_bayar', 'Dibayar')
            ->get()
            ->getRow()
            ->jumlah_produk ?? 0;
    }

    public function tot_nilai_penjualan_by_sesi()
    {
        $sesi_user = session()->get('sesi_user');
        return $this->db->table('tbl_data_pembelian')
            ->selectSum('total_harga')
            ->where('sesi_user', $sesi_user)
            ->where('status_bayar', 'Dibayar')
            ->get()
            ->getRow()
            ->total_harga ?? 0;
    }

    public function tot_pembayaran_selesai()
    {
        $sesi_user = session()->get('sesi_user');
        return $this->db->table('tbl_data_pembayaran')
        ->where('tbl_data_pembayaran.sesi_user', $sesi_user)
            ->where('tbl_data_pembayaran.status_bayar', 'Dibayar')
            ->get()
            ->getResultArray();
    }

    public function tot_pembayaran_batal()
    {
        $sesi_user = session()->get('sesi_user');
        return $this->db->table('tbl_data_pembayaran')
            ->where('tbl_data_pembayaran.sesi_user', $sesi_user)
            ->where('tbl_data_pembayaran.status_bayar', 'Dibatalkan')
            ->get()
            ->getResultArray();
    }

    // Fungsi untuk grafik bulanan
    public function getPenjualanPerBulan($tahun, $bulan = null)
    {
        $sesi_user = session()->get('sesi_user');

        $builder = $this->db->table('tbl_data_pembelian')
            ->select('MONTH(waktu_pembelian) as bulan, nama_produk, SUM(jumlah_produk) as total')
            ->where('YEAR(waktu_pembelian)', $tahun)
            ->where('sesi_user', $sesi_user)
          ->where('tbl_data_pembelian.status_bayar', 'Dibayar');

        if ($bulan) {
            $builder->where('MONTH(waktu_pembelian)', $bulan);
        }

        return $builder->groupBy('MONTH(waktu_pembelian), nama_produk')
            ->orderBy('bulan')
            ->get()
            ->getResultArray();
    }

    public function getNilaiPenjualanPerBulan($tahun, $bulan = null)
    {
        $sesi_user = session()->get('sesi_user');

        $builder = $this->db->table('tbl_data_pembelian')
            ->select('MONTH(waktu_pembelian) as bulan, nama_produk, SUM(total_harga) as total_nilai')
            ->where('YEAR(waktu_pembelian)', $tahun)
            ->where('sesi_user', $sesi_user)
            ->where('tbl_data_pembelian.status_bayar', 'Dibayar');
        if ($bulan) {
            $builder->where('MONTH(waktu_pembelian)', $bulan);
        }

        return $builder->groupBy('MONTH(waktu_pembelian), nama_produk')
            ->orderBy('bulan')
            ->get()
            ->getResultArray();
    }

    // Produk Terlaris (jumlah terbanyak)
    public function getProdukTerlaris($tahun)
    {
        $sesi_user = session()->get('sesi_user');
        return $this->db->table('tbl_data_pembelian')
            ->select('tbl_data_produk.nama_produk, SUM(tbl_data_pembelian.jumlah_produk) as total')
            ->join('tbl_data_produk', 'tbl_data_produk.nama_produk = tbl_data_pembelian.nama_produk')
            ->where('YEAR(tbl_data_pembelian.waktu_pembelian)', $tahun)
            ->where('tbl_data_pembelian.sesi_user', $sesi_user)
            ->where('tbl_data_pembelian.status_bayar', 'Dibayar')
            ->groupBy('tbl_data_produk.nama_produk')
            ->orderBy('total', 'DESC')
            ->limit(1)
            ->get()
            ->getRowArray();
    }

    // Produk paling sedikit terjual
    public function getProdukTersedikit($tahun)
    {
        $sesi_user = session()->get('sesi_user');
        return $this->db->table('tbl_data_pembelian')
            ->select('tbl_data_produk.nama_produk, SUM(tbl_data_pembelian.jumlah_produk) as total')
            ->join('tbl_data_produk', 'tbl_data_produk.nama_produk = tbl_data_pembelian.nama_produk')
            ->where('YEAR(tbl_data_pembelian.waktu_pembelian)', $tahun)
            ->where('tbl_data_pembelian.sesi_user', $sesi_user)
            ->where('tbl_data_pembelian.status_bayar', 'Dibayar')
            ->groupBy('tbl_data_produk.nama_produk')
            ->orderBy('total', 'ASC')
            ->limit(1)
            ->get()
            ->getRowArray();
    }

    // Produk dengan nilai penjualan tertinggi
    public function getProdukNilaiTertinggi($tahun)
    {
        $sesi_user = session()->get('sesi_user');
        return $this->db->table('tbl_data_pembelian')
            ->select('tbl_data_produk.nama_produk, SUM(tbl_data_pembelian.total_harga) as total')
            ->join('tbl_data_produk', 'tbl_data_produk.nama_produk = tbl_data_pembelian.nama_produk')
            ->where('YEAR(tbl_data_pembelian.waktu_pembelian)', $tahun)
            ->where('tbl_data_pembelian.sesi_user', $sesi_user)
            ->where('tbl_data_pembelian.status_bayar', 'Dibayar')
            ->groupBy('tbl_data_produk.nama_produk')
            ->orderBy('total', 'DESC')
            ->limit(1)
            ->get()
            ->getRowArray();
    }

    // Produk dengan nilai penjualan terendah
    public function getProdukNilaiTerendah($tahun)
    {
        $sesi_user = session()->get('sesi_user');
        return $this->db->table('tbl_data_pembelian')
            ->select('tbl_data_produk.nama_produk, SUM(tbl_data_pembelian.total_harga) as total')
            ->join('tbl_data_produk', 'tbl_data_produk.nama_produk = tbl_data_pembelian.nama_produk')
            ->where('YEAR(tbl_data_pembelian.waktu_pembelian)', $tahun)
            ->where('tbl_data_pembelian.sesi_user', $sesi_user)
            ->where('tbl_data_pembelian.status_bayar', 'Dibayar')
            ->groupBy('tbl_data_produk.nama_produk')
            ->orderBy('total', 'ASC')
            ->limit(1)
            ->get()
            ->getRowArray();
    }
}
