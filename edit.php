<?php
// Form edit, sudah terisi data lama karyawan. Submit -> update.php.
session_start();
require('config.php');

if (!isset($_SESSION['admin'])) {
    header("Location: login.html");
    exit();
}

$id = (int) $_GET['id'];

$query = "SELECT * FROM karyawan WHERE id = $id";
$hasil = mysqli_query($koneksi, $query);
$data = mysqli_fetch_assoc($hasil);

if (!$data) {
    die("Data tidak ditemukan.");
}

// kalau 0, tampilkan kosong aja biar nggak perlu dihapus manual
$gaji_pokok_tampil = ($data['gaji_pokok'] != 0) ? $data['gaji_pokok'] : '';
$lembur_tampil     = ($data['lembur'] != 0) ? $data['lembur'] : '';
$pinjaman_tampil   = ($data['pinjaman'] != 0) ? $data['pinjaman'] : '';
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Karyawan</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container-slip">
        <h1>Edit Data Karyawan</h1>
        <p class="subjudul-periode"><?php echo PERIODE_SLIP_GAJI; ?></p>

        <form method="post" action="update.php">
            <input type="hidden" name="id" value="<?php echo $data['id']; ?>">

            <div class="baris-inline">
                <label for="nama">Nama</label>
                <span>:</span>
                <input type="text" id="nama" name="nama" value="<?php echo htmlspecialchars($data['nama']); ?>" required>
            </div>
            <div class="baris-inline">
                <label for="nik">NIK</label>
                <span>:</span>
                <input type="text" id="nik" name="nik" value="<?php echo htmlspecialchars($data['nik']); ?>" required>
            </div>
            <div class="baris-inline">
                <label for="jabatan">Jabatan</label>
                <span>:</span>
                <input type="text" id="jabatan" name="jabatan" value="<?php echo htmlspecialchars($data['jabatan']); ?>" required>
            </div>

            <div class="grid-2col">
                <div class="section-title">PENGHASILAN</div>
                <div class="section-title">POTONGAN</div>

                <div class="kolom">
                    <label for="gaji_pokok">Gaji Pokok</label>
                    <input type="number" id="gaji_pokok" name="gaji_pokok" value="<?php echo $gaji_pokok_tampil; ?>" class="input-oranye" placeholder="0" step="0.01" oninput="hitungGajiBersih()">

                    <label for="lembur">Lembur</label>
                    <input type="number" id="lembur" name="lembur" value="<?php echo $lembur_tampil; ?>" class="input-oranye" placeholder="0" step="0.01" oninput="hitungGajiBersih()">
                </div>
                <div class="kolom">
                    <label for="pinjaman">Pinjaman Karyawan</label>
                    <input type="number" id="pinjaman" name="pinjaman" value="<?php echo $pinjaman_tampil; ?>" class="input-oranye" placeholder="0" step="0.01" oninput="hitungGajiBersih()">
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

            <button type="submit">Update</button>
        </form>
    </div>

    <script>
        function hitungGajiBersih() {
            const gajiPokok = parseFloat(document.getElementById('gaji_pokok').value) || 0;
            const lembur = parseFloat(document.getElementById('lembur').value) || 0;
            const pinjaman = parseFloat(document.getElementById('pinjaman').value) || 0;

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

        // Dipanggil sekali saat halaman dimuat, biar preview langsung sesuai data yang ada.
        hitungGajiBersih();
    </script>
</body>

</html>
