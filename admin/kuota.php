<?php
session_start();
require '../koneksi.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// Update kuota dokter jika ada perubahan
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    foreach ($_POST['kuota'] as $id => $kuota) {
        $kuota = (int)$kuota; // Pastikan angka
        $query_update = "UPDATE dokter_kuota SET kuota = $kuota WHERE id = $id";
        $conn->query($query_update);
    }
    $_SESSION['success'] = "Kuota dokter berhasil diperbarui.";
    header("Location: kuota.php");
    exit();
}

// Ambil daftar dokter dan kuota
$query_dokter = "SELECT * FROM dokter_kuota";
$result_dokter = $conn->query($query_dokter);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Kuota Dokter</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>
        <img src="logo.png" alt="Logo" class="logo">
        Set Kuota Dokter Rehab Medik
    </h2>

    <?php if (isset($_SESSION['success'])): ?>
        <p class="success-message"><?= $_SESSION['success']; unset($_SESSION['success']); ?></p>
    <?php endif; ?>

    <form method="POST">
        <table class="table-kuota">
            <tr>
                <th>Dokter</th>
                <th>Kuota</th>
            </tr>
            <?php while ($row = $result_dokter->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['dokter']); ?></td>
                <td><input type="number" name="kuota[<?= $row['id']; ?>]" value="<?= $row['kuota']; ?>" min="1"></td>
            </tr>
            <?php endwhile; ?>
        </table>

        <!-- Pusatkan tombol -->
        <div class="button-container">
            <button type="submit" class="btn-primary">Simpan Perubahan</button>
            <a href="index.php" class="btn-secondary">Kembali</a>
        </div>
    </form>
</div>
<footer class="footer">
    <p>&copy; 2025 Gusviyan - SI RS Permata Pamulang | All Rights Reserved</p>
</footer>
</body>
</html>

