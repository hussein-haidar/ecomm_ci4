<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\M_platform_kebijakan;

class Superadmin_kebijakan_platform extends BaseController
{
    protected $M_platform_kebijakan;

    public function __construct()
    {
        $this->M_platform_kebijakan = new M_platform_kebijakan();
    }

    // ==================== FAQ Platform ====================

    public function faq()
    {
        $data = [
            'title' => 'Data FAQ (Bantuan) Platform',
            'title2' => 'Kebijakan Platform',
            'faq' => $this->M_platform_kebijakan->get_all_faq(),
            'isi' => 'superadmin/kebijakan/v_faq',
        ];
        return view('layout/v_template', $data);
    }

    public function add_faq()
    {
        $data = [
            'title' => 'Tambah FAQ Platform',
            'title2' => 'Kebijakan Platform',
            'isi' => 'superadmin/kebijakan/v_add_faq',
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
            $this->M_platform_kebijakan->add_faq([
                'kategori' => $this->request->getPost('kategori'),
                'pertanyaan' => $this->request->getPost('pertanyaan'),
                'jawaban' => $this->request->getPost('jawaban'),
                'urutan' => $this->request->getPost('urutan') ?: 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
            session()->setFlashdata('pesan', 'FAQ Platform Berhasil Ditambahkan !!!');
            return redirect()->to(base_url('superadmin_kebijakan_platform/faq'));
        } else {
            session()->setFlashdata('errors', \Config\Services::validation()->getErrors());
            return redirect()->to(base_url('superadmin_kebijakan_platform/add_faq'));
        }
    }

    public function edit_faq($id_faq)
    {
        $data = [
            'title' => 'Edit FAQ Platform',
            'title2' => 'Kebijakan Platform',
            'faq' => $this->M_platform_kebijakan->detail_faq($id_faq),
            'isi' => 'superadmin/kebijakan/v_edit_faq',
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
            $this->M_platform_kebijakan->edit_faq($id_faq, [
                'kategori' => $this->request->getPost('kategori'),
                'pertanyaan' => $this->request->getPost('pertanyaan'),
                'jawaban' => $this->request->getPost('jawaban'),
                'urutan' => $this->request->getPost('urutan') ?: 0,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
            session()->setFlashdata('pesan', 'FAQ Platform Berhasil Diubah !!!');
            return redirect()->to(base_url('superadmin_kebijakan_platform/faq'));
        } else {
            session()->setFlashdata('errors', \Config\Services::validation()->getErrors());
            return redirect()->to(base_url('superadmin_kebijakan_platform/edit_faq/' . $id_faq));
        }
    }

    public function delete_faq($id_faq)
    {
        $this->M_platform_kebijakan->delete_faq($id_faq);
        session()->setFlashdata('pesan', 'FAQ Platform Berhasil Dihapus !!!');
        return redirect()->to(base_url('superadmin_kebijakan_platform/faq'));
    }

    // ==================== Syarat & Ketentuan Platform ====================

    public function syarat()
    {
        $data = [
            'title' => 'Data Syarat & Ketentuan Platform',
            'title2' => 'Kebijakan Platform',
            'skt' => $this->M_platform_kebijakan->get_all_skt(),
            'isi' => 'superadmin/kebijakan/v_syarat',
        ];
        return view('layout/v_template', $data);
    }

    public function add_syarat()
    {
        $data = [
            'title' => 'Tambah Syarat & Ketentuan Platform',
            'title2' => 'Kebijakan Platform',
            'isi' => 'superadmin/kebijakan/v_add_syarat',
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
            $this->M_platform_kebijakan->add_skt([
                'judul' => $this->request->getPost('judul'),
                'isi' => $this->request->getPost('isi'),
                'urutan' => $this->request->getPost('urutan') ?: 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
            session()->setFlashdata('pesan', 'Syarat & Ketentuan Platform Berhasil Ditambahkan !!!');
            return redirect()->to(base_url('superadmin_kebijakan_platform/syarat'));
        } else {
            session()->setFlashdata('errors', \Config\Services::validation()->getErrors());
            return redirect()->to(base_url('superadmin_kebijakan_platform/add_syarat'));
        }
    }

    public function edit_syarat($id_skt)
    {
        $data = [
            'title' => 'Edit Syarat & Ketentuan Platform',
            'title2' => 'Kebijakan Platform',
            'skt' => $this->M_platform_kebijakan->detail_skt($id_skt),
            'isi' => 'superadmin/kebijakan/v_edit_syarat',
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
            $this->M_platform_kebijakan->edit_skt($id_skt, [
                'judul' => $this->request->getPost('judul'),
                'isi' => $this->request->getPost('isi'),
                'urutan' => $this->request->getPost('urutan') ?: 0,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
            session()->setFlashdata('pesan', 'Syarat & Ketentuan Platform Berhasil Diubah !!!');
            return redirect()->to(base_url('superadmin_kebijakan_platform/syarat'));
        } else {
            session()->setFlashdata('errors', \Config\Services::validation()->getErrors());
            return redirect()->to(base_url('superadmin_kebijakan_platform/edit_syarat/' . $id_skt));
        }
    }

    public function delete_syarat($id_skt)
    {
        $this->M_platform_kebijakan->delete_skt($id_skt);
        session()->setFlashdata('pesan', 'Syarat & Ketentuan Platform Berhasil Dihapus !!!');
        return redirect()->to(base_url('superadmin_kebijakan_platform/syarat'));
    }
}