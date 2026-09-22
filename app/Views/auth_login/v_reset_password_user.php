<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= $title ?></title>
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
    <!-- Custom icon styles for this template-->
    <link href="<?= base_url() ?>/icon/gudang.ico" rel="shortcut icon">

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
</style>

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-box">
            <div class="login-box-body">

                <div class="login-logo">
                    <b> <?= get_data_toko()['nama_toko'] ?? 'Nama Toko Default'; ?></b>
                </div>

                <?php
                if (session()->getFlashdata('pesan_warning')) {
                    echo '<div class="alert alert-warning" role="alert">' . session()->getFlashdata('pesan_warning') . '</div>';
                }
                if (session()->getFlashdata('pesan_success')) {
                    echo '<div class="alert alert-success" role="alert">' . session()->getFlashdata('pesan_success') . '</div>';
                }
                ?>

                <form action="<?= base_url('auth/proses_reset_user/' . $token) ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="form-group">
                        <label for="new_password">Password Baru</label>
                        <input type="password" name="new_password" id="new_password" class="form-control" placeholder="Masukkan Password Baru" required minlength="4">
                        <small class="text-danger"><?= session()->getFlashdata('error_password') ?></small>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Konfirmasi Password</label>
                        <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Masukkan Konfirmasi Password" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Perbarui Password</button>
                </form>

            </div>
        </div>

        <script src="<?= base_url() ?>/template_admin/bower_components/jquery/dist/jquery.min.js"></script>
        <script src="<?= base_url() ?>/template_admin/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

        <script>
            window.setTimeout(function() {
                $(".alert").fadeTo(500, 0).slideUp(500, function() {
                    $(this).remove();
                });
            }, 3000);
        </script>
</body>

</html>