<?php

namespace App\Models;

use CodeIgniter\Model;

class M_pelanggan_keranjang extends Model
{
    protected $table = 'tbl_data_keranjang'; // Nama tabel
    protected $primaryKey = 'id_keranjang'; // Primary key
    protected $allowedFields = ['nama_pelanggan', 'alamat', 'latitude', 'longitude', 'id_stok', 'nama_produk', 'ukuran_produk', 'jumlah_produk', 'satuan_produk', 'harga_produk', 'berat_produk', 'total_harga', 'status_keranjang', 'waktu_ditambahkan'];

    public function get_keranjang($sesi_user = null)
    {
        $nama_pelanggan = session()->get('nama_pelanggan');
        $builder = $this->db->table('tbl_data_keranjang')
            ->select('tbl_data_keranjang.*, tbl_data_produk.foto_produk, tbl_stok_produk.jumlah_stok_produk')
            ->join('tbl_stok_produk', 'tbl_stok_produk.id_stok = tbl_data_keranjang.id_stok', 'left')
            ->join('tbl_data_produk', 'tbl_data_produk.nama_produk = tbl_stok_produk.nama_produk', 'left')
            ->where('tbl_data_keranjang.nama_pelanggan', $nama_pelanggan)
            ->where('tbl_data_keranjang.status_keranjang', 'proses');

        if (!empty($sesi_user)) {
            $builder->where('tbl_stok_produk.sesi_user', $sesi_user);
        }

        return $builder->orderBy('tbl_data_keranjang.id_keranjang', 'DESC')
            ->get()
            ->getResultArray();
    }

    public function get_keranjang_by_ids($ids, $sesi_user = null)
    {
        $builder = $this->db->table('tbl_data_keranjang')
            ->select('tbl_data_keranjang.*, tbl_data_produk.foto_produk, tbl_stok_produk.jumlah_stok_produk')
            ->join('tbl_stok_produk', 'tbl_stok_produk.id_stok = tbl_data_keranjang.id_stok', 'left')
            ->join('tbl_data_produk', 'tbl_data_produk.nama_produk = tbl_stok_produk.nama_produk', 'left')
            ->whereIn('tbl_data_keranjang.id_keranjang', $ids);

        if (!empty($sesi_user)) {
            $builder->where('tbl_stok_produk.sesi_user', $sesi_user);
        }

        return $builder->get()
            ->getResultArray();
    }
    
    public function get_user_by_id($nama_pelanggan)
    {
        return $this->db->table('tbl_data_keranjang')
            ->select('nama_pelanggan')
            ->select('latitude')
            ->select('longitude')
            ->select('alamat')
            ->select('id_stok')
            ->select('nama_produk')
            ->select('ukuran_produk')
            ->select('jumlah_produk')
            ->select('satuan_produk')
            ->select('berat_produk')
            ->select('total_harga')
            ->where('nama_pelanggan', $nama_pelanggan)
            ->get()
            ->getRowArray();
    }

    // Ambil item keranjang berdasarkan nama pelanggan, nama produk, dan id_stok
    public function get_cart_item($nama_pelanggan, $nama_produk, $id_stok)
    {
        return $this->db->table('tbl_data_keranjang')
            ->where('nama_pelanggan', $nama_pelanggan)
            ->where('id_stok', $id_stok)
            ->where('nama_produk', $nama_produk)
            ->where('status_keranjang', 'proses')
            ->orderBy('id_keranjang', 'DESC')
            ->get()->getRowArray();
    }

    // Update jumlah dan total harga item di keranjang berdasarkan nama, produk, dan id_stok
    public function update_cart($nama_pelanggan, $id_stok, $nama_produk, $jumlah_produk, $total_harga)
    {
        return $this->db->table('tbl_data_keranjang')
            ->where('nama_pelanggan', $nama_pelanggan)
            ->where('id_stok', $id_stok)
            ->where('nama_produk', $nama_produk)
            ->where('status_keranjang', 'proses')
            ->update([
                'jumlah_produk' => $jumlah_produk,
                'total_harga' => $total_harga
            ]);
    }

    public function updateStatusKeranjang($nama_pelanggan, $waktu_ditambahkan)
    {
        return $this->db->table('tbl_data_keranjang')
            ->where('nama_pelanggan', $nama_pelanggan)
            ->where('waktu_ditambahkan', $waktu_ditambahkan)
            ->update([
            'waktu_ditambahkan' => date('Y-m-d H:i:s'),
            'status_keranjang' => 'Selesai']);
    }

    public function delete_data($data)
    {
        $this->db->table('tbl_data_keranjang')
            ->where('id_keranjang', $data['id_keranjang'])
            ->delete($data);
    }
}
