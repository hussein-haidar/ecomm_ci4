<?= view('layout_toko/v_navbar.php') ?>

<body>
    <main class="product-section">
        <div class="container">
            <h3>Pembelian Produk</h3>

            <?php if (!empty($keranjang_terpilih)) : ?>
                <!-- Form Pembayaran -->
                <?= form_open_multipart('pelanggan_kelola_data/save_beli'); ?>

                <!-- Pesan error validasi -->
                <?php $flashErrors = session()->getFlashdata('errors'); ?>
                <?php if (!empty($flashErrors) && is_array($flashErrors)) : ?>
                    <div class="alert alert-danger" role="alert">
                        <ul>
                            <?php foreach ($flashErrors as $error) : ?>
                                <li><?= esc($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php elseif (!empty($flashErrors) && is_string($flashErrors)): ?>
                    <div class="alert alert-danger" role="alert">
                        <?= esc($flashErrors) ?>
                    </div>
                <?php endif; ?>

                <div class="form-group">
                    <label for="kode_beli">Kode Transaksi</label>
                    <input type="text" class="form-control" id="kode_beli" name="kode_beli" value="<?= isset($kode_beli) ? $kode_beli : ''; ?>" readonly>
                </div>

                <div class="form-group profile">
                    <label for="nama_pelanggan" class="form-label">Pelanggan</label>
                    <input type="text" id="nama_pelanggan" name="nama_pelanggan" value="<?= session()->get('nama_pelanggan'); ?>" class="form-control" readonly>
                </div>

                <input type="hidden" name="sesi_user" value="<?= session()->get('sesi_user'); ?>">

                <!-- Kirimkan ID produk yang dipilih sebagai input hidden agar bisa diproses di controller -->
                <?php if (isset($selected_products)) : ?>
                    <?php foreach ($selected_products as $id_keranjang) : ?>
                        <input type="hidden" name="selected_products[]" value="<?= esc($id_keranjang); ?>">
                    <?php endforeach; ?>
                <?php endif; ?>

                <!-- Informasi produk yang dipilih -->
                <h5>Rincian Produk Yang Dipilih:</h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Gambar</th>
                            <th>Nama Produk</th>
                            <th>Ukuran</th>
                            <th>Jumlah</th>
                            <th>Harga Satuan</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($keranjang_terpilih as $item) : ?>
                            <tr>
                                <td>
                                    <?php if (isset($item['foto_produk']) && !empty($item['foto_produk'])) : ?>
                                        <img src="<?= base_url('fotoproduk/' . esc($item['foto_produk'])); ?>" class="img-circle" width="80px" height="80px">
                                    <?php else : ?>
                                        <span>Tidak ada foto</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= esc($item['nama_produk']); ?></td>
                                <td><?= esc($item['ukuran_produk']); ?></td>
                                <td><?= esc($item['jumlah_produk']); ?> <?= esc($item['satuan_produk']); ?></td>
                                <td>Rp. <?= number_format($item['harga_produk'], 0, ',', '.'); ?></td>
                                <td>Rp. <?= number_format($item['harga_produk'] * $item['jumlah_produk'], 0, ',', '.'); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <div id="map_pusat" style="height: 400px; width: 100%;"></div>

                <!-- Input Lokasi Pelanggan -->
                <input type="hidden" id="latitude_pelanggan" name="latitude" value="<?= isset($latitude) ? $latitude : ''; ?>" readonly>
                <input type="hidden" id="longitude_pelanggan" name="longitude" value="<?= isset($longitude) ? $longitude : ''; ?>" readonly>

                <div class="form-group profile">
                    <label for="alamat" class="form-label">Alamat Lengkap</label>
                    <input type="text" id="alamat" name="alamat" value="<?= isset($alamat) ? $alamat : ''; ?>" class="form-control" placeholder="Isi alamat lengkap pengiriman" required>
                </div>

                <div class="form-group profile">
                    <label for="bank_tujuan" class="form-label">Bank Tujuan</label>
                    <select id="bank_tujuan" name="bank_tujuan" class="form-control" required>
                        <option value="">--Pilih Bank--</option>
                        <?php if (!empty($bank)): ?>
                            <?php foreach ($bank as $key => $value): ?>
                                <option
                                    value="<?= esc($value['nama_bank']) ?>"
                                    data-rekening="<?= esc($value['no_rek']) ?>"
                                    data-a_n="<?= esc($value['a_n']) ?>">
                                    <?= esc($value['nama_bank']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option value="" disabled>Tidak ada data bank</option>
                        <?php endif; ?>
                    </select>
                </div>

                <div class="form-group profile">
                    <label for="a_n" class="form-label">A.N Rekening</label>
                    <input type="text" id="a_n" name="a_n" class="form-control" value="" placeholder="A.N Rekening" readonly>
                </div>

                <div class="form-group profile">
                    <label for="no_rek" class="form-label">No Rekening</label>
                    <input type="text" id="no_rek" name="no_rek" class="form-control" value="" placeholder="No Rek" readonly>
                </div>

                <hr>
                <h5><i class="fa fa-truck"></i> Pengiriman</h5>

                <div class="form-group profile">
                    <label class="form-label"><strong>Pemesan</strong></label>
                    <input type="text" class="form-control" value="<?= esc(session()->get('nama_pelanggan') ?? '') ?>" readonly style="font-weight:bold;">
                    <input type="text" class="form-control" value="<?= esc(session()->get('alamat') ?? '') ?>" readonly style="font-size:12px; color:#555; margin-top:2px;">
                </div>

                <div class="form-group profile">
                    <label class="form-label"><strong>Pengirim</strong></label>
                    <input type="text" class="form-control" value="<?= esc($nama_toko ?? 'Toko') ?>" readonly style="font-weight:bold;">
                    <input type="text" class="form-control" value="<?= esc($alamat_toko ?? 'Alamat toko belum diatur') ?>" readonly style="font-size:12px; color:#555; margin-top:2px;">
                    <input type="hidden" id="origin_id" value="<?= esc($kode_kota_toko ?? '') ?>">
                </div>

                <div id="info_jarak" style="display:none; margin-bottom:15px;">
                    <div class="alert alert-info" style="margin-bottom:0;">
                        <i class="fa fa-map-marker"></i> <strong>Jarak ke Toko:</strong> <span id="jarak_text"></span>
                    </div>
                </div>

                <input type="hidden" id="jarak_km" name="jarak_km">

                <div id="loading_ongkir" style="display:none; text-align:center; padding:15px;">
                    <i class="fa fa-spinner fa-spin fa-2x"></i><br>Memuat data ongkir...
                </div>

                <div id="hasil_ongkir" style="display:none;">
                    <div id="section_ongkir_lokal" style="display:none;">
                        <label class="form-label"><strong>Kurir Lokal:</strong></label>
                        <div id="daftar_ongkir_lokal"></div>
                    </div>
                    <div id="section_ongkir_nasional" style="display:none;">
                        <label class="form-label"><strong>Kurir Nasional:</strong></label>
                        <div id="daftar_ongkir_nasional"></div>
                    </div>
                </div>

                <input type="hidden" id="jenis_kurir" name="jenis_kurir">
                <input type="hidden" id="ongkir" name="ongkir">
                <input type="hidden" id="estimasi_waktu" name="estimasi_waktu">
                <input type="hidden" id="destination_id" value="">
                <input type="hidden" id="selected_destination" value="">

                <div class="form-group profile">
                    <label>Ongkos Kirim</label>
                    <input type="text" id="ongkir_display" class="form-control" value="" readonly placeholder="Pilih tujuan dan layanan pengiriman">
                </div>

                <div class="form-group profile">
                    <label>Estimasi Sampai Tujuan</label>
                    <input type="text" id="estimasi_display" class="form-control" value="" readonly>
                </div>

                <div class="notif-pembayaran alert-info p-2" style="margin-bottom:15px;">
                    <p class="notif-title" style="margin:0;">
                        ℹ️ <strong>Silahkan lakukan pembayaran sebesar:</strong>
                        <span id="total_bayar_text">Rp. <?= number_format($totalSemua, 0, ',', '.'); ?></span>,- melalui bank beserta rekening yang tertera.
                    </p>
                </div>

                <button type="submit" class="btn btn-primary">Konfirmasi Pemesanan</button>

                <?= form_close(); ?>

            <?php else : ?>
                <div class="alert alert-warning">Tidak ada produk yang dipilih.</div>
            <?php endif; ?>
        </div>
    </main>

    <!-- Leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <!-- Script untuk bank dan map -->
    <script>
        // Kode Transaksi (generate berdasarkan produk pertama)
        <?php if (!empty($keranjang_terpilih)) : ?>
            fetch('<?= base_url("pelanggan_kelola_data/generateKodeTransaksi") ?>', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        nama_produk: "<?= $keranjang_terpilih[0]['nama_produk']; ?>"
                    })
                }).then(response => response.json())
                .then(data => {
                    if (data.kode_beli) {
                        document.getElementById('kode_beli').value = data.kode_beli;
                    }
                }).catch(error => console.error('Error:', error));
        <?php endif; ?>

        // MAP
        document.addEventListener("DOMContentLoaded", function() {
            const lat = parseFloat(document.getElementById('latitude_pelanggan').value) || -6.9175;
            const lng = parseFloat(document.getElementById('longitude_pelanggan').value) || 107.6191;

            const map = L.map('map_pusat').setView([lat, lng], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

            const marker = L.marker([lat, lng], {
                draggable: true
            }).addTo(map);

            marker.on('moveend', function(e) {
                const pos = e.target.getLatLng();
                document.getElementById('latitude_pelanggan').value = pos.lat;
                document.getElementById('longitude_pelanggan').value = pos.lng;
                updateInfoJarak(pos.lat, pos.lng);
                debounceGeocodeCheckout(pos.lat, pos.lng);
            });

            map.on('click', function(e) {
                marker.setLatLng(e.latlng);
                document.getElementById('latitude_pelanggan').value = e.latlng.lat;
                document.getElementById('longitude_pelanggan').value = e.latlng.lng;
                updateInfoJarak(e.latlng.lat, e.latlng.lng);
                debounceGeocodeCheckout(e.latlng.lat, e.latlng.lng);
            });

            // Jalankan geocoding sekali saat load
            if (lat !== -6.9175 && lng !== 107.6191) {
                updateInfoJarak(lat, lng);
                debounceGeocodeCheckout(lat, lng);
            }
        });

        let geocodeTimer = null;
        function debounceGeocodeCheckout(lat, lng) {
            if (geocodeTimer) clearTimeout(geocodeTimer);
            geocodeTimer = setTimeout(function() { reverseGeocodeCheckout(lat, lng); }, 500);
        }

        function reverseGeocodeCheckout(lat, lng) {
            const controller = new AbortController();
            const timeoutId = setTimeout(() => controller.abort(), 10000);

            fetch("https://nominatim.openstreetmap.org/reverse?format=json&lat=" + lat + "&lon=" + lng + "&zoom=18&addressdetails=1&accept-language=id",
                { headers: { 'User-Agent': 'TokoOnlineApp/1.0' }, signal: controller.signal })
            .then(r => { clearTimeout(timeoutId); return r.json(); })
            .then(data => {
                if (data.address) {
                    const addr = data.address;
                    let desa = addr.village || addr.hamlet || addr.suburb || addr.neighbourhood || addr.quarter || addr.residential || addr.locality || '';
                    let provinsi = addr.state || '';
                    let kota = '';
                    if (addr.county && /kabupaten|kota/i.test(addr.county)) kota = addr.county;
                    else if (addr.city) kota = addr.city;
                    else if (addr.town) kota = addr.town;
                    else if (addr.county) kota = addr.county;
                    let kecamatan = addr.subdistrict || addr.district || '';
                    if (kecamatan === '' && addr.town && addr.town !== kota) kecamatan = addr.town;
                    if (kota === '' && kecamatan !== '') { kota = kecamatan; kecamatan = ''; }
                    refineAlamatCheckout(desa, kecamatan, kota, provinsi, lat, lng);
                }
            })
            .catch(err => {
                clearTimeout(timeoutId);
                console.error("Geocode error:", err);
            });
        }

        function titleCase(s) {
            return String(s).toLowerCase().replace(/\b\w/g, function(c) { return c.toUpperCase(); });
        }

        function setAlamatCheckout(desa, kecamatan, kota, provinsi, lat, lng) {
            const alamatParts = [desa, kecamatan, kota, provinsi].filter(s => s);
            if (alamatParts.length > 0) {
                document.getElementById('alamat').value = alamatParts.join(', ');
            }
            autoDetectKodeKotaCheckout(desa, kecamatan, kota, provinsi, lat, lng);
        }

        function refineAlamatCheckout(desa, kecamatan, kota, provinsi, lat, lng) {
            if (kecamatan !== '' || desa === '' || kota === '') {
                setAlamatCheckout(desa, kecamatan, kota, provinsi, lat, lng);
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
                setAlamatCheckout(desa, kecamatan, kota, provinsi, lat, lng);
            })
            .catch(() => setAlamatCheckout(desa, kecamatan, kota, provinsi, lat, lng));
        }

        function autoDetectKodeKotaCheckout(desa, kecamatan, kota, provinsi, lat, lng) {
            const queries = [
                [desa, kecamatan, kota, provinsi].filter(s => s).join(', '),
                [kecamatan, kota, provinsi].filter(s => s).join(', '),
                [kota, provinsi].filter(s => s).join(', ')
            ];
            trySearchCheckout(queries, 0, lat, lng);
        }

        function trySearchCheckout(queries, index, lat, lng) {
            if (index >= queries.length) return;
            fetch("<?= base_url('pelanggan_kelola_data/search_destination') ?>?search=" + encodeURIComponent(queries[index]) + "&lat=" + lat + "&lng=" + lng)
            .then(r => r.json())
            .then(res => {
                if (res.data && res.data.length > 0) {
                    const addr = queries[index].toLowerCase();
                    const scored = res.data.map(item => {
                        const label = item.label.toLowerCase();
                        let score = 0;
                        addr.split(',').map(s => s.trim()).forEach(part => {
                            if (label.includes(part)) score++;
                        });
                        return Object.assign({}, item, { score: score });
                    }).sort(function(a, b) { return b.score - a.score; });
                    const best = scored[0];
                    document.getElementById('destination_id').value = best.id;
                    document.getElementById('selected_destination').value = best.label;
                    hitungSemuaOngkir(best.id);
                } else {
                    trySearchCheckout(queries, index + 1, lat, lng);
                }
            })
            .catch(err => console.error("Search error:", err));
        }
    </script>

    <script>
        const kodeKotaToko = <?= json_encode($kode_kota_toko ?? '') ?>;
        const totalBelanja = <?= json_encode($totalSemua ?? 0) ?>;
        const latToko = <?= json_encode($latitude_pusat ?? -6.9175) ?>;
        const lngToko = <?= json_encode($longitude_pusat ?? 107.6191) ?>;

        function haversine(lat1, lon1, lat2, lon2) {
            const R = 6371;
            const dLat = (lat2 - lat1) * Math.PI / 180;
            const dLon = (lon2 - lon1) * Math.PI / 180;
            const a = Math.sin(dLat / 2) * Math.sin(dLat / 2)
                    + Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180)
                    * Math.sin(dLon / 2) * Math.sin(dLon / 2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
            return R * c;
        }

        function updateInfoJarak(latPelanggan, lngPelanggan) {
            const jarak = haversine(latToko, lngToko, latPelanggan, lngPelanggan);
            const jarakR = Math.round(jarak * 10) / 10;
            document.getElementById('jarak_km').value = jarakR;
            document.getElementById('jarak_text').textContent = jarakR + ' km dari toko';
            document.getElementById('info_jarak').style.display = 'block';
            return jarakR;
        }

        document.addEventListener("DOMContentLoaded", function() {
            const bankSelect = document.getElementById("bank_tujuan");
            const noA_NInput = document.getElementById("a_n");
            const noRekInput = document.getElementById("no_rek");

            bankSelect.addEventListener("change", function() {
                const selectedOption = this.options[this.selectedIndex];
                noA_NInput.value = selectedOption.getAttribute("data-a_n") || "";
                noRekInput.value = selectedOption.getAttribute("data-rekening") || "";
            });

            const kodeKotaPelanggan = <?= json_encode($kode_kota_pelanggan ?? '') ?>;
            const namaKotaPelanggan = <?= json_encode($nama_kota_pelanggan ?? '') ?>;
            if (kodeKotaPelanggan && !document.getElementById("destination_id").value) {
                document.getElementById("destination_id").value = kodeKotaPelanggan;
                document.getElementById("selected_destination").value = namaKotaPelanggan;
                const latP = parseFloat(document.getElementById('latitude_pelanggan').value) || 0;
                const lngP = parseFloat(document.getElementById('longitude_pelanggan').value) || 0;
                if (latP && lngP) updateInfoJarak(latP, lngP);
                hitungSemuaOngkir(kodeKotaPelanggan);
            }
        });

        function hitungSemuaOngkir(destId) {
            const loading = document.getElementById("loading_ongkir");
            const hasil = document.getElementById("hasil_ongkir");
            loading.style.display = "block";
            hasil.style.display = "none";
            document.getElementById("daftar_ongkir_lokal").innerHTML = '';
            document.getElementById("daftar_ongkir_nasional").innerHTML = '';
            document.getElementById("section_ongkir_lokal").style.display = 'none';
            document.getElementById("section_ongkir_nasional").style.display = 'none';

            const jarakKm = parseFloat(document.getElementById('jarak_km').value) || 0;
            const promises = [];

            if (jarakKm > 0 && jarakKm <= 50) {
                const fdLokal = new FormData();
                fdLokal.append("jarak", jarakKm);
                promises.push(
                    fetch("<?= base_url('pelanggan_kelola_data/hitung_ongkir_lokal') ?>", { method: "POST", body: fdLokal })
                    .then(r => r.json())
                    .then(res => {
                        if (res.data && res.data.length > 0) {
                            renderOngkirLokal(res.data, jarakKm);
                        }
                    })
                );
            }

            const originId = <?= (int)($kode_kota_toko ?? 0) ?>;
            if (originId && destId) {
                let totalBerat = 0;
                <?php foreach ($keranjang_terpilih as $item): ?>
                    totalBerat += <?= ($item['berat_produk'] ?? 500) * ($item['jumlah_produk'] ?? 1) ?>;
                <?php endforeach; ?>
                if (totalBerat <= 0) totalBerat = 1000;

                const fdNasional = new FormData();
                fdNasional.append("origin", originId);
                fdNasional.append("destination", destId);
                fdNasional.append("weight", totalBerat);
                fdNasional.append("courier", "jne:jnt:sicepat");
                promises.push(
                    fetch("<?= base_url('pelanggan_kelola_data/hitung_ongkir') ?>", { method: "POST", body: fdNasional })
                    .then(r => r.json())
                    .then(res => {
                        if (res.data && res.data.length > 0) {
                            renderOngkirNasional(res.data);
                        } else if (res.error) {
                            document.getElementById("daftar_ongkir_nasional").innerHTML = '<div class="alert alert-warning"><i class="fa fa-exclamation-triangle"></i> ' + res.error + '</div>';
                            document.getElementById("section_ongkir_nasional").style.display = 'block';
                        }
                    })
                );
            }

            Promise.all(promises).then(() => {
                loading.style.display = "none";
                const hasLokal = document.getElementById("section_ongkir_lokal").style.display !== 'none';
                const hasNasional = document.getElementById("section_ongkir_nasional").style.display !== 'none';
                if (hasLokal || hasNasional) {
                    hasil.style.display = "block";
                } else {
                    document.getElementById("daftar_ongkir_nasional").innerHTML = '<div class="alert alert-warning">Tidak ada layanan pengiriman tersedia untuk tujuan ini.</div>';
                    document.getElementById("section_ongkir_nasional").style.display = 'block';
                    hasil.style.display = "block";
                }
            }).catch(err => {
                loading.style.display = "none";
                document.getElementById("daftar_ongkir_nasional").innerHTML = '<div class="alert alert-danger">Terjadi kesalahan saat menghitung ongkir.</div>';
                document.getElementById("section_ongkir_nasional").style.display = 'block';
                hasil.style.display = "block";
                console.error("Ongkir error:", err);
            });
        }

        function renderOngkirLokal(data, jarakKm) {
            let html = '<div class="table-responsive"><table class="table table-bordered table-hover">';
            html += '<thead style="background:#e8f5e9;"><tr><th>Pilih</th><th>Kurir</th><th>Layanan</th><th>Biaya</th><th>Estimasi</th></tr></thead><tbody>';

            data.forEach((item, idx) => {
                const costRp = 'Rp. ' + Number(item.cost).toLocaleString('id-ID');
                html += '<tr style="cursor:pointer;" onclick="pilihOngkir(this)" '
                    + 'data-kurir="' + item.name + '" '
                    + 'data-service="' + item.service + '" '
                    + 'data-cost="' + item.cost + '" '
                    + 'data-etd="' + item.etd + '">'
                    + '<td><input type="radio" name="pilih_ongkir" value="lokal_' + idx + '"></td>'
                    + '<td>' + item.name + '</td>'
                    + '<td>' + item.service + ' (' + jarakKm + ' km)</td>'
                    + '<td>' + costRp + '</td>'
                    + '<td>' + item.etd + '</td>'
                    + '</tr>';
            });

            html += '</tbody></table></div>';
            document.getElementById("daftar_ongkir_lokal").innerHTML = html;
            document.getElementById("section_ongkir_lokal").style.display = 'block';
        }

        function renderOngkirNasional(data) {
            let html = '<div class="table-responsive"><table class="table table-bordered table-hover">';
            html += '<thead style="background:#e3f2fd;"><tr><th>Pilih</th><th>Kurir</th><th>Layanan</th><th>Biaya</th><th>Estimasi</th></tr></thead><tbody>';

            data.forEach((item, idx) => {
                const costRp = 'Rp. ' + Number(item.cost).toLocaleString('id-ID');
                const etd = item.etd || '-';
                html += '<tr style="cursor:pointer;" onclick="pilihOngkir(this)" '
                    + 'data-kurir="' + item.name + '" '
                    + 'data-service="' + item.service + '" '
                    + 'data-cost="' + item.cost + '" '
                    + 'data-etd="' + etd + '">'
                    + '<td><input type="radio" name="pilih_ongkir" value="nasional_' + idx + '"></td>'
                    + '<td>' + item.name + '</td>'
                    + '<td>' + item.service + '</td>'
                    + '<td>' + costRp + '</td>'
                    + '<td>' + etd + '</td>'
                    + '</tr>';
            });

            html += '</tbody></table></div>';
            document.getElementById("daftar_ongkir_nasional").innerHTML = html;
            document.getElementById("section_ongkir_nasional").style.display = 'block';
        }

        function pilihOngkir(row) {
            row.querySelector('input[type="radio"]').checked = true;

            const kurir = row.dataset.kurir;
            const service = row.dataset.service;
            const cost = parseInt(row.dataset.cost);
            const etd = row.dataset.etd;

            document.getElementById("jenis_kurir").value = kurir + ' - ' + service;
            document.getElementById("ongkir").value = cost;
            document.getElementById("estimasi_waktu").value = etd;
            document.getElementById("ongkir_display").value = 'Rp. ' + cost.toLocaleString('id-ID') + ' (' + kurir + ' ' + service + ')';
            document.getElementById("estimasi_display").value = etd;

            const totalAkhir = totalBelanja + cost;
            document.getElementById("total_bayar_text").textContent = 'Rp. ' + totalAkhir.toLocaleString('id-ID');

            document.querySelectorAll('#daftar_ongkir_lokal tr, #daftar_ongkir_nasional tr').forEach(tr => tr.style.backgroundColor = '');
            row.style.backgroundColor = '#d4edda';
        }
    </script>
    <?= view('layout_toko/v_footer.php') ?>