<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class Filter_pemilik implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Do something here
        if (session()->get('level') == "") {
            session()->setFlashdata('pesan', 'Anda Tidak Memiliki Akses Website, Silahkan Login Terlebih Dahulu !!!');
            return redirect()->to(base_url('auth/login_user'));
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        $level = session()->get('level');
        if (!empty($level) && (int) $level !== 1) {
            session()->setFlashdata('message', 'Anda Tidak Memiliki Akses Halaman Yang Dituju !!!');
            return redirect()->to(base_url('home_pemilik'));
        }
    }
}
