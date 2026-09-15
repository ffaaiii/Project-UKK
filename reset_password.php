<?php
// Dibuka lewat link di email reset. Cek token valid, lalu tampilkan form password baru.

require('config.php');

$token = $_GET['token'] ?? '';
$token_aman = mysqli_real_escape_string($koneksi, $token);

// Token harus cocok DAN belum kadaluwarsa
$query = "SELECT * FROM admin WHERE reset_token = '$token_aman' AND reset_expired > NOW()";
$hasil = mysqli_query($koneksi, $query);
$admin = mysqli_fetch_assoc($hasil);

if (!$admin) {
    echo "<!DOCTYPE html><html lang='id'><head><meta charset='UTF-8'>"
        . "<link rel='stylesheet' href='style.css'></head><body>"
        . "<div class='container'><h1>Link Tidak Valid</h1>"
        . "<p>Link reset ini sudah kadaluwarsa atau sudah pernah dipakai.</p>"
        . "<p style='text-align:center;'><a href='lupa_password.php'>Minta link baru</a></p>"
        . "</div></body></html>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Password Baru - Slip Gaji Karyawan</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <h1>Buat Password Baru</h1>
        <p>Untuk akun: <strong><?php echo htmlspecialchars($admin['username']); ?></strong></p>

        <form method="post" action="proses_reset_password.php">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">

            <label for="password">Password Baru</label>
            <input type="password" id="password" name="password" required minlength="6">

            <label for="konfirmasi">Ulangi Password Baru</label>
            <input type="password" id="konfirmasi" name="konfirmasi" required minlength="6">

            <button type="submit">Simpan Password Baru</button>
        </form>
    </div>
</body>

</html>
