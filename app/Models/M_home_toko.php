<?php

namespace App\Models;

use CodeIgniter\Model;

class M_home_toko extends Model
{
    protected $table   = 'tbl_stok_produk';
    protected $primaryKey = 'id_stok';
    protected $allowedFields = [
        'sesi_user',
        'kode_stok',
        'jumlah_stok_produk',
        'satuan_produk',
        'tanggal_masuk_produk',
        'id_stok',
        'nama_produk',
        'jenis_produk',
        'harga_produk',
        'berat_produk',
        'satuan_berat',
        'total_harga',
        'total_berat'
    ];

    public function getProduk($sesi_user, $keyword = '', $harga_min = null, $harga_max = null, $sort_by = '', $jenis_produk = '')
    {
        // Ambil semua produk sesuai filter
        $builder = $this->db->table('tbl_stok_produk')
            ->select('tbl_stok_produk.*, tbl_data_produk.harga_produk, tbl_data_produk.foto_produk, tbl_data_produk.berat_produk')
            ->join('tbl_data_produk', 'tbl_stok_produk.nama_produk = tbl_data_produk.nama_produk')
            ->where('tbl_stok_produk.sesi_user', $sesi_user);

        if (!empty($keyword)) {
            $builder->groupStart()
                ->like('tbl_stok_produk.nama_produk', $keyword)
                ->orLike('tbl_stok_produk.ukuran_produk', $keyword)
                ->orLike('tbl_stok_produk.jenis_produk', $keyword)
                ->groupEnd();
        }

        if (!empty($jenis_produk)) {
            $builder->where('tbl_stok_produk.jenis_produk', $jenis_produk);
        }

        if (!empty($harga_min)) {
            $builder->where('tbl_stok_produk.harga_produk >=', $harga_min);
        }
        if (!empty($harga_max)) {
            $builder->where('tbl_stok_produk.harga_produk <=', $harga_max);
        }
        switch ($sort_by) {
            case 'asc':
                $builder->orderBy('tbl_stok_produk.harga_produk', 'ASC');
                break;
            case 'desc':
                $builder->orderBy('tbl_stok_produk.harga_produk', 'DESC');
                break;
            case 'a-z':
                $builder->orderBy('tbl_stok_produk.nama_produk', 'ASC');
                break;
            case 'z-a':
                $builder->orderBy('tbl_stok_produk.nama_produk', 'DESC');
                break;
            default:
                $builder->orderBy('tbl_stok_produk.id_stok', 'ASC');
                break;
        }

        // Ambil semua data tanpa groupBy
        $query = $builder->get();
        $produk_data = $query->getResultArray();

        // Kelompokkan berdasarkan nama_produk
        $grouped = [];

        foreach ($produk_data as $item) {
            $key = $item['nama_produk'];

            if (!isset($grouped[$key])) {
                $grouped[$key] = [];
            }

            $grouped[$key][] = $item;
        }

        // Filter tiap kelompok
        $result = [];

        foreach ($grouped as $nama_produk => $items) {
            // Urutkan items berdasarkan id_stok (lama ke muda)
            usort($items, function ($a, $b) {
                return $a['id_stok'] <=> $b['id_stok'];
            });

            $stok_aktif = null;

            foreach ($items as $item) {
                if ($item['jumlah_stok_produk'] > 0) {
                    $stok_aktif = $item;
                    break; // Ambil stok pertama yang tersedia
                }
            }

            if ($stok_aktif !== null) {
                $stok_aktif['ukuran_list'] = explode('-', $stok_aktif['ukuran_produk']);
                $result[] = $stok_aktif;
            }
        }

        return $result;
    }

    public function getJenisProdukDropdown($sesi_user)
    {
        return $this->db->table('tbl_stok_produk')
            ->select('jenis_produk')
            ->where('sesi_user', $sesi_user)
            ->groupBy('jenis_produk')
            ->get()
            ->getResultArray();
    }

    public function getProdukByJenis($sesi_user, $jenis_produk, $keyword)
    {
        return $this->db->table('tbl_stok_produk')
            ->select('tbl_stok_produk.id_stok, tbl_stok_produk.nama_produk, tbl_stok_produk.ukuran_produk, tbl_stok_produk.satuan_produk, 
            tbl_stok_produk.jumlah_stok_produk, tbl_data_produk.harga_produk, tbl_data_produk.foto_produk, tbl_data_produk.berat_produk') // <== tambahkan ini
            ->join('tbl_data_produk', 'tbl_stok_produk.nama_produk = tbl_data_produk.nama_produk')
            ->where('tbl_stok_produk.sesi_user', $sesi_user)
            ->where('tbl_stok_produk.jenis_produk', $jenis_produk)
            ->groupStart()
            ->like('tbl_stok_produk.nama_produk', $keyword)
            ->orLike('tbl_stok_produk.jenis_produk', $keyword)
            ->groupEnd()
            ->groupBy('tbl_stok_produk.nama_produk')
            ->get()
            ->getResultArray();
    }

    public function detailStok($nama_produk, $id_stok = null)
    {
        $builder = $this->db->table('tbl_stok_produk')
            ->select('tbl_stok_produk.nama_produk, tbl_data_produk.*, SUM(tbl_stok_produk.jumlah_stok_produk) as total_stok')
            ->join('tbl_data_produk', 'tbl_data_produk.nama_produk = tbl_stok_produk.nama_produk', 'left')
            ->where('tbl_stok_produk.nama_produk', $nama_produk);

        if (!empty($id_stok)) {
            $builder->where('tbl_stok_produk.id_stok', $id_stok);
        }

        $builder->groupBy('tbl_stok_produk.nama_produk');

        $result = $builder->get()->getRowArray();

        if ($result && isset($result['ukuran_produk'])) {
            $result['ukuran_list'] = explode('-', $result['ukuran_produk']);
        } else {
            $result['ukuran_list'] = [];
        }

        return $result;
    }
}
