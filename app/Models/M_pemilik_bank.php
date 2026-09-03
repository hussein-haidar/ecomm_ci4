<?php

namespace App\Models;

use CodeIgniter\Model;

class M_pemilik_bank extends Model
{
    protected $table = 'tbl_data_bank'; // Nama tabel
    protected $primaryKey = 'id_bank'; // Primary key
    protected $allowedFields = ['sesi_user', 'a_n', 'nama_bank', 'no_rek'];

    public function get_bank()
    {
        $sesi_user = session()->get('sesi_user');
        return $this->db->table('tbl_data_bank')
            ->select('tbl_data_bank.*, tbl_website.nama_toko')
            ->join('tbl_website', 'tbl_website.sesi_user = tbl_data_bank.sesi_user', 'left')
            ->where('tbl_data_bank.deleted_at', 0) // Hanya ambil data yang belum dihapus
            ->where('tbl_data_bank.sesi_user', $sesi_user)
            ->get()->getResultArray();
    }

    public function get_bank_dihapus()
    {
        return $this->db->table('tbl_data_bank')
            ->select('tbl_data_bank.*, tbl_website.nama_toko')
            ->join('tbl_website', 'tbl_website.sesi_user = tbl_data_bank.sesi_user', 'left')
            ->where('tbl_data_bank.deleted_at', 1) // Hanya ambil data yang sudah dihapus
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

    public function detailBank($id_bank)
    {
        return $this->db->table('tbl_data_bank')
            ->where('id_bank', $id_bank)
            ->get()->getRowArray();
    }

    public function add($data)
    {
        $this->db->table('tbl_data_bank')
            ->insert($data);
    }

    public function edit($data)
    {
        return $this->db->table('tbl_data_bank')
            ->where('id_bank', $data['id_bank'])
            ->update($data);
    }

    public function delete_data($id_bank)
    {
        return $this->db->table('tbl_data_bank')
            ->where('id_bank', $id_bank)
            ->update(['deleted_at' => 1]);
    }

    public function restore($id_bank)
    {
        return $this->db->table('tbl_data_bank')
            ->where('id_bank', $id_bank)
            ->update(['deleted_at' => 0]);
    }
    
    public function delete_hard($data)
    {
        return $this->db->table('tbl_data_bank')
            ->where('id_bank', $data['id_bank'])
            ->delete($data);
    }
}
