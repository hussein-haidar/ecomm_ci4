<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Midtrans extends BaseConfig
{
    /**
     * Server key Midtrans diambil dari .env untuk keamanan.
     * Pastikan variabel MIDTRANS_SERVER_KEY terdefinisi di file .env.
     */
    public $serverKey = '';

    public $isProduction = true;

    public $isSanitized = true;

    public $is3ds = true;

    public $notificationUrl = 'pelanggan_kelola_data/midtrans_notification';

    public function __construct()
    {
        parent::__construct();

        $this->serverKey = env('MIDTRANS_SERVER_KEY', '');
        $this->isProduction = (bool) env('MIDTRANS_IS_PRODUCTION', $this->isProduction);
        $this->notificationUrl = env('MIDTRANS_NOTIFICATION_URL', $this->notificationUrl);
    }
}
