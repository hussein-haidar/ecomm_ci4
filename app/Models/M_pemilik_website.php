<?php

namespace App\Models;

use CodeIgniter\Model;

class M_pemilik_website extends Model
{
    protected $table = 'tbl_website'; // Nama tabel
    protected $primaryKey = 'id_website'; // Primary key
    protected $allowedFields = ['sesi_user', 'level', 'nama_toko', 'kode_kota', 'nama_kota', 'alamat_pusat', 'alamat_cabang', 'wa_pusat', 'wa_cabang', 'logo_website', 'bgd_web', 'tema_website', 'footer_title','link_IG','link_FB','link_Tiktok','is_checked','updated_at'];

    public function get_website()
    {
        $sesi_user = session()->get('sesi_user');
        $level = session()->get('level');

        return $this->db->table('tbl_website')
            ->select('
                tbl_website.*,
                tbl_data_user.level
            ')
            ->join('tbl_data_user', 'tbl_data_user.sesi_user = tbl_website.sesi_user', 'left')
            ->where('tbl_website.sesi_user', $sesi_user)
            ->where('tbl_data_user.level', $level)
            ->orderBy('tbl_website.id_website', 'DESC')
            ->get()->getResultArray();
    }
    
    public function get_website_by_nama($sesi_user = null)
    {
        if ($sesi_user === null) {
            $sesi_user = session()->get('sesi_user');
        }
        return $this->db->table('tbl_website')
        ->select('tbl_website.*, tbl_website.sesi_user, tbl_data_user.username, tbl_data_user.nama_lengkap, tbl_data_user.sesi_user as user_sesi_user, tbl_data_user.level')
        ->join('tbl_data_user', 'tbl_data_user.sesi_user = tbl_website.sesi_user', 'left')
        ->where('tbl_website.sesi_user', $sesi_user)
            ->where('tbl_website.is_checked', 2)
            ->orderBy('id_website', 'DESC')
            ->get()->getResultArray();
    }

    public function get_all_website_user()
    {
        return $this->db->table('tbl_website')
            ->select('tbl_website.*')
            ->orderBy('id_website', 'DESC')
            ->get()->getResultArray();
    }

    public function get_status_website($sesi_user = null)
    {
        if ($sesi_user === null) {
            $sesi_user = session()->get('sesi_user');
        }
        return $this->db->table('tbl_website')
            ->select('id_website, sesi_user, nama_toko, is_checked, logo_website, alasan_tolak')
            ->where('tbl_website.sesi_user', $sesi_user)
            ->orderBy('id_website', 'DESC')
            ->get()->getRowArray();
    }

    public function get_website_aktif()
    {
        return $this->db->table('tbl_website')
            ->select('tbl_website.*')
            ->where('tbl_website.is_checked', 2)
            ->groupBy('tbl_website.id_website')
            ->orderBy('id_website', 'DESC')
            ->get()->getResultArray();
    }

    public function get_user_by_id($sesi_user)
    {
        return $this->db->table('tbl_data_user')
        ->select('sesi_user, level') // Ambil kedua kolom sekaligus
        ->where('sesi_user', $sesi_user)
            ->get()
            ->getRowArray();
    }
    
    public function update_checked($id_website, $is_checked)
    {
        $this->db->table('tbl_website')
        ->where('id_website', $id_website)
            ->update([
                'is_checked' => $is_checked,
                'updated_at' => date('Y-m-d H:i:s') // Tambahkan timestamp saat update
            ]);
    }
    
    public function detailWebsite($id_website)
    {
        return $this->db->table('tbl_website')
            ->where('id_website', $id_website)
            ->get()->getRowArray();
    }

    public function add($data)
    {
        $this->db->table('tbl_website')
            ->insert($data);
    }

    public function edit($data)
    {
        $this->db->table('tbl_website')
            ->where('id_website', $data['id_website'])
            ->update($data);
    }

    public function delete_data($data)
    {
        return $this->db->table('tbl_website')
            ->where('id_website', $data['id_website'])
            ->delete($data);
    }

}

