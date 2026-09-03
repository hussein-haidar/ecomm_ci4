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
  $bgd_path = 'bgdweb/' . ($toko['bgd_web'] ?? ''); // gunakan null coalescing
  $default_bgd = 'fotodefault/bgdefault.jpeg'; // Path ke background default
  $bgd_to_use = file_exists(FCPATH . $bgd_path) && !empty($toko['bgd_web']) ? $bgd_path : $default_bgd;
  ?>.login-page {
    background: url('<?= base_url($bgd_to_use) ?>') no-repeat center center fixed;
    background-size: cover;
  }

  /* Login logo size adjustment */
  .login-logo {
    text-align: center;
    font-size: 24px;
  }
</style>

<body class="hold-transition login-page">
  <div class="login-box">

    <!-- Login Box -->
    <div class="login-box">
      <div class="login-box-body">

        <div class="login-logo">
          <b> <?= get_data_toko()['nama_toko'] ?? 'Nama Toko Default'; ?></b>
        </div>

        <div class="text-center" style="margin-bottom: 15px;">
          <small class="text-muted">
            <i class="fa fa-store"></i> Toko: <strong><?= esc(session()->get('toko_sesi_user') ?? '-') ?></strong>
            <a href="<?= base_url('auth/pilih_toko_user') ?>" class="text-primary" style="text-decoration: underline;">
              <i class="fa fa-exchange"></i> Ganti Toko
            </a>
          </small>
        </div>

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
        //Display error hak akses halaman
        if (session()->getFlashdata('pesan')) {
          echo '<div class="alert alert-warning" role="alert">' . session()->getFlashdata('pesan') . '</div>';
        }
        //Display error username dan password salah
        if (session()->getFlashdata('pesan_warning')) {
          echo '<div class="alert alert-warning" role="alert">' . session()->getFlashdata('pesan_warning') . '</div>';
        }
        // Dsiplay logout success
        if (session()->getFlashdata('pesan_success')) {
          echo '<div class="alert alert-success" role="alert">' . session()->getFlashdata('pesan_success') . '</div>';
        }
        ?>

        <div class="col-mt-4">
          <?php
          $toko = get_data_toko(); // Simpan dulu ke variabel biar rapi & aman
          $logo_path = 'logowebsite/' . ($toko['logo_website'] ?? '');
          $default_logo = 'fotodefault/logofaaro.png';
          $logo_to_use = file_exists(FCPATH . $logo_path) && !empty($toko['logo_website']) ? $logo_path : $default_logo;
          ?>
          <img class="img-responsive" src="<?= base_url($logo_to_use) ?>" alt="Logo">
          <p></p>
        </div>

        <?php echo form_open('auth/cek_login_user'); ?>
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
            <a href="<?= base_url('auth/register_user') ?>" class="btn btn-link">Belum Punya Akun?</a>
          </div>
          <!-- /.col -->
          <div class="col-xs-4">
            <button type="submit" class="btn btn-primary btn-block btn-flat">SIGN IN</button>
          </div>
          
        </div>

      </div>
      <!-- /.login-box-body -->
    </div>
    <!-- /.login-box -->

    <!-- jQuery 3 -->
    <script src="<?= base_url() ?>/template_admin/bower_components/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap 3.3.7 -->
    <script src="<?= base_url() ?>template_admin/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- iCheck -->
    <script src="<?= base_url() ?>template_admin/plugins/iCheck/icheck.min.js"></script>
    <!-- Script slide up alert window otomatis -->
    <script>
      window.setTimeout(function() {
        $(".alert").fadeTo(500, 0).slideUp(500, function() {
          $(this).remove();
        });
      }, 3000);
    </script>

    <!-- Script tampil gambar -->
    <script>
      function bacaGambar(input) {
        if (input.files && input.files[0]) {
          var reader = new FileReader();
          reader.onload = function(e) {
            $('#gambar_load').attr('src', e.target.result);
          }
          reader.readAsDataURL(input.files[0]);
        }
      }
      $('#preview_gambar').change(function() {
        bacaGambar(this);
      });
    </script>
    <!-- Script hide password -->
    <script>
      function myFunction() {
        var x = document.getElementById("ShowPass");
        if (x.type === "password") {
          x.type = "text";

        } else {
          x.type = "password";
        }
      }
    </script>
</body>

</html>