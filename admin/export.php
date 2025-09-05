<?php
require '../koneksi.php';
require '../vendor/autoload.php'; // Make sure you have installed PhpSpreadsheet via Composer

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

// Default today's date
$tanggal = date('Y-m-d');
$message = "";

// If form is submitted, get the selected date
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["tanggal"])) {
    $tanggal = mysqli_real_escape_string($conn, $_POST["tanggal"]);

    // Retrieve appointment data by date
    $query = "SELECT nama, tgl_lahir, nik, no_hp, dokter, tgl_kunjungan FROM appointments WHERE tgl_kunjungan = '$tanggal'";
    $result = mysqli_query($conn, $query);

    // Check if the query executed successfully
    if (!$result) {
        die("❌ Error in query: " . mysqli_error($conn));
    }

    // If there is data, create an XLSX file and download automatically
    if (mysqli_num_rows($result) > 0) {
        // Create Spreadsheet object
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // // Add logo
        // $drawing = new Drawing();
        // $drawing->setName('Logo');
        // $drawing->setDescription('Logo');
        // $drawing->setPath('path/to/logo.png'); // Path to your logo file
        // $drawing->setHeight(100);
        // $drawing->setCoordinates('A1');
        // $drawing->setWorksheet($sheet);

        // Write header
        $sheet->setCellValue('A3', 'Nama Pasien');
        $sheet->setCellValue('B3', 'Tanggal Lahir');
        $sheet->setCellValue('C3', 'No Kartu');
        $sheet->setCellValue('D3', 'No HP');
        $sheet->setCellValue('E3', 'Dokter');
        $sheet->setCellValue('F3', 'Tanggal Kunjungan');

        // Write appointment data
        $row = 4; // Start from row 4 after the logo and header
        while ($data = mysqli_fetch_assoc($result)) {
            $sheet->setCellValue('A' . $row, $data['nama']);
            $sheet->setCellValue('B' . $row, $data['tgl_lahir']);
            $sheet->setCellValue('C' . $row, $data['nik']);
            $sheet->setCellValue('D' . $row, $data['no_hp']);
            $sheet->setCellValue('E' . $row, $data['dokter']);
            
            // Format date for 'Tanggal Kunjungan' and 'Tanggal Lahir' columns
            $sheet->setCellValue('F' . $row, \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel(new DateTime($data['tgl_kunjungan'])));
            $sheet->getStyle('F' . $row)->getNumberFormat()->setFormatCode('DD-MM-YYYY');
            
            $row++;
        }

        // Set header for XLSX file download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="appointment_' . $tanggal . '.xlsx"');
        header('Cache-Control: max-age=0');

        // Write XLSX file to output
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
    <a href="kuota.php" class="sidebar-btn">Kuota dan Jadwal</a>
    <a href="tambah_dokter.php" class="sidebar-btn">Tambah Dokter</a> <!-- Tambah Button -->
    <!-- <a href="export.php" class="sidebar-btn">Export</a> -->
    <a href="delete.php" class="sidebar-btn">Hapus Data Lama</a> <!-- Tambah Button -->
    <a href="logout.php" class="sidebar-btn logout-btn">Logout</a>
</div>


<div class="main-content">
    <div class="container">
        <h2>
            <img src="logo.png" alt="Logo" class="logo">
            Export Data Appointment Rehabilitasi Medik
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

<!-- Footer -->
<footer class="footer">
<p>&copy; 2025 Gusviyan - RS Permata Pamulang | All Rights Reserved</p>
</footer>

</body>
</html>
