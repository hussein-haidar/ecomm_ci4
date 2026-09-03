<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class Filter_toko implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Tidak ada aksi khusus, semua pengunjung diperbolehkan
        if (session()->get('level') == "") {
            session()->setFlashdata('pesan', 'Anda Tidak Memiliki Akses Website, Silahkan Login Terlebih Dahulu !!!');
            return redirect()->to(base_url('home_toko/index'));
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here

    }
}
