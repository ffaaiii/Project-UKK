-- Skema database untuk Slip Gaji Karyawan.
-- Jalankan file ini di phpMyAdmin / mysql client pada database db_slip_gaji
-- (nama database harus sama dengan yang ditulis di config.php)

DROP TABLE IF EXISTS karyawan;
DROP TABLE IF EXISTS admin;

CREATE TABLE admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL, -- disimpan sebagai HASH bcrypt, bukan teks asli
    -- Buat fitur Lupa Kata Sandi: email tujuan kirim link reset, token acak
    -- sekali pakai, dan batas waktu token itu masih berlaku.
    email VARCHAR(100) NULL,
    reset_token VARCHAR(64) NULL,
    reset_expired DATETIME NULL
);

-- Akun contoh: username "admin", password "admin123" (sudah di-hash di bawah).
-- GANTI 'admin@gmail.com' dengan email asli sebelum demo -- dipakai fitur
-- Lupa Kata Sandi buat kirim link reset. Kalau database sudah pernah dibuat
-- dan tidak mau dihapus ulang, cukup jalankan manual di phpMyAdmin:
--   UPDATE admin SET email = 'email_asli_kamu@gmail.com' WHERE username = 'admin';
INSERT INTO admin (username, password, email) VALUES ('admin', '$2b$10$15ODiD12tQ1Sgtxc9kYQkubPz7VYOt0uMwILxrLiPL4kO8Dc.K7P.', 'admin@gmail.com');

-- Tabel karyawan: data slip gaji
CREATE TABLE karyawan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    nik VARCHAR(30) NOT NULL,
    jabatan VARCHAR(50) NOT NULL,
    -- pakai DECIMAL biar bisa nilai koma
    gaji_pokok DECIMAL(15,2) NOT NULL DEFAULT 0,
    lembur DECIMAL(15,2) NOT NULL DEFAULT 0,
    pinjaman DECIMAL(15,2) NOT NULL DEFAULT 0,
    gaji_bersih DECIMAL(15,2) NOT NULL DEFAULT 0
);
