<?php

namespace App\Models;

use CodeIgniter\Model;

class M_pelanggan_bayar extends Model
{
    protected $table   = 'tbl_data_pembayaran';
    protected $primaryKey = 'id_bayar';
    protected $allowedFields = [
        'nama_pelanggan',
        'nama_produk',
        'ukuran_produk',
        'jumlah_produk',
        'satuan_produk',
        'bank_tujuan',
        'no_rek',
        'total_harga',
        'total_bayar',
        'foto_bayar',
        'waktu_pembayaran',
        'batas_waktu_bayar',
        'status_bayar',
        'id_beli'
    ];

    public function get_bayar()
    {
        $sesi_user = session()->get('sesi_user');
        return $this->db->table('tbl_data_pembayaran')
            ->select('tbl_data_pembayaran.*, tbl_data_produk.*')
            ->join('tbl_data_produk', 'tbl_data_produk.nama_produk = tbl_data_pembayaran.nama_produk', 'left')
            ->where('tbl_data_pembayaran.sesi_user', $sesi_user)
            ->orderBy('id_bayar', 'DESC')
            ->get()->getResultArray();
    }

    public function get_bayar_by_status($sesiUserToko = null)
    {
        $nama_pelanggan = session()->get('nama_pelanggan');
        $builder = $this->db->table('tbl_data_pembayaran')
            ->select('tbl_data_pembayaran.*, tbl_data_produk.*')
            ->join('tbl_data_produk', 'tbl_data_produk.nama_produk = tbl_data_pembayaran.nama_produk', 'left')
            ->where('tbl_data_pembayaran.nama_pelanggan', $nama_pelanggan)
            ->whereIn('tbl_data_pembayaran.status_bayar', ['Belum Bayar', 'Dibatalkan']);

        if (!empty($sesiUserToko)) {
            $builder->where('tbl_data_pembayaran.sesi_user', $sesiUserToko);
        }

        return $builder->orderBy('id_bayar', 'DESC')
            ->get()->getResultArray();
    }

    public function get_status_bayar_by_status()
    {
        $sesi_user = session()->get('sesi_user');
        return $this->db->table('tbl_data_pembayaran')
            ->select('tbl_data_pembayaran.*, tbl_data_produk.*')
            ->join('tbl_data_produk', 'tbl_data_produk.nama_produk = tbl_data_pembayaran.nama_produk', 'left')
            ->where('tbl_data_pembayaran.sesi_user', $sesi_user)
            ->where('tbl_data_pembayaran.status_bayar', 'Dibayar')
            ->orderBy('id_bayar', 'DESC')
            ->get()->getResultArray();
    }

    public function get_status_bayar_sesi($sesi_user, $nama_pelanggan)
{
    return $this->db->table('tbl_data_pembayaran')
        ->where('tbl_data_pembayaran.sesi_user', $sesi_user)
        ->where('tbl_data_pembayaran.nama_pelanggan', $nama_pelanggan)
        ->where('tbl_data_pembayaran.status_bayar', 'Dibayar')
        ->orderBy('id_bayar', 'DESC')
        ->get()->getResultArray();
}

    public function get_riwayat_bayar_pelanggan($sesiUserToko, $nama_pelanggan)
    {
        return $this->db->table('tbl_data_pembayaran')
            ->select('tbl_data_pembayaran.*, tbl_data_produk.*')
            ->join('tbl_data_produk', 'tbl_data_produk.nama_produk = tbl_data_pembayaran.nama_produk', 'left')
            ->where('tbl_data_pembayaran.sesi_user', $sesiUserToko)
            ->where('tbl_data_pembayaran.nama_pelanggan', $nama_pelanggan)
            ->where('tbl_data_pembayaran.status_bayar', 'Dibayar')
            ->orderBy('id_bayar', 'DESC')
            ->get()->getResultArray();
    }

    public function get_riwayat_beli_pelanggan($sesiUserToko, $nama_pelanggan)
    {
        return $this->db->table('tbl_data_pembelian')
            ->select('tbl_data_pembelian.*, tbl_data_produk.foto_produk, tbl_data_pembayaran.id_bayar, tbl_data_pembayaran.waktu_pembayaran, tbl_data_pembayaran.total_bayar')
            ->join('tbl_data_produk', 'tbl_data_produk.nama_produk = tbl_data_pembelian.nama_produk', 'left')
            ->join('tbl_data_pembayaran', 'tbl_data_pembayaran.id_beli = tbl_data_pembelian.id_beli', 'left')
            ->where('tbl_data_pembelian.sesi_user', $sesiUserToko)
            ->where('tbl_data_pembelian.nama_pelanggan', $nama_pelanggan)
            ->orderBy('tbl_data_pembelian.id_beli', 'DESC')
            ->get()->getResultArray();
    }

