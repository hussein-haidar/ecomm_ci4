<?php

namespace App\Models;

use CodeIgniter\Model;

class M_admin_stok extends Model
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

    public function get_stok()
    {
        $sesi_user = session()->get('sesi_user');
        return $this->db->table('tbl_stok_produk')
        ->select('tbl_stok_produk.*, tbl_stok_produk.nama_produk, tbl_data_produk.*')
        ->join('tbl_jenis_produk', 'tbl_jenis_produk.jenis_produk = tbl_stok_produk.jenis_produk', 'left')
        ->join('tbl_data_produk', 'tbl_data_produk.nama_produk = tbl_stok_produk.nama_produk', 'left')
            ->where('tbl_stok_produk.sesi_user', $sesi_user)
            ->orderBy('id_stok', 'DESC') // Urutkan berdasarkan id_stok
            ->get()->getResultArray();
    }

    public function get_tot_stok()
    {
        $sesi_user = session()->get('sesi_user');
        return $this->db->table('tbl_stok_produk')
        ->select('
            tbl_stok_produk.*, 
            tbl_stok_produk.nama_produk, 
            tbl_data_produk.*, 
            SUM(tbl_stok_produk.jumlah_stok_produk) as total_stok,
            SUM(tbl_stok_produk.jumlah_stok_produk * tbl_data_produk.harga_produk) AS total_harga
        ')
        ->join('tbl_data_produk', 'tbl_data_produk.nama_produk = tbl_stok_produk.nama_produk', 'left')
        ->groupBy('tbl_stok_produk.nama_produk') // Group by nama_produk untuk menghitung total stok per produk
            ->where('tbl_stok_produk.sesi_user', $sesi_user)
            ->orderBy('id_stok', 'DESC') // Urutkan berdasarkan id_stok
            ->get()
            ->getResultArray();
    }

    public function get_stok_by_name($nama_produk)
    {
        return $this->db->table('tbl_stok_produk')
        ->select('tbl_stok_produk.*, tbl_data_produk.*')
        ->join('tbl_data_produk', 'tbl_data_produk.nama_produk = tbl_stok_produk.nama_produk', 'left')
        ->where('tbl_stok_produk.nama_produk', $nama_produk) // Filter berdasarkan nama produk  
            ->orderBy('id_stok', 'DESC') // Urutkan berdasarkan ID stok (ID terbaru pertama)  
            ->get()
            ->getResultArray();
    }

    public function get_user_by_id($sesi_user)
    {
        return $this->db->table('tbl_data_user')
            ->select('sesi_user')
            ->where('sesi_user', $sesi_user)
            ->get()
            ->getRowArray();
    }

    public function get_produk()
    {
        $sesi_user = session()->get('sesi_user');
        return $this->db->table('tbl_data_produk')
            ->select('tbl_data_produk.*, tbl_varian_produk.varian_produk, tbl_jenis_produk.jenis_produk')
            ->join('tbl_varian_produk', 'tbl_varian_produk.id_varian = tbl_data_produk.id_varian', 'left')
            ->join('tbl_jenis_produk', 'tbl_jenis_produk.id_jenis = tbl_data_produk.id_jenis', 'left')
            ->where('tbl_data_produk.sesi_user', $sesi_user) // Hanya ambil data yang belum dihapus
            ->where('tbl_data_produk.deleted_at', 0) // Hanya ambil data yang belum dihapus
            ->orderBy('id_produk', 'DESC')
            ->get()->getResultArray();
    }

    // Data Satuan Produk
    public function get_satuan()
    {
        $sesi_user = session()->get('sesi_user');
        return $this->db->table('tbl_satuan_produk')
            ->where('sesi_user', $sesi_user)
            ->where('deleted_at', 0) // Filter hanya data yang belum dihapus
            ->get()
            ->getResultArray();
    }

    public function kurangi_stok_fifo($nama_produk, $jumlah_produk)
    {
        $sesi_user = session()->get('toko_sesi_user') ?? session()->get('sesi_user');
        $total_harga = 0;
        $total_berat = 0;
        $stok_digunakan = [];

        $db = $this->db;
        $db->transBegin();

        try {
            // Ambil data stok produk dengan row lock (FOR UPDATE) agar tidak
            // terjadi race condition saat dua transaksi mengurangi stok bersamaan.
            // Catatan: membutuhkan engine InnoDB.
            $sql = "SELECT * FROM tbl_stok_produk
                    WHERE nama_produk = ? AND sesi_user = ? AND jumlah_stok_produk > 0
                    ORDER BY tanggal_masuk_produk ASC
                    FOR UPDATE";
            $stokList = $db->query($sql, [$nama_produk, $sesi_user])->getResultArray();

            if (empty($stokList)) {
                $db->transRollback();
                return [
                    'success' => false,
                    'message' => 'Stok tidak tersedia untuk produk: ' . $nama_produk
                ];
            }

            $sisa = $jumlah_produk;

            // Iterasi untuk mengurangi stok menggunakan metode FIFO
            foreach ($stokList as $stok) {
                if ($sisa <= 0) break;

                $id_stok = $stok['id_stok'];
                $jumlah_stok = $stok['jumlah_stok_produk'];
                $harga = $stok['harga_produk'];
                $berat = $stok['berat_produk'];

                if ($jumlah_stok >= $sisa) {
                    // Kurangi sesuai sisa
                    $this->edit([
                        'id_stok' => $id_stok,
                        'jumlah_stok_produk' => $jumlah_stok - $sisa,
                        'nama_produk' => $nama_produk, // Pastikan nama produk ada untuk perhitungan edit
                    ]);
                    $total_harga += $sisa * $harga;
                    $total_berat += $sisa * $berat;

                    $stok_digunakan[] = [
                        'id_stok' => $id_stok,
                        'jumlah_diambil' => $sisa,
                        'harga' => $harga,
                        'berat' => $berat
                    ];
                    $sisa = 0;
                } else {
                    // Kurangi seluruh stok ini
                    $this->edit([
                        'id_stok' => $id_stok,
                        'jumlah_stok_produk' => 0,
                        'nama_produk' => $nama_produk, // Pastikan nama produk ada untuk perhitungan edit
                    ]);
                    $total_harga += $jumlah_stok * $harga;
                    $total_berat += $jumlah_stok * $berat;

                    $stok_digunakan[] = [
                        'id_stok' => $id_stok,
                        'jumlah_diambil' => $jumlah_stok,
                        'harga' => $harga,
                        'berat' => $berat
                    ];
                    $sisa -= $jumlah_stok;
                }
            }

            // Jika stok masih kurang, batalkan transaksi
            if ($sisa > 0) {
                $db->transRollback();
                return [
                    'success' => false,
                    'message' => 'Stok tidak mencukupi untuk produk: ' . $nama_produk
                ];
            }

            $db->transCommit();

            return [
                'success' => true,
                'total_harga' => $total_harga,
                'total_berat' => $total_berat,
                'stok_digunakan' => $stok_digunakan
            ];
        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'FIFO stok gagal untuk ' . $nama_produk . ': ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Gagal mengurangi stok: ' . $e->getMessage()
            ];
        }
    }

    public function tambah_stok($id_stok, $jumlah)
    {
        // Cari data stok berdasarkan id_stok
        $produk = $this->find($id_stok);

        if ($produk) {
            // Cek jika jumlah yang ditambahkan lebih besar dari 0
            if ($jumlah <= 0) {
                return [
                    'success' => false,
                    'message' => 'Jumlah stok yang ditambahkan harus lebih besar dari 0.'
                ];
            }

            // Ambil data yang diperlukan dari produk
            $harga_produk    = $produk['harga_produk'] ?? 0;
            $berat_produk    = $produk['berat_produk'] ?? 0;
            $stok_lama       = $produk['jumlah_stok_produk'] ?? 0;
            $nama_produk     = $produk['nama_produk'] ?? 'Produk Tidak Ditemukan';  // Ambil nama produk

            // Hitung jumlah stok baru
            $stok_baru       = $stok_lama + $jumlah;
            // Hitung total harga dan total berat berdasarkan stok baru
            $total_harga     = $harga_produk * $stok_baru;
            $total_berat     = $berat_produk * $stok_baru;

            // Data yang akan diupdate
            $update_data = [
                'id_stok'           => $id_stok,
                'nama_produk'       => $nama_produk,  // Menambahkan nama produk yang diperoleh
                'jumlah_stok_produk' => $stok_baru,
                'total_harga'        => $total_harga,
                'total_berat'        => $total_berat
            ];

            // Panggil fungsi edit untuk memperbarui stok
            $this->edit($update_data);

            return [
                'success' => true,
                'message' => 'Stok berhasil ditambahkan.'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Produk dengan ID stok ' . $id_stok . ' tidak ditemukan.'
            ];
        }
    }

    public function detailStok($id_stok)
    {
        return $this->db->table('tbl_stok_produk')
            ->where('id_stok', $id_stok)
            ->get()->getRowArray();
    }

    public function add($data)
    {
        // Ambil harga dan berat produk berdasarkan nama_produk
        $produk = $this->db->table('tbl_data_produk')
        ->select('harga_produk, berat_produk') // Ambil juga berat_produk
        ->where('nama_produk', $data['nama_produk'])
        ->get()
            ->getRowArray();

        // Pastikan harga dan berat produk ditemukan
        if (!$produk) {
            throw new \Exception("Data produk tidak ditemukan untuk " . $data['nama_produk']);
        }

        // Hitung total_harga
        $harga_produk = $produk['harga_produk'] ?? 0;
        $jumlah_stok = $data['jumlah_stok_produk'] ?? 0;
        $total_harga = $harga_produk * $jumlah_stok;

        // Hitung total_berat
        $berat_produk = $produk['berat_produk'] ?? 0; // Ambil berat produk
        $total_berat = $berat_produk * $jumlah_stok; // Total berat berdasarkan jumlah stok

        // Tambahkan total_harga dan total_berat ke data
        $data['total_harga'] = $total_harga;
        $data['total_berat'] = $total_berat;

        // Masukkan data ke database
        $this->db->table('tbl_stok_produk')
        ->insert($data);
    }

    public function edit($data)
    {
        // Ambil harga dan berat produk berdasarkan nama_produk
        $produk = $this->db->table('tbl_data_produk')
        ->select('harga_produk, berat_produk') // Ambil juga berat_produk
        ->where('nama_produk', $data['nama_produk'])
        ->get()
            ->getRowArray();

        if (!$produk) {
            log_message('error', 'Data produk tidak ditemukan untuk ' . ($data['nama_produk'] ?? 'unknown'));
            return;
        }
        $harga_produk = $produk['harga_produk'] ?? 0;
        $jumlah_stok = $data['jumlah_stok_produk'] ?? 0;
        $total_harga = $harga_produk * $jumlah_stok;

        // Hitung ulang total_berat
        $berat_produk = $produk['berat_produk'] ?? 0; // Ambil berat produk
        $total_berat = $berat_produk * $jumlah_stok; // Total berat berdasarkan jumlah stok

        // Tambahkan total_harga dan total_berat ke data
        $data['total_harga'] = $total_harga;
        $data['total_berat'] = $total_berat;

        // Update data di database
        $this->db->table('tbl_stok_produk')
        ->where('id_stok', $data['id_stok'])
        ->update($data);
    }

    public function delete_data($data)
    {
        $this->db->table('tbl_stok_produk')
            ->where('id_stok', $data['id_stok'])
            ->delete($data);
    }
}
