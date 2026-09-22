<div class="box">
    <div class="box-header">
        <!-- Judul atau header lainnya bisa ditambahkan di sini -->
    </div>

    <!-- /.box-header -->
    <div class="box-body">
        <!-- Alert Windows Success -->
        <?php
        // Menampilkan pesan sukses
        if (session()->getFlashdata('success')) {
            echo '<div class="alert alert-success" role="alert">';
            echo session()->getFlashdata('success');
            echo '</div>';
        }

        // Menampilkan pesan error
        if (session()->getFlashdata('error')) {
            echo '<div class="alert alert-danger" role="alert">';
            echo session()->getFlashdata('error');
            echo '</div>';
        }
        ?>

        <!-- Form untuk trigger proses backup -->
        <form action="<?= base_url('pemilik_kelola_website/proses_db') ?>" method="post">
            <?= csrf_field() ?>
            <div class="form-group">
                <label>Silahkan klik tombol backup untuk ambil database toko</label>
            </div>
            <button type="submit" class="btn btn-sm btn-success">
                <i class="fa fa-plus-circle" aria-hidden="true"></i> Backup Database
            </button>
        </form>
    </div>
</div>