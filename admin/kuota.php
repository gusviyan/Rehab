<?php
session_start();
require '../koneksi.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// Update kuota dan jadwal jika form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Update kuota
    foreach ($_POST['kuota'] as $id => $kuota) {
        $kuota = (int)$kuota;
        $id = (int)$id;
        $conn->query("UPDATE dokter_kuota SET kuota = $kuota WHERE id = $id");
    }

    // Update jadwal dokter
    if (isset($_POST['jadwal'])) {
        foreach ($_POST['jadwal'] as $dokter_id => $hari_array) {
            $dokter_id = (int)$dokter_id;
            $conn->query("DELETE FROM jadwal_dokter WHERE dokter_id = $dokter_id");

            foreach ($hari_array as $hari) {
                $hari = $conn->real_escape_string($hari);
                $conn->query("INSERT INTO jadwal_dokter (dokter_id, hari) VALUES ($dokter_id, '$hari')");
            }
        }
    }

    $_SESSION['success'] = "Kuota dan jadwal dokter berhasil diperbarui.";
    header("Location: kuota.php");
    exit();
}

// Ambil daftar dokter dan kuota
$query_dokter = "SELECT * FROM dokter_kuota";
$result_dokter = $conn->query($query_dokter);

// Ambil data jadwal dokter
$jadwal_query = "SELECT * FROM jadwal_dokter";
$jadwal_result = $conn->query($jadwal_query);

$jadwal_map = [];
while ($jadwal = $jadwal_result->fetch_assoc()) {
    $dokter_id = $jadwal['dokter_id'];
    $hari = $jadwal['hari'];
    $jadwal_map[$dokter_id][] = $hari;
}
$hari_list = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Kuota Dokter</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .jadwal-checkboxes {
            margin-top: 5px;
            font-size: 0.9em;
        }
        .jadwal-checkboxes label {
            display: inline-block;
            margin-right: 10px;
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <h3>Admin Panel</h3>
    <a href="index.php" class="sidebar-btn">Data Appointment</a>
    <!-- <a href="kuota.php" class="sidebar-btn">Kuota dan Jadwal</a> -->
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
            Set Kuota & Jadwal Dokter Rehabilitasi Medik
        </h2>

        <?php if (isset($_SESSION['success'])): ?>
            <p class="success-message"><?= $_SESSION['success']; unset($_SESSION['success']); ?></p>
        <?php endif; ?>

        <form method="POST">
            <table class="table-kuota">
                <tr>
                    <th>Dokter</th>
                    <th>Kuota</th>
                    <th>Jadwal Praktek</th>
                </tr>
                <?php while ($row = $result_dokter->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['dokter']); ?></td>
                    <td>
                        <input type="number" name="kuota[<?= $row['id']; ?>]" value="<?= $row['kuota']; ?>" min="1">
                    </td>
                    <td>
                        <div class="jadwal-checkboxes">
                            <?php foreach ($hari_list as $hari): ?>
                                <label>
                                    <input type="checkbox" name="jadwal[<?= $row['id']; ?>][]" value="<?= $hari ?>"
                                        <?= in_array($hari, $jadwal_map[$row['id']] ?? []) ? 'checked' : '' ?>>
                                    <?= $hari ?>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>

            <div class="button-container">
                <button type="submit" class="btn-primary">Simpan Perubahan</button>
                <a href="index.php" class="btn-secondary">Kembali</a>
            </div>
        </form>
    </div>
</div>

<!-- Footer -->
<footer class="footer">
    <p>&copy; 2025 RS Permata Pamulang | All Rights Reserved</p>
</footer>

</body>
</html>
