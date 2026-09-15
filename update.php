<?php
// Terima data dari form edit.php, update baris karyawan di database.
session_start();
require('config.php');

if (!isset($_SESSION['admin'])) {
    header("Location: login.html");
    exit();
}

$id       = (int) $_POST['id'];
$nama     = mysqli_real_escape_string($koneksi, $_POST['nama']);
$nik      = mysqli_real_escape_string($koneksi, $_POST['nik']);
$jabatan  = mysqli_real_escape_string($koneksi, $_POST['jabatan']);

// pakai float biar bisa nilai koma
$gaji_pokok = (float) $_POST['gaji_pokok'];
$lembur     = (float) $_POST['lembur'];
$pinjaman   = (float) $_POST['pinjaman'];

// Gaji bersih dihitung ulang di server, bukan dipercaya dari form.
$total_penghasilan = $gaji_pokok + $lembur;
$gaji_bersih = $total_penghasilan - $pinjaman;

$query = "UPDATE karyawan SET
            nama = '$nama',
            nik = '$nik',
            jabatan = '$jabatan',
            gaji_pokok = $gaji_pokok,
            lembur = $lembur,
            pinjaman = $pinjaman,
            gaji_bersih = $gaji_bersih
          WHERE id = $id";

if (mysqli_query($koneksi, $query)) {
    header("Location: crud.php");
    exit();
} else {
    echo "Gagal update data: " . mysqli_error($koneksi);
}
