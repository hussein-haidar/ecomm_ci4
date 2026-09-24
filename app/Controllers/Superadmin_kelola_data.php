<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\M_superadmin;
use App\Models\M_notifikasi;

class Superadmin_kelola_data extends BaseController
{
    protected $M_superadmin;
    protected $M_notifikasi;

    public function __construct()
    {
        $this->M_superadmin = new M_superadmin();
        $this->M_notifikasi = new M_notifikasi();
    }

    public function toko()
    {
        $data = [
            'title' => 'Data Toko',
            'title2' => 'Data Toko',
            'semua_toko' => $this->M_superadmin->get_semua_toko(),
            'isi' => 'superadmin/data_website/v_toko',
        ];

        return view('layout/v_template', $data);
    }

    public function detail($id_website)
    {
        $data = [
            'title' => 'Detail Toko',
            'title2' => 'Data Toko',
            'toko' => $this->M_superadmin->detail_toko($id_website),
            'isi' => 'superadmin/data_website/v_detail',
        ];

        return view('layout/v_template', $data);
    }

    /**
     * Verifikasi terpusat: Setujui (aktifkan) / Tolak (nonaktifkan + alasan)
     * POST: id_website, action (setujui|tolak), alasan_tolak (opsional, wajib jika tolak)
     */
    public function update_verifikasi()
    {
        $id_website = $this->request->getPost('id_website');
        $action     = $this->request->getPost('action'); // 'setujui' | 'tolak'
        $alasan     = $this->request->getPost('alasan_tolak');

        if (!$id_website || !in_array($action, ['setujui', 'tolak'])) {
            session()->setFlashdata('pesan', 'Parameter tidak valid!');
            return redirect()->to(base_url('superadmin_kelola_data/toko'));
        }

        $toko = $this->M_superadmin->detail_toko($id_website);
        if (!$toko) {
            session()->setFlashdata('pesan', 'Toko tidak ditemukan!');
            return redirect()->to(base_url('superadmin_kelola_data/toko'));
        }

        $sesi_user = $toko['sesi_user'];

        if ($action === 'setujui') {
            $this->M_superadmin->update_checked($id_website, 2);
            $notifResult = $this->M_notifikasi->create_verifikasi($sesi_user, 2);
            log_message('debug', 'Notifikasi setujui result: ' . ($notifResult ? 'OK' : 'FAIL') . ' for sesi_user: ' . $sesi_user);
            session()->setFlashdata('pesan', 'Toko Berhasil Disetujui & Diaktifkan !!!');
        } else {
            // Tolak: wajib alasan
            if (empty(trim($alasan))) {
                session()->setFlashdata('pesan', 'Alasan penolakan wajib diisi!');
                return redirect()->to(base_url('superadmin_kelola_data/detail/' . $id_website));
            }
            $this->M_superadmin->update_checked($id_website, 1, $alasan);
            $notifResult = $this->M_notifikasi->create_verifikasi($sesi_user, 1, $alasan);
            log_message('debug', 'Notifikasi tolak result: ' . ($notifResult ? 'OK' : 'FAIL') . ' for sesi_user: ' . $sesi_user);
            session()->setFlashdata('pesan', 'Toko Ditolak & Alasan Tersimpan !!!');
        }

        return redirect()->to(base_url('superadmin_kelola_data/toko'));
    }

    // Legacy - tetap bisa dipakai
    public function aktifkan($id_website)
    {
        $this->M_superadmin->update_checked($id_website, 2);
        session()->setFlashdata('pesan', 'Toko Berhasil Diaktifkan !!!');
        return redirect()->to(base_url('superadmin_kelola_data/toko'));
    }

    public function nonaktifkan($id_website)
    {
        $alasan = $this->request->getPost('alasan_tolak') ?? $this->request->getGet('alasan_tolak');
        $this->M_superadmin->update_checked($id_website, 1, $alasan);
        session()->setFlashdata('pesan', 'Toko Berhasil Dinonaktifkan !!!');
        return redirect()->to(base_url('superadmin_kelola_data/toko'));
    }
}