<?php

namespace App\Models;

use CodeIgniter\Model;

class M_home_pemilik extends Model
{

    public function tot_jenis_by_sesi()
    {
        $sesi_user = session()->get('sesi_user');
        return $this->db->table('tbl_jenis_produk')
        -> where('tbl_jenis_produk.sesi_user', $sesi_user)
            ->get()
            ->getResultArray();
    }

    public function tot_varian_by_sesi()
    {
        $sesi_user = session()->get('sesi_user');
        return $this->db->table('tbl_varian_produk')
        ->where('tbl_varian_produk.sesi_user', $sesi_user)
            ->get()
            ->getResultArray();
    }

    public function tot_produk_by_sesi()
    {
        $sesi_user = session()->get('sesi_user');
        return $this->db->table('tbl_data_produk')
        ->where('tbl_data_produk.sesi_user', $sesi_user)
            ->get()
            ->getResultArray();
    }

    public function tot_stok_by_sesi()
    {
        $sesi_user = session()->get('sesi_user');
        return $this->db->table('tbl_stok_produk')
        ->where('tbl_stok_produk.sesi_user', $sesi_user)
            ->get()
            ->getResultArray();
    }
    
    public function tot_laporan_by_sesi()
    {
        $sesi_user = session()->get('sesi_user');
        return $this->db->table('tbl_data_pembelian')
            ->where('tbl_data_pembelian.sesi_user', $sesi_user)
            ->get()
            ->getResultArray();
    }

}
