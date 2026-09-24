<?php

namespace App\Models;

use CodeIgniter\Model;

class M_kebijakan_toko extends Model
{
    protected $tblFaq = 'tbl_faq';
    protected $tblSkt = 'tbl_syarat_ketentuan';

    private function tokoUser()
    {
        return session()->get('toko_sesi_user') ?: session()->get('sesi_user');
    }

    // ==================== FAQ ====================

    public function get_faq()
    {
        return $this->db->table($this->tblFaq)
            ->where('sesi_user', $this->tokoUser())
            ->orderBy('kategori', 'ASC')
            ->orderBy('urutan', 'ASC')
            ->get()->getResultArray();
    }

    public function get_faq_by_sesi($sesi_user)
    {
        return $this->db->table($this->tblFaq)
            ->where('sesi_user', $sesi_user)
            ->orderBy('kategori', 'ASC')
            ->orderBy('urutan', 'ASC')
            ->get()->getResultArray();
    }

    public function detail_faq($id_faq)
    {
        return $this->db->table($this->tblFaq)
            ->where('id_faq', $id_faq)
            ->where('sesi_user', $this->tokoUser())
            ->get()->getRowArray();
    }

    public function add_faq($data)
    {
        return $this->db->table($this->tblFaq)->insert($data);
    }

    public function edit_faq($id_faq, $data)
    {
        return $this->db->table($this->tblFaq)
            ->where('id_faq', $id_faq)
            ->where('sesi_user', $this->tokoUser())
            ->update($data);
    }

    public function delete_faq($id_faq)
    {
        return $this->db->table($this->tblFaq)
            ->where('id_faq', $id_faq)
            ->where('sesi_user', $this->tokoUser())
            ->delete();
    }

    // ==================== Syarat Ketentuan ====================

    public function get_skt()
    {
        return $this->db->table($this->tblSkt)
            ->where('sesi_user', $this->tokoUser())
            ->orderBy('urutan', 'ASC')
            ->get()->getResultArray();
    }

    public function get_skt_by_sesi($sesi_user)
    {
        return $this->db->table($this->tblSkt)
            ->where('sesi_user', $sesi_user)
            ->orderBy('urutan', 'ASC')
            ->get()->getResultArray();
    }

    public function detail_skt($id_skt)
    {
        return $this->db->table($this->tblSkt)
            ->where('id_skt', $id_skt)
            ->where('sesi_user', $this->tokoUser())
            ->get()->getRowArray();
    }

    public function add_skt($data)
    {
        return $this->db->table($this->tblSkt)->insert($data);
    }

    public function edit_skt($id_skt, $data)
    {
        return $this->db->table($this->tblSkt)
            ->where('id_skt', $id_skt)
            ->where('sesi_user', $this->tokoUser())
            ->update($data);
    }

    public function delete_skt($id_skt)
    {
        return $this->db->table($this->tblSkt)
            ->where('id_skt', $id_skt)
            ->where('sesi_user', $this->tokoUser())
            ->delete();
    }
}