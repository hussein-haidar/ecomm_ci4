<?php

namespace App\Models;

use CodeIgniter\Model;

class M_pemilik_satuan_produk extends Model
{
    protected $table = 'tbl_satuan_produk'; // Nama tabel
    protected $primaryKey = 'id_satuan'; // Primary key
    protected $allowedFields = ['sesi_user', 'satuan_produk', 'deleted_at'];

    public function get_satuan()
    {
        $sesi_user = session()->get('sesi_user');
        return $this->db->table('tbl_satuan_produk')
            ->where('deleted_at', 0) // Hanya ambil data yang belum dihapus
            ->where('tbl_satuan_produk.sesi_user', $sesi_user)
            ->get()->getResultArray();
    }

    public function get_satuan_dihapus()
    {
        return $this->db->table('tbl_satuan_produk')
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

    public function detailSatuan($id_satuan)
    {
        return $this->db->table('tbl_satuan_produk')
            ->where('id_satuan', $id_satuan)
            ->get()->getRowArray();
    }

    public function add($data)
    {
        $this->db->table('tbl_satuan_produk')
            ->insert($data);
    }

    public function edit($data)
    {
        return $this->db->table('tbl_satuan_produk')
            ->where('id_satuan', $data['id_satuan'])
            ->update($data);
    }

    public function delete_data($id_satuan)
    {
        return $this->db->table('tbl_satuan_produk')
            ->where('id_satuan', $id_satuan)
            ->update(['deleted_at' => 1]);
    }

    public function restore($id_satuan)
    {
        return $this->db->table('tbl_satuan_produk')
            ->where('id_satuan', $id_satuan)
            ->update(['deleted_at' => 0]);
    }
    public function delete_hard($data)
    {
        return $this->db->table('tbl_satuan_produk')
            ->where('id_satuan', $data['id_satuan'])
            ->delete($data);
    }
}
