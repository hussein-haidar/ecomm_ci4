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
        'email_user',
        'jobdesk_user',
        'level','tampilkan_varian', 'foto_user', 'last_login',
        'reset_token', 'reset_expires'];

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

    public function findByResetToken($token)
    {
        return $this->where('reset_token', $token)
            ->where('reset_expires >=', date('Y-m-d H:i:s'))
            ->first();
    }

    public function setResetToken($id_user, $token, $expires)
    {
        return $this->update($id_user, [
            'reset_token'   => $token,
            'reset_expires' => $expires,
        ]);
    }

    public function clearResetToken($id_user)
    {
        return $this->update($id_user, [
            'reset_token'   => null,
            'reset_expires' => null,
        ]);
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