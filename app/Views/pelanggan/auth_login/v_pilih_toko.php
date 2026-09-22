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
    .pilih-wrapper {
      max-width: 800px;
      width: 100%;
      padding: 30px;
    }
    .pilih-wrapper h2 {
      text-align: center;
      margin-bottom: 10px;
      font-weight: 700;
    }
    .pilih-wrapper p {
      text-align: center;
      color: #888;
      margin-bottom: 30px;
    }
    .toko-card {
      border: 2px solid #e0e0e0;
      border-radius: 12px;
      padding: 25px;
      margin-bottom: 20px;
      cursor: pointer;
      transition: all 0.3s ease;
      background: #fff;
      text-align: center;
    }
    .toko-card:hover {
      border-color: #3c8dbc;
      box-shadow: 0 4px 15px rgba(60, 141, 188, 0.3);
      transform: translateY(-3px);
    }
    .toko-card img {
      max-height: 80px;
      margin-bottom: 10px;
    }
    .toko-card h4 {
      font-weight: 700;
      margin-bottom: 5px;
    }
    .toko-card small {
      color: #999;
    }
    .toko-card .btn-pilih {
      margin-top: 12px;
    }
  </style>
  <?= view('layout_toko/v_csrf_script') ?>
</head>

<body>
  <div class="pilih-wrapper">
    <h2><i class="fa fa-store"></i> Pilih Toko</h2>
    <p>Silakan pilih toko tempat Anda ingin berbelanja</p>

    <?php if (session()->getFlashdata('pesan_warning')): ?>
      <div class="alert alert-warning text-center"><?= session()->getFlashdata('pesan_warning') ?></div>
    <?php endif; ?>

    <?php foreach ($toko_list as $toko): ?>
      <form method="POST" action="<?= base_url('auth/set_toko') ?>">
        <?= csrf_field() ?>
        <input type="hidden" name="sesi_user_toko" value="<?= esc($toko['sesi_user']) ?>">
        <div class="toko-card" onclick="this.closest('form').submit();">
          <img src="<?= base_url($toko['logo']) ?>" alt="<?= esc($toko['nama_toko']) ?>">
          <h4><?= esc($toko['nama_toko']) ?></h4>
          <?php if (!empty($toko['alamat'])): ?>
            <small><i class="fa fa-map-marker"></i> <?= esc($toko['alamat']) ?></small>
          <?php endif; ?>
          <br>
          <button type="submit" class="btn btn-primary btn-sm btn-pilih">
            <i class="fa fa-arrow-right"></i> Masuk ke Toko Ini
          </button>
        </div>
      </form>
    <?php endforeach; ?>

  </div>
</body>

</html>
