 <?= view('layout_toko/v_navbar.php') ?>

 <body>
     <!-- Product Section -->
     <main class="product-section">

         <!-- /.query foreach  -->
         <?php
            foreach ($data_profil as $key => $value)
            ?>
         <!-- Profile Content -->
         <div class="container">
             <h3>Profil Saya</h3>

             <?php
             $foto = session()->get('foto_pelanggan');
             $isGoogle = !empty($foto) && filter_var($foto, FILTER_VALIDATE_URL);
             $isDefaultTgl = session()->get('tanggal_lahir') === '0000-00-00' || session()->get('tanggal_lahir') === '2000-01-01';
             $isEmptyNoTelp = empty(session()->get('no_telpon'));
             $isEmptyPass = empty(session()->get('password'));
             $isEmptyKota = empty(session()->get('kode_kota'));
             ?>

             <?php if ($isGoogle || $isDefaultTgl || $isEmptyNoTelp || $isEmptyPass || $isEmptyKota): ?>
             <div class="alert alert-warning">
                 <i class="fa fa-exclamation-triangle"></i> <strong>Lengkapi data berikut agar akun Anda sempurna:</strong>
                 <ul style="margin-bottom: 0; margin-top: 8px;">
                     <?php if ($isEmptyPass): ?><li>Password (untuk login manual)</li><?php endif; ?>
                     <?php if ($isEmptyNoTelp): ?><li>No Telepon</li><?php endif; ?>
                     <?php if ($isDefaultTgl): ?><li>Tanggal Lahir</li><?php endif; ?>
                     <?php if ($isEmptyKota): ?><li>Kota Asal (untuk ongkir)</li><?php endif; ?>
                 </ul>
             </div>
             <?php endif; ?>

             <?= form_open_multipart(base_url('pelanggan_kelola_data/update_profile/' . $data_profil['id_pelanggan'])) ?>

             <input type="hidden" name="sesi_user" value="<?= esc($sesi_user) ?>" class="form-control" readonly>

             <div class="form-group profile">
                 <label>Email</label>
                 <input type="email" name="email" value="<?= session()->get('email') ?>" class="form-control profile" placeholder="Masukkan Email" readonly>
             </div>

             <div class="form-group profile">
                 <label>Kata Sandi <?php if ($isEmptyPass): ?><span class="text-danger">*</span><?php endif; ?></label>
                 <input name="password" type="text" value="<?= session()->get('password') ?>" class="form-control profile" placeholder="<?= $isEmptyPass ? 'Wajib diisi untuk login manual' : '' ?>" <?php if ($isEmptyPass) echo 'style="border-color: #dd4b39;"'; ?>>
             </div>

             <div class="form-group profile">
                 <label>Nama Lengkap</label>
                 <input name="nama_pelanggan" value="<?= session()->get('nama_pelanggan') ?>" class="form-control profile" placeholder="Masukkan Nama Lengkap" required>
             </div>

             <div class="form-group profile">
                 <label>Tanggal Lahir <?php if ($isDefaultTgl): ?><span class="text-danger">*</span><?php endif; ?></label>
                 <input type="date" name="tanggal_lahir" value="<?= session()->get('tanggal_lahir') ?>" class="form-control profile" <?php if ($isDefaultTgl) echo 'style="border-color: #dd4b39;"'; ?>>
             </div>

              <div class="form-group profile">
                  <label>No Telepon <?php if ($isEmptyNoTelp): ?><span class="text-danger">*</span><?php endif; ?></label>
                  <input type="number" name="no_telpon" value="<?= session()->get('no_telpon') ?>" class="form-control profile" placeholder="<?= $isEmptyNoTelp ? 'Wajib diisi' : 'Masukkan No Telepon User' ?>" <?php if ($isEmptyNoTelp) echo 'style="border-color: #dd4b39;"'; ?>>
              </div>

              <label><strong>Lokasi Peta</strong> <small class="text-muted">(Geser marker untuk menentukan lokasi Anda)</small></label>
              <div id="map_profil" style="height: 350px; width: 100%; margin-bottom: 15px;"></div>

              <div class="form-group">
                  <label>Longitude & Latitude:</label>
                  <input type="text" id="longitude_profil" name="longitude" class="form-control mb-2" readonly value="<?= esc($data_profil['longitude'] ?? '') ?>">
                  <input type="text" id="latitude_profil" name="latitude" class="form-control mb-2" readonly value="<?= esc($data_profil['latitude'] ?? '') ?>">
                  <input type="hidden" name="alamat" id="alamat_profil" value="<?= esc($data_profil['alamat'] ?? '') ?>">
                  <div id="alamatLengkap_profil" class="alamat-info-box" style="min-height: 80px; padding: 10px; border: 1px solid #d1ecf1; background: #d1ecf1; border-radius: 4px; color: #0c5460;">
                      <i class="fa fa-spinner fa-spin"></i> Mendeteksi lokasi...
                  </div>
              </div>

              <div class="form-group">
                  <label><i class="fa fa-truck"></i> Kota Asal (untuk hitung ongkir) <small class="text-muted">(Otomatis dari peta)</small></label>
                  <input type="text" id="search_kota" class="form-control" readonly placeholder="Otomatis dari lokasi peta..." value="<?= esc($data_profil['nama_kota'] ?? '') ?>" style="background:#f0f0f0;">
                  <small class="text-muted" id="kota_hint">Kota asal otomatis terisi dari lokasi marker peta.</small>
                  <input type="hidden" name="kode_kota" id="kode_kota_profil" value="<?= esc($data_profil['kode_kota'] ?? '') ?>">
                  <input type="hidden" name="nama_kota" id="nama_kota_profil" value="<?= esc($data_profil['nama_kota'] ?? '') ?>">
              </div>

             <div class=" form-group profile">
                 <label>Foto Profil Terkini</label>
                 <p></p>
                 <?php if ($isGoogle): ?>
                     <img src="<?= esc($foto) ?>" class="img-circle" id="gambar_load" width="100px" height="100px">
                 <?php elseif (!empty($foto) && $foto !== 'profil_default.png'): ?>
                     <img src="<?= base_url('fotopelanggan/' . session()->get('foto_pelanggan')) ?>" class="img-circle" id="gambar_load" width="100px" height="100px">
                 <?php else: ?>
                     <img src="<?= base_url('fotodefault/profil_default.png') ?>" class="img-circle" id="gambar_load" width="100px" height="100px">
                 <?php endif; ?>
             </div>

             <div class="form-group profile">
                 <label>Upload Foto Profil Baru</label>
                 <p></p>
                 <input type="file" class="form-control profile" name="foto_pelanggan" id="preview_gambar">
             </div>

             <div class="modal-footer">
                 <button type="submit" class="btn btn-success">Simpan</button>
                 <a href="<?= base_url('pelanggan_kelola_data/profil') ?>" class="btn btn-primary">Kembali</a>
             </div>

             <?= form_close() ?>

         </div>
     </main>

     <!-- Leaflet CSS -->
     <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
     <!-- Leaflet JS -->
     <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

     <script>
         document.addEventListener("DOMContentLoaded", function() {
             var latVal = '<?= $data_profil['latitude'] ?? '' ?>';
             var lngVal = '<?= $data_profil['longitude'] ?? '' ?>';
             var hasCoords = latVal && lngVal;
             var defaultLat = hasCoords ? parseFloat(latVal) : -6.9175;
             var defaultLng = hasCoords ? parseFloat(lngVal) : 107.6191;

             var map = L.map('map_profil').setView([defaultLat, defaultLng], 13);
             L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                 attribution: '&copy; OpenStreetMap contributors'
             }).addTo(map);

             var marker = L.marker([defaultLat, defaultLng], { draggable: true }).addTo(map);
             document.getElementById('latitude_profil').value = defaultLat;
             document.getElementById('longitude_profil').value = defaultLng;

             var debounceTimer = null;
             function onMapMove(lat, lng) {
                 document.getElementById('latitude_profil').value = lat;
                 document.getElementById('longitude_profil').value = lng;
                 if (debounceTimer) clearTimeout(debounceTimer);
                 debounceTimer = setTimeout(function() {
                     geocodeAndDetectKota(lat, lng);
                 }, 500);
             }

             map.on('click', function(e) { marker.setLatLng(e.latlng); onMapMove(e.latlng.lat, e.latlng.lng); });
             marker.on('moveend', function(e) { var pos = e.target.getLatLng(); onMapMove(pos.lat, pos.lng); });
             map.invalidateSize();

             if (hasCoords) {
                 geocodeAndDetectKota(defaultLat, defaultLng);
             }

             function geocodeAndDetectKota(lat, lng) {
                 var alamatBox = document.getElementById('alamatLengkap_profil');
                 alamatBox.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Mendeteksi lokasi...';

                 var controller = new AbortController();
                 var timeoutId = setTimeout(function() { controller.abort(); }, 10000);

                 fetch("https://nominatim.openstreetmap.org/reverse?format=json&lat=" + lat + "&lon=" + lng + "&zoom=18&addressdetails=1&accept-language=id",
                     { headers: { 'User-Agent': 'TokoOnlineApp/1.0' }, signal: controller.signal })
                 .then(function(r) { clearTimeout(timeoutId); return r.json(); })
                 .then(function(data) {
                     if (data.address) {
                         var addr = data.address;
                         var desa = addr.village || addr.hamlet || addr.suburb || addr.neighbourhood || addr.quarter || addr.residential || addr.locality || '-';
                         var provinsi = addr.state || '-';
                         var kota = '-';
                         if (addr.county && /kabupaten|kota/i.test(addr.county)) kota = addr.county;
                         else if (addr.city) kota = addr.city;
                         else if (addr.town) kota = addr.town;
                         else if (addr.county) kota = addr.county;
                         var kecamatan = addr.subdistrict || addr.district || '-';
                         if (kecamatan === '-' && addr.town && addr.town !== kota) kecamatan = addr.town;
                         if (kecamatan === '-' && addr.county && !/kabupaten|kota/i.test(addr.county) && addr.county !== kota) kecamatan = addr.county;
                         if (kota === '-' && kecamatan !== '-') { kota = kecamatan; kecamatan = '-'; }

                         refineAlamat(desa, kecamatan, kota, provinsi, lat, lng, alamatBox);
                     } else {
                         alamatBox.innerHTML = 'Alamat tidak ditemukan.';
                     }
                 })
                 .catch(function(err) {
                     clearTimeout(timeoutId);
                     if (err.name === 'AbortError') alamatBox.innerHTML = '<i class="fa fa-clock-o text-warning"></i> Timeout. Geser marker.';
                     else alamatBox.innerHTML = '<i class="fa fa-times-circle text-danger"></i> Gagal mengambil alamat.';
                 });
             }

             function titleCase(s) {
                 return String(s).toLowerCase().replace(/\b\w/g, function(c) { return c.toUpperCase(); });
             }

             function showAlamatProfil(alamatBox, desa, kecamatan, kota, provinsi, lat, lng) {
                 alamatBox.innerHTML =
                     '<div><strong>Desa/Kelurahan:</strong> ' + desa + '</div>' +
                     '<div><strong>Kecamatan:</strong> ' + kecamatan + '</div>' +
                     '<div><strong>Kota/Kabupaten:</strong> ' + kota + '</div>' +
                     '<div><strong>Provinsi:</strong> ' + provinsi + '</div>';

                 var alamatText = [desa, kecamatan, kota, provinsi].filter(function(s) { return s !== '-'; }).join(', ');
                 document.getElementById('alamat_profil').value = alamatText;

                 detectKota(desa, kecamatan, kota, provinsi, lat, lng);
             }

             function refineAlamat(desa, kecamatan, kota, provinsi, lat, lng, alamatBox) {
                 if (kecamatan !== '-' || desa === '-' || kota === '-') {
                     showAlamatProfil(alamatBox, desa, kecamatan, kota, provinsi, lat, lng);
                     return;
                 }
                 fetch("<?= base_url('pelanggan_kelola_data/refine_alamat') ?>?desa=" + encodeURIComponent(desa) +
                     "&kota=" + encodeURIComponent(kota) + "&lat=" + lat + "&lng=" + lng)
                 .then(function(r) { return r.json(); })
                 .then(function(res) {
                     if (res.match) {
                         desa = titleCase(res.match.desa);
                         kecamatan = titleCase(res.match.kecamatan);
                     }
                     showAlamatProfil(alamatBox, desa, kecamatan, kota, provinsi, lat, lng);
                 })
                 .catch(function() { showAlamatProfil(alamatBox, desa, kecamatan, kota, provinsi, lat, lng); });
             }

             function detectKota(desa, kecamatan, kota, provinsi, lat, lng) {
                 var queries = [
                     [desa, kecamatan, kota, provinsi].filter(function(s) { return s !== '-'; }).join(', '),
                     [kecamatan, kota, provinsi].filter(function(s) { return s !== '-'; }).join(', '),
                     [kota, provinsi].filter(function(s) { return s !== '-'; }).join(', ')
                 ];
                 var fallbackName = [kota, provinsi].filter(function(s) { return s !== '-'; }).join(', ');
                 (function tryQ(i) {
                     if (i >= queries.length) {
                         if (fallbackName) {
                             document.getElementById('search_kota').value = fallbackName;
                             document.getElementById('search_kota').style.borderColor = '#ffc107';
                             document.getElementById('kota_hint').innerHTML = '<span class="text-warning"><i class="fa fa-exclamation-triangle"></i> Kota terdeteksi dari alamat (tanpa validasi API).</span>';
                         }
                         return;
                     }
                     fetch("<?= base_url('pelanggan_kelola_data/search_destination') ?>?search=" + encodeURIComponent(queries[i]) + "&lat=" + lat + "&lng=" + lng)
                     .then(function(r) { return r.json(); })
                     .then(function(res) {
                         if (res.error) { tryQ(i + 1); return; }
                         if (res.data && res.data.length > 0) {
                             var addr = queries[i].toLowerCase();
                             var scored = res.data.map(function(item) {
                                 var l = item.label.toLowerCase();
                                 var s = 0;
                                 addr.split(',').forEach(function(p) { if (l.includes(p.trim())) s++; });
                                 return Object.assign({}, item, { score: s });
                             }).sort(function(a, b) { return b.score - a.score; });
                             document.getElementById('kode_kota_profil').value = scored[0].id;
                             document.getElementById('nama_kota_profil').value = scored[0].label;
                             document.getElementById('search_kota').value = scored[0].label;
                             document.getElementById('search_kota').style.borderColor = '#28a745';
                             document.getElementById('kota_hint').innerHTML = '<span class="text-success"><i class="fa fa-check-circle"></i> Kota asal terdeteksi.</span>';
                         } else { tryQ(i + 1); }
                     }).catch(function() { tryQ(i + 1); });
                 })(0);
             }
         });
     </script>

     <?= view('layout_toko/v_footer.php') ?>