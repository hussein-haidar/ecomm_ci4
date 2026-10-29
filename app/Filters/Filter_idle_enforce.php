<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class Filter_idle_enforce implements FilterInterface
{
    public function __construct()
    {
        // Response diinisialisasi di method before, bukan di constructor
    }

    /**
     * Batas idle per-role (detik).
     *  - level 3 (pelanggan/pembeli) : 10 menit
     *  - level 0,1,2 (superadmin/pemilik/admin) : 1 hari
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $level = session()->get('level');

        // hanya berlaku untuk yang sudah login
        $isAuthed = session()->get('user_logged_in') === true
            || in_array($level, ['0', '1', '2', '3'], true)
            || in_array((int) $level, [0, 1, 2, 3], true);

        if (!$isAuthed) {
            return;
        }

        $lv = (int) $level;

        // batas idle
        if ($lv === 3) {
            $idleLimit = 600;      // 10 menit
            $logoutUrl = base_url('auth/logout_pelanggan');
        } else {
            $idleLimit = 86400;   // 1 hari
            $logoutUrl = $lv === 0
                ? base_url('auth/logout_user')
                : base_url('auth/logout_user');
        }

        $last = session()->get('last_activity_at');

        if (empty($last)) {
            // pertama kali: catat sekarang
            session()->set('last_activity_at', time());
            return;
        }

        $elapsed = time() - (int) $last;

        if ($elapsed > $idleLimit) {
            // idle melebihi batas -> flush & redirect
            $this->flushSession();

            // Check if request expects JSON (API call)
            $isAjax = $request->isAJAX();
            $accept = $request->header('Accept');
            $expectsJson = $isAjax || ($accept && stripos($accept->getValue(), 'application/json') !== false);

            if ($expectsJson) {
                return \Config\Services::response()
                    ->setStatusCode(401)
                    ->setJSON(['success' => false, 'message' => 'Sesi berakhir. Silakan login kembali.']);
            }

            return redirect()->to($logoutUrl);
        }

        // masih aktif: refresh
        session()->set('last_activity_at', time());
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // tidak perlu
    }

    private function flushSession()
    {
        session()->remove([
            'username', 'id_user', 'level', 'user_logged_in', 'nama_lengkap',
            'sesi_user', 'toko_sesi_user', 'last_login', 'login_expires_at',
            'nama_pelanggan', 'id_pelanggan', 'email', 'jenis_kelamin',
            'no_telpon', 'tanggal_lahir', 'longitude', 'latitude', 'alamat',
            'foto_pelanggan', 'kode_kota', 'nama_kota',
            'notelpon_user', 'jobdesk_user', 'foto_user', 'nama_title',
            'last_activity_at',
        ]);
    }
}
