<?php

namespace App\Models;

use CodeIgniter\Model;

class M_pemilik_produk extends Model
{
    protected $table = 'tbl_data_produk'; // Nama tabel
    protected $primaryKey = 'id_produk'; // Primary key
protected $allowedFields = [
        'sesi_user',
        'kode_produk',
        'ukuran_produk',
        'nama_produk',
        'checked',
        'berat_produk',
        'satuan_berat',
        'harga_produk',
        'deskripsi_produk',
        'foto_produk',
        'size_guide_image',
        'gallery_images',
        'video_url',
        'id_produk',
        'id_jenis',
        'id_varian',
        'deleted_at'
    ];

    public function get_produk()
    {
        $sesi_user = session()->get('sesi_user');
        return $this->db->table($this->table)
            ->select('tbl_data_produk.*, tbl_varian_produk.varian_produk, tbl_jenis_produk.jenis_produk')
            ->join('tbl_varian_produk', 'tbl_varian_produk.id_varian = tbl_data_produk.id_varian', 'left')
            ->join('tbl_jenis_produk', 'tbl_jenis_produk.id_jenis = tbl_data_produk.id_jenis', 'left')
            ->where('tbl_data_produk.deleted_at', 0) // Hanya ambil data yang belum dihapus
            ->where('tbl_data_produk.sesi_user', $sesi_user)
            ->orderBy('id_produk', 'DESC')
            ->get()->getResultArray();
    }

    public function get_carousel()
    {
        $sesi_user = session()->get('sesi_user');
        return $this->db->table($this->table)
            ->select('tbl_data_produk.*, tbl_varian_produk.varian_produk, tbl_jenis_produk.jenis_produk')
            ->join('tbl_varian_produk', 'tbl_varian_produk.id_varian = tbl_data_produk.id_varian', 'left')
            ->join('tbl_jenis_produk', 'tbl_jenis_produk.id_jenis = tbl_data_produk.id_jenis', 'left')
            ->where('tbl_data_produk.deleted_at', 0) // Hanya ambil data yang belum dihapus
            ->where('tbl_data_produk.sesi_user', $sesi_user)
            ->where('tbl_data_produk.checked', 1)
            ->orderBy('id_produk', 'DESC')
            ->get()->getResultArray();
    }

    public function get_produk_dihapus()
    {
        $sesi_user = session()->get('sesi_user');
        return $this->db->table($this->table)
            ->select('tbl_data_produk.*, tbl_varian_produk.varian_produk, tbl_jenis_produk.jenis_produk')
            ->join('tbl_varian_produk', 'tbl_varian_produk.id_varian = tbl_data_produk.id_varian', 'left')
            ->join('tbl_jenis_produk', 'tbl_jenis_produk.id_jenis = tbl_data_produk.id_jenis', 'left')
            ->where('tbl_data_produk.deleted_at', 1) // Hanya ambil data yang sudah dihapus
            ->where('tbl_data_produk.sesi_user', $sesi_user)
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

    public function getJenisById($id_jenis)
    {
        return $this->db->table('tbl_jenis_produk')->where('id_jenis', $id_jenis)->get()->getRowArray();
    }

    public function getVarianById($id_varian)
    {
        return $this->db->table('tbl_varian_produk')->where('id_varian', $id_varian)->get()->getRowArray();
    }

    // Data Motif Produk
    public function all_varian()
    {
        $sesi_user = session()->get('sesi_user');
        return $this->db->table('tbl_varian_produk')
            ->where('sesi_user', $sesi_user)
            ->where('deleted_at', 0) // Filter hanya data yang belum dihapus
            ->get()
            ->getResultArray();
    }

    // Data Jenis Produk
    public function all_jenis()
    {
        $sesi_user = session()->get('sesi_user');
        return $this->db->table('tbl_jenis_produk')
            ->where('sesi_user', $sesi_user)
            ->where('deleted_at', 0) // Filter hanya data yang belum dihapus
            ->get()
            ->getResultArray();
    }

    // Data Satuan Produk
    public function all_satuan()
    {
        $sesi_user = session()->get('sesi_user');
        return $this->db->table('tbl_satuan_produk')
            ->where('sesi_user', $sesi_user)
            ->where('deleted_at', 0) // Filter hanya data yang belum dihapus
            ->get()
            ->getResultArray();
    }

    public function detailProduk($id_produk)
    {
        return $this->db->table('tbl_data_produk')
            ->where('id_produk', $id_produk)
            ->get()->getRowArray();
    }

    // Menambahkan data produk
    public function add($data)
    {
        $this->db->table($this->table)->insert($data);
    }

    public function edit($data)
    {
        return $this->db->table($this->table)
            ->where('id_produk', $data['id_produk'])
            ->update($data);
    }

    public function delete_data($id_produk)
    {
        return $this->db->table('tbl_data_produk')
            ->where('id_produk', $id_produk)
            ->update(['deleted_at' => 1]);
    }

    public function restore($id_produk)
    {
        return $this->db->table('tbl_data_produk')
            ->where('id_produk', $id_produk)
            ->update(['deleted_at' => 0]);
    }

    public function delete_hard($data)
    {
        return $this->db->table('tbl_data_produk')
            ->where('id_produk', $data['id_produk'])
            ->delete($data);
    }
}
