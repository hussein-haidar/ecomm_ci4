<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\M_kebijakan_toko;
use App\Models\M_profil_user;

class Kebijakan_toko extends BaseController
{
    protected $M_kebijakan_toko;
    protected $M_profil_user;

    public function __construct()
    {
        $this->M_kebijakan_toko = new M_kebijakan_toko();
        $this->M_profil_user = new M_profil_user();
    }

    // ==================== FAQ ====================

    public function faq()
    {
        $data = [
            'title' => 'Data FAQ (Bantuan)',
            'title2' => 'Kebijakan Toko',
            'tampilkan_varian' => $this->M_profil_user->getTampilanPengaturan()['tampilkan_varian'] ?? 1,
            'faq' => $this->M_kebijakan_toko->get_faq(),
            'isi' => 'kebijakan/v_faq',
        ];
        return view('layout/v_template', $data);
    }

    public function add_faq()
    {
        $data = [
            'title' => 'Tambah FAQ',
            'title2' => 'Kebijakan Toko',
            'isi' => 'kebijakan/v_add_faq',
        ];
        return view('layout/v_template', $data);
    }

    public function save_faq()
    {
        if ($this->validate([
            'kategori' => [
                'label' => 'Kategori',
                'rules' => 'required',
                'errors' => ['required' => '{field} Wajib Dipilih !!!']
            ],
            'pertanyaan' => [
                'label' => 'Pertanyaan',
                'rules' => 'required',
                'errors' => ['required' => '{field} Wajib Diisi !!!']
            ],
            'jawaban' => [
                'label' => 'Jawaban',
                'rules' => 'required',
                'errors' => ['required' => '{field} Wajib Diisi !!!']
            ],
        ])) {
            $this->M_kebijakan_toko->add_faq([
                'sesi_user' => session()->get('toko_sesi_user') ?: session()->get('sesi_user'),
                'kategori' => $this->request->getPost('kategori'),
                'pertanyaan' => $this->request->getPost('pertanyaan'),
                'jawaban' => $this->request->getPost('jawaban'),
                'urutan' => $this->request->getPost('urutan') ?: 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
            session()->setFlashdata('pesan', 'FAQ Berhasil Ditambahkan !!!');
            return redirect()->to(base_url('kebijakan_toko/faq'));
        } else {
            session()->setFlashdata('errors', \Config\Services::validation()->getErrors());
            return redirect()->to(base_url('kebijakan_toko/add_faq'));
        }
    }

    public function edit_faq($id_faq)
    {
        $data = [
            'title' => 'Edit FAQ',
            'title2' => 'Kebijakan Toko',
            'faq' => $this->M_kebijakan_toko->detail_faq($id_faq),
            'isi' => 'kebijakan/v_edit_faq',
        ];
        return view('layout/v_template', $data);
    }

    public function update_faq($id_faq)
    {
        if ($this->validate([
            'kategori' => [
                'label' => 'Kategori',
                'rules' => 'required',
                'errors' => ['required' => '{field} Wajib Dipilih !!!']
            ],
            'pertanyaan' => [
                'label' => 'Pertanyaan',
                'rules' => 'required',
                'errors' => ['required' => '{field} Wajib Diisi !!!']
            ],
            'jawaban' => [
                'label' => 'Jawaban',
                'rules' => 'required',
                'errors' => ['required' => '{field} Wajib Diisi !!!']
            ],
        ])) {
            $this->M_kebijakan_toko->edit_faq($id_faq, [
                'kategori' => $this->request->getPost('kategori'),
                'pertanyaan' => $this->request->getPost('pertanyaan'),
                'jawaban' => $this->request->getPost('jawaban'),
                'urutan' => $this->request->getPost('urutan') ?: 0,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
            session()->setFlashdata('pesan', 'FAQ Berhasil Diubah !!!');
            return redirect()->to(base_url('kebijakan_toko/faq'));
        } else {
            session()->setFlashdata('errors', \Config\Services::validation()->getErrors());
            return redirect()->to(base_url('kebijakan_toko/edit_faq/' . $id_faq));
        }
    }

    public function delete_faq($id_faq)
    {
        $this->M_kebijakan_toko->delete_faq($id_faq);
        session()->setFlashdata('pesan', 'FAQ Berhasil Dihapus !!!');
        return redirect()->to(base_url('kebijakan_toko/faq'));
    }

    // ==================== Syarat & Ketentuan ====================

    public function syarat()
    {
        $data = [
            'title' => 'Data Syarat & Ketentuan',
            'title2' => 'Kebijakan Toko',
            'tampilkan_varian' => $this->M_profil_user->getTampilanPengaturan()['tampilkan_varian'] ?? 1,
            'skt' => $this->M_kebijakan_toko->get_skt(),
            'isi' => 'kebijakan/v_syarat',
        ];
        return view('layout/v_template', $data);
    }

    public function add_syarat()
    {
        $data = [
            'title' => 'Tambah Syarat & Ketentuan',
            'title2' => 'Kebijakan Toko',
            'isi' => 'kebijakan/v_add_syarat',
        ];
        return view('layout/v_template', $data);
    }

    public function save_syarat()
    {
        if ($this->validate([
            'judul' => [
                'label' => 'Judul',
                'rules' => 'required',
                'errors' => ['required' => '{field} Wajib Diisi !!!']
            ],
            'isi' => [
                'label' => 'Isi',
                'rules' => 'required',
                'errors' => ['required' => '{field} Wajib Diisi !!!']
            ],
        ])) {
            $this->M_kebijakan_toko->add_skt([
                'sesi_user' => session()->get('toko_sesi_user') ?: session()->get('sesi_user'),
                'judul' => $this->request->getPost('judul'),
                'isi' => $this->request->getPost('isi'),
                'urutan' => $this->request->getPost('urutan') ?: 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
            session()->setFlashdata('pesan', 'Syarat & Ketentuan Berhasil Ditambahkan !!!');
            return redirect()->to(base_url('kebijakan_toko/syarat'));
        } else {
            session()->setFlashdata('errors', \Config\Services::validation()->getErrors());
            return redirect()->to(base_url('kebijakan_toko/add_syarat'));
        }
    }

    public function edit_syarat($id_skt)
    {
        $data = [
            'title' => 'Edit Syarat & Ketentuan',
            'title2' => 'Kebijakan Toko',
            'skt' => $this->M_kebijakan_toko->detail_skt($id_skt),
            'isi' => 'kebijakan/v_edit_syarat',
        ];
        return view('layout/v_template', $data);
    }

    public function update_syarat($id_skt)
    {
        if ($this->validate([
            'judul' => [
                'label' => 'Judul',
                'rules' => 'required',
                'errors' => ['required' => '{field} Wajib Diisi !!!']
            ],
            'isi' => [
                'label' => 'Isi',
                'rules' => 'required',
                'errors' => ['required' => '{field} Wajib Diisi !!!']
            ],
        ])) {
            $this->M_kebijakan_toko->edit_skt($id_skt, [
                'judul' => $this->request->getPost('judul'),
                'isi' => $this->request->getPost('isi'),
                'urutan' => $this->request->getPost('urutan') ?: 0,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
            session()->setFlashdata('pesan', 'Syarat & Ketentuan Berhasil Diubah !!!');
            return redirect()->to(base_url('kebijakan_toko/syarat'));
        } else {
            session()->setFlashdata('errors', \Config\Services::validation()->getErrors());
            return redirect()->to(base_url('kebijakan_toko/edit_syarat/' . $id_skt));
        }
    }

    public function delete_syarat($id_skt)
    {
        $this->M_kebijakan_toko->delete_skt($id_skt);
        session()->setFlashdata('pesan', 'Syarat & Ketentuan Berhasil Dihapus !!!');
        return redirect()->to(base_url('kebijakan_toko/syarat'));
    }
}