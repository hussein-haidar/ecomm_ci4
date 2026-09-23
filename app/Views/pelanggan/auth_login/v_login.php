<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?= $title ?></title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?= base_url() ?>/template_admin/bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700&display=swap">
  <!-- Icon -->
  <link href="<?= base_url() ?>/icon/gudang.ico" rel="shortcut icon">
  <?= view('layout_toko/v_csrf_script') ?>
</head>

<style>
  <?php
  $toko = get_data_toko();
  $logo_path = 'logowebsite/' . ($toko['logo_website'] ?? '');
  $default_logo = 'fotodefault/logofaaro.png';
  $logo_to_use = file_exists(FCPATH . $logo_path) && !empty($toko['logo_website']) ? $logo_path : $default_logo;
  $bgd_path = 'bgdweb/' . ($toko['bgd_web'] ?? '');
  $default_bgd = 'fotodefault/bgdefault.jpeg';
  $bgd_to_use = file_exists(FCPATH . $bgd_path) && !empty($toko['bgd_web']) ? $bgd_path : $default_bgd;
  $brand = 'rgb(78, 89, 240)';
  ?>

  /* ===== Reset dasar ===== */
  *,
  *::before,
  *::after {
    box-sizing: border-box;
  }

  html,
  body {
    margin: 0;
    padding: 0;
  }

  body {
    font-family: 'Source Sans Pro', Arial, sans-serif;
    min-height: 100vh;
  }

  /* ===== Latar belakang + overlay ===== */
  .auth-page {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 100vh;
    padding: 24px 16px;
    background: #0f1421;
    /* meredam background ramai */
  }

  .auth-bg {
    position: fixed;
    inset: 0;
    background: url('<?= base_url($bgd_to_use ?? 'fotodefault/bgdefault.jpeg') ?>') no-repeat center center fixed;
    background-size: cover;
    filter: blur(6px) brightness(0.55) saturate(0.8);
    transform: scale(1.05);
    z-index: 0;
  }

  .auth-wrap {
    position: relative;
    z-index: 1;
    width: 100%;
    max-width: 420px;
    margin: auto;
  }

  /* ===== Kartu utama ===== */
  .auth-card {
    background: #ffffff;
    border-radius: 18px;
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.35);
    padding: 32px 28px 24px;
  }

  /* ===== Brand / header ===== */
  .auth-brand {
    text-align: center;
    margin-bottom: 22px;
  }

  .auth-logo {
    width: 84px;
    height: 84px;
    object-fit: cover;
    border-radius: 50%;
    border: 3px solid <?= $brand ?>;
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.15);
    background: #fff;
    padding: 4px;
  }

  .auth-store-name {
    margin: 12px 0 4px;
    font-size: 22px;
    font-weight: 700;
    color: #1c2333;
    letter-spacing: 0.3px;
    line-height: 1.2;
    word-break: break-word;
  }

  .auth-store-switch {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    color: #5a6478;
    background: #f1f3f9;
    border: 1px solid #e2e6f0;
    padding: 6px 14px;
    border-radius: 999px;
    margin-top: 6px;
  }

  .auth-store-switch a {
    color: <?= $brand ?>;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 4px;
  }

  .auth-store-switch a:hover {
    text-decoration: underline;
  }

  /* ===== Alert ===== */
  .auth-alert {
    border-radius: 10px;
    padding: 12px 14px;
    font-size: 14px;
    line-height: 1.5;
    margin-bottom: 14px;
  }

  .auth-alert ul {
    margin: 0;
    padding-left: 18px;
  }

  .auth-alert-danger {
    background: #fdecec;
    color: #a52828;
    border: 1px solid #f5c6c6;
  }

  .auth-alert-warning {
    background: #fff7e0;
    color: #8a6116;
    border: 1px solid #f0dfae;
  }

  .auth-alert-success {
    background: #eafaf0;
    color: #1d7a43;
    border: 1px solid #bfe8cf;
  }

  /* ===== Google login ===== */
  .auth-google {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    width: 100%;
    min-height: 48px;
    border: 1px solid #d9ddeb;
    border-radius: 10px;
    background: #ffffff;
    color: #333c4e;
    font-weight: 600;
    font-size: 15px;
    text-decoration: none;
    transition: background 0.15s ease;
  }

  .auth-google:hover {
    background: #f4f5fa;
    color: #333c4e;
  }

  .auth-google i {
    font-size: 18px;
    color: #4285f4;
  }

  .auth-divider {
    display: flex;
    align-items: center;
    gap: 12px;
    margin: 18px 0;
    color: #9aa3b5;
    font-size: 13px;
  }

  .auth-divider::before,
  .auth-divider::after {
    content: "";
    flex: 1;
    height: 1px;
    background: #e6e9f2;
  }

  /* ===== Form ===== */
  .auth-field {
    margin-bottom: 16px;
  }

  .auth-label {
    display: block;
    font-size: 14px;
    font-weight: 600;
    color: #3d4659;
    margin-bottom: 6px;
  }

  .auth-input-wrap {
    position: relative;
  }

  .auth-input {
    width: 100%;
    min-height: 48px;
    padding: 12px 44px 12px 42px;
    border: 1.5px solid #d9ddeb;
    border-radius: 10px;
    font-size: 15px;
    color: #232a3a;
    background: #fafbff;
    transition: border-color 0.15s ease, box-shadow 0.15s ease;
  }

  .auth-input::placeholder {
    color: #a6aec0;
  }

  .auth-input:focus {
    outline: none;
    border-color: <?= $brand ?>;
    background: #ffffff;
    box-shadow: 0 0 0 3px rgba(78, 89, 240, 0.15);
  }

  .auth-input-icon {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: #9aa3b5;
    font-size: 16px;
    pointer-events: none;
  }

  .auth-eye {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: #9aa3b5;
    font-size: 16px;
    padding: 10px;
    cursor: pointer;
    border-radius: 8px;
  }

  .auth-eye:hover {
    color: <?= $brand ?>;
    background: #f1f3f9;
  }

  /* Tombol LOGIN full-width di bawah form */
  .auth-submit {
    width: 100%;
    min-height: 50px;
    border: none;
    border-radius: 10px;
    background: <?= $brand ?>;
    color: #ffffff;
    font-size: 16px;
    font-weight: 700;
    letter-spacing: 1px;
    cursor: pointer;
    margin-top: 4px;
    box-shadow: 0 8px 18px rgba(78, 89, 240, 0.3);
    transition: background 0.15s ease, transform 0.1s ease;
  }

  .auth-submit:hover {
    background: rgb(62, 73, 218);
  }

  .auth-submit:active {
    transform: scale(0.98);
  }

  /* ===== Tautan navigasi bawah ===== */
  .auth-links {
    margin-top: 22px;
    border-top: 1px solid #e9ebf3;
    padding-top: 16px;
    display: flex;
    flex-direction: column;
    gap: 10px;
  }

  .auth-link {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    min-height: 44px;
    border-radius: 10px;
    border: 1.5px solid #d9ddeb;
    background: #ffffff;
    color: #3d4659;
    font-size: 14.5px;
    font-weight: 600;
    text-decoration: none;
    padding: 10px 14px;
    transition: background 0.15s ease, border-color 0.15s ease, color 0.15s ease;
  }

  .auth-link:hover {
    border-color: <?= $brand ?>;
    color: <?= $brand ?>;
    background: #f5f6ff;
  }

  .auth-link em {
    font-style: normal;
    font-weight: 700;
    color: <?= $brand ?>;
  }

  .auth-link-ghost {
    border: none;
    background: none;
    color: #5a6478;
    font-weight: 600;
    min-height: 40px;
  }

  .auth-link-ghost:hover {
    background: #f1f3f9;
    color: <?= $brand ?>;
  }

  /* ===== Responsive mobile ===== */
  @media (max-width: 480px) {
    .auth-page {
      padding: 12px 10px;
      align-items: flex-start;
      /* tidak memakan ruang vertikal berlebihan */
    }

    .auth-card {
      padding: 22px 18px 18px;
      border-radius: 14px;
    }

    .auth-logo {
      width: 64px;
      height: 64px;
    }

    .auth-store-name {
      font-size: 18px;
    }

    .auth-input {
      min-height: 50px;
      font-size: 16px;
      /* mencegah zoom otomatis iOS */
    }

    .auth-submit {
      min-height: 52px;
      font-size: 16px;
    }
  }
