<div class="box box-primary box-solid">
    <div class="box-header">
        <a href="<?= base_url('admin_kelola_data/bayar') ?>" class="btn-sm">
            <i class="fa fa-arrow-left" aria-hidden="true"></i> Kembali ke Daftar Bayar
        </a>
    </div>

    <!-- /.box-header -->
    <div class="box-body">
        <div class="row">
            <style>
                iframe {
                    width: 100%;
                    height: 80vh;
                    /* Sesuaikan dengan viewport height */
                    border: none;
                    /* Opsional: Menghapus border */
                }

                .box-header a.btn-sm {
                    font-size: 14px;
                    /* Atur ukuran font link */
                    padding: 5px 10px;
                    /* Sesuaikan padding tombol */
                }
            </style>
            <div class="col-sm-12">
                <iframe src="<?= base_url('fotobayar/' . $data_bayar['foto_bayar']) ?>" width="100%" height="800px"></iframe>
            </div>
        </div>
    </div>
</div>