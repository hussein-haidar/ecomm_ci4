<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\M_chat;

class ChatCleanup extends BaseCommand
{
    protected $group       = 'Chat';
    protected $name        = 'chat:cleanup';
    protected $description = 'Bersihkan chat lama & batasi jumlah pesan per percakapan.';

    protected $usage       = 'chat:cleanup [usia_bulan] [batas_pesan]';
    protected $arguments   = [
        'usia_bulan'  => 'Hapus pesan lebih tua dari N bulan (default: 6).',
        'batas_pesan' => 'Maksimal pesan tersimpan per percakapan (default: 200).',
    ];
    protected $options = [
        '--bulan' => 'Hapus pesan lebih tua dari N bulan (default: 6).',
        '--batas' => 'Maksimal pesan tersimpan per percakapan (default: 200).',
    ];

    public function run(array $params)
    {
        $chatModel = new M_chat();

        $usia_bulan  = (int)($params[0] ?? CLI::getOption('bulan') ?? 6);
        $batas_pesan = (int)($params[1] ?? CLI::getOption('batas') ?? 200);

        $hapus = $chatModel->hapus_pesan_lama_global($usia_bulan);
        CLI::write("Hapus pesan lebih tua dari {$usia_bulan} bulan: {$hapus} baris.", 'green');

        $konversasi = $chatModel->db->table($chatModel->table)
            ->select('sesi_user, nama_pelanggan, nama_produk')
            ->groupBy('sesi_user, nama_pelanggan, nama_produk')
            ->get()
            ->getResultArray();

        $total = 0;
        foreach ($konversasi as $k) {
            $total += $chatModel->trim_percakapan(
                $k['sesi_user'],
                $k['nama_pelanggan'],
                $k['nama_produk'],
                $batas_pesan
            );
        }

        CLI::write("Pangkas pesan berlebih per percakapan (maks {$batas_pesan}): {$total} baris dihapus.", 'green');
        CLI::write('Pembersihan chat selesai.', 'green');
    }
}
