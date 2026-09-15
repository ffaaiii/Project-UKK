<?php
// Terima password baru dari reset_password.php, validasi token & password, simpan.

require('config.php');

$token = $_POST['token'] ?? '';
$password = $_POST['password'] ?? '';
$konfirmasi = $_POST['konfirmasi'] ?? '';
$token_aman = mysqli_real_escape_string($koneksi, $token);

// cek token masih valid dan belum expired
$query = "SELECT * FROM admin WHERE reset_token = '$token_aman' AND reset_expired > NOW()";
$hasil = mysqli_query($koneksi, $query);
$admin = mysqli_fetch_assoc($hasil);

if (!$admin) {
    echo "Link reset sudah tidak valid/kadaluwarsa. <a href='lupa_password.php'>Minta link baru</a>";
    exit();
}

if ($password !== $konfirmasi) {
    echo "Password baru dan konfirmasi tidak sama. <a href='reset_password.php?token=" . urlencode($token) . "'>Coba lagi</a>";
    exit();
}

// validasi lagi di server
if (strlen($password) < 6) {
    echo "Password minimal 6 karakter. <a href='reset_password.php?token=" . urlencode($token) . "'>Coba lagi</a>";
    exit();
}

$password_hash = password_hash($password, PASSWORD_BCRYPT);

// simpan password baru, token dikosongin lagi biar gak kepake ulang
$id_admin = (int) $admin['id'];
$query_update = "UPDATE admin SET password = '$password_hash', reset_token = NULL, reset_expired = NULL WHERE id = $id_admin";
mysqli_query($koneksi, $query_update);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Berhasil Diubah - Slip Gaji Karyawan</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <h1>Password Berhasil Diubah</h1>
        <p>Password baru sudah tersimpan. Silakan login memakai password barumu.</p>
        <a href="login.html"><button type="button">Ke Halaman Login</button></a>
    </div>
</body>

</html>
