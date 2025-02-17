<?php
require '../koneksi.php';
require '../vendor/autoload.php'; // Pastikan sudah menginstal PhpSpreadsheet via Composer

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Default tanggal hari ini
$tanggal = date('Y-m-d');
$message = "";

// Jika form dikirim, ambil tanggal yang dipilih
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["tanggal"])) {
    $tanggal = mysqli_real_escape_string($conn, $_POST["tanggal"]);

    // Ambil data appointment berdasarkan tanggal
    $query = "SELECT nama, tgl_lahir, nik, no_hp, dokter, tgl_kunjungan FROM appointments WHERE tgl_kunjungan = '$tanggal'";
    $result = mysqli_query($conn, $query);

    // Cek apakah query berhasil dijalankan
    if (!$result) {
        die("❌ Error pada query: " . mysqli_error($conn));
    }

    // Jika ada data, buat file XLSX dan download otomatis
    if (mysqli_num_rows($result) > 0) {
        // Membuat objek Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Menulis header
        $sheet->setCellValue('A1', 'Nama Pasien');
        $sheet->setCellValue('B1', 'Tanggal Lahir');
        $sheet->setCellValue('C1', 'No Kartu');
        $sheet->setCellValue('D1', 'No HP');
        $sheet->setCellValue('E1', 'Dokter');
        $sheet->setCellValue('F1', 'Tanggal Kunjungan');

        // Menulis data appointment
        $row = 2; // Mulai dari baris 2 setelah header
        while ($data = mysqli_fetch_assoc($result)) {
            $sheet->setCellValue('A' . $row, $data['nama']);
            $sheet->setCellValue('B' . $row, $data['tgl_lahir']);
            $sheet->setCellValue('C' . $row, $data['nik']);
            $sheet->setCellValue('D' . $row, $data['no_hp']);
            $sheet->setCellValue('E' . $row, $data['dokter']);
            
            // Format tanggal untuk kolom 'Tanggal Kunjungan' dan 'Tanggal Lahir'
            $sheet->setCellValue('F' . $row, \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel(new DateTime($data['tgl_kunjungan'])));
            $sheet->getStyle('F' . $row)->getNumberFormat()->setFormatCode('DD-MM-YYYY');
            
            $row++;
        }

        // Set header untuk download file XLSX
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="appointment_' . $tanggal . '.xlsx"');
        header('Cache-Control: max-age=0');

        // Menulis file XLSX ke output
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit();
    } else {
        $message = "⚠️ Tidak ada data appointment untuk tanggal $tanggal.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Export Data Appointment</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            text-align: center;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            margin: 50px auto;
        }
        h2 {
            margin-bottom: 20px;
        }
        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        input[type="date"] {
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
            width: 100%;
            text-align: center;
        }
        .export-btn {
            background: #423f90;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            margin-top: 10px;
        }
        .export-btn:hover {
            background:rgb(53, 49, 157);
        }
        .notification {
            padding: 10px;
            margin-top: 10px;
            border-radius: 5px;
            font-weight: bold;
            color: white;
            background: #dc3545;
        }
        .back-btn {
            display: block;
            margin-top: 15px;
            text-decoration: none;
            color: #007bff;
        }
        .back-btn:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<!-- Sidebar -->
<div class="sidebar">
    <h3>Admin Panel</h3>
    <a href="index.php" class="sidebar-btn">Data Appointment</a>
    <a href="kuota.php" class="sidebar-btn">Set Kuota Dokter</a>
    <a href="tambah_dokter.php" class="sidebar-btn">Tambah Dokter</a> <!-- Tambah Button -->
    <a href="export.php" class="sidebar-btn">Export</a> <!-- Tambah Button -->
    <a href="logout.php" class="sidebar-btn logout-btn">Logout</a>
</div>

<div class="main-content">
    <div class="container">
        <h2>
            <img src="logo.png" alt="Logo" class="logo">
            Set Kuota Dokter Rehab Medik
        </h2>

    <!-- Notifikasi jika tidak ada data -->
    <?php if (!empty($message)): ?>
        <p class="notification"><?= htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <!-- Form Pilih Tanggal -->
    <form method="POST">
        <label for="tanggal">Pilih Tanggal:</label>
        <input type="date" name="tanggal" id="tanggal" value="<?= htmlspecialchars($tanggal); ?>" required>
        <button type="submit" class="export-btn">Export ke Excel</button>
    </form>

    <a href="index.php" class="back-btn">← Kembali ke Data Appointment</a>
</div>

</body>
</html>
