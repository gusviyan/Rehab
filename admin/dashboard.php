<?php
session_start();
require '../koneksi.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// Ambil daftar dokter dari database
$dokterQuery = "SELECT DISTINCT dokter FROM dokter_kuota";
$dokterResult = $conn->query($dokterQuery);
$dokterOptions = [];
while ($dokterRow = $dokterResult->fetch_assoc()) {
    $dokterOptions[] = $dokterRow['dokter'];
}

// Ambil filter tanggal dan dokter jika ada
$tgl_filter = isset($_GET['tgl_filter']) ? $_GET['tgl_filter'] : '';
$dokter_filter = isset($_GET['dokter_filter']) ? $_GET['dokter_filter'] : '';

// Ambil parameter sorting
$columns = ['nama', 'tgl_lahir', 'nik', 'no_hp', 'dokter', 'tgl_kunjungan'];
$sort_column = isset($_GET['sort']) && in_array($_GET['sort'], $columns) ? $_GET['sort'] : 'tgl_kunjungan'; // Default sort
$sort_order = isset($_GET['order']) && $_GET['order'] == 'asc' ? 'asc' : 'desc'; // Default desc
$next_order = ($sort_order == 'asc') ? 'desc' : 'asc'; // Toggle order

// Query dengan filter tanggal, dokter, dan sorting
$query = "SELECT * FROM appointments WHERE 1=1";
if (!empty($tgl_filter)) {
    $query .= " AND tgl_kunjungan = '$tgl_filter'";
}
if (!empty($dokter_filter)) {
    $query .= " AND dokter = '$dokter_filter'";
}
$query .= " ORDER BY $sort_column $sort_order";
$result = $conn->query($query);

// Fetch data for the chart
$chartDataQuery = "
    SELECT dokter, tgl_kunjungan, COUNT(*) as total_appointments 
    FROM appointments 
    WHERE DAYOFWEEK(tgl_kunjungan) BETWEEN 2 AND 7 
    GROUP BY dokter, tgl_kunjungan";
$chartDataResult = $conn->query($chartDataQuery);
$chartData = [];
while ($row = $chartDataResult->fetch_assoc()) {
    $chartData[] = $row;
}

// Function untuk menampilkan ikon panah sorting putih minimalis
function getSortIcon($column, $sort_column, $sort_order) {
    if ($column === $sort_column) {
        return $sort_order === 'asc' ? ' ▲' : ' ▼';
    }
    return ' ⇅'; // Default icon saat belum diklik
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
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <h3>Admin Panel</h3>
    <a href="index.php" class="sidebar-btn">Data Appointment</a>
    <a href="kuota.php" class="sidebar-btn">Kuota dan Jadwal</a>
    <a href="tambah_dokter.php" class="sidebar-btn">Tambah Dokter</a> <!-- Tambah Button -->
    <a href="export.php" class="sidebar-btn">Export</a> <!-- Tambah Button -->
    <a href="delete.php" class="sidebar-btn">Hapus Data Lama</a> <!-- Tambah Button -->
    <a href="logout.php" class="sidebar-btn logout-btn">Logout</a>
</div>

<!-- Main Content -->
<div class="main-content">
    <div class="container">
        <h2>
            <img src="logo.png" alt="Logo" class="logo">
            Data Appointment Rehabilitasi Medik
        </h2>

        <!-- Filter Tanggal dan Dokter -->
    
        <!-- Grafik Jumlah Appointment -->
        <div>
            <canvas id="appointmentChart"></canvas>
        </div>

        <script>
            const ctx = document.getElementById('appointmentChart').getContext('2d');
            const chartData = <?= json_encode($chartData); ?>;
            const labels = [...new Set(chartData.map(item => item.tgl_kunjungan))];
            const datasets = [];
            const doctors = [...new Set(chartData.map(item => item.dokter))];
            
            doctors.forEach(doctor => {
                const data = labels.map(date => {
                    const appointment = chartData.find(item => item.tgl_kunjungan === date && item.dokter === doctor);
                    return appointment ? appointment.total_appointments : 0;
                });
                datasets.push({
                    label: doctor,
                    data: data,
                    borderColor: '#' + Math.floor(Math.random()*16777215).toString(16),
                    fill: false
                });
            });

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: datasets
                },
                options: {
                    responsive: true,
                    title: {
                        display: true,
                        text: 'Jumlah Appointment per Dokter'
                    },
                    tooltips: {
                        mode: 'index',
                        intersect: false
                    },
                    hover: {
                        mode: 'nearest',
                        intersect: true
                    },
                    scales: {
                        x: {
                            display: true,
                            title: {
                                display: true,
                                text: 'Tanggal'
                            }
                        },
                        y: {
                            display: true,
                            title: {
                                display: true,
                                text: 'Jumlah Appointment'
                            }
                        }
                    }
                }
            });
        </script>
    </div>
</div>

<!-- Footer -->
<footer class="footer">
    <p>&copy; 2025 Gusviyan - RS Permata Pamulang | All Rights Reserved</p>
</footer>