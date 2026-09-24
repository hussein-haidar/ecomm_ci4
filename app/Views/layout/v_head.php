<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title> <?= $title2 ?? 'Admin Panel' ?></title>
    <meta name="csrf-token" content="<?= csrf_token() ?>">
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
    <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="<?= base_url() ?>/template_admin/dist/css/skins/_all-skins.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="<?= base_url() ?>/template_admin/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
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
</head>

<body class="hold-transition skin-blue sidebar-mini">

    <?php
    $jumlahBayar = 0;
    $daftarNotifikasi = [];

    $sesi_user = session()->get('sesi_user'); // ganti sesuai nama variabel yang kamu simpan di session
    $nama_pelanggan = session()->get('nama_pelanggan');

    // Jika sesi user adalah pelanggan dan nama pelanggan tersedia
    if ($sesi_user && !empty($nama_pelanggan)) {
        $pembayaranModel = new \App\Models\M_pelanggan_bayar();
        $status = $pembayaranModel->get_status_bayar_sesi($sesi_user, $nama_pelanggan);
        $jumlahBayar = count($status);
        $daftarNotifikasi = $status;
    }
    ?>
    
    <!-- Site wrapper -->
    <div class="wrapper">

        <header class="main-header">
            <!-- Logo -->
            <a href="#" class="logo">
                <?php
                if ((int) session()->get('level') === 0) {
                    $nama_toko = 'Superadmin Panel';
                } else {
                    $nama_toko = get_data_toko()['nama_toko'] ?? 'Nama Toko Default';
                }
                ?>

                <!-- mini logo for sidebar mini 50x50 pixels -->
                <span class="logo-mini">
                    <?php
                    echo "<b>" . htmlspecialchars($nama_toko) . "</b>";
                    ?>
                </span>

                <!-- logo for regular state and mobile devices -->
                <span class="logo-lg">
                    <?php
                    echo "<b>" . htmlspecialchars($nama_toko) . "</b>";
                    ?>
                </span>
            </a>
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top">
                <!-- Sidebar toggle button-->
                <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>

                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        <?php if (session()->get('level') == 1) { ?>
                            <!-- User Account: style can be found in dropdown.less -->
                            <li class="dropdown user user-menu">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <img src="<?= base_url('fotouser/' . session()->get('foto_user')) ?>" class="user-image" alt="User Image">
                                    <span class="hidden-xs"><?= session()->get('nama_lengkap') ?></span>
                                </a>
                                <ul class="dropdown-menu">
                                    <!-- User image -->
                                    <li class="user-header">
                                        <img src="<?= base_url('fotouser/' . session()->get('foto_user')) ?>" class="img-circle" alt="User Image">
                                        <p>
                                            <?= session()->get('nama_lengkap') ?>
                                            <small>Level :&nbsp;
                                                <?php if (session()->get('level') == 1) {
                                                    echo 'Pemilik';
                                                }
                                                ?>
                                                <br>
                                                Last Login : <?= session()->get('last_login') ?>
                                            </small>
                                            </br>
                                        </p>
                                    </li>

                                    <!-- Menu Footer-->
                                    <li class="user-footer">
                                        <div class="pull-left">
                                            <a href="<?= base_url('home_pemilik/profil') ?>" class="btn btn-default btn-flat"><i class="fa fa-fw fa-user"></i>&nbsp;Profile</a>
                                        </div>
                                        <div class="pull-right">
                                            <button class="btn btn-default btn-flat" onclick="logoutConfirm()"><i class="fa fa-fw fa-key"></i>&nbsp;Sign Out</button></td>
                                        </div>
                                    </li>
                                </ul>
                            </li>

                            <!-- Control Sidebar Toggle Button -->
                            <li>
                                <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                            </li>
                    </ul>
                </div>
            </nav>
        </header>

        <!-- =============================================== -->
    <?php } ?>

    <?php if ((int) session()->get('level') === 0) { ?>
        <!-- User Account: style can be found in dropdown.less -->
        <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <img src="<?= base_url('fotouser/' . session()->get('foto_user')) ?>" class="user-image" alt="User Image">
                <span class="hidden-xs"><?= session()->get('nama_lengkap') ?></span>
            </a>
            <ul class="dropdown-menu">
                <!-- User image -->
                <li class="user-header">
                    <img src="<?= base_url('fotouser/' . session()->get('foto_user')) ?>" class="img-circle" alt="User Image">
                    <p>
                        <?= session()->get('nama_lengkap') ?>
                        <small>Level :&nbsp;Superadmin
                            <br>
                            Last Login : <?= session()->get('last_login') ?>
                        </small>
                        </br>
                    </p>
                </li>

                <!-- Menu Footer-->
                <li class="user-footer">
                    <div class="pull-left">
                        <a href="<?= base_url('home_superadmin/profil') ?>" class="btn btn-default btn-flat"><i class="fa fa-fw fa-user"></i>&nbsp;Profile</a>
                    </div>
                    <div class="pull-right">
                        <button class="btn btn-default btn-flat" onclick="logoutConfirm()"><i class="fa fa-fw fa-key"></i>&nbsp;Sign Out</button></td>
                    </div>
                </li>
            </ul>
        </li>

        <!-- Control Sidebar Toggle Button -->
        <li>
            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
        </li>
        </ul>
    </div>
    </nav>
    </header>

    <!-- =============================================== -->
<?php } ?>

    <?php if (session()->get('level') == 2) { ?>

        <!-- Notifications -->
        <li class="dropdown notifications-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-bell-o"></i>
                <span class="label label-warning"><?= $jumlahBayar ?></span>
            </a>
            <ul class="dropdown-menu">
                <li class="header">Anda memiliki <?= $jumlahBayar ?> Notifikasi pembayaran</li>
                <li>
                    <!-- inner menu -->
                    <ul class="menu">
                        <?php if (!empty($daftarNotifikasi)) : ?>
                            <?php foreach ($daftarNotifikasi as $notif) : ?>
                                <li>
                                    <a href="<?= base_url('admin_kelola_data/bayar') ?>">
                                        <i class="fa fa-money text-yellow"></i>
                                        <?= esc($notif['nama_pelanggan']) ?> - <?= esc($notif['status_bayar']) ?>
                                        (<?= date('d/m/Y H:i:s', strtotime($notif['waktu_pembayaran'])) ?>)
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <li><a href="#"><i class="fa fa-info-circle text-muted"></i> Tidak ada notifikasi</a></li>
                        <?php endif; ?>
                    </ul>

                </li>
                <li class="footer">
                    <a href="<?= base_url('admin_kelola_data/bayar') ?>">Lihat semua</a>
                </li>
            </ul>
        </li>

        <!-- User Account: style can be found in dropdown.less -->
        <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <img src="<?= base_url('fotouser/' . session()->get('foto_user')) ?>" class="user-image" alt="User Image">
                <span class="hidden-xs"><?= session()->get('nama_lengkap') ?></span>
            </a>
            <ul class="dropdown-menu">
                <!-- User image -->
                <li class="user-header">
                    <img src="<?= base_url('fotouser/' . session()->get('foto_user')) ?>" class="img-circle" alt="User Image">
                    <p>
                        <?= session()->get('nama_lengkap') ?>
                        <small>Level :&nbsp;
                            <?php if (session()->get('level') == 2) {
                                echo 'Admin Toko';
                            }
                            ?>
                            <br>
                            Last Login : <?= session()->get('last_login') ?>
                        </small>
                        </br>
                    </p>
                </li>

                <!-- Menu Footer-->
                <li class="user-footer">
                    <div class="pull-left">
                        <a href="<?= base_url('home_admin/profil') ?>" class="btn btn-default btn-flat"><i class="fa fa-fw fa-user"></i>&nbsp;Profile</a>
                    </div>
                    <div class="pull-right">
                        <button class="btn btn-default btn-flat" onclick="logoutConfirm()"><i class="fa fa-fw fa-key"></i>&nbsp;Sign Out</button></td>
                    </div>
                </li>
            </ul>
        </li>

        <!-- Control Sidebar Toggle Button -->
        <li>
            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
        </li>
        </ul>
    </div>
    </nav>
    </header>

    <!-- =============================================== -->
<?php } ?>