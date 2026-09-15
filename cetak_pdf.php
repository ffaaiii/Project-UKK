<?php
// Ambil data satu karyawan, buat PDF slip gaji pakai library FPDF (folder fpdf/).
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

// Total Penghasilan = Gaji Pokok + Lembur, Total Potongan = Pinjaman,
// Gaji Bersih = Total Penghasilan - Total Potongan
$total_penghasilan = $data['gaji_pokok'] + $data['lembur'];
$total_potongan = $data['pinjaman'];
$gaji_bersih = $total_penghasilan - $total_potongan;

require('fpdf/fpdf.php');

$pdf = new FPDF();
$pdf->AddPage();

// Judul + Periode sebagai subjudul di bawahnya
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(190, 10, 'SLIP GAJI KARYAWAN', 0, 1, 'C');

$pdf->SetFont('Arial', '', 11);
$pdf->Cell(190, 7, PERIODE_SLIP_GAJI, 0, 1, 'C');
$pdf->Ln(3);

// Informasi Karyawan
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(50, 8, 'Nama Karyawan', 1, 0);
$pdf->Cell(140, 8, $data['nama'], 1, 1);

$pdf->Cell(50, 8, 'NIK', 1, 0);
$pdf->Cell(140, 8, $data['nik'], 1, 1);

$pdf->Cell(50, 8, 'Jabatan', 1, 0);
$pdf->Cell(140, 8, $data['jabatan'], 1, 1);

$pdf->Cell(50, 8, 'Gaji Pokok', 1, 0);
$pdf->Cell(140, 8, 'Rp ' . number_format($data['gaji_pokok'], 2, ',', '.'), 1, 1);

$pdf->Cell(50, 8, 'Lembur', 1, 0);
$pdf->Cell(140, 8, 'Rp ' . number_format($data['lembur'], 2, ',', '.'), 1, 1);

$pdf->Cell(50, 8, 'Pinjaman Karyawan', 1, 0);
$pdf->Cell(140, 8, 'Rp ' . number_format($data['pinjaman'], 2, ',', '.'), 1, 1);

// Rincian
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(190, 8, 'RINCIAN', 1, 1, 'L');

$pdf->SetFont('Arial', '', 11);
$pdf->Cell(50, 8, 'Total Penghasilan', 1, 0);
$pdf->Cell(140, 8, 'Rp ' . number_format($total_penghasilan, 2, ',', '.'), 1, 1);

$pdf->Cell(50, 8, 'Total Potongan', 1, 0);
$pdf->Cell(140, 8, 'Rp ' . number_format($total_potongan, 2, ',', '.'), 1, 1);

// Kalau minus, FPDF otomatis cetak tanda "-" di depan angka
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(50, 9, 'Gaji Bersih', 1, 0);
$pdf->Cell(140, 9, 'Rp ' . number_format($gaji_bersih, 2, ',', '.'), 1, 1);

$pdf->Output();
