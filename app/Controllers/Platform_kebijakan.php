<?php

namespace App\Controllers;

use App\Models\M_platform_kebijakan;

class Platform_kebijakan extends BaseController
{
    protected $M_platform_kebijakan;

    public function __construct()
    {
        $this->M_platform_kebijakan = new M_platform_kebijakan();
    }

    public function bantuan()
    {
        $faq_data = $this->M_platform_kebijakan->get_all_faq();

        $data = [
            'title' => 'Bantuan / FAQ Platform',
            'title2' => 'Bantuan / FAQ Platform',
            'faq_data' => $faq_data,
        ];

        return view('platform/v_bantuan', $data);
    }

    public function syaket()
    {
        $skt_data = $this->M_platform_kebijakan->get_all_skt();

        $data = [
            'title' => 'Syarat & Ketentuan Platform',
            'title2' => 'Syarat & Ketentuan Platform',
            'skt_data' => $skt_data,
        ];

        return view('platform/v_syaket', $data);
    }
}