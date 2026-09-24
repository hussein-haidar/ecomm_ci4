<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class Filter_superadmin implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (session()->get('level') == "") {
            session()->setFlashdata('pesan', 'Anda Tidak Memiliki Akses Website, Silahkan Login Terlebih Dahulu !!!');
            return redirect()->to(base_url('auth/login_superadmin'));
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        $level = (int) session()->get('level');
        if ($level === 0) {
            return;
        }

        switch ($level) {
            case 1:
                $dest = base_url('home_pemilik');
                break;
            case 2:
                $dest = base_url('home_admin');
                break;
            default:
                $dest = base_url('auth/login_superadmin');
                break;
        }

        session()->setFlashdata('message', 'Anda Tidak Memiliki Akses Halaman Yang Dituju !!!');
        return redirect()->to($dest);
    }
}