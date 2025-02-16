<?php
session_start();
require '../koneksi.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: tambah_dokter.php");
    exit();
}

$id = (int)$_GET['id'];
$message = "";

// Ambil data dokter
$query = "SELECT * FROM dokter_kuota WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$dokter = $result->fetch_assoc();

if (!$dokter) {
    header("Location: tambah_dokter.php");
    exit();
}

// Update dokter
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_dokter = trim($_POST["nama_dokter"]);
    $kuota = (int)$_POST["kuota"];

    if (!empty($nama_dokter) && $kuota > 0) {
        $updateQuery = "UPDATE dokter_kuota SET dokter = ?, kuota = ? WHERE id = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("sii", $nama_dokter, $kuota, $id);
        if ($stmt->execute()) {
            header("Location: tambah_dokter.php");
            exit();
        } else {
            $message = "Gagal mengupdate data!";
        }
    } else {
        $message = "Nama dokter dan kuota harus diisi!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Dokter</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <h3>Admin Panel</h3>
    <a href="index.php" class="sidebar-btn">Data Appointment</a>
    <a href="tambah_dokter.php" class="sidebar-btn">Tambah Dokter</a>
    <a href="logout.php" class="sidebar-btn logout-btn">Logout</a>
</div>

<!-- Main Content -->
<div class="main-content">
    <div class="container">
        <h2>Edit Dokter</h2>

        <!-- Notifikasi -->
        <?php if (!empty($message)): ?>
            <p class="notification"><?= htmlspecialchars($message); ?></p>
        <?php endif; ?>

        <!-- Form Edit Dokter -->
        <form method="POST" class="form-container">
            <label>Nama Dokter:</label>
            <input type="text" name="nama_dokter" value="<?= htmlspecialchars($dokter['dokter']); ?>" required>
            
            <label>Kuota Per Hari:</label>
            <input type="number" name="kuota" value="<?= htmlspecialchars($dokter['kuota']); ?>" min="1" required>

            <button type="submit">Update Dokter</button>
        </form>
    </div>
</div>

</body>
</html>
