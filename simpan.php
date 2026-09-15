<?php
// Terima data dari tambah.php, cek captcha, lalu insert karyawan baru.
session_start();
require('config.php');

if (!isset($_SESSION['admin'])) {
    header("Location: login.html");
    exit();
}

// Cocokkan jawaban captcha dengan yang disimpan di session waktu form dibuka.
$captcha_user = (int) $_POST['captcha'];
$captcha_benar = isset($_SESSION['captcha_jawaban']) ? $_SESSION['captcha_jawaban'] : null;

if ($captcha_user !== $captcha_benar) {
    header("Location: tambah.php?error=captcha");
    exit();
}

// Captcha sudah dipakai, hapus supaya tidak bisa dipakai ulang.
unset($_SESSION['captcha_jawaban']);

$nama     = mysqli_real_escape_string($koneksi, $_POST['nama']);
$nik      = mysqli_real_escape_string($koneksi, $_POST['nik']);
$jabatan  = mysqli_real_escape_string($koneksi, $_POST['jabatan']);

// pakai float biar bisa nilai koma
$gaji_pokok = (float) $_POST['gaji_pokok'];
$lembur     = (float) $_POST['lembur'];
$pinjaman   = (float) $_POST['pinjaman'];

// Gaji bersih dihitung ulang di server, bukan cuma percaya JavaScript di form.
$total_penghasilan = $gaji_pokok + $lembur;
$gaji_bersih = $total_penghasilan - $pinjaman;

$query = "INSERT INTO karyawan (nama, nik, jabatan, gaji_pokok, lembur, pinjaman, gaji_bersih)
          VALUES ('$nama', '$nik', '$jabatan', $gaji_pokok, $lembur, $pinjaman, $gaji_bersih)";

if (mysqli_query($koneksi, $query)) {
    header("Location: crud.php");
    exit();
} else {
    echo "Gagal menyimpan data: " . mysqli_error($koneksi);
}
