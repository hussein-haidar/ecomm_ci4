<br>

<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title"><i class="fa fa-user"></i> Profil Superadmin</h3>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
        <div class="row">
            <div class="col-md-4 text-center">
                <img src="<?= base_url('fotouser/' . session()->get('foto_user')) ?>" class="img-circle" alt="User Image" style="width: 150px; height: 150px; object-fit: cover;">
            </div>
            <div class="col-md-8">
                <table class="table table-bordered">
                    <tr>
                        <th style="width: 30%;">Nama Lengkap</th>
                        <td><?= esc(session()->get('nama_lengkap')) ?></td>
                    </tr>
                    <tr>
                        <th>Username</th>
                        <td><?= esc(session()->get('username')) ?></td>
                    </tr>
                    <tr>
                        <th>Level</th>
                        <td><span class="label label-warning">Superadmin</span></td>
                    </tr>
                    <tr>
                        <th>Jobdesk</th>
                        <td><?= esc(session()->get('jobdesk_user')) ?></td>
                    </tr>
                    <tr>
                        <th>No Telepon</th>
                        <td><?= esc(session()->get('notelpon_user')) ?></td>
                    </tr>
                    <tr>
                        <th>Last Login</th>
                        <td><?= esc(session()->get('last_login')) ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <!-- /.box-body -->
</div>
<!-- /.box -->