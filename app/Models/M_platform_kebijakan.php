<?php

namespace App\Models;

use CodeIgniter\Model;

class M_platform_kebijakan extends Model
{
    protected $tblFaq = 'tbl_faq_platform';
    protected $tblSkt = 'tbl_syarat_platform';

    // ==================== FAQ ====================

    public function get_all_faq()
    {
        return $this->db->table($this->tblFaq)
            ->orderBy('kategori', 'ASC')
            ->orderBy('urutan', 'ASC')
            ->get()->getResultArray();
    }

    public function detail_faq($id_faq)
    {
        return $this->db->table($this->tblFaq)
            ->where('id_faq', $id_faq)
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
            ->update($data);
    }

    public function delete_faq($id_faq)
    {
        return $this->db->table($this->tblFaq)
            ->where('id_faq', $id_faq)
            ->delete();
    }

    // ==================== Syarat Ketentuan ====================

    public function get_all_skt()
    {
        return $this->db->table($this->tblSkt)
            ->orderBy('urutan', 'ASC')
            ->get()->getResultArray();
    }

    public function detail_skt($id_skt)
    {
        return $this->db->table($this->tblSkt)
            ->where('id_skt', $id_skt)
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
            ->update($data);
    }

    public function delete_skt($id_skt)
    {
        return $this->db->table($this->tblSkt)
            ->where('id_skt', $id_skt)
            ->delete();
    }
}