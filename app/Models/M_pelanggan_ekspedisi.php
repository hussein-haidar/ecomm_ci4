<?php

namespace App\Models;

use CodeIgniter\Model;

class M_pelanggan_ekspedisi extends Model
{
    protected $table   = 'tbl_data_ekspedisi';
    protected $primaryKey = 'id_ekspedisi';
    protected $allowedFields = [
        'nama_pelanggan',
        'alamat',
     'jenis_kurir',
        'ongkir',
        'estimasi_waktu',
    ];

    public function get_ekspedisi()
    {
        $nama_pelanggan = session()->get('nama_pelanggan');
        return $this->db->table('tbl_data_ekspedisi')
            ->select('tbl_data_ekspedisi.*, tbl_data_pembayaran.*') // Hanya memilih kolom dari tbl_data_pembelian dan tbl_data_produk  
            ->join('tbl_data_pembayaran', 'tbl_data_pembayaran.nama_pelanggan = tbl_data_ekspedisi.nama_pelanggan', 'left') // Menyertakan join ke tbl_data_produk  
            ->where('tbl_data_ekspedisi.nama_pelanggan', $nama_pelanggan)
            ->orderBy('id_ekspedisi', 'DESC') // Mengurutkan berdasarkan id_transaksi  
            ->get()->getResultArray();
    }

    public function add($data_ekspedisi)
    {
        $this->db->table('tbl_data_ekspedisi')
            ->insert($data_ekspedisi);
    }

    public function detailEkspedisi($id_ekspedisi)
    {
        return $this->db->table('tbl_data_ekspedisi')
            ->where('id_ekspedisi', $id_ekspedisi)
            ->get()
            ->getRowArray(); // Pastikan ini mengembalikan array  
    }

    public function get_website()
    {
        $sesi_user = session()->get('sesi_user');
        $level = session()->get('level');
        return $this->db->table('tbl_website')
            ->select('tbl_website.*, tbl_website.sesi_user, tbl_data_user.*')
            ->join('tbl_data_user', 'tbl_data_user.sesi_user = tbl_website.sesi_user', 'left')
            ->where('tbl_website.sesi_user', $sesi_user)
            ->where('tbl_data_user.level', $level)
            ->orderBy('id_website', 'DESC')
            ->get()->getResultArray();
    }
    
}