    public function get_user_by_id($id_bayar)
    {
        return $this->db->table('tbl_data_pembayaran')
            ->select('tbl_data_pembayaran.*, tbl_data_pembelian.id_beli, tbl_data_pembelian.nama_pelanggan')
            ->join('tbl_data_pembelian', 'tbl_data_pembayaran.id_beli = tbl_data_pembelian.id_beli')
            ->where('tbl_data_pembayaran.id_bayar', $id_bayar)
            ->get()
            ->getRowArray();
    }

    public function get_beli_by_id($id_beli)
    {
        return $this->db->table('tbl_data_pembelian')
            ->select('id_beli')
            ->where('tbl_data_pembelian.id_beli', $id_beli)
            ->get()
            ->getRowArray();
    }

    public function get_beli()
    {
        $sesi_user = session()->get('sesi_user');
        return $this->db->table('tbl_data_pembelian')
            ->select('tbl_data_pembelian.*, tbl_data_produk.*')
            ->join('tbl_data_produk', 'tbl_data_produk.nama_produk = tbl_data_pembelian.nama_produk', 'left')
            ->where('tbl_data_pembelian.sesi_user', $sesi_user)
            ->where('tbl_data_pembelian.status_bayar', 'Dibayar')
            ->orderBy('id_beli', 'DESC')
            ->get()->getResultArray();
    }

    public function add($data_pembayaran)
    {
        $this->db->table('tbl_data_pembayaran')
            ->insert($data_pembayaran);
    }

    public function detailBayar($id_bayar)
    {
        return $this->db->table('tbl_data_pembayaran')
            ->select('tbl_data_pembayaran.*, tbl_data_produk.*, tbl_data_pembelian.*, tbl_data_ekspedisi.*, tbl_data_pelanggan.*')
            ->join('tbl_data_pelanggan', 'tbl_data_pelanggan.nama_pelanggan = tbl_data_pembayaran.nama_pelanggan', 'left')
            ->join('tbl_data_produk', 'tbl_data_produk.nama_produk = tbl_data_pembayaran.nama_produk', 'left')
            ->join('tbl_data_pembelian', 'tbl_data_pembelian.nama_pelanggan = tbl_data_pembayaran.nama_pelanggan', 'left')
            ->join('tbl_data_ekspedisi', 'tbl_data_ekspedisi.nama_pelanggan = tbl_data_pembayaran.nama_pelanggan', 'left')
            ->where('tbl_data_pembayaran.id_bayar', $id_bayar)
            ->get()
            ->getRowArray(); // Pastikan ini mengembalikan array  
    }

    public function edit($data)
    {
        return $this->db->table('tbl_data_pembayaran')
            ->where('id_bayar', $data['id_bayar'])
            ->update($data);
    }

    public function konfirmasi_via_midtrans($id_bayar, $status_bayar)
    {
        // Ambil data pembayaran untuk cari pembelian terkait
        $bayar = $this->db->table('tbl_data_pembayaran')
            ->where('id_bayar', $id_bayar)
            ->get()
            ->getRowArray();

        if (!$bayar) {
            return false;
        }

        // Update status pembayaran
        $this->db->table('tbl_data_pembayaran')
            ->where('id_bayar', $id_bayar)
            ->update([
                'status_bayar'     => $status_bayar,
                'waktu_pembayaran' => date('Y-m-d H:i:s'),
            ]);

        // Update status pembelian terkait (link via pembayaran ini, scoped per toko)
        $this->db->table('tbl_data_pembelian')
            ->where('nama_pelanggan', $bayar['nama_pelanggan'])
            ->where('nama_produk', $bayar['nama_produk'])
            ->where('sesi_user', $bayar['sesi_user'] ?? '')
            ->where('status_bayar', 'Belum Bayar')
            ->update([
                'status_bayar'     => $status_bayar,
                'waktu_pembelian'  => date('Y-m-d H:i:s'),
            ]);

        return true;
    }

    public function konfirmasi_pembayaran($id_bayar, $id_beli, $nama_pelanggan)    {
        // Update tbl_data_pembayaran
        $this->db->table('tbl_data_pembayaran')
            ->where('id_bayar', $id_bayar)
            ->update([
                'waktu_pembayaran' => date('Y-m-d H:i:s'),
                'status_bayar' => 'Dibayar',
            ]);

        // Update tbl_data_pembelian berdasarkan id_transaksi
        $this->db->table('tbl_data_pembelian')
            ->where('id_beli', $id_beli)
            ->update([
                'waktu_pembelian' => date('Y-m-d H:i:s'),
                'status_bayar' => 'Dibayar',
            ]);

        // Hapus semua data keranjang terkait pelanggan yang statusnya selesai
        $this->db->table('tbl_data_keranjang')
            ->where('nama_pelanggan', $nama_pelanggan)
            ->where('status_keranjang', 'Selesai')
            ->delete();
    }

