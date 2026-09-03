<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\M_pemilik_website;
use Config\Database;

class Pemilik_kelola_website extends BaseController
{
    protected $M_pemilik_website;

    public function __construct()
    {
        $this->M_pemilik_website = new M_pemilik_website();
    }

    public function index()
    {
        $data = [
            'title' => 'Daftar Website Toko',
            'title2' => 'Data Website',
            'data_website' => $this->M_pemilik_website->get_website(),
            'isi' => 'pemilik/data_website/v_website',
        ];
        return view('layout/v_template', $data);
    }

    public function update_checked()
    {
        // Ambil data dari form
        $id_website = $this->request->getPost('id_website');
        $is_checked = $this->request->getPost('is_checked');

        // Perbarui status di database + waktu update
        $this->M_pemilik_website->update_checked($id_website, $is_checked);

        // Redirect kembali ke halaman utama
        return redirect()->to(base_url('pemilik_kelola_website/index'));
    }

    public function add()
    {
        // Ambil nama_pelanggan dari session
        $sesi_user = session()->get('sesi_user');  // Menggunakan session() di CodeIgniter 4

        // Cek jika nama_pelanggan tidak ditemukan dalam session
        if (empty($sesi_user)) {
            // Redirect atau beri pesan error jika session nama_pelanggan tidak ada
            return redirect()->to('auth/login_user');  // Ganti dengan URL login atau halaman lainnya
        }
        // Ambil data pelanggan
        $data_pelanggan = $this->M_pemilik_website->get_user_by_id($sesi_user);

        $data = [
            'title' => 'Tambah Website',
            'title2' => 'Daftar Website',
            'data_website' => $this->M_pemilik_website->get_website(),
            'sesi_user' => $data_pelanggan ? $data_pelanggan['sesi_user'] : 'Nama Lengkap Tidak Ditemukan',
            'level'         => $data_pelanggan['level'] ?? 'Level Tidak Diketahui',
            'isi' => 'pemilik/data_website/v_add',
        ];
        return view('layout/v_template', $data);
    }

    public function save()
    {
        if ($this->validate([
            'nama_toko' => [
                'label' => 'Nama Toko',
                'rules' => 'required',
                'errors' => ['required' => '{field} Wajib Diisi !!!']
            ],
            'alamat_pusat' => [
                'label' => 'Alamat Pusat',
                'rules' => 'required',
                'errors' => ['required' => '{field} Wajib Diisi !!!']
            ],
            'wa_pusat'=> [
                'label' => 'WA Pusat',
                'rules' => 'required',
                'errors' => ['required' => '{field} Wajib Diisi !!!']
            ],
        ])) {
            $logo = $this->request->getFile('logo_website');
            $bgd = $this->request->getFile('bgd_web');
            $nama_logo = $logo ? $logo->getRandomName() : '';
            $nama_bgd = $bgd ? $bgd->getRandomName() : '';

            if ($logo && $logo->isValid()) $logo->move('logowebsite', $nama_logo);
            if ($bgd && $bgd->isValid()) $bgd->move('bgdweb', $nama_bgd);

            $data = [
                'sesi_user' => $this->request->getPost('sesi_user'),
                'level' => $this->request->getPost('level'),
                'nama_toko' => $this->request->getPost('nama_toko'),
                'latitude_pusat' => $this->request->getPost('latitude_pusat'),
                'longitude_pusat' => $this->request->getPost('longitude_pusat'),
                'alamat_pusat' => $this->request->getPost('alamat_pusat'),
                'wa_pusat' => $this->request->getPost('wa_pusat'),
                'footer_title' => $this->request->getPost('footer_title'),
                'link_IG' => $this->request->getPost('link_IG'),
                'link_FB' => $this->request->getPost('link_FB'),
                'link_Tiktok' => $this->request->getPost('link_Tiktok'),
                'kode_kota' => $this->request->getPost('kode_kota'),
                'nama_kota' => $this->request->getPost('nama_kota'),
                'logo_website' => $nama_logo,
                'bgd_web' => $nama_bgd,
            ];

            $this->M_pemilik_website->add($data);

            $id_website = $this->M_pemilik_website->getInsertID();
            $cabangModel = new \App\Models\M_pemilik_cabang();
            $cabang = $this->request->getPost('cabang');
            if (!empty($cabang) && is_array($cabang)) {
                foreach ($cabang as $c) {
                    $cabangModel->insert([
                        'id_website' => $id_website,
                        'nama_cabang' => $c['nama_cabang'] ?? '',
                        'latitude_cabang' => $c['latitude'] ?? null,
                        'longitude_cabang' => $c['longitude'] ?? null,
                        'alamat_cabang' => $c['alamat_cabang'] ?? '',
                        'wa_cabang' => $c['wa_cabang'] ?? '',
                    ]);
                }
            }

            session()->setFlashdata('pesan', 'Data Website Berhasil Ditambahkan!');
            return redirect()->to(base_url('pemilik_kelola_website'));
        } else {
            session()->setFlashdata('errors', \Config\Services::validation()->getErrors());
            return redirect()->to(base_url('pemilik_kelola_website/add'));
        }
    }

