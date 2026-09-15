<?php
// Proses form lupa password, bikin token reset terus siapkan link ke Gmail
require('config.php');

$username = $_POST['username'];
$username_aman = mysqli_real_escape_string($koneksi, $username);

$query = "SELECT * FROM admin WHERE username = '$username_aman'";
$hasil = mysqli_query($koneksi, $query);
$admin = mysqli_fetch_assoc($hasil);

if (!$admin) {
    echo "Username tidak ditemukan. <a href='lupa_password.php'>Coba lagi</a>";
    exit();
}

if (empty($admin['email'])) {
    echo "Email untuk akun ini belum diatur di database, jadi link reset tidak bisa dikirim. "
        . "Silakan isi kolom email pada tabel admin (lewat phpMyAdmin atau file database.sql). "
        . "<a href='login.html'>Kembali ke Login</a>";
    exit();
}

// bikin token acak buat link reset
$token = bin2hex(random_bytes(32));

$id_admin = (int) $admin['id'];
$query_update = "UPDATE admin SET reset_token = '$token', reset_expired = NOW() + INTERVAL 15 MINUTE WHERE id = $id_admin";
mysqli_query($koneksi, $query_update);

// deteksi base url biar linknya sesuai
$protokol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$base_url = $protokol . '://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
$link_reset = $base_url . '/reset_password.php?token=' . $token;

// bikin link buat buka gmail compose, isinya sudah otomatis terisi
$tujuan = urlencode($admin['email']);
$subjek = urlencode('Reset Password - Slip Gaji Karyawan');
$isi = urlencode(
    "Halo,\n\n" .
    "Ada permintaan reset password untuk akun admin \"" . $admin['username'] . "\" pada aplikasi Slip Gaji Karyawan.\n" .
    "Salin (copy) link di bawah ini, lalu tempel (paste) di tab browser baru untuk membuat password baru (link berlaku 15 menit):\n\n" .
    $link_reset . "\n\n" .
    "(Kalau link di atas tidak bisa langsung diklik, blok/select teksnya lalu Copy-Paste ke address bar browser.)\n\n" .
    "Jika kamu tidak meminta reset ini, abaikan saja email ini."
);
$link_gmail = "https://mail.google.com/mail/?view=cm&fs=1&to=$tujuan&su=$subjek&body=$isi";
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kirim Link Reset - Slip Gaji Karyawan</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <h1>Link Reset Siap Dikirim</h1>
        <p>
            Klik tombol di bawah ini untuk membuka draft email Gmail yang
            sudah terisi otomatis (tujuan, subjek, dan link reset). Setelah
            draft-nya terbuka, tinggal klik <strong>Kirim</strong> di Gmail.
        </p>

        <a href="<?php echo htmlspecialchars($link_gmail); ?>" target="_blank" rel="noopener">
            <button type="button">Buka Gmail</button>
        </a>

        <p style="text-align:center; margin-top: 12px;">
            <a href="login.html">Kembali ke Login</a>
        </p>
    </div>
</body>

</html>
