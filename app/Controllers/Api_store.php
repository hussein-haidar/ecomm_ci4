<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\M_home_toko;
use App\Models\M_review;
use App\Models\M_pemilik_website;

class Api_store extends Controller
{
    private function cekApiKey()
    {
        $apiKeyEnv = env('API_KEY', '');

        // Jika API_KEY belum diset di .env, izinkan akses (mode pengembangan).
        if ($apiKeyEnv === '') {
            log_message('warning', 'Api_store dipanggil tanpa API_KEY terkonfigurasi di .env.');
            return true;
        }

        $requestKey = $this->request->getGet('api_key') ?? $this->request->getHeaderLine('X-API-Key');

        if ($requestKey !== $apiKeyEnv) {
            return false;
        }

        return true;
    }

    public function toko()
    {
        if (!$this->cekApiKey()) {
            return $this->response->setStatusCode(401)->setJSON([
                'success' => false,
                'message' => 'API key tidak valid.',
            ]);
        }

        $storeModel = new M_pemilik_website();
        $toko = $storeModel->get_website_aktif();

        $data = [];
        foreach ($toko as $t) {
            $data[] = [
                'id_website'  => $t['id_website'],
                'sesi_user'   => $t['sesi_user'],
                'nama_toko'   => $t['nama_toko'],
                'alamat'      => $t['alamat_pusat'] ?? '',
                'no_wa'       => $t['wa_pusat'] ?? '',
                'logo'        => base_url('logowebsite/' . ($t['logo_website'] ?? '')),
                'background'  => base_url('bgdweb/' . ($t['bgd_web'] ?? '')),
            ];
        }

        return $this->response->setJSON([
            'success' => true,
            'data'    => $data,
        ]);
    }

    public function produk()
    {
        if (!$this->cekApiKey()) {
            return $this->response->setStatusCode(401)->setJSON([
                'success' => false,
                'message' => 'API key tidak valid.',
            ]);
        }

        $sesi_user   = $this->request->getGet('sesi_user');
        $keyword     = $this->request->getGet('keyword') ?? '';
        $harga_min   = $this->request->getGet('harga_min');
        $harga_max   = $this->request->getGet('harga_max');
        $jenis       = $this->request->getGet('jenis_produk') ?? '';
        $sort_by     = $this->request->getGet('sort_by') ?? '';
        $rating_min  = $this->request->getGet('rating_min');

        if (empty($sesi_user)) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Parameter sesi_user (nama pemilik toko) wajib diisi.',
            ]);
        }

        $produkModel = new M_home_toko();
        $produkList  = $produkModel->getProduk($sesi_user, $keyword, $harga_min, $harga_max, $sort_by, $jenis, $rating_min);

        $namaProdukList = array_column($produkList, 'nama_produk');
        $reviewModel = new M_review();
        $ratingMap   = $reviewModel->get_rating_summary_batch($sesi_user, $namaProdukList);

        $data = [];
        foreach ($produkList as $p) {
            $rating = $ratingMap[$p['nama_produk']] ?? null;
            $data[] = [
                'id_stok'          => $p['id_stok'],
                'nama_produk'      => $p['nama_produk'],
                'jenis_produk'     => $p['jenis_produk'],
                'harga'            => (float) $p['harga_produk'],
                'stok'             => (int) $p['jumlah_stok_produk'],
                'ukuran'           => array_map('trim', explode('-', $p['ukuran_produk'])),
                'satuan'           => $p['satuan_produk'] ?? '',
                'berat'            => (float) ($p['berat_produk'] ?? 0),
                'foto'             => base_url('fotoproduk/' . ($p['foto_produk'] ?? '')),
                'rating_total'     => $rating['total'] ?? 0,
                'rating_rata_rata' => $rating['rata_rata'] ?? 0,
            ];
        }

        return $this->response->setJSON([
            'success' => true,
            'total'   => count($data),
            'data'    => $data,
        ]);
    }

    public function detail_produk($nama_produk)
    {
        if (!$this->cekApiKey()) {
            return $this->response->setStatusCode(401)->setJSON([
                'success' => false,
                'message' => 'API key tidak valid.',
            ]);
        }

        $sesi_user = $this->request->getGet('sesi_user');

        if (empty($sesi_user)) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'message' => 'Parameter sesi_user (nama pemilik toko) wajib diisi.',
            ]);
        }

        $produkModel = new M_home_toko();
        $produk = $produkModel->detailStok($nama_produk);

        if (!$produk) {
            return $this->response->setStatusCode(404)->setJSON([
                'success' => false,
                'message' => 'Produk tidak ditemukan.',
            ]);
        }

        $reviewModel = new M_review();
        $rating = $reviewModel->get_rating_summary($sesi_user, $nama_produk);
        $reviews = $reviewModel->get_reviews($sesi_user, $nama_produk);

        $galeri = [];
        $rawGallery = $produk['gallery_images'] ?? '';
        if (!empty($rawGallery)) {
            foreach (json_decode($rawGallery, true) ?: [] as $g) {
                $galeri[] = base_url('fotoproduk/' . $g);
            }
        }

        $data = [
            'nama_produk'   => $produk['nama_produk'],
            'jenis_produk'  => $produk['jenis_produk'] ?? '',
            'harga'         => (float) ($produk['harga_produk'] ?? 0),
            'deskripsi'     => $produk['deskripsi_produk'] ?? '',
            'foto'          => base_url('fotoproduk/' . ($produk['foto_produk'] ?? '')),
            'galeri'        => $galeri,
            'size_guide'    => !empty($produk['size_guide_image']) ? base_url('fotoproduk/' . $produk['size_guide_image']) : null,
            'video_url'     => $produk['video_url'] ?? null,
            'ukuran'        => $produk['ukuran_list'],
            'satuan'        => $produk['satuan_produk'] ?? '',
            'stok_total'    => (int) ($produk['total_stok'] ?? 0),
            'rating'        => $rating,
            'reviews'       => array_map(function ($r) {
                return [
                    'nama_pelanggan' => $r['nama_pelanggan'],
                    'rating'         => (int) $r['rating'],
                    'ulasan'         => $r['ulasan'],
                    'waktu'          => $r['waktu_review'],
                ];
            }, $reviews),
        ];

        return $this->response->setJSON([
            'success' => true,
            'data'    => $data,
        ]);
    }
}