<?php

namespace App\Models;

use CodeIgniter\Model;

class M_chat extends Model
{
    protected $table      = 'tbl_chat';
    protected $primaryKey = 'id_chat';
    protected $allowedFields = [
        'sesi_user', 'nama_pelanggan', 'nama_produk', 'id_stok', 'pengirim', 'nama_pengirim', 'pesan', 'dibaca', 'waktu_chat'
    ];

    public function get_konversasi($sesi_user, $nama_pelanggan, $nama_produk)
    {
        return $this->where('sesi_user', $sesi_user)
            ->where('nama_pelanggan', $nama_pelanggan)
            ->where('nama_produk', $nama_produk)
            ->orderBy('id_chat', 'ASC')
            ->get()
            ->getResultArray();
    }

    public function get_inbox_admin($sesi_user)
    {
        $rows = $this->db->table('tbl_chat')
            ->select('nama_pelanggan, nama_produk, id_stok, MAX(id_chat) as id_terakhir')
            ->where('sesi_user', $sesi_user)
            ->groupBy('nama_pelanggan, nama_produk')
            ->orderBy('id_terakhir', 'DESC')
            ->get()
            ->getResultArray();

        $result = [];
        foreach ($rows as $row) {
            $last = $this->where('id_chat', $row['id_terakhir'])->get()->getRowArray();
            $unread = $this->where('sesi_user', $sesi_user)
                ->where('nama_pelanggan', $row['nama_pelanggan'])
                ->where('nama_produk', $row['nama_produk'])
                ->where('pengirim', 'pelanggan')
                ->where('dibaca', 0)
                ->countAllResults();
            $result[] = [
                'nama_pelanggan'    => $row['nama_pelanggan'],
                'nama_produk'       => $row['nama_produk'],
                'nama_pengirim'     => $last['nama_pengirim'] ?? '',
                'pesan_terakhir'    => $last['pesan'] ?? '',
                'pengirim_terakhir' => $last['pengirim'] ?? '',
                'waktu_terakhir'    => $last['waktu_chat'] ?? '',
                'unread'            => $unread,
            ];
        }
        return $result;
    }

    public function get_inbox_pelanggan($sesi_user, $nama_pelanggan)
    {
        $rows = $this->db->table('tbl_chat')
            ->select('nama_produk, id_stok, MAX(id_chat) as id_terakhir')
            ->where('sesi_user', $sesi_user)
            ->where('nama_pelanggan', $nama_pelanggan)
            ->groupBy('nama_produk')
            ->orderBy('id_terakhir', 'DESC')
            ->get()
            ->getResultArray();

        $result = [];
        foreach ($rows as $row) {
            $last = $this->where('id_chat', $row['id_terakhir'])->get()->getRowArray();
            $unread = $this->where('sesi_user', $sesi_user)
                ->where('nama_pelanggan', $nama_pelanggan)
                ->where('nama_produk', $row['nama_produk'])
                ->where('pengirim', 'admin')
                ->where('dibaca', 0)
                ->countAllResults();
            $result[] = [
                'nama_produk'       => $row['nama_produk'],
                'id_stok'           => $row['id_stok'] ?? 0,
                'nama_pengirim'     => $last['nama_pengirim'] ?? '',
                'pesan_terakhir'    => $last['pesan'] ?? '',
                'pengirim_terakhir' => $last['pengirim'] ?? '',
                'waktu_terakhir'    => $last['waktu_chat'] ?? '',
                'unread'            => $unread,
            ];
        }
        return $result;
    }

    public function tandai_dibaca($sesi_user, $nama_pelanggan, $nama_produk, $pengirim)
    {
        return $this->db->table('tbl_chat')
            ->where('sesi_user', $sesi_user)
            ->where('nama_pelanggan', $nama_pelanggan)
            ->where('nama_produk', $nama_produk)
            ->where('pengirim', $pengirim)
            ->where('dibaca', 0)
            ->set('dibaca', 1)
            ->update();
    }

    public function count_unread_admin($sesi_user)
    {
        return $this->where('sesi_user', $sesi_user)
            ->where('pengirim', 'pelanggan')
            ->where('dibaca', 0)
            ->countAllResults();
    }

    public function count_unread_pelanggan($sesi_user, $nama_pelanggan)
    {
        return $this->where('sesi_user', $sesi_user)
            ->where('nama_pelanggan', $nama_pelanggan)
            ->where('pengirim', 'admin')
            ->where('dibaca', 0)
            ->countAllResults();
    }

    public function hapus_pesan_lama_global($usia_bulan = 6)
    {
        return $this->db->table($this->table)
            ->where('waktu_chat <', date('Y-m-d H:i:s', strtotime("-{$usia_bulan} months")))
            ->delete();
    }

    public function hapus_pesan_lama_toko($sesi_user, $usia_bulan = 6)
    {
        return $this->db->table($this->table)
            ->where('sesi_user', $sesi_user)
            ->where('waktu_chat <', date('Y-m-d H:i:s', strtotime("-{$usia_bulan} months")))
            ->delete();
    }

    public function trim_percakapan($sesi_user, $nama_pelanggan, $nama_produk, $batas_pesan = 200)
    {
        $id_lama = $this->db->table($this->table)
            ->select('id_chat')
            ->where('sesi_user', $sesi_user)
            ->where('nama_pelanggan', $nama_pelanggan)
            ->where('nama_produk', $nama_produk)
            ->orderBy('id_chat', 'DESC')
            ->limit(1000, $batas_pesan)
            ->get()
            ->getResultArray();

        if (empty($id_lama)) {
            return 0;
        }

        $this->db->table($this->table)
            ->whereIn('id_chat', array_column($id_lama, 'id_chat'))
            ->delete();

        return $this->db->affectedRows();
    }
}
