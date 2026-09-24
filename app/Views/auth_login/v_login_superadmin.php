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
  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700">
  <?= view('layout_toko/v_csrf_script') ?>
</head>

<body class="hold-transition login-page" style="background: #222d32;">
  <div class="login-box">
    <div class="login-box-body" style="border-top: 3px solid #f39c12; border-radius: 4px;">

      <div class="login-logo" style="margin: 10px 0 20px;">
        <b><i class="fa fa-shield"></i> Superadmin</b>
      </div>

      <p class="login-box-msg" style="margin-bottom: 20px;">
        Masuk sebagai pengelola seluruh toko
      </p>

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

      <?php if (session()->getFlashdata('pesan')) {
        echo '<div class="alert alert-warning" role="alert">' . session()->getFlashdata('pesan') . '</div>';
      } ?>
      <?php if (session()->getFlashdata('pesan_warning')) {
        echo '<div class="alert alert-warning" role="alert">' . session()->getFlashdata('pesan_warning') . '</div>';
      } ?>
      <?php if (session()->getFlashdata('pesan_success')) {
        echo '<div class="alert alert-success" role="alert">' . session()->getFlashdata('pesan_success') . '</div>';
      } ?>

      <?php echo form_open('auth/cek_login_superadmin'); ?>
      <div class="form-group has-feedback">
        <label>Username</label>
        <input type="text" name="username" class="form-control" placeholder="Masukkan Nama Pengguna">
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
      </div>

      <div class="form-group has-feedback">
        <label>Password</label>
        <input type="password" name="password" class="form-control" placeholder="Masukkan Kata Sandi">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>

      <div class="row">
        <div class="col-xs-8">
          <a href="<?= base_url('auth/pilih_toko_user') ?>" class="btn btn-link"><i class="fa fa-store"></i> Login Toko</a>
        </div>
        <div class="col-xs-4">
          <button type="submit" class="btn btn-warning btn-block btn-flat">SIGN IN</button>
        </div>
      </div>
      <?php echo form_close(); ?>

    </div>
  </div>

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
</body>

</html>