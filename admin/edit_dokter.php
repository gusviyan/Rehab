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
            $message = "❌ Gagal mengupdate data!";
        }
    } else {
        $message = "⚠️ Nama dokter dan kuota harus diisi!";
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
            background: #007bff;
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
            background: #0056b3;
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
    </style>
</head>
<body>

<!-- Sidebar -->
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
        <h2>Edit Dokter</h2>

        <!-- Notifikasi -->
        <?php if (!empty($message)): ?>
            <p class="notification <?= strpos($message, '❌') !== false ? 'error-message' : 'success-message'; ?>">
                <?= htmlspecialchars($message); ?>
            </p>
        <?php endif; ?>

        <!-- Form Edit Dokter -->
        <div class="form-container">
            <form method="POST">
                <div class="form-row">
                    <div class="form-group">
                        <label>Nama Dokter:</label>
                        <input type="text" name="nama_dokter" value="<?= htmlspecialchars($dokter['dokter']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Kuota Per Hari:</label>
                        <input type="number" name="kuota" value="<?= htmlspecialchars($dokter['kuota']); ?>" min="1" required>
                    </div>
                </div>

                <button type="submit" class="btn-primary">Update Dokter</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>
