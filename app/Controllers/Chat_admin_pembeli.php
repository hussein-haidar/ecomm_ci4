<?php

namespace App\Controllers;

use App\Models\M_chat;
use App\Models\M_home_toko;

class Chat_admin_pembeli extends BaseController
{
    protected function isAdminToko()
    {
        return (int)session()->get('level') === 2
            && session()->get('sesi_user')
            && session()->get('nama_lengkap');
    }

    protected function isPelanggan()
    {
        return (int)session()->get('level') === 3
            && session()->get('nama_pelanggan')
            && session()->get('toko_sesi_user');
    }

    public function index()
    {
        if (!$this->isAdminToko()) {
            return redirect()->to(base_url('auth/login_user'));
        }

        $chatModel = new M_chat();
        $data = [
            'title'     => 'Inbox Chat Pembeli',
            'title2'    => 'Chat',
            'chat_list' => $chatModel->get_inbox_admin(session()->get('sesi_user')),
            'isi'       => 'pemilik/chat/v_inbox',
        ];
        return view('layout/v_template', $data);
    }

    public function buka($nama_pelanggan, $nama_produk)
    {
        if (!$this->isAdminToko()) {
            return redirect()->to(base_url('auth/login_user'));
        }
        if (empty($nama_pelanggan) || empty($nama_produk)) {
            return redirect()->to(base_url('chat_admin_pembeli/index'));
        }

        $sesi_user = session()->get('sesi_user');
        $chatModel = new M_chat();
        $chatModel->tandai_dibaca($sesi_user, $nama_pelanggan, $nama_produk, 'pelanggan');

        $produkModel = new M_home_toko();
        $produk = $produkModel->detailStok($nama_produk);
        $data = [
            'title'          => 'Chat Pembeli',
            'title2'         => 'Chat',
            'nama_pelanggan' => $nama_pelanggan,
            'nama_produk'    => $nama_produk,
            'id_stok'        => $produk['id_stok'] ?? 0,
            'produk'         => $produk,
            'messages'       => $chatModel->get_konversasi($sesi_user, $nama_pelanggan, $nama_produk),
            'isi'            => 'pemilik/chat/v_chat',
        ];
        return view('layout/v_template', $data);
    }

    public function produk($nama_produk)
    {
        return redirect()->to(base_url('home_toko/index'));
    }

    public function riwayat()
    {
        return redirect()->to(base_url('home_toko/index'));
    }

    public function kirim()
    {
        $session = session();
        if ($this->isPelanggan()) {
            $sesi_user = $session->get('toko_sesi_user');
            $nama_pelanggan = $session->get('nama_pelanggan');
            $pengirim = 'pelanggan';
            $nama_pengirim = $nama_pelanggan;
        } elseif ($this->isAdminToko()) {
            $sesi_user = $session->get('sesi_user');
            $nama_pelanggan = $this->request->getPost('nama_pelanggan');
            $pengirim = 'admin';
            $nama_pengirim = $session->get('nama_lengkap') ?: 'Admin Toko';
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Anda belum login.']);
        }

        $nama_produk = $this->request->getPost('nama_produk');
        $id_stok = $this->request->getPost('id_stok') ?? 0;
        $pesan = trim((string)$this->request->getPost('pesan'));

        if (empty($pesan)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Pesan tidak boleh kosong.']);
        }
        if (empty($nama_pelanggan) || empty($nama_produk)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Data tidak lengkap.']);
        }

        $chatModel = new M_chat();
        $chatModel->insert([
            'sesi_user'      => $sesi_user,
            'nama_pelanggan' => $nama_pelanggan,
            'nama_produk'    => $nama_produk,
            'id_stok'        => $id_stok ?? 0,
            'pengirim'       => $pengirim,
            'nama_pengirim'  => $nama_pengirim,
            'pesan'          => $pesan,
            'dibaca'         => 0,
            'waktu_chat'     => date('Y-m-d H:i:s'),
        ]);

        $chatModel->hapus_pesan_lama_toko($sesi_user, 6);
        $chatModel->trim_percakapan($sesi_user, $nama_pelanggan, $nama_produk, 200);

        return $this->response->setJSON(['success' => true]);
    }

    public function api_messages($nama_pelanggan, $nama_produk)
    {
        $session = session();
        if ($this->isPelanggan()) {
            $sesi_user = $session->get('toko_sesi_user');
            $nama_pelanggan = $session->get('nama_pelanggan');
            $pengirim_lawan = 'admin';
        } elseif ($this->isAdminToko()) {
            $sesi_user = $session->get('sesi_user');
            $pengirim_lawan = 'pelanggan';
        } else {
            return $this->response->setJSON(['messages' => []]);
        }

        $chatModel = new M_chat();
        $chatModel->tandai_dibaca($sesi_user, $nama_pelanggan, $nama_produk, $pengirim_lawan);
        $messages = $chatModel->get_konversasi($sesi_user, $nama_pelanggan, $nama_produk);

        return $this->response->setJSON(['messages' => $messages]);
    }

    public function api_inbox()
    {
        $session = session();
        if (!$this->isPelanggan()) {
            return $this->response->setJSON(['conversations' => []]);
        }

        $chatModel = new M_chat();
        $inbox = $chatModel->get_inbox_pelanggan(
            $session->get('toko_sesi_user'),
            $session->get('nama_pelanggan')
        );

        return $this->response->setJSON(['conversations' => $inbox]);
    }
}
