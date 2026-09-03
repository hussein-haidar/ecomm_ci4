<?php

namespace App\Models;

use CodeIgniter\Model;

class M_admin_laporan_stok_mingguan extends Model
{
    protected $table   = 'tbl_stok_produk';
    protected $primaryKey = 'id_stok';
    protected $allowedFields = [
        'kode_stok',
        'jumlah_stok_produk',
        'tanggal_masuk_produk',
        'id_stok',
        'nama_produk'
    ];

    public function get_laporan_stok_by_date_range($start_date, $end_date)
    {
        $sesi_user = session()->get('sesi_user');
        return $this->db->table('tbl_stok_produk')
            ->select('
              tbl_stok_produk.nama_produk, 
              tbl_stok_produk.foto_produk, 
              tbl_stok_produk.ukuran_produk,
              tbl_stok_produk.satuan_produk,
              tbl_stok_produk.jenis_produk,
              tbl_data_produk.harga_produk,
              tbl_data_produk.berat_produk,
              tbl_data_produk.satuan_berat,
              SUM(tbl_stok_produk.jumlah_stok_produk) as total_stok,
              SUM(tbl_stok_produk.jumlah_stok_produk * tbl_data_produk.harga_produk) AS total_harga
            ')
            ->join('tbl_data_produk', 'tbl_data_produk.nama_produk = tbl_stok_produk.nama_produk', 'left')
            ->where('tanggal_masuk_produk >=', $start_date)
            ->where('tanggal_masuk_produk <=', $end_date)
            ->where('tbl_stok_produk.sesi_user', $sesi_user)
            ->groupBy(['tbl_stok_produk.nama_produk'])
            ->orderBy('tbl_stok_produk.nama_produk', 'ASC')
            ->get()->getResultArray();
    }

    public function all_produk()
    {
        return $this->db->table('tbl_data_produk')
            ->get()->getResultArray();
    }
}

?>
