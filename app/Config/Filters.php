<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\Honeypot;

class Filters extends BaseConfig
{
	/**
	 * Configures aliases for Filter classes to
	 * make reading things nicer and simpler.
	 *
	 * @var array
	 */
	public $aliases = [
		'csrf'     => \CodeIgniter\Filters\CSRF::class,
		'toolbar'  => \CodeIgniter\Filters\DebugToolbar::class,
		'honeypot' => \CodeIgniter\Filters\Honeypot::class,
		'filter_pemilik' => \App\Filters\Filter_pemilik::class,
		'filter_admin' => \App\Filters\Filter_admin::class,
		'filter_pelanggan' => \App\Filters\Filter_pelanggan::class,
		'filter_toko' => \App\Filters\Filter_toko::class,
	];

	/**
	 * List of filter aliases that are always
	 * applied before and after every request.
	 *
	 * @var array
	 */
	public $globals = [
		'before' => [
			// Proteksi CSRF global (aktif seperti Laravel).
			// Webhook Midtrans dikecualikan karena dipanggil server-to-server
			// oleh pihak Midtrans tanpa token CSRF (meniru pengecualian webhook
			// VerifyCsrfToken pada Laravel).
			'csrf' => [
				'except' => [
					'pelanggan_kelola_data/midtrans_notification', // Webhook Midtrans
				],
			],
			'filter_toko' => [
				'except' => [
					'auth',
					'auth/*',
					'home_toko',       // Halaman publik
					'home_toko/*',     // Sub-rute publik
					'review',          // Review publik
					'review/*',        // Review publik
					'pelanggan_kelola_data/midtrans_notification', // Webhook Midtrans (tanpa session)
				],
			],
			//'honeypot',
		],
		'after'  => [
			'filter_admin' => ['except' => [
				'home_admin',
				'home_admin/*',
				'admin_kelola_data',
				'admin_kelola_data/*',
				'admin_laporan_mingguan',
				'admin_laporan_mingguan/*',
				'admin_laporan_bulanan',
				'admin_laporan_bulanan/*',
				'chat_admin_pembeli',
				'chat_admin_pembeli/*',
			]],

			'filter_pemilik' => ['except' => [
				'home_pemilik',
				'home_pemilik/*',
				'pemilik_kelola_user',
				'pemilik_kelola_user/*',
				'pemilik_kelola_data',
				'pemilik_kelola_data/*',
				'pemilik_kelola_website',
				'pemilik_kelola_website/*',
			]],

			'filter_pelanggan' => ['except' => [
				'home_toko',
				'home_toko/*',  
				'update_profile',
				'update_profile/*',
				'pelanggan_kelola_data',
				'pelanggan_kelola_data/*',
				'review',
				'review/*',
				'chat_admin_pembeli',
				'chat_admin_pembeli/*',
			]],

		],
	];

	/**
	 * List of filter aliases that works on a
	 * particular HTTP method (GET, POST, etc.).
	 *
	 * Example:
	 * 'post' => ['csrf', 'throttle']
	 *
	 * @var array
	 */
	public $methods = [];

	/**
	 * List of filter aliases that should run on any
	 * before or after URI patterns.
	 *
	 * Example:
	 * 'isLoggedIn' => ['before' => ['account/*', 'profiles/*']]
	 *
	 * @var array
	 */
	public $filters = [];
}
