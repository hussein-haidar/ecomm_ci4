<?php

namespace App\Models;

use CodeIgniter\Model;

class M_superadmin extends Model
{
    public function tot_toko()
    {
        return $this->db->table('tbl_website')
            ->selectCount('id_website', 'total')
            ->get()->getRow()->total ?? 0;
    }

    public function tot_toko_aktif()
    {
        return $this->db->table('tbl_website')
            ->selectCount('id_website', 'total')
            ->where('is_checked', 2)
            ->get()->getRow()->total ?? 0;
    }

    public function tot_toko_nonaktif()
    {
        return $this->db->table('tbl_website')
            ->selectCount('id_website', 'total')
            ->where('is_checked', 1)
            ->get()->getRow()->total ?? 0;
    }

    public function tot_toko_menunggu()
    {
        return $this->db->table('tbl_website')
            ->selectCount('id_website', 'total')
            ->where('is_checked', 0)
            ->get()->getRow()->total ?? 0;
    }

    public function tot_pemilik()
    {
        return $this->db->table('tbl_data_user')
            ->selectCount('id_user', 'total')
            ->where('level', 1)
            ->get()->getRow()->total ?? 0;
    }

    public function tot_admin()
    {
        return $this->db->table('tbl_data_user')
            ->selectCount('id_user', 'total')
            ->where('level', 2)
            ->get()->getRow()->total ?? 0;
    }

    public function tot_pelanggan()
    {
        return $this->db->table('tbl_data_pelanggan')
            ->selectCount('id_pelanggan', 'total')
            ->get()->getRow()->total ?? 0;
    }

    public function tot_produk()
    {
        return $this->db->table('tbl_data_produk')
            ->selectCount('id_produk', 'total')
            ->get()->getRow()->total ?? 0;
    }

    public function tot_stok()
    {
        return $this->db->table('tbl_stok_produk')
            ->selectCount('id_stok', 'total')
            ->get()->getRow()->total ?? 0;
    }

    public function tot_penjualan()
    {
        return $this->db->table('tbl_data_pembelian')
            ->selectSum('jumlah_produk')
            ->where('status_bayar', 'Dibayar')
            ->get()->getRow()->jumlah_produk ?? 0;
    }

    public function tot_nilai_penjualan()
    {
        return $this->db->table('tbl_data_pembelian')
            ->selectSum('total_harga')
            ->where('status_bayar', 'Dibayar')
            ->get()->getRow()->total_harga ?? 0;
    }

    public function get_semua_toko()
    {
        return $this->db->table('tbl_website')
            ->select('
                tbl_website.*,
                tbl_data_user.nama_lengkap,
                tbl_data_user.notelpon_user,
                tbl_data_user.email_user
            ')
            ->join('tbl_data_user', 'tbl_data_user.sesi_user = tbl_website.sesi_user AND tbl_data_user.level = 1', 'left')
            ->orderBy('tbl_website.id_website', 'DESC')
            ->get()->getResultArray();
    }

    public function detail_toko($id_website)
    {
        return $this->db->table('tbl_website')
            ->select('
                tbl_website.*,
                tbl_data_user.nama_lengkap,
                tbl_data_user.username,
                tbl_data_user.notelpon_user,
                tbl_data_user.email_user
            ')
            ->join('tbl_data_user', 'tbl_data_user.sesi_user = tbl_website.sesi_user AND tbl_data_user.level = 1', 'left')
            ->where('tbl_website.id_website', $id_website)
            ->get()->getRowArray();
    }

    public function update_checked($id_website, $is_checked)
    {
        return $this->db->table('tbl_website')
            ->where('id_website', $id_website)
            ->update([
                'is_checked' => $is_checked,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }
}