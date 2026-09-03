<div class="row">
    <div class="col-md-3">
    </div>
    <div class="col-md-6">
        <div class="box">
            <div class="box-header">
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <!-- Pop up alert -->
                <?php
                $errors = session()->getFlashdata('errors');
                if (!empty($errors)) { ?>
                    <div class="alert alert-danger" role="alert">
                        <ul>
                            <?php foreach ($errors as $key => $value) { ?>
                                <li><?= esc($value) ?></li>
                            <?php } ?>
                        </ul>
                    </div>
                <?php } ?>

                <?= form_open_multipart('pemilik_kelola_website/save'); ?>

                <div class="form-group">
                    <label>Sesi User</label>
                    <input type="text" name="sesi_user" value="<?= esc($sesi_user) ?>" class="form-control" readonly>
                </div>

                <input type="hidden" name="level" value="<?= esc($level) ?>" class="form-control" readonly>

                <div class="form-group">
                    <label>Nama Toko</label>
                    <input name="nama_toko" class="form-control" placeholder="Masukkan Nama Toko" required>
                </div>

                <!-- MAP TOKO PUSAT -->
                <label><strong>Pilih Lokasi Toko Pusat</strong></label>
                <div id="map_pusat" style="height: 400px; width: 100%; margin-bottom: 15px;"></div>

                <div class="form-group">
                    <label for="longitude_pusat">Longitude & Latitude:</label>
                    <input type="text" id="longitude_pusat" name="longitude_pusat" class="form-control mb-2" readonly required>
                    <input type="text" id="latitude_pusat" name="latitude_pusat" class="form-control mb-2" readonly required>
                    <div id="alamatLengkap_pusat" class="alamat-info-box" style="min-height: 80px; padding: 10px; border: 1px solid #d1ecf1; background: #d1ecf1; border-radius: 4px; color: #0c5460;">
                        <i class="fa fa-spinner fa-spin"></i> Mendeteksi lokasi...
                    </div>
                </div>

                <div class="form-group">
                    <label>Kota Asal Toko (untuk hitung ongkir)</label>
                    <input type="text" id="search_kota_asal" class="form-control" readonly placeholder="Otomatis dari peta..." style="background:#f0f0f0;">
                    <input type="hidden" id="kode_kota_hidden" name="kode_kota" value="">
                    <input type="hidden" id="nama_kota_hidden" name="nama_kota" value="">
                    <small class="text-muted"><i class="fa fa-map-marker"></i> Kota asal otomatis terisi dari lokasi marker peta pusat.</small>
                </div>

                <div class="form-group">
                    <label>Alamat Lengkap</label>
                    <input name="alamat_pusat" value="" class="form-control" placeholder="Masukkan Alamat Toko" required>
                </div>

                <div class="form-group">
                    <label>Nomor Telepon Toko Pusat</label>
                    <input type="number" name="wa_pusat" value="" class="form-control" placeholder="Masukkan No Telepon Toko" required>
                </div>

                <!-- TOKO CABANG -->
                <div style="display:flex; justify-content:space-between; align-items:center; margin-top:15px;">
                    <label style="margin:0;"><strong>Toko Cabang</strong></label>
                    <button type="button" id="btn_add_cabang" class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Tambah Cabang</button>
                </div>

                <div id="cabang_container">
                    <div class="cabang-entry" data-index="0" style="border:1px solid #ddd; border-radius:8px; padding:15px; margin-top:10px; background:#fafafa; position:relative;">
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">
                            <strong>Cabang 1</strong>
                            <button type="button" class="btn btn-danger btn-sm btn-remove-cabang" style="display:none;"><i class="fa fa-minus"></i> Hapus</button>
                        </div>

                        <label><strong>Pilih Lokasi</strong></label>
                        <div id="map_cabang_0" class="map-cabang" style="height: 400px; width: 100%; margin-bottom: 15px;"></div>

                        <div class="form-group">
                            <label>Longitude & Latitude:</label>
                            <input type="text" name="cabang[0][longitude]" class="form-control mb-2 longitude-input" readonly required>
                            <input type="text" name="cabang[0][latitude]" class="form-control mb-2 latitude-input" readonly required>
                            <div class="alamat-info-box alamat-box" style="min-height: 80px; padding: 10px; border: 1px solid #d1ecf1; background: #d1ecf1; border-radius: 4px; color: #0c5460;">
                                <i class="fa fa-spinner fa-spin"></i> Mendeteksi lokasi...
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Nama Cabang</label>
                            <input name="cabang[0][nama_cabang]" class="form-control" placeholder="Nama Toko Cabang">
                        </div>

                        <div class="form-group">
                            <label>Alamat Lengkap</label>
                            <input name="cabang[0][alamat_cabang]" class="form-control" placeholder="Masukkan Alamat Cabang">
                        </div>

                        <div class="form-group">
                            <label>Nomor Telepon</label>
                            <input type="number" name="cabang[0][wa_cabang]" class="form-control" placeholder="Masukkan No Telepon Cabang">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Footer Toko</label>
                    <input type="text" name="footer_title" value="" class="form-control" placeholder="Masukkan Footer Toko" required>
                </div>

                <div class="form-group">
                    <label>Link IG</label>
                    <input type="text" name="link_IG" value="" class="form-control" placeholder="Masukkan Link Toko" required>
                </div>

                <div class="form-group">
                    <label>Link IG</label>
                    <input type="text" name="link_IG" value="" class="form-control" placeholder="Masukkan Link Toko" required>
                </div>

                <div class="form-group">
                    <label>Link Tiktok</label>
                    <input type="text" name="link_Tiktok" value="" class="form-control" placeholder="Masukkan Link Toko" required>
                </div>

                <div class="form-group">
                    <label for="logo_website">Ganti Logo Website (Opsional)</label>
                    <input type="file" name="logo_website" id="logo_input" class="form-control">
                    <small class="text-muted">Kosongkan jika tidak ingin mengganti foto.</small>
                </div>

                <div class="form-group">
                    <label for="bgd_web">Ganti Background Website (Opsional)</label>
                    <input type="file" name="bgd_web" id="bg_input" class="form-control">
                    <small class="text-muted">Kosongkan jika tidak ingin mengganti foto.</small>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Simpan</button>
                    <a href="<?= base_url('pemilik_kelola_website') ?>" class="btn btn-primary">Kembali</a>
                </div>
                <?php echo form_close() ?>
            </div>
        </div>

    </div>
    <div class="col-md-3"></div>
