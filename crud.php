<?php
// Halaman utama: tabel semua data slip gaji + tombol aksi (Edit, Hapus, PDF, WA, Email).
session_start();
require('config.php');

if (!isset($_SESSION['admin'])) {
    header("Location: login.html");
    exit();
}

$query = "SELECT * FROM karyawan ORDER BY id DESC";
$hasil = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Slip Gaji Karyawan</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container-wide">

        <div class="header-bar">
            <h1>Data Slip Gaji Karyawan</h1>
            <div>
                <a href="tambah.php"><button type="button" class="btn-tambah">+ Tambah Karyawan</button></a>
                <a href="logout.php"><button type="button" class="btn-logout">Logout</button></a>
            </div>
        </div>

        <!-- .table-wrapper: biar tabel lebar ini bisa discroll ke samping di HP -->
        <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>NIK</th>
                    <th>Jabatan</th>
                    <th>Periode</th>
                    <th>Gaji Pokok</th>
                    <th>Lembur</th>
                    <th>Total Penghasilan</th>
                    <th>Pinjaman</th>
                    <th>Gaji Bersih</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($hasil) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($hasil)): ?>
                        <?php
                        // Total Penghasilan tidak disimpan terpisah, dihitung ulang di sini saja
                        $total_penghasilan_row = $row['gaji_pokok'] + $row['lembur'];
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['nama']); ?></td>
                            <td><?php echo htmlspecialchars($row['nik']); ?></td>
                            <td><?php echo htmlspecialchars($row['jabatan']); ?></td>
                            <td><?php echo PERIODE_SLIP_GAJI; ?></td>
                            <td><?php echo number_format($row['gaji_pokok'], 2, ',', '.'); ?></td>
                            <td><?php echo number_format($row['lembur'], 2, ',', '.'); ?></td>
                            <td><?php echo number_format($total_penghasilan_row, 2, ',', '.'); ?></td>
                            <td><?php echo number_format($row['pinjaman'], 2, ',', '.'); ?></td>
                            <td>
                                <?php $kelas = ($row['gaji_bersih'] < 0) ? 'gaji-minus' : ''; ?>
                                <strong class="<?php echo $kelas; ?>">
                                    Rp <?php echo number_format($row['gaji_bersih'], 2, ',', '.'); ?>
                                </strong>
                            </td>
                            <td class="aksi">
                                <a href="edit.php?id=<?php echo $row['id']; ?>">
                                    <button type="button" class="btn-kecil btn-edit">Edit</button>
                                </a>
                                <a href="hapus.php?id=<?php echo $row['id']; ?>"
                                   class="link-hapus" data-nama="<?php echo htmlspecialchars($row['nama']); ?>">
                                    <button type="button" class="btn-kecil btn-hapus">Hapus</button>
                                </a>
                                <a href="cetak_pdf.php?id=<?php echo $row['id']; ?>" target="_blank">
                                    <button type="button" class="btn-kecil btn-pdf">PDF</button>
                                </a>
                                <?php
                                // Pesan untuk tombol WA & Email
                                $pesan = "Halo " . $row['nama'] . ", berikut slip gaji Anda:\n"
                                       . PERIODE_SLIP_GAJI . "\n"
                                       . "Gaji Pokok: Rp " . number_format($row['gaji_pokok'], 2, ',', '.') . "\n"
                                       . "Lembur: Rp " . number_format($row['lembur'], 2, ',', '.') . "\n"
                                       . "Pinjaman: Rp " . number_format($row['pinjaman'], 2, ',', '.') . "\n"
                                       . "Gaji Bersih: Rp " . number_format($row['gaji_bersih'], 2, ',', '.');

                                // nomor/email tujuan diisi manual sendiri pas kebuka
                                $link_wa = "https://api.whatsapp.com/send?text=" . urlencode($pesan);

                                $subjek_email = "Slip Gaji - " . $row['nama'] . " (" . PERIODE_SLIP_GAJI . ")";
                                $link_gmail = "https://mail.google.com/mail/?view=cm&fs=1"
                                            . "&su=" . urlencode($subjek_email)
                                            . "&body=" . urlencode($pesan);
                                ?>
                                <a href="<?php echo $link_wa; ?>" target="_blank">
                                    <button type="button" class="btn-kecil btn-wa">WA</button>
                                </a>
                                <a href="<?php echo $link_gmail; ?>" target="_blank">
                                    <button type="button" class="btn-kecil btn-email">Email</button>
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="10" style="text-align:center;">Belum ada data. Klik "Tambah Karyawan" untuk mulai input.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        </div>
    </div>

    <script>
        // Konfirmasi sebelum hapus, nama diambil dari data-nama (sudah aman lewat htmlspecialchars di PHP)
        document.querySelectorAll('.link-hapus').forEach(function (link) {
            link.addEventListener('click', function (e) {
                var nama = this.dataset.nama;
                if (!confirm('Yakin mau hapus data ' + nama + '?')) {
                    e.preventDefault();
                }
            });
        });
    </script>
</body>

</html>
