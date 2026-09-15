# Aplikasi Slip Gaji Karyawan

Project UKK Junior Web Programmer. Aplikasi admin buat kelola data slip gaji karyawan: tambah, lihat, edit, hapus, cetak PDF, sama kirim slip gaji lewat WhatsApp/Email.

## Teknologi

- PHP native + MySQL
- HTML, CSS, JS biasa (tanpa framework)
- FPDF buat cetak PDF (folder `fpdf/`)
- Link WhatsApp (`api.whatsapp.com`) dan Gmail compose (`mail.google.com`) buat kirim slip gaji, jadi nggak perlu pasang API key/App Password segala

## Struktur File

| File | Isi |
|------|-----|
| `database.sql` | Bikin tabel `admin` dan `karyawan` + akun admin contoh |
| `config.php` | Koneksi database + konstanta periode slip gaji |
| `login.html`, `proses_login.php` | Login admin |
| `logout.php` | Logout |
| `crud.php` | Halaman utama, tabel data karyawan |
| `tambah.php`, `simpan.php` | Form tambah karyawan + proses simpannya |
| `edit.php`, `update.php` | Form edit karyawan + proses update |
| `hapus.php` | Hapus data karyawan |
| `cetak_pdf.php` | Cetak slip gaji ke PDF |
| `lupa_password.php`, `proses_lupa_password.php`, `reset_password.php`, `proses_reset_password.php` | Fitur lupa password admin |
| `style.css` | Tampilan semua halaman |
| `fpdf/` | Library PDF pihak ketiga |

## Alur Pakai

1. Login di `login.html` (akun contoh: `admin` / `admin123`)
2. Masuk ke `crud.php`, tabel semua karyawan
3. Klik **Tambah Karyawan** buat nambah data baru (ada captcha)
4. Klik **Edit** buat ubah data, **Hapus** buat hapus data
5. Klik **PDF** buat cetak slip gaji, **WA**/**Email** buat kirim pesan slip gaji (nomor/email tujuan diisi manual sendiri pas kebuka)
6. Kalau lupa password, klik link di halaman login

Rumus gaji:
```
Total Penghasilan = Gaji Pokok + Lembur
Total Potongan    = Pinjaman Karyawan
Gaji Bersih       = Total Penghasilan - Total Potongan
```
Perhitungannya dihitung ulang di PHP (`simpan.php`/`update.php`), nggak cuma dari JS di form.

## Catatan

- Periode slip gaji (contoh: "PERIODE 25 Agustus 2026 - 25 September 2026") ditulis di `config.php` (`PERIODE_SLIP_GAJI`), berlaku buat semua slip gaji. Kalau mau ganti periode tinggal edit baris itu.
- Kolom gaji (`gaji_pokok`, `lembur`, `pinjaman`, `gaji_bersih`) di database pakai `DECIMAL(15,2)` biar bisa nilai desimal/koma.
- Password admin disimpan dalam bentuk hash, dicek pakai `password_verify()`.
- Input form di-escape pakai `mysqli_real_escape_string()` dan `htmlspecialchars()`.

## Cara Setup

1. Siapkan XAMPP/LAMPP, nyalain Apache & MySQL
2. Taruh folder project di `htdocs`/`www`
3. Buat database `db_slip_gaji` di phpMyAdmin, import `database.sql`
4. Cek `config.php`, sesuaikan kalau koneksi database beda
5. Buka lewat browser, login pakai akun contoh di atas
# Project-UKK
