# Rencana: Auto Logout Berbasis Inactivity

## Masalah
Saat ini **tidak ada auto logout**. Session dikonfigurasi hidup 10 tahun (`Session.php:43-45`). Logout hanya bisa dilakukan manual.

## Solusi
Implementasi auto logout **hybrid** (server-side + client-side):
- **Server-side**: Filter CI4 memeriksa `last_activity` di session, hancurkan session jika timeout
- **Client-side**: JS timer menampilkan warning SweetAlert2, lalu redirect ke logout

## Timeout
| Parameter | Nilai |
|-----------|-------|
| Inactivity timeout | 1 hari (86400 detik) |
| Warning sebelum logout | 60 detik |
| Session hard limit | 1 hari + 5 menit (86700 detik) |

---

## File yang Diubah

### 1. `app/Config/Session.php`
- `$expiration`: `315360000` → `2100` (35 menit)
- `$cookieLifetime`: `315360000` → `2100` (35 menit)

### 2. `app/Filters/Filter_autologout.php` (BARU)
- `before` filter
- Jika user logged in (ada `level` di session):
  - Cek `last_activity` di session
  - Jika kosong atau sudah lewat 30 menit → destroy session, redirect ke login
  - Jika masih aktif → update `last_activity` ke waktu sekarang
- Jika tidak logged in → skip (biarkan filter lain handle)

### 3. `app/Config/Filters.php`
- Tambah alias `filter_autologout` → `Filter_autologout`
- Tambah ke array `before` (global, dengan exception untuk auth routes)

### 4. `app/Views/layout/v_content.php` (Admin)
- Tambah JS auto-logout timer setelah script yang ada:
  - Timer 30 menit, reset setiap user activity (mouse, keyboard, scroll)
  - Pada sisa 60 detik: tampilkan SweetAlert2 warning "Session akan berakhir"
  - Jika user klik "Perpanjang" → reset timer
  - Jika timeout → redirect ke `auth/logout_user`

### 5. `app/Views/layout_toko/v_footer.php` (Pelanggan)
- Sama seperti admin, tapi redirect ke `auth/logout_pelanggan`

---

## Urutan Eksekusi
1. Ubah `Session.php`
2. Buat `Filter_autologout.php`
3. Daftarkan filter di `Filters.php`
4. Tambah JS auto-logout di `v_content.php` (admin)
5. Tambah JS auto-logout di `v_footer.php` (pelanggan)

## Verifikasi
- Login sebagai admin/pemilik, diam 30 menit tanpa action → harus logout otomatis
- Login sebagai pelanggan, diam 30 menit → harus logout otomatis
- Aktif bergerak/klik selama 30 menit → session tetap hidup (timer reset)
- Muncul warning 60 detik sebelum timeout, klik "Perpanjang" → session tetap hidup
