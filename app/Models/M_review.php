<?php

namespace App\Models;

use CodeIgniter\Model;

class M_review extends Model
{
    protected $table      = 'tbl_review';
    protected $primaryKey = 'id_review';
    protected $allowedFields = [
        'sesi_user', 'nama_pelanggan', 'nama_produk', 'rating', 'ulasan', 'waktu_review'
    ];

    public function get_reviews($sesi_user, $nama_produk, $rating = null)
    {
        $builder = $this->db->table('tbl_review')
            ->where('sesi_user', $sesi_user)
            ->where('nama_produk', $nama_produk);

        if ($rating !== null && $rating > 0) {
            $builder->where('rating', $rating);
        }

        return $builder->orderBy('waktu_review', 'DESC')
            ->get()
            ->getResultArray();
    }

    public function get_rating_summary($sesi_user, $nama_produk)
    {
        $data = $this->db->table('tbl_review')
            ->select('COUNT(*) as total, AVG(rating) as rata_rata')
            ->where('sesi_user', $sesi_user)
            ->where('nama_produk', $nama_produk)
            ->get()
            ->getRowArray();

        $perRating = [];
        for ($i = 5; $i >= 1; $i--) {
            $count = $this->db->table('tbl_review')
                ->where('sesi_user', $sesi_user)
                ->where('nama_produk', $nama_produk)
                ->where('rating', $i)
                ->countAllResults();
            $perRating[$i] = $count;
        }

        return [
            'total'     => (int)($data['total'] ?? 0),
            'rata_rata' => round((float)($data['rata_rata'] ?? 0), 1),
            'per_rating' => $perRating,
        ];
    }

    public function has_reviewed($nama_pelanggan, $nama_produk, $sesi_user = null)
    {
        $builder = $this->where('nama_pelanggan', $nama_pelanggan)
            ->where('nama_produk', $nama_produk);

        if (!empty($sesi_user)) {
            $builder->where('sesi_user', $sesi_user);
        }

        return $builder->countAllResults() > 0;
    }

    public function get_user_review($sesi_user, $nama_pelanggan, $nama_produk)
    {
        return $this->where('sesi_user', $sesi_user)
            ->where('nama_pelanggan', $nama_pelanggan)
            ->where('nama_produk', $nama_produk)
            ->orderBy('id_review', 'DESC')
            ->get()
            ->getRowArray();
    }

    public function get_user_reviews($sesi_user, $nama_pelanggan)
    {
        return $this->where('sesi_user', $sesi_user)
            ->where('nama_pelanggan', $nama_pelanggan)
            ->get()
            ->getResultArray();
    }
}
