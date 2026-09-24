<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class Filter_toko_aktif implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $tokoSesi = session()->get('toko_sesi_user');
        if (empty($tokoSesi)) {
            return;
        }

        $model = new \App\Models\M_pemilik_website();
        $toko = $model->get_status_website($tokoSesi);

        // Hanya boleh menampilkan halaman toko jika toko berstatus Aktif (2)
        if (!$toko || (int) $toko['is_checked'] !== 2) {
            return redirect()->to(base_url('auth/status_toko'));
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}