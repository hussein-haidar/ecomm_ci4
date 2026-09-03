<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Gudang Web | <?= $title3 ?></title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="<?= base_url() ?>/template/bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?= base_url() ?>/template/bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?= base_url() ?>/template/bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?= base_url() ?>/template/dist/css/AdminLTE.min.css">
  <!-- Custom icon styles for this template-->
  <link href="<?= base_url() ?>/icon/gudang.ico" rel="shortcut icon">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

  <!-- Custom Print Styles -->
  <style>
    @page {
      margin: 0;
      /* Remove default margins */
    }

    @media print {
      body {
        margin: 0;
        /* Remove body margins */
        padding: 10mm;
        /* Add padding to avoid cutting off content */
      }

      .no-print {
        display: none !important;
        /* Hide elements */
      }
    }

    .kop-surat {
      text-align: center;
      margin: 20px 0;
      /* Add margin to the top and bottom */
    }

    .kop-surat h1 {
      margin: 0;
      font-size: 24px;
    }

    .kop-surat h2 {
      margin: 0;
      font-size: 18px;
    }

    .kop-surat p {
      margin: 0;
      font-size: 14px;
    }

    .kop-surat img {
      width: 100px;
      height: auto;
    }

    .page-header {
      margin-top: 20px;
      /* Add margin to push the content down */
    }

    .report-date {
      float: left;
      /* Align the date to the left */
      width: 100%;
      /* Ensure it takes up the full width */
      text-align: left;
      /* Align text to the left */
    }

    .page-header h2 small {
      display: block;
      /* Make small elements take up full width */
    }
  </style>
</head>

<body onload="window.print();">
  <div class="wrapper">
    <!-- Kop Surat -->
    <div class="kop-surat">
      <?php
      $logo = get_data_toko()['logo_website'] ?? 'fotodefault/gudang.png';
      $nama_toko = get_data_toko()['nama_toko'] ?? 'Nama Toko Default';
      $alamat = get_data_toko()['alamat_pusat'] ?? 'Alamat Default';
      $telpon = get_data_toko()['wa_pusat'] ?? 'Nomor Telepon Default';
      ?>
      <img src="<?= base_url('logowebsite/' . $logo) ?>" alt="Logo">
      <h1><?= $nama_toko; ?></h1>
      <h2>LAPORAN MUTASI PRODUK BULANAN <?= $nama_toko; ?></h2>
      <p>Alamat: <?= $alamat; ?></p>
      <p>Telpon: <?= $telpon; ?></p>
    </div>

    <!-- Main content -->
    <section class="invoice">
      <!-- title row -->
      <div class="row">
        <div class="col-xs-12">
          <h2 class="page-header">
            <small class="report-date">
              Laporan Stok Produk Dibuat:
              <?php
              $bulan = array(
                1 => 'Januari',
                'Februari',
                'Maret',
                'April',
                'Mei',
                'Juni',
                'Juli',
                'Agustus',
                'September',
                'Oktober',
                'November',
                'Desember'
              );
              $tanggalSekarang = date('d') . ' ' . $bulan[date('n')] . ' ' . date('Y');
              echo $tanggalSekarang;
              ?>
            </small>
          </h2>
        </div>
        <!-- /.col -->
      </div>
      <!-- info row -->
      <div class="row invoice-info">
        <!-- Additional information if needed -->
      </div>
      <!-- /.row -->

      <!-- Table row -->
      <div class="row">
        <div class="col-xs-12 table-responsive">
          <table id="example1" class="table table-bordered table-striped ">
            <thead>
              <tr>
                <th scope="col" width="1%">No</th>
                <th>Kode Stok Produk</th>
                <th>Tanggal Masuk</th>
                <th>Nama Produk</th>
                <th>Jumlah Stok Produk</th>
                <th>Harga Total Produk</th>
                <th>Foto Produk</th>
            
              </tr>
            </thead>
            <tbody>
              <?php $no = 1;
              foreach ($data_laporan_admin as $key => $value) {
              ?>
                <tr>
                  <td><?= $no++; ?></td>
                  <td><?= $value['kode_stok']; ?></td>
                  <td>
                    <?php
                    setlocale(LC_TIME, 'id_ID');
                    $tanggal = strtotime($value['tanggal_masuk_produk']);
                    $bulan = array(
                      1 => 'Januari',
                      'Februari',
                      'Maret',
                      'April',
                      'Mei',
                      'Juni',
                      'Juli',
                      'Agustus',
                      'September',
                      'Oktober',
                      'November',
                      'Desember'
                    );
                    $formattedDate = date('d', $tanggal) . ' ' . $bulan[date('n', $tanggal)] . ' ' . date('Y', $tanggal);
                    echo $formattedDate;
                    ?>
                  </td>
                  <td><?= $value['nama_produk']; ?></td>
                  <td><?= $value['jumlah_stok_produk']; ?></td>
                  <td>Rp. <?= number_format($value['total_harga'], 0, ',', '.'); ?></td>
                  <td><img src="<?= base_url('fotoproduk/' . $value['foto_produk']) ?>" class="img-circle" width="80px" height="80px"></td>
              
                </tr>
              <?php } ?>
            </tbody>

          </table>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <div class="row">
        <!-- accepted payments column -->
        <div class="col-xs-4"></div>

        <div class="col-xs-4"></div>
        <!-- /.col -->
        <div class="col-xs-4">
          Pekalongan,
          <?php
          echo $tanggalSekarang;
          ?>
          </br>
          Penanggung Jawab:
          </br>
          </br>
          </br>
          </br>
          </br>
          <?= session()->get('nama_lengkap') ?>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- ./wrapper -->
</body>

</html>