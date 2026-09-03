 <?= view('layout_toko/v_navbar.php') ?>

 <body>
     <!-- Product Section -->
     <main class="product-section">

         <!-- /.query foreach  -->
         <?php
            foreach ($data_profil as $key => $result)
            ?>
         <!-- Profile Content -->
         <div class="container">
             <h3>Profil Saya</h3>

              <!-- Profile Picture -->
              <div class="form-group profile text-center">
                  <?php $foto = session()->get('foto_pelanggan'); $isGoogle = !empty($foto) && filter_var($foto, FILTER_VALIDATE_URL); $hasFoto = !empty($foto) && $foto !== 'profil_default.png'; ?>
                  <?php if ($isGoogle): ?>
                      <img src="<?= esc($foto) ?>" class="profile-pic" id="gambar_load">
                  <?php elseif ($hasFoto): ?>
                      <img src="<?= base_url('fotopelanggan/' . session()->get('foto_pelanggan')) ?>" class="profile-pic" id="gambar_load">
                  <?php else: ?>
                      <img src="<?= base_url('fotodefault/profil_default.png') ?>" class="profile-pic" id="gambar_load">
                  <?php endif; ?>
              </div>

             <div class="form-group profile">
                 <label>Email</label>
                 <input name="email" value="<?= session()->get('email') ?>" class="form-control profile" placeholder="Masukkan Email" readonly>
             </div>

             <div class="form-group profile">
                 <label>Password</label>
                 <input name="password" type="password" id="ShowPass" value="<?= session()->get('password') ?>" class="form-control profile" readonly>
                 <input type="checkbox" onclick="myFunction()">&nbsp; Show Password
             </div>

             <div class="form-group profile">
                 <label>Nama Lengkap</label>
                 <input name="nama_pelanggan" value="<?= session()->get('nama_pelanggan') ?>" class="form-control profile" readonly>
             </div>
             
             <?php
                $tanggal = strtotime(session()->get('tanggal_lahir'));
                $bulan = [
                    1 => 'Januari',
                    2 => 'Februari',
                    3 => 'Maret',
                    4 => 'April',
                    5 => 'Mei',
                    6 => 'Juni',
                    7 => 'Juli',
                    8 => 'Agustus',
                    9 => 'September',
                    10 => 'Oktober',
                    11 => 'November',
                    12 => 'Desember'
                ];
                $formattedTanggal = date('d', $tanggal) . ' ' . $bulan[(int)date('n', $tanggal)] . ' ' . date('Y', $tanggal);
                ?>

             <div class="form-group profile">
                 <label>Tanggal Lahir</label>
                 <input type="text" class="form-control profile" value="<?= $formattedTanggal ?>" readonly>
             </div>

             <div class="form-group profile">
                 <label>No Telepon</label>
                 <input type="number" value="<?= session()->get('no_telpon') ?>" class="form-control profile" readonly>
             </div>

             <div class="form-group profile">
                 <label>Terakhir Login</label>
                 <?php
                    setlocale(LC_TIME, 'id_ID');
                    $tanggal = strtotime($result['last_login']);
                    $bulan = array(1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
                    $formattedDate = date('d', $tanggal) . ' ' . $bulan[date('n', $tanggal)] . ' ' . date('Y H:i:s', $tanggal);
                    ?>
                 <input type="text" value="<?= $formattedDate ?>" class="form-control profile" readonly>
             </div>

             <div>
                 <a href="<?= base_url('pelanggan_kelola_data/edit/' . $result['id_pelanggan']) ?>" class="btn btn-warning"><i class="fa fa-fw fa-edit"></i> Edit Profil</a>
             </div>

         </div>
     </main>

 </body>

 <?= view('layout_toko/v_footer.php') ?>