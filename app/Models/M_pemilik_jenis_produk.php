<?php

namespace App\Models;

use CodeIgniter\Model;

class M_pemilik_jenis_produk extends Model
{
    protected $table = 'tbl_jenis_produk'; // Nama tabel
    protected $primaryKey = 'id_jenis'; // Primary key
    protected $allowedFields = ['sesi_user','jenis_produk', 'deleted_at'];

    public function get_jenis()
    {
        $sesi_user = session()->get('sesi_user');
        return $this->db->table('tbl_jenis_produk')
            ->where('deleted_at', 0) // Hanya ambil data yang belum dihapus
            ->where('tbl_jenis_produk.sesi_user', $sesi_user)
            ->get()->getResultArray();
    }

    public function get_jenis_dihapus()
    {
        return $this->db->table('tbl_jenis_produk')
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

    public function detailJenis($id_jenis)
    {
        return $this->db->table('tbl_jenis_produk')
            ->where('id_jenis', $id_jenis)
            ->get()->getRowArray();
    }

       public function add($data)
    {
        $this->db->table('tbl_jenis_produk')
        ->insert($data);
    }

    public function edit($data)
    {
        return $this->db->table('tbl_jenis_produk')
            ->where('id_jenis', $data['id_jenis'])
            ->update($data);
    }

    public function delete_data($id_jenis)
    {
        return $this->db->table('tbl_jenis_produk')
            ->where('id_jenis', $id_jenis)
            ->update(['deleted_at' => 0]); // Kembalikan produk yang dihapus
    }

    public function restore($id_jenis)
    {
        return $this->db->table('tbl_jenis_produk')
            ->where('id_jenis', $id_jenis)
            ->update(['deleted_at' => null]);
    }
    public function delete_hard($data)
    {
        return $this->db->table('tbl_jenis_produk')
        ->where('id_jenis', $data['id_jenis'])
        ->delete($data);
    }
}
?>
