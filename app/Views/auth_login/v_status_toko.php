<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?= $title ?></title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="<?= base_url() ?>/template_admin/bower_components/bootstrap/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?= base_url() ?>/template_admin/bower_components/font-awesome/css/font-awesome.min.css">
  <link href="<?= base_url() ?>/icon/gudang.ico" rel="shortcut icon">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700">
  <style>
    body {
      background: #f4f6f9;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .status-wrapper {
      max-width: 520px;
      width: 100%;
      padding: 30px;
      text-align: center;
    }
    .status-card {
      background: #fff;
      border-radius: 12px;
      padding: 40px 30px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }
    .status-icon {
      font-size: 56px;
      margin-bottom: 15px;
    }
    .status-card h3 {
      font-weight: 700;
      margin-bottom: 10px;
    }
    .status-box {
      display: inline-block;
      padding: 6px 18px;
      border-radius: 20px;
      font-size: 14px;
      font-weight: 700;
      margin: 10px 0 15px;
    }
    .text-success { color: #155724 !important; }
    .text-warning { color: #856404 !important; }
    .text-danger { color: #721c24 !important; }
    .box-success { background: #d4edda; color: #155724; }
    .box-warning { background: #fff3cd; color: #856404; }
    .box-danger { background: #f8d7da; color: #721c24; }
  </style>
</head>

<body>
  <div class="status-wrapper">
    <div class="status-card">
      <div class="status-icon">
        <?php if ($status['warna'] === 'success'): ?>
          <i class="fa fa-check-circle text-success"></i>
        <?php elseif ($status['warna'] === 'danger'): ?>
          <i class="fa fa-times-circle text-danger"></i>
        <?php else: ?>
          <i class="fa fa-hourglass-half text-warning"></i>
        <?php endif; ?>
      </div>
      <h3><?= esc($toko['nama_toko'] ?? 'Toko Anda') ?></h3>
      <span class="status-box box-<?= $status['warna'] ?>"><?= esc($status['label']) ?></span>

      <?php if (session()->getFlashdata('pesan_warning')): ?>
        <div class="alert alert-warning text-center"><?= session()->getFlashdata('pesan_warning') ?></div>
      <?php elseif (session()->getFlashdata('pesan_welcome')): ?>
        <div class="alert alert-success text-center"><?= session()->getFlashdata('pesan_welcome') ?></div>
      <?php endif; ?>

      <?php if ((int) ($toko['is_checked'] ?? 1) !== 2): ?>
        <p class="text-muted">
          Akun lapak Anda sedang ditinjau oleh tim <strong>Superadmin</strong> sesuai peraturan yang berlaku.
          Anda dapat masuk ke dashboard pemilik setelah toko berstatus <strong>Aktif</strong>.
        </p>
      <?php else: ?>
        <p class="text-muted">Toko Anda sudah aktif. Silakan login untuk mengelola toko.</p>
      <?php endif; ?>

      <?php if (!empty($toko['alasan_tolak']) && (int) ($toko['is_checked'] ?? 2) === 1): ?>
        <div class="alert alert-danger mt-3">
          <strong><i class="fa fa-exclamation-triangle"></i> Alasan Penolakan:</strong>
          <div class="mt-2"><?= nl2br(esc($toko['alasan_tolak'])) ?></div>
        </div>
      <?php endif; ?>

      <a href="<?= base_url('auth/login_user') ?>" class="btn btn-primary"><i class="fa fa-sign-in-alt"></i> Halaman Login</a>
      <a href="<?= base_url('auth/pilih_toko_user') ?>" class="btn btn-default"><i class="fa fa-store"></i> Pilih Toko</a>
    </div>
  </div>
</body>

</html>