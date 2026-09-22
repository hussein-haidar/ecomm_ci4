<div class="box">
    <div class="box-header">
        <a href="<?= base_url('pemilik_kelola_data/banner') ?>" class="btn-sm"><i class="fa fa-arrow-left" aria-hidden="true"></i> Kembali ke Daftar Banner Promo</a>
    </div>

    <div class="box-body">
        <!-- /.alert windows success -->
        <?php
        if (session()->getFlashdata('pesan')) {
            echo '<div class="alert alert-success" role="alert">';
            echo session()->getFlashdata('pesan');
            echo '</div>';
        }
        ?>

        <div class="table-responsive">
            <table id="example1" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th scope="col" width="1%">No</th>
                        <th>Gambar</th>
                        <th>Judul</th>
                        <th>Deskripsi</th>
                        <th scope="col" width="auto">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1;
                    foreach ($data_banner_dihapus as $key => $value) {
                    ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td>
                                <img src="<?= base_url('fotobanner/' . $value['foto_banner']) ?>" width="100" alt="<?= esc($value['judul_banner']) ?>">
                            </td>
                            <td><?= esc($value['judul_banner']); ?></td>
                            <td><?= esc($value['deskripsi_banner'] ?? '-'); ?></td>
                            <td>
                                <a href="<?= base_url('pemilik_kelola_data/restore_banner/' . $value['id_banner']) ?>" class="btn btn-success btn-xs">
                                    <i class="fa fa-fw fa-undo"></i>Restore
                                </a>
                                <button class="btn btn-danger btn-sm btn-xs" data-toggle="modal" data-target="#delete<?= $value['id_banner']; ?>">
                                    <i class="fa fa-fw fa-trash"></i> Delete
                                </button>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Delete -->
<?php foreach ($data_banner_dihapus as $value) : ?>
    <div class="modal fade" id="delete<?= $value['id_banner']; ?>" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="deleteModalLabel">Hapus Banner Promo</h4>
                </div>
                <div class="modal-body">
                    <h4>
                        <p>
                            <center>Apakah Anda Ingin Menghapus Data&nbsp;<?= esc($value['judul_banner']) ?> ?</center>
                        </p>
                    </h4>
                </div>
                <div class="modal-footer">
                    <a href="<?= base_url('pemilik_kelola_data/delete_hard_banner/' . $value['id_banner']); ?>" class="btn btn-success">Hapus</a>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                </div>
            </div>
        </div>
    </div>
<?php endforeach; ?>