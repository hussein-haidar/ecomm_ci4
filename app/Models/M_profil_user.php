<?php

namespace App\Models;

use CodeIgniter\Model;

class M_profil_user extends Model
{
    protected $table = 'tbl_data_user';
    protected $primaryKey = 'id_user';
    protected $allowedFields = ['username', 'password', 'nama_lengkap',
        'sesi_user',
        'nama_title',
        'notelpon_user',
        'jobdesk_user',
        'level','tampilkan_varian', 'foto_user', 'last_login'];

    public function get_profile()
    {
        return $this->db->table($this->table)
            ->get()->getResultArray();
    }

    public function getTampilanPengaturan()
    {
        return $this->where('id_user', session()->get('id_user'))
            ->first(['tampilkan_varian']);
    }

    public function get_user_by_id($nama_lengkap)
    {
        return $this->db->table('tbl_data_user')
            ->select('nama_lengkap')
            ->where('nama_lengkap', $nama_lengkap)
            ->get()
            ->getRowArray();
    }

    public function add($data)
    {
        $this->db->table('tbl_data_user')
            ->insert($data);
    }
    
    public function detailProfile($id_user)
    {
        return $this->db->table($this->table)
            ->where($this->primaryKey, $id_user)
            ->get()->getRowArray();
    }

    public function findByUsername($username)
    {
        return $this->where('username', $username)->first();
    }

    public function updateLastLogin($userId)
    {
        return $this->update($userId, ['last_login' => date('Y-m-d H:i:s')]);
    }

    public function updatePassword($id_user, $password)
    {
        return $this->update($id_user, ['password' => $password]);
    }

    public function edit($id_user, $data)
    {
        $builder = $this->db->table($this->table);
        $builder->where($this->primaryKey, $id_user);
        $result = $builder->update($data);

        if (!$result) {
            log_message('error', 'Error saat mengupdate data pengguna: ' . $this->db->error()['message']);
            return false;
        }

        return true;
    }
}