    public function edit($id_website)
    {
        // Ambil sesi user dari session
        $sesi_user = session()->get('sesi_user');

        // Jika belum login, redirect ke halaman login
        if (empty($sesi_user)) {
            return redirect()->to('auth/login_user');
        }

        // Ambil data user dari model
        $data_pelanggan = $this->M_pemilik_website->get_user_by_id($sesi_user);

        // Pastikan data tidak null sebelum digunakan
        $cabangModel = new \App\Models\M_pemilik_cabang();
        $data_cabang = $cabangModel->getCabangByWebsite($id_website);

        $data = [
            'title'         => 'Edit Data Website',
            'title2'        => 'Data Website',
            'data_website'  => $this->M_pemilik_website->detailWebsite($id_website),
            'data_cabang'   => $data_cabang,
            'sesi_user'     => $data_pelanggan['sesi_user'] ?? 'User Tidak Ditemukan',
            'level'         => $data_pelanggan['level'] ?? 'Level Tidak Diketahui',
            'isi'           => 'pemilik/data_website/v_edit',
        ];

        return view('layout/v_template', $data);
    }

    public function update($id_website)
    {
        $data_website = $this->M_pemilik_website->detailWebsite($id_website);

        $data = [
            'id_website' => $id_website,
            'sesi_user' => $this->request->getPost('sesi_user'),
            'level' => $this->request->getPost('level'),
            'nama_toko' => $this->request->getPost('nama_toko'),
            'latitude_pusat' => $this->request->getPost('latitude_pusat'),
            'longitude_pusat' => $this->request->getPost('longitude_pusat'),
            'alamat_pusat' => $this->request->getPost('alamat_pusat'),
            'wa_pusat' => $this->request->getPost('wa_pusat'),
            'footer_title' => $this->request->getPost('footer_title'),
            'link_IG' => $this->request->getPost('link_IG'),
            'link_FB' => $this->request->getPost('link_FB'),
            'link_Tiktok' => $this->request->getPost('link_Tiktok'),
            'kode_kota' => $this->request->getPost('kode_kota'),
            'nama_kota' => $this->request->getPost('nama_kota'),
        ];

        $logo = $this->request->getFile('logo_website');
        $bgd = $this->request->getFile('bgd_web');

        if ($logo && $logo->isValid() && !$logo->hasMoved()) {
            if (!empty($data_website['logo_website'])) @unlink('logowebsite/' . $data_website['logo_website']);
            $nama_logo = $logo->getRandomName();
            $logo->move('logowebsite', $nama_logo);
            $data['logo_website'] = $nama_logo;
        } else {
            $data['logo_website'] = $data_website['logo_website'];
        }

        if ($bgd && $bgd->isValid() && !$bgd->hasMoved()) {
            if (!empty($data_website['bgd_web'])) @unlink('bgdweb/' . $data_website['bgd_web']);
            $nama_bgd = $bgd->getRandomName();
            $bgd->move('bgdweb', $nama_bgd);
            $data['bgd_web'] = $nama_bgd;
        } else {
            $data['bgd_web'] = $data_website['bgd_web'];
        }

        $this->M_pemilik_website->edit($data);

        $cabangModel = new \App\Models\M_pemilik_cabang();
        $cabangModel->deleteByWebsite($id_website);
        $cabang = $this->request->getPost('cabang');
        if (!empty($cabang) && is_array($cabang)) {
            foreach ($cabang as $c) {
                $cabangModel->insert([
                    'id_website' => $id_website,
                    'nama_cabang' => $c['nama_cabang'] ?? '',
                    'latitude_cabang' => $c['latitude'] ?? null,
                    'longitude_cabang' => $c['longitude'] ?? null,
                    'alamat_cabang' => $c['alamat_cabang'] ?? '',
                    'wa_cabang' => $c['wa_cabang'] ?? '',
                ]);
            }
        }

        session()->setFlashdata('pesan', 'Data Website Berhasil Diupdate!');
        return redirect()->to(base_url('pemilik_kelola_website'));
    }

    public function delete($id_website)
    {
        $data = $this->M_pemilik_website->detailWebsite($id_website);

        if (!empty($data['logo_website'])) {
            unlink('logowebsite/' . $data['logo_website']);
        }

        if (!empty($data['bgd_web'])) {
            unlink('bgdweb/' . $data['bgd_web']);
        }

        $this->M_pemilik_website->delete($id_website);

        session()->setFlashdata('pesan', 'Data Website Berhasil Dihapus!');
        return redirect()->to(base_url('pemilik_kelola_website'));
    }
    