    public function konfirm_status_bayar($id_bayar, $sesi_user, $status_bayar, $alasan_batal)
    {
        $this->db->table('tbl_data_pembayaran')
            ->where('id_bayar', $id_bayar)
            ->update([
                'sesi_user' => $sesi_user,
                'status_bayar' => $status_bayar,
                'alasan_batal' => $alasan_batal,
            ]);
    }

    public function batalkanTransaksiExpiredByUser($nama_pelanggan)
    {
        $now = date('Y-m-d H:i:s');

        // Ambil semua data pembayaran yang kadaluarsa dan belum dibayar oleh pelanggan
        $expired = $this->where('status_bayar', 'Belum Bayar')
            ->where('batas_waktu_bayar <', $now)
            ->where('nama_pelanggan', $nama_pelanggan)
            ->findAll();

        foreach ($expired as $data) {
            $this->prosesPembatalanExpired($data);
        }
    }

    /**
     * Membatalkan SEMUA transaksi yang melewati batas waktu bayar (dipakai cron job).
     * Tidak bergantung pada pelanggan yang sedang login.
     */
    public function batalkanSemuaTransaksiExpired()
    {
        $now = date('Y-m-d H:i:s');

        $expired = $this->where('status_bayar', 'Belum Bayar')
            ->where('batas_waktu_bayar <', $now)
            ->findAll();

        $total = 0;
        foreach ($expired as $data) {
            $this->prosesPembatalanExpired($data);
            $total++;
        }

        return $total;
    }

    /**
     * Logika pembatalan satu transaksi kadaluarsa: kembalikan stok,
     * hapus pembelian/keranjang/ekspedisi/pembayaran terkait.
     */
    protected function prosesPembatalanExpired($data)
    {
        $nama_pelanggan = $data['nama_pelanggan'];
        $nama_produk    = $data['nama_produk'];

        $pembelianModel = new \App\Models\M_pelanggan_beli();
        $stokModel      = new \App\Models\M_admin_stok();
        $keranjangModel = new \App\Models\M_pelanggan_keranjang();
        $ekspedisiModel = new \App\Models\M_pelanggan_ekspedisi();

        // Ambil semua data pembelian untuk pelanggan dan produk tersebut
        $pembelian_list = $pembelianModel->where([
            'nama_pelanggan' => $nama_pelanggan,
            'nama_produk'    => $nama_produk
        ])->findAll();

        // Kembalikan stok berdasarkan id_stok yang digunakan saat pembelian
        foreach ($pembelian_list as $pembelian) {
            $id_stok = $pembelian['id_stok'];
            $jumlah  = $pembelian['jumlah_produk'];

            // Tambah stok ke id_stok yang sama
            $stokModel->tambah_stok($id_stok, $jumlah);
        }

        // Hapus data keranjang terkait (scoped per data pembayaran)
        $keranjangModel->where([
            'nama_pelanggan' => $nama_pelanggan,
            'nama_produk'    => $nama_produk,
            'status_keranjang' => 'Selesai'
        ])->delete();

        // Hapus data pembelian (scoped by id_beli jika ada, selain itu oleh pembayaran ini)
        if (!empty($data['id_beli'])) {
            $pembelianModel->where([
                'id_beli' => $data['id_beli'],
                'status_bayar' => 'Belum Bayar',
                'sesi_user' => $data['sesi_user'] ?? ''
            ])->delete();
        } else {
            $pembelianModel->where([
                'nama_pelanggan' => $nama_pelanggan,
                'nama_produk'    => $nama_produk,
                'status_bayar' => 'Belum Bayar',
                'sesi_user' => $data['sesi_user'] ?? ''
            ])->delete();
        }

        // Hapus data ekspedisi (scoped by id_ekspedisi dari pembayaran)
        if (!empty($data['id_ekspedisi'])) {
            $ekspedisiModel->where([
                'id_ekspedisi' => $data['id_ekspedisi']
            ])->delete();
        }

        // Hapus data pembayaran (dari model ini sendiri)
        $this->where([
            'id_bayar' => $data['id_bayar']
        ])->delete();

        // Hapus file QR Code jika ada
        $qrFile = FCPATH . 'qr/qr_' . $data['id_bayar'] . '.png';
        if (file_exists($qrFile)) {
            unlink($qrFile);
        }
        // Hapus file snap_url jika ada
        $urlFile = FCPATH . 'qr/qr_' . $data['id_bayar'] . '_url.txt';
        if (file_exists($urlFile)) {
            unlink($urlFile);
        }
    }
}
