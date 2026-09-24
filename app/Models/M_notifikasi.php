<?php

namespace App\Models;

use CodeIgniter\Model;

class M_notifikasi extends Model
{
    protected $table = 'tbl_notifikasi';
    protected $primaryKey = 'id_notifikasi';
    protected $allowedFields = ['sesi_user', 'judul', 'isi', 'tipe', 'dibaca', 'created_at'];
    protected $useTimestamps = false;

    public function create_verifikasi($sesi_user, $status, $alasan = null)
    {
        if ($status === 2) { // Setujui
            $judul = 'Toko Disetujui';
            $isi = "Selamat! Toko Anda telah <strong>disetujui</strong> oleh Superadmin dan kini berstatus <strong>Aktif</strong>. Anda sudah bisa login ke dashboard dan mengelola toko.";
        } else { // Tolak (1)
            $judul = 'Toko Ditolak';
            $isi = "Mohon maaf, toko Anda <strong>ditolak</strong> oleh Superadmin. Status: <strong>Nonaktif</strong>.";
            if ($alasan) {
                $isi .= "<br><br><strong>Alasan:</strong><br>" . nl2br(htmlspecialchars($alasan, ENT_QUOTES, 'UTF-8'));
            }
            $isi .= "<br><br>Anda dapat memperbaiki dan mendaftar kembali, atau menghubungi Superadmin untuk klarifikasi.";
        }

        return $this->insert([
            'sesi_user' => $sesi_user,
            'judul' => $judul,
            'isi' => $isi,
            'tipe' => 'verifikasi',
            'dibaca' => 0,
        ]);
    }

    public function get_by_user($sesi_user, $limit = 20, $offset = 0)
    {
        return $this->where('sesi_user', $sesi_user)
            ->orderBy('created_at', 'DESC')
            ->limit($limit, $offset)
            ->findAll();
    }

    public function count_unread($sesi_user)
    {
        return $this->where('sesi_user', $sesi_user)
            ->where('dibaca', 0)
            ->countAllResults();
    }

    public function mark_as_read($id_notifikasi, $sesi_user)
    {
        return $this->where('id_notifikasi', $id_notifikasi)
            ->where('sesi_user', $sesi_user)
            ->update(['dibaca' => 1]);
    }

    public function mark_all_read($sesi_user)
    {
        return $this->where('sesi_user', $sesi_user)
            ->update(['dibaca' => 1]);
    }
}