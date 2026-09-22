<?php

namespace App\Models;

use CodeIgniter\Model;

class M_pemilik_banner extends Model
{
    protected $table = 'tbl_promo_banner'; // Nama tabel
    protected $primaryKey = 'id_banner'; // Primary key
    protected $allowedFields = ['sesi_user', 'judul_banner', 'deskripsi_banner', 'foto_banner', 'link_banner', 'status', 'deleted_at'];

    public function get_banner()
    {
        $sesi_user = session()->get('sesi_user');
        return $this->db->table('tbl_promo_banner')
            ->where('deleted_at', 0) // Hanya ambil data yang belum dihapus
            ->where('sesi_user', $sesi_user)
            ->orderBy('id_banner', 'DESC')
            ->get()->getResultArray();
    }

    public function get_banner_aktif($sesi_user = null)
    {
        if ($sesi_user === null) {
            $sesi_user = session()->get('sesi_user');
        }
        return $this->db->table('tbl_promo_banner')
            ->where('deleted_at', 0) // Hanya ambil data yang belum dihapus
            ->where('status', 1)     // Hanya banner yang aktif di slider
            ->where('sesi_user', $sesi_user)
            ->orderBy('id_banner', 'DESC')
            ->get()->getResultArray();
    }

    public function get_banner_dihapus()
    {
        return $this->db->table('tbl_promo_banner')
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

    public function detailBanner($id_banner)
    {
        return $this->db->table('tbl_promo_banner')
            ->where('id_banner', $id_banner)
            ->get()->getRowArray();
    }

    public function add($data)
    {
        $this->db->table('tbl_promo_banner')
            ->insert($data);
    }

    public function edit($data)
    {
        return $this->db->table('tbl_promo_banner')
            ->where('id_banner', $data['id_banner'])
            ->update($data);
    }

    public function delete_data($id_banner)
    {
        return $this->db->table('tbl_promo_banner')
            ->where('id_banner', $id_banner)
            ->update(['deleted_at' => 1]);
    }

    public function restore($id_banner)
    {
        return $this->db->table('tbl_promo_banner')
            ->where('id_banner', $id_banner)
            ->update(['deleted_at' => 0]);
    }

    public function delete_hard($data)
    {
        return $this->db->table('tbl_promo_banner')
            ->where('id_banner', $data['id_banner'])
            ->delete($data);
    }
}