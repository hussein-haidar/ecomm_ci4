<?php

namespace App\Models;

use CodeIgniter\Model;

class M_pelanggan_beli extends Model
{
    protected $table   = 'tbl_data_pembelian';
    protected $primaryKey = 'id_beli';
    protected $allowedFields = [
        'kode_beli',
        'sesi_user',
        'nama_pelanggan',
        'nama_produk',
        'ukuran_produk',
        'jumlah_produk',
        'satuan_produk',
        'total_harga',
        'total_berat',
        'status_bayar',
        'waktu_pembelian',
    ];

    public function get_beli()
    {
        $sesi_user = session()->get('sesi_user');
        return $this->db->table('tbl_data_pembelian')
            ->select('tbl_data_pembelian.*, tbl_data_produk.*')
            ->join('tbl_data_produk', 'tbl_data_produk.nama_produk = tbl_data_pembelian.nama_produk', 'left')
            ->where('tbl_data_pembelian.sesi_user', $sesi_user)
            ->where('tbl_data_pembelian.status_bayar', 'Dibayar')
            ->orderBy('id_beli', 'DESC')
            ->get()->getResultArray();
    }

    public function add($data_pembelian)
    {
        $this->db->table('tbl_data_pembelian')
            ->insert($data_pembelian);
    }

    public function edit($data)
    {
        return $this->db->table('tbl_data_pembelian')
            ->where('id_beli', $data['id_beli'])
            ->update($data);
    }

    public function get_bank($sesi_user = null)
    {
        if ($sesi_user === null) {
            $sesi_user = session()->get('toko_sesi_user') ?? session()->get('sesi_user');
        }
        return $this->db->table('tbl_data_bank')
            ->select('tbl_data_bank.*, tbl_website.nama_toko')
            ->join('tbl_website', 'tbl_website.sesi_user = tbl_data_bank.sesi_user', 'left')
            ->where('tbl_data_bank.deleted_at', 0)
            ->where('tbl_data_bank.sesi_user', $sesi_user)
            ->get()->getResultArray();
    }
    
    public function get_kurir($sesi_user = null)
    {
        if ($sesi_user === null) {
            $sesi_user = session()->get('toko_sesi_user') ?? session()->get('sesi_user');
        }
        return $this->db->table('tbl_data_kurir')
            ->where('deleted_at', 0)
            ->where('tbl_data_kurir.sesi_user', $sesi_user)
            ->get()->getResultArray();
    }
}
