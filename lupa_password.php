<?php
// Form "Lupa Kata Sandi" - cuma minta username, prosesnya di proses_lupa_password.php
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Kata Sandi - Slip Gaji Karyawan</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <h1>Lupa Kata Sandi</h1>
        <p>Masukkan username admin. Kami akan siapkan link untuk membuat password baru, dikirim lewat email admin yang sudah tersimpan di database.</p>

        <form method="post" action="proses_lupa_password.php">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>

            <button type="submit">Kirim Link Reset</button>
        </form>

        <p style="text-align:center; margin-top: 12px;">
            <a href="login.html">Kembali ke Login</a>
        </p>
    </div>
</body>

</html>
