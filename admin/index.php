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


<!-- Main Content -->
<div class="main-content">
    <div class="container">
        <h2>
            <img src="logo.png" alt="Logo" class="logo">
            Data Appointment Rehabilitasi Medik
        </h2>

        <!-- Filter Tanggal dan Dokter -->
        <form method="GET" class="filter-container">
            <label>Filter berdasarkan tanggal kunjungan:</label>
            <input type="date" name="tgl_filter" value="<?= htmlspecialchars($tgl_filter); ?>">
            
            <label>Pilih Dokter:</label>
            <select name="dokter_filter">
                <option value="">Semua Dokter</option>
                <?php foreach ($dokterOptions as $dokter): ?>
                    <option value="<?= htmlspecialchars($dokter); ?>" <?= ($dokter == $dokter_filter) ? 'selected' : ''; ?>>
                        <?= htmlspecialchars($dokter); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <button type="submit">Filter</button>
            <a href="index.php" class="reset">Reset</a>
        </form>

        <!-- Tabel Data Appointment -->
        <table>
            <thead>
                <tr>
                    <th><a href="?sort=nama&order=<?= $next_order; ?>&tgl_filter=<?= $tgl_filter; ?>&dokter_filter=<?= $dokter_filter; ?>">Nama Lengkap<?= getSortIcon('nama', $sort_column, $sort_order); ?></a></th>
                    <th><a href="?sort=tgl_lahir&order=<?= $next_order; ?>&tgl_filter=<?= $tgl_filter; ?>&dokter_filter=<?= $dokter_filter; ?>">Tgl Lahir<?= getSortIcon('tgl_lahir', $sort_column, $sort_order); ?></a></th>
                    <th><a href="?sort=nik&order=<?= $next_order; ?>&tgl_filter=<?= $tgl_filter; ?>&dokter_filter=<?= $dokter_filter; ?>">No BPJS<?= getSortIcon('nik', $sort_column, $sort_order); ?></a></th>
                    <th><a href="?sort=no_hp&order=<?= $next_order; ?>&tgl_filter=<?= $tgl_filter; ?>&dokter_filter=<?= $dokter_filter; ?>">No Tlp (WA)<?= getSortIcon('no_hp', $sort_column, $sort_order); ?></a></th>
                    <th><a href="?sort=dokter&order=<?= $next_order; ?>&tgl_filter=<?= $tgl_filter; ?>&dokter_filter=<?= $dokter_filter; ?>">Dokter<?= getSortIcon('dokter', $sort_column, $sort_order); ?></a></th>
                    <th><a href="?sort=tgl_kunjungan&order=<?= $next_order; ?>&tgl_filter=<?= $tgl_filter; ?>&dokter_filter=<?= $dokter_filter; ?>">Tgl Kunjungan<?= getSortIcon('tgl_kunjungan', $sort_column, $sort_order); ?></a></th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['nama']); ?></td>
                    <td><?= date('d-m-Y', strtotime($row['tgl_lahir'])); ?></td>
                    <td><?= htmlspecialchars($row['nik']); ?></td>
                    <td><?= htmlspecialchars($row['no_hp']); ?></td>
                    <td><?= htmlspecialchars($row['dokter']); ?></td>
                    <td><?= date('d-m-Y', strtotime($row['tgl_kunjungan'])); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Footer -->
<footer class="footer">
    <p>&copy; 2025 Gusviyan - SI RS Permata Pamulang | All Rights Reserved</p>
</footer>

</body>
</html>
