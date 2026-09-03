<?php

namespace App\Models;

use CodeIgniter\Model;

class M_pemilik_varian_produk extends Model
{
    protected $table = 'tbl_varian_produk'; // Nama tabel
    protected $primaryKey = 'id_varian'; // Primary key
    protected $allowedFields = ['sesi_user', 'varian_produk', 'deleted_at'];

    public function get_varian()
    {
        $sesi_user = session()->get('sesi_user');
        return $this->db->table('tbl_varian_produk')
            ->where('deleted_at', 0) // Hanya ambil data yang belum dihapus
            ->where('tbl_varian_produk.sesi_user', $sesi_user)
        ->get()->getResultArray();
    }

    public function get_varian_dihapus()
    {
        return $this->db->table('tbl_varian_produk')
        ->where('deleted_at', 1) // Hanya ambil data yang sudah dihapus
        ->get()->getResultArray();
    }

    public function get_user_by_id($sesi_user)
    {
        return $this->db->table('tbl_data_user')
        ->select('sesi_user')
        ->where('sesi_user', $sesi_user)
            ->get()
            ->getRowArray();
    }

    public function detailVarian($id_varian)
    {
        return $this->db->table('tbl_varian_produk')
            ->where('id_varian', $id_varian)
            ->get()->getRowArray();
    }

      public function add($data)
    {
        $this->db->table('tbl_varian_produk')
        ->insert($data);
    }

    public function edit($data)
    {
        $this->db->table('tbl_varian_produk')
            ->where('id_varian', $data['id_varian'])
            ->update($data);
    }

    public function delete_data($id_varian)
    {
        return $this->db->table('tbl_varian_produk')
            ->where('id_varian', $id_varian)
            ->update(['deleted_at' => 1]);
    }

    public function restore($id_varian)
    {
        return $this->db->table('tbl_varian_produk')
            ->where('id_varian', $id_varian)
            ->update(['deleted_at' => 0]);
    }
    public function delete_hard($data)
    {
        return $this->db->table('tbl_varian_produk')
        ->where('id_varian', $data['id_varian'])
        ->delete($data);
    }
}
?>