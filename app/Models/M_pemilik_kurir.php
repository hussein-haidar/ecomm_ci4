<?php

namespace App\Models;

use CodeIgniter\Model;

class M_pemilik_kurir extends Model
{
    protected $table = 'tbl_data_kurir'; // Adjust the table name accordingly
    protected $primaryKey = 'id_kurir'; // Adjust the primary key if necessary
    protected $allowedFields = [
        'jenis_kurir',
       'ongkir',
       'sesi_user'
    ];

    public function get_kurir()
    {
        $sesi_user = session()->get('sesi_user');
        return $this->db->table('tbl_data_kurir')
            ->where('tbl_data_kurir.sesi_user', $sesi_user)
            ->orderBy('id_kurir', 'DESC')
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

    public function detailKurir($id_kurir)
    {
        return $this->db->table('tbl_data_kurir')
            ->where('id_kurir', $id_kurir)
            ->get()->getRowArray();
    }

    public function add($data)
    {
        $this->db->table('tbl_data_kurir')
            ->insert($data);
    }

    public function edit($data)
    {
        return $this->db->table('tbl_data_kurir')
            ->where('id_kurir', $data['id_kurir'])
            ->update($data);
    }

    public function delete_data($data)
    {
        return $this->db->table('tbl_data_kurir')
            ->where('id_kurir', $data['id_kurir'])
            ->delete($data);
    }
}
