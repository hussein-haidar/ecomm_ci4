<?php

namespace App\Models;

use CodeIgniter\Model;

class M_profil_pelanggan extends Model
{
    protected $table = 'tbl_data_pelanggan';
    protected $primaryKey = 'id_pelanggan';
    protected $allowedFields = ['sesi_user', 'email', 'google_id', 'password', 'nama_pelanggan', 'jenis_kelamin', 'tanggal_lahir', 'alamat', 'longitude', 'latitude', 'kode_kota', 'nama_kota', 'level', 'no_telpon', 'foto_pelanggan', 'last_login'];

    public function get_profile()
    {
        $sesi_user = session()->get('sesi_user');
        return $this->db->table($this->table)
            ->where('tbl_data_pelanggan.sesi_user', $sesi_user)
            ->get()->getResultArray();
    }

    public function get_user_by_id($sesi_user)
    {
        return $this->db->table('tbl_data_pelanggan')
            ->select('sesi_user')
            ->where('sesi_user', $sesi_user)
            ->get()
            ->getRowArray();
    }

    public function detailProfile($id_pelanggan)
    {
        return $this->db->table($this->table)
            ->where($this->primaryKey, $id_pelanggan)
            ->get()->getRowArray();
    }

    public function add($data)
    {
        $this->db->table('tbl_data_pelanggan')
            ->insert($data);
    }

    public function findByEmail($email)
    {
        return $this->where('email', $email)->first();
    }

    public function findByGoogleId($googleId)
    {
        return $this->where('google_id', $googleId)->first();
    }

    public function updateLastLogin($userId)
    {
        return $this->update($userId, ['last_login' => date('Y-m-d H:i:s')]);
    }

    public function updatePassword($id_pelanggan, $password)
    {
        return $this->update($id_pelanggan, ['password' => $password]);
    }

    public function edit($id_pelanggan, $data)
    {
        $builder = $this->db->table($this->table);
        $builder->where($this->primaryKey, $id_pelanggan);
        $result = $builder->update($data);

        if (!$result) {
            log_message('error', 'Error saat mengupdate data pengguna: ' . $this->db->error()['message']);
            return false;
        }

        return true;
    }

    public function delete_data($data)
    {
        return $this->db->table('tbl_data_pelanggan')
            ->where('id_pelanggan', $data['id_pelanggan'])
            ->delete();
    }
}
