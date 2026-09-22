<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class CancelExpiredOrders extends BaseCommand
{
    protected $group       = 'transaksi';
    protected $name        = 'transaksi:cancel-expired';
    protected $description = 'Membatalkan semua order yang melewati batas waktu bayar (dijalankan via cron).';
    protected $usage       = 'transaksi:cancel-expired';

    public function run(array $params)
    {
        $total = 0;

        try {
            $model = new \App\Models\M_pelanggan_bayar();
            $total = $model->batalkanSemuaTransaksiExpired();

            CLI::write(CLI::color('[OK]', 'green') . ' ' . $total . ' transaksi kadaluarsa berhasil dibatalkan.');
        } catch (\Throwable $e) {
            CLI::error('Gagal membatalkan transaksi: ' . $e->getMessage());
            return EXIT_ERROR;
        }

        return EXIT_SUCCESS;
    }
}