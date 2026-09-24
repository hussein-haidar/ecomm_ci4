<?php

use App\Models\M_pemilik_website;

if (!function_exists('get_data_toko')) {
    function get_data_toko()
    {
        $model = new M_pemilik_website();

        // Jika ada toko_sesi_user di session, filter berdasarkan itu
        $tokoSesiUser = session()->get('toko_sesi_user');

        if (!empty($tokoSesiUser)) {
            $data = $model->get_website_by_nama($tokoSesiUser);
        } else {
            $data = $model->get_website_aktif();
        }

        if (!empty($data)) {
            return [
                'nama_toko' => $data[0]['nama_toko'],
                'alamat_pusat' => $data[0]['alamat_pusat'],
                'alamat_cabang' => $data[0]['alamat_cabang'],
                'wa_pusat' => $data[0]['wa_pusat'],
                'wa_cabang' => $data[0]['wa_cabang'],
                'footer_title' => $data[0]['footer_title'],
                'link_IG' => $data[0]['link_IG'],
                'link_FB' => $data[0]['link_FB'],
                'link_Tiktok' => $data[0]['link_Tiktok'],
                'logo_website' => $data[0]['logo_website'],
                'bgd_web' => $data[0]['bgd_web'],
                'tema_website' => $data[0]['tema_website'] ?? 'default',
                'email_toko' => strtolower(str_replace(' ', '', $data[0]['nama_toko'])) . '@gmail.com',
                'is_checked' => $data[0]['is_checked']
            ];
        } else {
            return ['nama_toko' => 'Nama Toko Default'];
        }
    }
}
