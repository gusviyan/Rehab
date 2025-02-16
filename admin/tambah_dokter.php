<?php
session_start();
require '../koneksi.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// Notifikasi
$message = "";

// Tambah Dokter
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["tambah_dokter"])) {
    $nama_dokter = trim($_POST["nama_dokter"]);
    $kuota = (int)$_POST["kuota"];

    if (!empty($nama_dokter) && $kuota > 0) {
        $query = "INSERT INTO dokter_kuota (dokter, kuota) VALUES (?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("si", $nama_dokter, $kuota);
        if ($stmt->execute()) {
            $message = "Dokter berhasil ditambahkan!";
        } else {
            $message = "Gagal menambahkan dokter.";
        }
    } else {
        $message = "Nama dokter dan kuota harus diisi!";
    }
}

// Hapus Dokter
if (isset($_GET["hapus"])) {
    $id = (int)$_GET["hapus"];
    $query = "DELETE FROM dokter_kuota WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        header("Location: tambah_dokter.php");
        exit();
    }
}

// Ambil data dokter
$query = "SELECT * FROM dokter_kuota ORDER BY dokter ASC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Dokter & Set Kuota</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <h3>Admin Panel</h3>
    <a href="index.php" class="sidebar-btn">Data Appointment</a>
    <a href="tambah_dokter.php" class="sidebar-btn active">Tambah Dokter</a>
    <a href="logout.php" class="sidebar-btn logout-btn">Logout</a>
</div>

<!-- Main Content -->
<div class="main-content">
    <div class="container">
        <h2>Tambah Dokter & Set Kuota</h2>

        <!-- Notifikasi -->
        <?php if (!empty($message)): ?>
            <p class="notification"><?= htmlspecialchars($message); ?></p>
        <?php endif; ?>

        <!-- Form Tambah Dokter -->
        <form method="POST" class="form-container">
            <label>Nama Dokter:</label>
            <input type="text" name="nama_dokter" required>
            
            <label>Kuota Per Hari:</label>
            <input type="number" name="kuota" min="1" required>

            <button type="submit" name="tambah_dokter">Tambah Dokter</button>
        </form>

        <!-- Tabel Daftar Dokter -->
        <h3>Daftar Dokter</h3>
        <table>
            <thead>
                <tr>
                    <th>Nama Dokter</th>
                    <th>Kuota</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['dokter']); ?></td>
                    <td><?= htmlspecialchars($row['kuota']); ?></td>
                    <td>
                        <a href="edit_dokter.php?id=<?= $row['id']; ?>" class="edit-btn">Edit</a>
                        <a href="tambah_dokter.php?hapus=<?= $row['id']; ?>" class="delete-btn" onclick="return confirm('Hapus dokter ini?')">Hapus</a>
                    </td>
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
