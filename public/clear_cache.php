<?php
/**
 * Secure Cache Clear Script for CodeIgniter 4
 * 
 * USAGE:
 * 1. Upload ke public_html/clear_cache.php
 * 2. Akses via browser: https://domain.com/clear_cache.php?token=YOUR_SECRET_TOKEN
 * 3. HAPUS FILE INI SELESAI DIGUNAKAN!
 * 
 * SECURITY:
 * - Token-based authentication
 * - IP whitelist support
 * - Auto-delete after use (optional)
 * - No sensitive info exposure
 */

// ===========================================
// KONFIGURASI KEAMANAN - UBAH SEBELUM UPLOAD!
// ===========================================
$config = [
    // Token rahasia (generate: php -r "echo bin2hex(random_bytes(32));")
    'secret_token' => 'CHANGE_ME_GENERATE_RANDOM_TOKEN_32_CHARS',
    
    // IP yang diizinkan (kosongkan = semua IP, tapi tetap butuh token)
    'allowed_ips' => [
        // '127.0.0.1',
        // '::1',
        // '192.168.1.100',
    ],
    
    // Hapus file ini setelah berhasil? (true = aman, false = manual delete)
    'auto_delete' => true,
    
    // Path ke root CI4 (ci4app folder)
    'ci4_root' => __DIR__ . '/../ci4app',
    
    // Folder yang akan dibersihkan
    'clean_paths' => [
        'writable/cache',
        'writable/logs',
        'writable/session',
        'writable/debugbar',
    ],
];

// ===========================================
// VALIDASI AKSES
// ===========================================
function denyAccess($message = 'Access Denied', $code = 403) {
    http_response_code($code);
    header('Content-Type: text/plain; charset=utf-8');
    exit($message);
}

// Cek token
$token = $_GET['token'] ?? '';
if (empty($token) || !hash_equals($config['secret_token'], $token)) {
    denyAccess('Invalid or missing token');
}

// Cek IP whitelist
if (!empty($config['allowed_ips'])) {
    $clientIp = $_SERVER['REMOTE_ADDR'] ?? '';
    if (!in_array($clientIp, $config['allowed_ips'], true)) {
        denyAccess('IP not allowed: ' . $clientIp);
    }
}

// ===========================================
// VALIDASI PATH CI4
// ===========================================
$ci4Root = realpath($config['ci4_root']);
if (!$ci4Root || !is_dir($ci4Root)) {
    denyAccess('CI4 root path not found: ' . $config['ci4_root']);
}

// Cek file penting ada
$requiredFiles = [
    $ci4Root . '/spark',
    $ci4Root . '/app/Config/Paths.php',
];
foreach ($requiredFiles as $file) {
    if (!file_exists($file)) {
        denyAccess('Required file missing: ' . basename($file));
    }
}

// ===========================================
// LOAD CODEIGNITER BOOTSTRAP (MINIMAL)
// ===========================================
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);
require_once $ci4Root . '/app/Config/Paths.php';
$paths = new Config\Paths();

require_once rtrim($paths->systemDirectory, '\\/ ') . DIRECTORY_SEPARATOR . 'bootstrap.php';
require_once SYSTEMPATH . 'Config/DotEnv.php';
(new CodeIgniter\Config\DotEnv($paths->rootDirectory))->load();

define('ENVIRONMENT', 'production');

// ===========================================
// BERSIHKAN CACHE
// ===========================================
$results = [
    'success' => true,
    'cleaned' => [],
    'errors' => [],
    'timestamp' => date('Y-m-d H:i:s'),
];

foreach ($config['clean_paths'] as $relativePath) {
    $fullPath = $paths->writableDirectory . DIRECTORY_SEPARATOR . $relativePath;
    
    if (!is_dir($fullPath)) {
        $results['errors'][] = "Directory not found: $relativePath";
        continue;
    }
    
    $files = glob($fullPath . '/*');
    $count = 0;
    
    foreach ($files as $file) {
        if (is_file($file)) {
            // Jangan hapus .htaccess, index.html, .gitkeep
            $basename = basename($file);
            if (in_array($basename, ['.htaccess', 'index.html', '.gitkeep', '.gitignore'])) {
                continue;
            }
            
            if (@unlink($file)) {
                $count++;
            } else {
                $results['errors'][] = "Failed to delete: $relativePath/$basename";
            }
        }
    }
    
    $results['cleaned'][] = [
        'path' => $relativePath,
        'files_deleted' => $count,
    ];
}

// Bersihkan config cache jika ada
$configCache = $paths->writableDirectory . '/config.php';
if (file_exists($configCache)) {
    if (@unlink($configCache)) {
        $results['cleaned'][] = ['path' => 'config.php (cache)', 'files_deleted' => 1];
    }
}

// ===========================================
// AUTO DELETE SELF
// ===========================================
if ($config['auto_delete']) {
    $self = __FILE__;
    // Schedule delete after response sent
    register_shutdown_function(function () use ($self) {
        @unlink($self);
    });
    $results['auto_deleted'] = true;
}

// ===========================================
// OUTPUT RESULT
// ===========================================
header('Content-Type: application/json; charset=utf-8');
echo json_encode($results, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);