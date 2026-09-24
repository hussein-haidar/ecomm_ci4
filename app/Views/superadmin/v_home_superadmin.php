<?php
if (session()->getFlashdata('pesan_welcome')) {
    echo '<div class="alert alert-success" role="alert">';
    echo session()->getFlashdata('pesan_welcome');
    echo '</div>';
}
?>
</p>
<!-- Pop up alert peringatan -->
<?php
if (session()->getFlashdata('message') && !session()->getFlashdata('pesan_welcome')) {
    echo '<div class="alert alert-warning" role="alert">';
    echo session()->getFlashdata('message');
    echo '</div>';
}
?>

<!-- Run text -->
<div class="card-body">
    <marquee style="font-family:arial; font-size:30px; color:#000000;">Selamat Datang Di <?= $title ?></marquee>
</div>
<br>

<div class="row">

    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-aqua">
            <div class="inner">
                <h3> <?= $tot_toko ?></h3>
                <p>Total Toko</p>
            </div>
            <div class="icon">
                <i class="ion ion-home"></i>
            </div>
        </div>
    </div>
    <!-- ./col -->

    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-green">
            <div class="inner">
                <h3> <?= $tot_toko_aktif ?></h3>
                <p>Toko Aktif</p>
            </div>
            <div class="icon">
                <i class="ion ion-checkmark-circled"></i>
            </div>
        </div>
    </div>
    <!-- ./col -->

    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-red">
            <div class="inner">
                <h3> <?= $tot_toko_nonaktif ?></h3>
                <p>Toko Nonaktif</p>
            </div>
            <div class="icon">
                <i class="ion ion-close-circled"></i>
            </div>
        </div>
    </div>
    <!-- ./col -->

    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-yellow">
            <div class="inner">
                <h3> <?= $tot_toko_menunggu ?></h3>
                <p>Toko Menunggu Verifikasi</p>
            </div>
            <div class="icon">
                <i class="ion ion-clock"></i>
            </div>
        </div>
    </div>
    <!-- ./col -->

    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-yellow">
            <div class="inner">
                <h3> <?= $tot_produk ?></h3>
                <p>Total Produk (Semua Toko)</p>
            </div>
            <div class="icon">
                <i class="ion ion-bag"></i>
            </div>
        </div>
    </div>
    <!-- ./col -->

</div>
<!-- /.row -->

<div class="row">

    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-aqua">
            <div class="inner">
                <h3> <?= $tot_pemilik ?></h3>
                <p>Total Pemilik</p>
            </div>
            <div class="icon">
                <i class="ion ion-person"></i>
            </div>
        </div>
    </div>
    <!-- ./col -->

    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-green">
            <div class="inner">
                <h3> <?= $tot_admin ?></h3>
                <p>Total Admin Toko</p>
            </div>
            <div class="icon">
                <i class="ion ion-person-stalker"></i>
            </div>
        </div>
    </div>
    <!-- ./col -->

    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-yellow">
            <div class="inner">
                <h3> <?= $tot_pelanggan ?></h3>
                <p>Total Pelanggan</p>
            </div>
            <div class="icon">
                <i class="ion ion-android-cart"></i>
            </div>
        </div>
    </div>
    <!-- ./col -->

    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-aqua">
            <div class="inner">
                <h3> <?= $tot_stok ?></h3>
                <p>Total Data Stok</p>
            </div>
            <div class="icon">
                <i class="ion ion-cube"></i>
            </div>
        </div>
    </div>
    <!-- ./col -->

</div>
<!-- /.row -->

<div class="row">

    <div class="col-lg-6 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-red">
            <div class="inner">
                <h3> <?= $tot_penjualan ?></h3>
                <p>Total Penjualan (Unit)</p>
            </div>
            <div class="icon">
                <i class="ion ion-stats-bars"></i>
            </div>
        </div>
    </div>
    <!-- ./col -->

    <div class="col-lg-6 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-green">
            <div class="inner">
                <h3>Rp <?= number_format($tot_nilai_penjualan, 0, ',', '.') ?></h3>
                <p>Total Nilai Penjualan</p>
            </div>
            <div class="icon">
                <i class="ion ion-cash"></i>
            </div>
        </div>
    </div>
    <!-- ./col -->

</div>
<!-- /.row -->