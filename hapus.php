<?php
// Hapus satu baris karyawan berdasarkan id dari URL (hapus.php?id=5).
session_start();
require('config.php');

if (!isset($_SESSION['admin'])) {
    header("Location: login.html");
    exit();
}

$id = (int) $_GET['id'];
mysqli_query($koneksi, "DELETE FROM karyawan WHERE id = $id");

header("Location: crud.php");
exit();
