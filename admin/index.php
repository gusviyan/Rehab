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

// Ambil parameter pagination
$records_per_page = isset($_GET['records_per_page']) ? (int)$_GET['records_per_page'] : 10; // Default records per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Default page
$offset = ($page - 1) * $records_per_page;

// Query dengan filter tanggal, dokter, sorting, dan pagination
$query = "SELECT * FROM appointments WHERE 1=1";
if (!empty($tgl_filter)) {
    $query .= " AND tgl_kunjungan = '$tgl_filter'";
}
if (!empty($dokter_filter)) {
    $query .= " AND dokter = '$dokter_filter'";
}
$query .= " ORDER BY $sort_column $sort_order LIMIT $offset, $records_per_page";
$result = $conn->query($query);

// Query untuk menghitung total records
$total_records_query = "SELECT COUNT(*) as total FROM appointments WHERE 1=1";
if (!empty($tgl_filter)) {
    $total_records_query .= " AND tgl_kunjungan = '$tgl_filter'";
}
if (!empty($dokter_filter)) {
    $total_records_query .= " AND dokter = '$dokter_filter'";
}
$total_records_result = $conn->query($total_records_query);
$total_records = $total_records_result->fetch_assoc()['total'];
$total_pages = ceil($total_records / $records_per_page);

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
    <script>
        function updateRecordsPerPage() {
            var select = document.getElementById('records_per_page_select');
            var value = select.options[select.selectedIndex].value;
            window.location.href = 'index.php?records_per_page=' + value;
        }
    </script>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <h3>Admin Panel</h3>
    <a href="index.php" class="sidebar-btn">Data Appointment</a>
    <a href="kuota.php" class="sidebar-btn">Set Kuota Dokter</a>
    <a href="tambah_dokter.php" class="sidebar-btn">Tambah Dokter</a>
    <a href="export.php" class="sidebar-btn">Export</a>
    <a href="delete.php" class="sidebar-btn">Hapus Data Lama</a>
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
                    <th><a href="?sort=nama&order=<?= $next_order; ?>&tgl_filter=<?= $tgl_filter; ?>&dokter_filter=<?= $dokter_filter; ?>&records_per_page=<?= $records_per_page; ?>&page=<?= $page; ?>">Nama Lengkap<?= getSortIcon('nama', $sort_column, $sort_order); ?></a></th>
                    <th><a href="?sort=tgl_lahir&order=<?= $next_order; ?>&tgl_filter=<?= $tgl_filter; ?>&dokter_filter=<?= $dokter_filter; ?>&records_per_page=<?= $records_per_page; ?>&page=<?= $page; ?>">Tgl Lahir<?= getSortIcon('tgl_lahir', $sort_column, $sort_order); ?></a></th>
                    <th><a href="?sort=nik&order=<?= $next_order; ?>&tgl_filter=<?= $tgl_filter; ?>&dokter_filter=<?= $dokter_filter; ?>&records_per_page=<?= $records_per_page; ?>&page=<?= $page; ?>">No BPJS<?= getSortIcon('nik', $sort_column, $sort_order); ?></a></th>
                    <th><a href="?sort=no_hp&order=<?= $next_order; ?>&tgl_filter=<?= $tgl_filter; ?>&dokter_filter=<?= $dokter_filter; ?>&records_per_page=<?= $records_per_page; ?>&page=<?= $page; ?>">No Tlp (WA)<?= getSortIcon('no_hp', $sort_column, $sort_order); ?></a></th>
                    <th><a href="?sort=dokter&order=<?= $next_order; ?>&tgl_filter=<?= $tgl_filter; ?>&dokter_filter=<?= $dokter_filter; ?>&records_per_page=<?= $records_per_page; ?>&page=<?= $page; ?>">Dokter<?= getSortIcon('dokter', $sort_column, $sort_order); ?></a></th>
                    <th><a href="?sort=tgl_kunjungan&order=<?= $next_order; ?>&tgl_filter=<?= $tgl_filter; ?>&dokter_filter=<?= $dokter_filter; ?>&records_per_page=<?= $records_per_page; ?>&page=<?= $page; ?>">Tgl Kunjungan<?= getSortIcon('tgl_kunjungan', $sort_column, $sort_order); ?></a></th>
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

        <!-- Pagination Controls -->
        <div class="pagination">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?page=<?= $i; ?>&records_per_page=<?= $records_per_page; ?>&sort=<?= $sort_column; ?>&order=<?= $sort_order; ?>&tgl_filter=<?= $tgl_filter; ?>&dokter_filter=<?= $dokter_filter; ?>" class="<?= ($i == $page) ? 'active' : ''; ?>"><?= $i; ?></a>
            <?php endfor; ?>
        </div>

        <!-- Records Per Page Selector -->
        <div class="records-per-page">
            <label for="records_per_page_select">Jumlah Data per Halaman:</label>
            <select id="records_per_page_select" onchange="updateRecordsPerPage()">
                <option value="10" <?= ($records_per_page == 10) ? 'selected' : ''; ?>>10</option>
                <option value="25" <?= ($records_per_page == 25) ? 'selected' : ''; ?>>25</option>
                <option value="50" <?= ($records_per_page == 50) ? 'selected' : ''; ?>>50</option>
                <option value="100" <?= ($records_per_page == 100) ? 'selected' : ''; ?>>100</option>
            </select>
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="footer">
    <p>&copy; 2025 Gusviyan - SI RS Permata Pamulang | All Rights Reserved</p>
</footer>

<style>
.pagination {
    display: flex;
    justify-content: center;
    margin-top: 20px;
}

.pagination a {
    color: #423f90;
    float: left;
    padding: 8px 16px;
    text-decoration: none;
    transition: background-color .3s;
    margin: 0 4px;
    border: 1px solid #ddd;
    border-radius: 5px;
}

.pagination a.active {
    background-color: #423f90;
    color: white;
    border: 1px solid #423f90;
}

.pagination a:hover:not(.active) {
    background-color: #ddd;
}

.records-per-page {
    display: flex;
    justify-content: center;
    margin-top: 20px;
}

.records-per-page label {
    margin-right: 10px;
    font-weight: bold;
}

.records-per-page select {
    padding: 5px;
    border-radius: 5px;
}
</style>

<script>
function updateRecordsPerPage() {
    var select = document.getElementById('records_per_page_select');
    var value = select.options[select.selectedIndex].value;
    var url = new URL(window.location.href);
    url.searchParams.set('records_per_page', value);
    window.location.href = url.href;
}
</script>

</body>
</html>