</div>

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const defaultPusatLat = -6.9175;
        const defaultPusatLng = 107.6191;

        function initMapGeneric(id, defaultLat, defaultLng, latInputEl, lngInputEl, alamatBoxEl, isPusat) {
            if (!document.getElementById(id)) return;
            let map = L.map(id).setView([defaultLat, defaultLng], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);
            let marker = L.marker([defaultLat, defaultLng], { draggable: true }).addTo(map);
            latInputEl.value = defaultLat;
            lngInputEl.value = defaultLng;

            let debounceTimerMap = null;
            function onMapMove(lat, lng) {
                latInputEl.value = lat;
                lngInputEl.value = lng;
                if (debounceTimerMap) clearTimeout(debounceTimerMap);
                debounceTimerMap = setTimeout(function() {
                    geocodeAddress(lat, lng, alamatBoxEl, isPusat);
                }, 500);
            }
            map.on('click', function(e) { marker.setLatLng(e.latlng); onMapMove(e.latlng.lat, e.latlng.lng); });
            marker.on('moveend', function(e) { const pos = e.target.getLatLng(); onMapMove(pos.lat, pos.lng); });
            map.invalidateSize();
        }

        function geocodeAddress(lat, lng, alamatBoxEl, isPusat) {
            alamatBoxEl.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Mendeteksi lokasi...';
            const controller = new AbortController();
            const timeoutId = setTimeout(() => controller.abort(), 10000);
            fetch("https://nominatim.openstreetmap.org/reverse?format=json&lat=" + lat + "&lon=" + lng + "&zoom=18&addressdetails=1&accept-language=id",
                { headers: { 'User-Agent': 'TokoOnlineApp/1.0' }, signal: controller.signal })
            .then(r => { clearTimeout(timeoutId); return r.json(); })
            .then(data => {
                if (data.address) {
                    const addr = data.address;
                    let desa = addr.village || addr.hamlet || addr.suburb || addr.neighbourhood || addr.quarter || addr.residential || addr.locality || '-';
                    let provinsi = addr.state || '-';
                    let kota = '-';
                    if (addr.county && /kabupaten|kota/i.test(addr.county)) kota = addr.county;
                    else if (addr.city) kota = addr.city;
                    else if (addr.town) kota = addr.town;
                    else if (addr.county) kota = addr.county;
                    let kecamatan = addr.subdistrict || addr.district || '-';
                    if (kecamatan === '-' && addr.town && addr.town !== kota) kecamatan = addr.town;
                    if (kecamatan === '-' && addr.county && !/kabupaten|kota/i.test(addr.county) && addr.county !== kota) kecamatan = addr.county;
                    if (kota === '-' && kecamatan !== '-') { kota = kecamatan; kecamatan = '-'; }
                    refineAlamat(desa, kecamatan, kota, provinsi, lat, lng, alamatBoxEl, isPusat);
                } else {
                    alamatBoxEl.innerHTML = 'Alamat tidak ditemukan.';
                }
            })
            .catch(err => {
                clearTimeout(timeoutId);
                if (err.name === 'AbortError') alamatBoxEl.innerHTML = '<i class="fa fa-clock-o text-warning"></i> Timeout. Geser marker.';
                else alamatBoxEl.innerHTML = '<i class="fa fa-times-circle text-danger"></i> Gagal mengambil alamat.';
            });
        }

        function titleCase(s) {
            return String(s).toLowerCase().replace(/\b\w/g, function(c) { return c.toUpperCase(); });
        }

        function showAlamatBox(alamatBoxEl, desa, kecamatan, kota, provinsi, lat, lng, isPusat) {
            alamatBoxEl.innerHTML =
                '<div><strong>Desa/Kelurahan:</strong> ' + desa + '</div>' +
                '<div><strong>Kecamatan:</strong> ' + kecamatan + '</div>' +
                '<div><strong>Kota/Kabupaten:</strong> ' + kota + '</div>' +
                '<div><strong>Provinsi:</strong> ' + provinsi + '</div>';
            if (isPusat) detectKotaAsal(desa, kecamatan, kota, provinsi, lat, lng);
        }

        function refineAlamat(desa, kecamatan, kota, provinsi, lat, lng, alamatBoxEl, isPusat) {
            if (kecamatan !== '-' || desa === '-' || kota === '-') {
                showAlamatBox(alamatBoxEl, desa, kecamatan, kota, provinsi, lat, lng, isPusat);
                return;
            }
            fetch("<?= base_url('pelanggan_kelola_data/refine_alamat') ?>?desa=" + encodeURIComponent(desa) +
                "&kota=" + encodeURIComponent(kota) + "&lat=" + lat + "&lng=" + lng)
            .then(r => r.json())
            .then(res => {
                if (res.match) {
                    desa = titleCase(res.match.desa);
                    kecamatan = titleCase(res.match.kecamatan);
                }
                showAlamatBox(alamatBoxEl, desa, kecamatan, kota, provinsi, lat, lng, isPusat);
            })
            .catch(() => showAlamatBox(alamatBoxEl, desa, kecamatan, kota, provinsi, lat, lng, isPusat));
        }

        function detectKotaAsal(desa, kecamatan, kota, provinsi, lat, lng) {
            const queries = [
                [desa, kecamatan, kota, provinsi].filter(s => s !== '-').join(', '),
                [kecamatan, kota, provinsi].filter(s => s !== '-').join(', '),
                [kota, provinsi].filter(s => s !== '-').join(', ')
            ];
            const fallbackName = [kota, provinsi].filter(s => s !== '-').join(', ');
            (function tryQ(i) {
                if (i >= queries.length) {
                    if (fallbackName) { document.getElementById('search_kota_asal').value = fallbackName; document.getElementById('search_kota_asal').style.borderColor = '#ffc107'; }
                    return;
                }
                fetch("<?= base_url('pelanggan_kelola_data/search_destination') ?>?search=" + encodeURIComponent(queries[i]) + "&lat=" + lat + "&lng=" + lng)
                .then(r => r.json()).then(res => {
                    if (res.error) { tryQ(i + 1); return; }
                    if (res.data && res.data.length > 0) {
                        const addr = queries[i].toLowerCase();
                        const scored = res.data.map(item => { const l = item.label.toLowerCase(); let s = 0; addr.split(',').forEach(p => { if (l.includes(p.trim())) s++; }); return Object.assign({}, item, { score: s }); }).sort((a, b) => b.score - a.score);
                        document.getElementById('kode_kota_hidden').value = scored[0].id;
                        document.getElementById('nama_kota_hidden').value = scored[0].label;
                        document.getElementById('search_kota_asal').value = scored[0].label;
                        document.getElementById('search_kota_asal').style.borderColor = '#28a745';
                    } else { tryQ(i + 1); }
                }).catch(() => tryQ(i + 1));
            })(0);
        }

        function initPusatMap() {
            initMapGeneric('map_pusat', defaultPusatLat, defaultPusatLng,
                document.getElementById('latitude_pusat'), document.getElementById('longitude_pusat'),
                document.getElementById('alamatLengkap_pusat'), true);
            geocodeAddress(defaultPusatLat, defaultPusatLng, document.getElementById('alamatLengkap_pusat'), true);
        }
        initPusatMap();

        function initCabangMap(entry) {
            const idx = entry.dataset.index;
            const latInput = entry.querySelector('.latitude-input');
            const lngInput = entry.querySelector('.longitude-input');
            const alamatBox = entry.querySelector('.alamat-box');
            const cbLat = parseFloat(latInput.value) || null;
            const cbLng = parseFloat(lngInput.value) || null;
            initMapGeneric('map_cabang_' + idx, cbLat || defaultPusatLat, cbLng || defaultPusatLng, latInput, lngInput, alamatBox, false);
            geocodeAddress(cbLat || defaultPusatLat, cbLng || defaultPusatLng, alamatBox, false);
        }

        initCabangMap(document.querySelector('.cabang-entry'));

        let cabangIndex = 1;
        document.getElementById('btn_add_cabang').addEventListener('click', function() {
            const idx = cabangIndex++;
            const container = document.getElementById('cabang_container');
            const tpl = container.querySelector('.cabang-entry');
            const clone = tpl.cloneNode(true);
            clone.dataset.index = idx;
            clone.style.borderColor = '#28a745';
            clone.style.borderStyle = 'solid';

            clone.querySelector('strong').textContent = 'Cabang ' + (idx + 1);

            clone.querySelectorAll('input').forEach(inp => {
                if (inp.name) inp.name = inp.name.replace(/\[\d+\]/, '[' + idx + ']');
                if (inp.type !== 'hidden') inp.value = '';
            });

            const oldMapDiv = clone.querySelector('.map-cabang');
            const newMapId = 'map_cabang_' + idx;
            oldMapDiv.id = newMapId;
            clone.querySelector('.alamat-box').innerHTML = '<i class="fa fa-spinner fa-spin"></i> Mendeteksi lokasi...';

            container.appendChild(clone);

            container.querySelectorAll('.cabang-entry').forEach(function(entry) {
                entry.querySelector('.btn-remove-cabang').style.display = 'inline-block';
            });

            requestAnimationFrame(function() { initCabangMap(clone); });
        });

        document.getElementById('cabang_container').addEventListener('click', function(e) {
            if (e.target.closest('.btn-remove-cabang')) {
                const entry = e.target.closest('.cabang-entry');
                const container = document.getElementById('cabang_container');
                if (container.querySelectorAll('.cabang-entry').length > 1) {
                    entry.remove();
                    const remaining = container.querySelectorAll('.cabang-entry');
                    if (remaining.length === 1) {
                        remaining[0].querySelector('.btn-remove-cabang').style.display = 'none';
                    }
                }
            }
        });

        const logoInput = document.getElementById('logo_input');
        const logoPreview = document.getElementById('logo_preview');
        if (logoInput && logoPreview) {
            logoInput.addEventListener('change', function() {
                if (this.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) { logoPreview.src = e.target.result; };
                    reader.readAsDataURL(this.files[0]);
                }
            });
        }
        const bgInput = document.getElementById('bg_input');
        const bgPreview = document.getElementById('bg_preview');
        if (bgInput && bgPreview) {
            bgInput.addEventListener('change', function() {
                if (this.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) { bgPreview.src = e.target.result; };
                    reader.readAsDataURL(this.files[0]);
                }
            });
        }
    });
</script>