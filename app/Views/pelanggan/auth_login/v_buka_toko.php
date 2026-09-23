<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?= $title ?></title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="<?= base_url() ?>/template_admin/bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?= base_url() ?>/template_admin/bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?= base_url() ?>/template_admin/bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?= base_url() ?>/template_admin/dist/css/AdminLTE.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="<?= base_url() ?>/template_admin/plugins/iCheck/square/blue.css">
  <!-- Icon -->
  <link href="<?= base_url() ?>/icon/gudang.ico" rel="shortcut icon">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  <?= view('layout_toko/v_csrf_script') ?>
</head>

<!-- Custom CSS -->
<style>
  <?php
  $toko = get_data_toko();
  $bgd_path = 'bgdweb/' . ($toko['bgd_web'] ?? '');
  $default_bgd = 'fotodefault/bgdefault.jpeg';
  $bgd_to_use = file_exists(FCPATH . $bgd_path) && !empty($toko['bgd_web']) ? $bgd_path : $default_bgd;
  ?>.login-page {
    background: url('<?= base_url($bgd_to_use) ?>') no-repeat center center fixed;
    background-size: cover;
  }

  .login-logo {
    text-align: center;
    font-size: 24px;
  }

  .register-box {
    width: 520px;
    margin: 7% auto;
  }
</style>

<body class="hold-transition login-page">
  <div class="register-box">
    <div class="login-box-body">

      <div class="login-logo">
        <b> Buka Toko Online </b>
      </div>

      <p class="text-center text-muted">
        <i class="fa fa-store"></i> Daftar sebagai Pemilik, toko Anda langsung aktif di
        <strong>Platform Multi-Toko</strong>
      </p>

      <!-- Display validation errors -->
      <?php $errors = session()->getFlashdata('errors'); ?>
      <?php if (!empty($errors)) : ?>
        <div class="alert alert-danger" role="alert">
          <ul>
            <?php foreach ($errors as $error) : ?>
              <li><?= esc($error) ?></li>
            <?php endforeach ?>
          </ul>
        </div>
      <?php endif; ?>

      <?php
      if (session()->getFlashdata('pesan')) {
        echo '<div class="alert alert-warning" role="alert">' . session()->getFlashdata('pesan') . '</div>';
      }
      if (session()->getFlashdata('pesan_warning')) {
        echo '<div class="alert alert-warning" role="alert">' . session()->getFlashdata('pesan_warning') . '</div>';
      }
      if (session()->getFlashdata('pesan_success')) {
        echo '<div class="alert alert-success" role="alert">' . session()->getFlashdata('pesan_success') . '</div>';
      }
      ?>

      <?php echo form_open_multipart('auth/save_buka_toko'); ?>

      <div class="form-group has-feedback">
        <label>Nama Toko *</label>
        <input type="text" name="nama_toko" class="form-control" placeholder="Contoh: Batik Nusantara Shop" required>
        <span class="glyphicon glyphicon-briefcase form-control-feedback"></span>
      </div>

      <div class="form-group has-feedback">
        <label>Nama Lengkap Pemilik * <small class="text-muted">(dipakai sebagai identitas toko)</small></label>
        <input type="text" name="nama_lengkap" class="form-control" placeholder="Nama Lengkap Anda" required>
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
      </div>

      <div class="form-group has-feedback">
        <label>Username * <small class="text-muted">(minimal 4 karakter)</small></label>
        <input type="text" name="username" class="form-control" placeholder="Username untuk login dashboard" required>
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
      </div>

      <div class="form-group has-feedback">
        <label>Password * <small class="text-muted">(minimal 4 karakter)</small></label>
        <input type="password" name="password" id="ShowPass" class="form-control" placeholder="Kata Sandi" required>
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>

      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label>Title Dashboard</label>
            <input type="text" name="nama_title" class="form-control" placeholder="Contoh: Admin Batik Nusantara">
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label>No. HP / WA Pusat</label>
            <input type="text" name="notelpon_user" class="form-control" placeholder="08xxxxxxxxxx">
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label>Email (opsional, untuk reset password)</label>
            <input type="email" name="email_user" class="form-control" placeholder="masukkan email">
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label>Alamat Toko</label>
            <input type="text" name="alamat_pusat" class="form-control" placeholder="Alamat pusat toko">
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label>No. WA Toko</label>
            <input type="text" name="wa_pusat" class="form-control" placeholder="WhatsApp dagang">
          </div>
        </div>
      </div>

      <div class="form-group">
        <label>Foto Profil <small class="text-muted">(opsional, PNG/JPG maks 1 MB)</small></label>
        <input type="file" class="form-control" name="foto_user" id="preview_gambar">
      </div>

      <label>
        <input type="checkbox" name="show_pass" id="tampil_password"> Tampilkan Password
      </label>

      <div class="row" style="margin-top: 10px;">
        <div class="col-xs-8">
          <a href="<?= base_url('auth/pilih_toko_user') ?>" class="btn btn-link">Sudah punya toko? Masuk</a>
        </div>
        <div class="col-xs-4">
          <button type="submit" class="btn btn-primary btn-block btn-flat"><i class="fa fa-rocket"></i> BUKA TOKO</button>
        </div>
      </div>

      <?php echo form_close(); ?>

    </div>
    <!-- /.login-box-body -->
  </div>
  <!-- /.register-box -->

  <!-- jQuery 3 -->
  <script src="<?= base_url() ?>/template_admin/bower_components/jquery/dist/jquery.min.js"></script>
  <!-- Bootstrap 3.3.7 -->
  <script src="<?= base_url() ?>template_admin/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
  <!-- Script slide up alert window otomatis -->
  <script>
    window.setTimeout(function() {
      $(".alert").fadeTo(500, 0).slideUp(500, function() {
        $(this).remove();
      });
    }, 3000);
  </script>
  <!-- Script tampilkan/sembunyikan password -->
  <script>
    $('#tampil_password').on('change', function() {
      var x = document.getElementById("ShowPass");
      if (x.type === "password") {
        x.type = "text";
      } else {
        x.type = "password";
      }
    });
  </script>
</body>