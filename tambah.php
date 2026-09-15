<?php
// Form tambah karyawan baru. Submit -> simpan.php.
session_start();
require('config.php');

if (!isset($_SESSION['admin'])) {
    header("Location: login.html");
    exit();
}

// captcha perkalian sederhana, jawabannya disimpan di session
$angka1 = rand(1, 10);
$angka2 = rand(1, 10);
$_SESSION['captcha_jawaban'] = $angka1 * $angka2;
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Karyawan</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container-slip">
        <h1>Tambah Data Karyawan</h1>
        <p class="subjudul-periode"><?php echo PERIODE_SLIP_GAJI; ?></p>

        <?php if (isset($_GET['error']) && $_GET['error'] == 'captcha'): ?>
            <p style="color:#d9534f; text-align:center;">Jawaban captcha salah, silakan coba lagi.</p>
        <?php endif; ?>

        <form method="post" action="simpan.php">
            <div class="baris-inline">
                <label for="nama">Nama</label>
                <span>:</span>
                <input type="text" id="nama" name="nama" required>
            </div>
            <div class="baris-inline">
                <label for="nik">NIK</label>
                <span>:</span>
                <input type="text" id="nik" name="nik" required>
            </div>
            <div class="baris-inline">
                <label for="jabatan">Jabatan</label>
                <span>:</span>
                <input type="text" id="jabatan" name="jabatan" required>
            </div>

            <div class="grid-2col">
                <div class="section-title">PENGHASILAN</div>
                <div class="section-title">POTONGAN</div>

                <div class="kolom">
                    <label for="gaji_pokok">Gaji Pokok</label>
                    <input type="number" id="gaji_pokok" name="gaji_pokok" class="input-oranye" placeholder="0" step="0.01" oninput="hitungGajiBersih()">

                    <label for="lembur">Lembur</label>
                    <input type="number" id="lembur" name="lembur" class="input-oranye" placeholder="0" step="0.01" oninput="hitungGajiBersih()">
                </div>
                <div class="kolom">
                    <label for="pinjaman">Pinjaman Karyawan</label>
                    <input type="number" id="pinjaman" name="pinjaman" class="input-oranye" placeholder="0" step="0.01" oninput="hitungGajiBersih()">
                </div>

                <div class="kolom">
                    <label>Total Penghasilan</label>
                    <div id="preview-total-penghasilan" class="preview-gaji">Rp 0</div>
                </div>
                <div class="kolom">
                    <label>Total Potongan</label>
                    <div id="preview-total-potongan" class="preview-gaji">Rp 0</div>
                </div>
            </div>

            <div class="section-title" style="margin-top: 16px;">GAJI BERSIH</div>
            <div id="preview-gaji-bersih" class="preview-gaji" style="margin-top: 4px;">Rp 0</div>

            <div class="captcha-row">
                <label>Captcha : <?php echo $angka1; ?> x <?php echo $angka2; ?></label>
                <a href="tambah.php" class="btn-refresh-captcha" title="Ganti soal captcha">&#8635;</a>
            </div>
            <input type="number" id="captcha" name="captcha" required>

            <button type="submit">Simpan</button>
        </form>
    </div>

    <script>
        function hitungGajiBersih() {
            const gajiPokok = parseFloat(document.getElementById('gaji_pokok').value) || 0;
            const lembur = parseFloat(document.getElementById('lembur').value) || 0;
            const pinjaman = parseFloat(document.getElementById('pinjaman').value) || 0;

            // Rumus sesuai soal: Total Penghasilan = Gaji Pokok + Lembur,
            // Total Potongan = Pinjaman, Gaji Bersih = Total Penghasilan - Total Potongan
            const totalPenghasilan = gajiPokok + lembur;
            const totalPotongan = pinjaman;
            const gajiBersih = totalPenghasilan - totalPotongan;

            document.getElementById('preview-total-penghasilan').textContent =
                'Rp ' + totalPenghasilan.toLocaleString('id-ID');
            document.getElementById('preview-total-potongan').textContent =
                'Rp ' + totalPotongan.toLocaleString('id-ID');

            const elHasil = document.getElementById('preview-gaji-bersih');
            elHasil.textContent = 'Rp ' + gajiBersih.toLocaleString('id-ID');

            if (gajiBersih < 0) {
                elHasil.classList.add('gaji-minus');
            } else {
                elHasil.classList.remove('gaji-minus');
            }
        }
    </script>
</body>

</html>
