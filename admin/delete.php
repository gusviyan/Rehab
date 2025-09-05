<?php
session_start();
require '../koneksi.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    // Query to delete appointments within the specified date range
    $deleteQuery = "DELETE FROM appointments WHERE tgl_kunjungan BETWEEN '$start_date' AND '$end_date'";
    if ($conn->query($deleteQuery) === TRUE) {
        $message = "Data appointment berhasil dihapus.";
    } else {
        $message = "Gagal menghapus data appointment: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Hapus Data Appointment</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 0;
        }
        .main-content {
            margin-left: 270px;
            padding: 20px;
        }
        .main-content h2 {
            display: flex;
            align-items: center;
        }
        .main-content .logo {
            width: 50px;
            margin-right: 10px;
        }
        .form-container {
            margin: 20px 0;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .form-container input,
        .form-container button {
            padding: 10px;
            border: 1px solid #ced4da;
            border-radius: 5px;
            width: 100%;
            margin-bottom: 10px;
        }
        .form-container button {
            background-color: #dc3545;
            color: #fff;
            cursor: pointer;
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
    <a href="export.php" class="sidebar-btn">Export</a>
    <!-- <a href="delete.php" class="sidebar-btn">Hapus Data Lama</a> Tambah Button -->
    <a href="logout.php" class="sidebar-btn logout-btn">Logout</a>
</div>
</div>

<!-- Main Content -->
<div class="main-content">
    <div class="container">
        <h2>
            <img src="logo.png" alt="Logo" class="logo">
            Hapus Data Appointment Lama
        </h2>

        <div class="form-container">
            <?php if (isset($message)): ?>
                <p><?= htmlspecialchars($message); ?></p>
            <?php endif; ?>
            
            <form method="POST">
                <label>Pilih Durasi Tanggal:</label>
                <input type="date" name="start_date" required>
                <input type="date" name="end_date" required>
                <button type="submit">Hapus Data</button>
            </form>
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="footer">
<p>&copy; 2025 Gusviyan - RS Permata Pamulang | All Rights Reserved</p>
</footer>

</body>
</html>