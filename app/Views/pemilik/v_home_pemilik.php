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
// Tampilkan alert warning hanya jika tidak ada pesan sukses
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
        <div class="small-box bg-green">
            <div class="inner">

                <h3> <?= count($tot_jenis) ?></h3> <!-- Tampilkan jumlah permintaab produk -->
                <p>Data Jenis Produk</p>
            </div>
            <div class="icon">
                <i class="ion ion-stats-bars"></i>
            </div>
        </div>
    </div>
    <!-- ./col -->

    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-red">
            <div class="inner">
                <h3> <?= count($tot_varian) ?></h3> <!-- Tampilkan jumlah permintaab produk -->
                <p>Data Varian Produk</p>
            </div>
            <div class="icon">
                <i class="ion ion-stats-bars"></i>
            </div>
        </div>
    </div>
    <!-- ./col -->

    <!-- Small boxes (Stat box) -->
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-aqua">
            <div class="inner">
                <h3> <?= count($tot_produk) ?></h3> <!-- Tampilkan jumlah produk -->
                <p>Data Produk</p>
            </div>
            <div class="icon">
                <i class="ion ion-stats-bars"></i>
            </div>
        </div>
    </div>
    <!-- ./col -->

    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-yellow">
            <div class="inner">
                <h3> <?= count($tot_stok) ?></h3> <!-- Tampilkan jumlah stok produk -->
                <p>Data Stok Produk</p>
            </div>
            <div class="icon">
                <i class="ion ion-stats-bars"></i>
            </div>
        </div>
    </div>
    <!-- ./col -->

</div>
<!-- /.row -->