    public function backup_db()
    {
        // Data untuk view
        $data = [
            'title' => 'Data Backup Database',
            'title2' => 'Backup Database',
            'isi' => 'pemilik/data_website/v_backup',  // View yang akan digunakan
        ];

        // Menampilkan view dengan data yang telah disiapkan
        return view('layout/v_template', $data);
    }

    // Method untuk memproses backup database
    public function proses_db()
    {
        // Tentukan lokasi penyimpanan file backup
        $backupPath = WRITEPATH . 'backup/';
        if (!is_dir($backupPath)) {
            mkdir($backupPath, 0777, true); // Membuat folder backup jika belum ada
        }

        $backupFile = $backupPath . 'backup_' . date('Y-m-d_H-i-s') . '.sql'; // Nama file backup dengan timestamp

        try {
            // Mengambil koneksi database dari konfigurasi CodeIgniter
            $db = \Config\Database::connect();

            // Query untuk mengambil semua tabel
            $tablesResult = $db->query('SHOW TABLES');

            if (!$tablesResult) {
                // Jika gagal mengambil tabel, tampilkan pesan error
                session()->setFlashdata('error', 'Gagal mengambil tabel dari database.');
                return redirect()->to(base_url('pemilik_kelola_website/backup_db'));
            }

            // Mulai menulis file backup
            $backupData = "-- phpMyAdmin SQL Dump\n";
            $backupData .= "-- version 5.2.0\n";
            $backupData .= "-- https://www.phpmyadmin.net/\n\n";
            $backupData .= "-- Host: " . $_SERVER['SERVER_ADDR'] . "\n";
            $backupData .= "-- Generation Time: " . date('Y-m-d H:i:s') . "\n";
            $backupData .= "-- PHP Version: " . phpversion() . "\n\n";
            $backupData .= "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\n";
            $backupData .= "START TRANSACTION;\n";
            $backupData .= "SET time_zone = \"+00:00\";\n\n";
            $backupData .= "/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;\n";
            $backupData .= "/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;\n";
            $backupData .= "/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;\n";
            $backupData .= "/*!40101 SET NAMES utf8mb4 */;\n\n";

            // Menentukan nama database
            $backupData .= "-- Database: " . $db->getDatabase() . "\n\n";

            // Loop untuk mengambil data dari setiap tabel
            foreach ($tablesResult->getResultArray() as $row) {
                // Ambil nama tabel dari hasil query
                $tableName = $row['Tables_in_' . $db->getDatabase()];

                // Menambahkan query untuk menghapus tabel jika sudah ada
                $backupData .= "DROP TABLE IF EXISTS `$tableName`;\n";

                // Menyertakan definisi tabel
                $createTableResult = $db->query("SHOW CREATE TABLE `$tableName`");
                if ($createTableResult) {
                    $createTableRow = $createTableResult->getRowArray();
                    $backupData .= $createTableRow['Create Table'] . ";\n\n";
                }

                // Menambahkan data tabel
                $dataResult = $db->query("SELECT * FROM `$tableName`");
                if ($dataResult->getNumRows() > 0) {
                    $columns = array_keys($dataResult->getRowArray()); // Ambil kolom tabel
                    $backupData .= "INSERT INTO `$tableName` (`" . implode('`, `', $columns) . "`) VALUES\n";
                    $rowCount = 0;
                    foreach ($dataResult->getResultArray() as $dataRow) {
                        $values = array_map(function ($value) use ($db) {
                            return $db->escape($value);  // Menggunakan escape untuk nilai
                        }, array_values($dataRow));
                        $backupData .= "    (" . implode(", ", $values) . ")";
                        $rowCount++;
                        if ($rowCount < $dataResult->getNumRows()) {
                            $backupData .= ",\n";
                        } else {
                            $backupData .= ";\n\n";
                        }
                    }
                }
            }

            // Akhirkan transaksi dan pengaturan SQL
            $backupData .= "COMMIT;\n";

            // Simpan data ke dalam file backup
            file_put_contents($backupFile, $backupData);

            // Simpan pesan sukses
            session()->setFlashdata('success', 'Backup database berhasil disimpan di folder backup.');
            return redirect()->to(base_url('pemilik_kelola_website/backup_db'));
        } catch (\Exception $e) {
            // Jika terjadi kesalahan, tampilkan pesan error
            session()->setFlashdata('error', 'Terjadi kesalahan: ' . $e->getMessage());
            return redirect()->to(base_url('pemilik_kelola_website/backup_db'));
        }
    }

}