<?php
// Cek username & password dari login.html, lalu buat session kalau cocok.
session_start();
require('config.php');

$username = $_POST['username'];
$password = $_POST['password'];
$username_aman = mysqli_real_escape_string($koneksi, $username);

// ambil data admin berdasarkan username, cek passwordnya pakai password_verify()
$query = "SELECT * FROM admin WHERE username = '$username_aman'";
$hasil = mysqli_query($koneksi, $query);
$data = mysqli_fetch_assoc($hasil);

if ($data && password_verify($password, $data['password'])) {
    $_SESSION['admin'] = $username;
    header("Location: crud.php");
    exit();
} else {
    echo "Username atau password salah!";
}
