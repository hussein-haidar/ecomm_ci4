<div class="box">
    <div class="box-header">
    </div>

    <!-- /.box-header -->
    <div class="box-body">
        <form method="post" action="<?= base_url('admin_laporan_mingguan/filter_stok_by_date') ?>">
            <?= csrf_field() ?>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="start_date">Dari Tanggal:</label>
                        <input type="date" name="start_date" id="start_date" class="form-control" value="<?= $start_date ?>" required>
                    </div>

                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="end_date">Sampai Tanggal:</label>
                        <input type="date" name="end_date" id="end_date" class="form-control" value="<?= $end_date ?>" required>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <button type="submit" class="btn btn-primary form-control">Filter</button>
                    </div>
                </div>
            </div>

            <?php if ($start_date && $end_date) : ?>
                <a href="<?= base_url('admin_laporan_mingguan/cetak_laporan_stok') . '?start_date=' . $start_date . '&end_date=' . $end_date ?>" class="btn btn-sm btn-success" target="_blank">
                    <i class="fa fa-print" aria-hidden="true"></i> Cetak Data
                </a>
                <a href="<?= base_url('admin_laporan_mingguan/export_excel') . '?start_date=' . $start_date . '&end_date=' . $end_date ?>" class="btn btn-sm btn-info">
                    <i class="fa fa-file-excel-o" aria-hidden="true"></i> Export Excel
                </a>
                <a href="<?= base_url('admin_laporan_mingguan/reset_filter_stok') ?>" class="btn btn-sm btn-secondary">
                    <i class="fa fa-refresh" aria-hidden="true"></i> Reset Filter
                </a>
            <?php endif; ?>
        </form>
        <p></p>

        <div class="table-responsive">
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
    </div>
</div>