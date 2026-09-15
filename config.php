<?php
// Koneksi database, dipakai bareng oleh semua file lain lewat require('config.php').
$koneksi = mysqli_connect("localhost", "root", "", "db_slip_gaji");

if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Periode slip gaji, tidak diinput manual lagi, langsung dari sini
define('PERIODE_SLIP_GAJI', 'PERIODE 25 Agustus 2026 - 25 September 2026');
