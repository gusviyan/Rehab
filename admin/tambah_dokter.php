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
            $message = "✅ Dokter berhasil ditambahkan!";
        } else {
            $message = "❌ Gagal menambahkan dokter.";
        }
    } else {
        $message = "⚠️ Nama dokter dan kuota harus diisi!";
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
    <style>
        .form-container {
            background: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 20px 0;
        }
        .form-row {
            display: flex;
            justify-content: flex-start;
            align-items: center;
            gap: 20px;
        }
        .form-group {
            display: flex;
            flex-direction: column;
            flex: 1;
        }
        .form-container label {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .form-container input {
            width: 100%;
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        .btn-primary {
            background: #423f90;
            color: white;
            padding: 10px;
            width: 100%;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
        }
        .btn-primary:hover {
            background:rgb(40, 37, 142);
        }
        .notification {
            text-align: center;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            font-weight: bold;
            color: white;
        }
        .success-message {
            background: #28a745;
        }
        .error-message {
            background: #dc3545;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table th, table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        .delete-btn {
            background: #dc3545;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 14px;
        }
        .delete-btn:hover {
            background: #c82333;
        }

        
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <h3>Admin Panel</h3>
    <a href="index.php" class="sidebar-btn">Data Appointment</a>
    <a href="kuota.php" class="sidebar-btn">Set Kuota Dokter</a>
    <!-- <a href="tambah_dokter.php" class="sidebar-btn">Tambah Dokter</a> Tambah Button -->
    <a href="export.php" class="sidebar-btn">Export</a> <!-- Tambah Button -->
    <a href="delete.php" class="sidebar-btn">Hapus Data Lama</a> <!-- Tambah Button -->
    <a href="logout.php" class="sidebar-btn logout-btn">Logout</a>
</div>


<!-- Main Content -->
<div class="main-content">
    <div class="container">
        <h2>
            <img src="logo.png" alt="Logo" class="logo">
            Tambah Data Dokter
        </h2>

        <!-- Notifikasi -->
        <?php if (!empty($message)): ?>
            <p class="notification <?= strpos($message, '✅') !== false ? 'success-message' : 'error-message'; ?>">
                <?= htmlspecialchars($message); ?>
            </p>
        <?php endif; ?>

        <!-- Form Tambah Dokter -->
        <div class="form-container">
            <form method="POST">
                <div class="form-row">
                    <div class="form-group">
                        <label>Nama Dokter:</label>
                        <input type="text" name="nama_dokter" required>
                    </div>
                    <div class="form-group">
                        <label>Kuota Per Hari:</label>
                        <input type="number" name="kuota" min="1" required>
                    </div>
                </div>

                <button type="submit" name="tambah_dokter" class="btn-primary">Tambah Dokter</button>
            </form>
        </div>

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
