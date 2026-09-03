<?php

namespace App\Models;

use CodeIgniter\Model;

class M_pemilik_kelola_user extends Model
{
    protected $table = 'tbl_data_user'; // Adjust the table name accordingly
    protected $primaryKey = 'id_user'; // Adjust the primary key if necessary
    protected $allowedFields = [
        'username',
        'password',
        'nama_lengkap',
        'sesi_user',
        'nama_title',
        'notelpon_user',
        'jobdesk_user',
        'level',
        'tampilkan_varian',
        'foto_user',
        'last_login'
    ];
    
    public function get_user()
    {
        $sesi_user = session()->get('sesi_user');
        return $this->db->table('tbl_data_user')
            ->where('tbl_data_user.sesi_user', $sesi_user)
            ->orderBy('id_user', 'DESC')
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
    
    public function detailUser($id_user)
    {
        return $this->db->table('tbl_data_user')
            ->where('id_user', $id_user)
            ->get()->getRowArray();
    }

    public function add($data)
    {
        $this->db->table('tbl_data_user')
        ->insert($data);
    }

    public function edit($data)
    {
        return $this->db->table('tbl_data_user')
            ->where('id_user', $data['id_user'])
            ->update($data);
    }

    public function delete_data($data)
    {
        return $this->db->table('tbl_data_user')
            ->where('id_user', $data['id_user'])
            ->delete();
    }
}
