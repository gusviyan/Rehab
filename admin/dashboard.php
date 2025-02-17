<?php
session_start();
require '../koneksi.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// Ambil data untuk chart
$chartQuery = "SELECT tgl_kunjungan, COUNT(*) as count FROM appointments GROUP BY tgl_kunjungan";
$chartResult = $conn->query($chartQuery);
$chartData = [];
while ($chartRow = $chartResult->fetch_assoc()) {
    $chartData[] = $chartRow;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Data Appointment</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 0;
        }
        .sidebar {
            width: 250px;
            background-color: #343a40;
            position: fixed;
            height: 100%;
            color: #fff;
            padding: 20px;
        }
        .sidebar h3 {
            text-align: center;
        }
        .sidebar a {
            display: block;
            padding: 10px;
            color: #fff;
            text-decoration: none;
            margin-bottom: 10px;
            border-radius: 5px;
        }
        .sidebar a:hover {
            background-color: #495057;
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
        .chart-container {
            margin: 20px 0;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .footer {
            text-align: center;
            padding: 10px;
            background-color: #343a40;
            color: #fff;
            position: fixed;
            width: 100%;
            bottom: 0;
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <h3>Admin Panel</h3>
    <a href="index.php" class="sidebar-btn">Data Appointment</a>
    <a href="kuota.php" class="sidebar-btn">Set Kuota Dokter</a>
    <a href="tambah_dokter.php" class="sidebar-btn">Tambah Dokter</a>
    <a href="export.php" class="sidebar-btn">Export</a>
    <a href="logout.php" class="sidebar-btn logout-btn">Logout</a>
</div>

<!-- Main Content -->
<div class="main-content">
    <div class="container">
        <h2>
            <img src="logo.png" alt="Logo" class="logo">
            Data Appointment Rehabilitasi Medik
        </h2>

        <!-- Chart -->
        <div class="chart-container">
            <canvas id="myChart"></canvas>
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="footer">
    <p>&copy; 2025 Gusviyan - SI RS Permata Pamulang | All Rights Reserved</p>
</footer>

<script>
    const chartData = <?= json_encode($chartData); ?>;
    const labels = chartData.map(data => data.tgl_kunjungan);
    const counts = chartData.map(data => data.count);

    const ctx = document.getElementById('myChart').getContext('2d');
    const myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Jumlah Appointment per Tanggal',
                data: counts,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>

</body>
</html>