</style>

<body class="auth-page">
  <div class="auth-bg"></div>

  <div class="auth-wrap">
    <div class="auth-card">

      <!-- Brand -->
      <div class="auth-brand">
        <img class="auth-logo" src="<?= base_url($logo_to_use) ?>" alt="Logo">
        <h1 class="auth-store-name"><?= esc($toko['nama_toko'] ?? 'Toko Online') ?></h1>
        <div class="auth-store-switch">
          <i class="fa fa-store"></i>
          <span>Toko: <strong><?= esc(session()->get('toko_sesi_user') ?? '-') ?></strong></span>
          <a href="<?= base_url('auth/pilih_toko') ?>"><i class="fa fa-exchange"></i> Ganti</a>
        </div>
      </div>

      <!-- Alert validasi -->
      <?php $errors = session()->getFlashdata('errors'); ?>
      <?php if (!empty($errors)) : ?>
        <div class="auth-alert auth-alert-danger" role="alert">
          <ul>
            <?php foreach ($errors as $error) : ?>
              <li><?= esc($error) ?></li>
            <?php endforeach ?>
          </ul>
        </div>
      <?php endif; ?>

      <?php
      if (session()->getFlashdata('pesan')) {
        echo '<div class="auth-alert auth-alert-warning" role="alert">' . session()->getFlashdata('pesan') . '</div>';
      }
      if (session()->getFlashdata('pesan_warning')) {
        echo '<div class="auth-alert auth-alert-warning" role="alert">' . session()->getFlashdata('pesan_warning') . '</div>';
      }
      if (session()->getFlashdata('pesan_success')) {
        echo '<div class="auth-alert auth-alert-success" role="alert">' . session()->getFlashdata('pesan_success') . '</div>';
      }
      ?>

      <!-- Google Login -->
      <a href="<?= base_url('auth/login_google') ?>" class="auth-google">
        <i class="fa fa-google"></i> Masuk dengan Google
      </a>

      <div class="auth-divider"><span>-- atau --</span></div>

      <!-- Manual Login -->
      <?php echo form_open('auth/cek_login_pelanggan'); ?>

      <div class="auth-field">
        <label class="auth-label" for="email">Email</label>
        <div class="auth-input-wrap">
          <i class="fa fa-envelope auth-input-icon"></i>
          <input class="auth-input" type="email" id="email" name="email" placeholder="Masukkan Email" autocomplete="email" required>
        </div>
      </div>

      <div class="auth-field">
        <label class="auth-label" for="password">Password</label>
        <div class="auth-input-wrap">
          <i class="fa fa-lock auth-input-icon"></i>
          <input class="auth-input" type="password" id="password" name="password" placeholder="Masukkan Kata Sandi" autocomplete="current-password" required>
          <button type="button" class="auth-eye" id="togglePassword" aria-label="Tampilkan password"><i class="fa fa-eye"></i></button>
        </div>
      </div>

      <!-- Tombol LOGIN full-width -->
      <button type="submit" class="auth-submit">LOGIN</button>

      <?php echo form_close(); ?>

      <!-- Tautan navigasi -->
      <div class="auth-links">
        <a href="<?= base_url('auth/register_pelanggan') ?>" class="auth-link">
          Belum punya akun? <em>Daftar Pelanggan</em>
        </a>
        <a href="<?= base_url('auth/buka_toko') ?>" class="auth-link">
          <i class="fa fa-store"></i> Ingin Buka Toko? <em>Daftar Pemilik</em>
        </a>
        <a href="<?= base_url('auth/lupa_password_pelanggan') ?>" class="auth-link auth-link-ghost">
          <i class="fa fa-key"></i> Lupa Password?
        </a>
      </div>

    </div>
  </div>

  <!-- jQuery -->
  <script src="<?= base_url() ?>/template_admin/bower_components/jquery/dist/jquery.min.js"></script>
  <script>
    // Tampilkan / sembunyikan password
    document.getElementById('togglePassword').addEventListener('click', function() {
      var pwd = document.getElementById('password');
      var icon = this.querySelector('i');
      if (pwd.type === 'password') {
        pwd.type = 'text';
        icon.className = 'fa fa-eye-slash';
      } else {
        pwd.type = 'password';
        icon.className = 'fa fa-eye';
      }
    });

    // Script slide up alert otomatis
    window.setTimeout(function() {
      $(".auth-alert").fadeTo(500, 0).slideUp(500, function() {
        $(this).remove();
      });
    }, 3000);
  </script>
</body>

</html>