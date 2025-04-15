<?php
// File: get_jadwal.php
require 'admin/../koneksi.php';

$dokter = $_GET['dokter'] ?? '';

$data = [];
if ($dokter) {
    $query = "SELECT id FROM dokter_kuota WHERE dokter = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $dokter);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    if ($result) {
        $dokter_id = $result['id'];
        $query_jadwal = "SELECT hari FROM jadwal_dokter WHERE dokter_id = ?";
        $stmt_jadwal = $conn->prepare($query_jadwal);
        $stmt_jadwal->bind_param("i", $dokter_id);
        $stmt_jadwal->execute();
        $res_jadwal = $stmt_jadwal->get_result();

        while ($row = $res_jadwal->fetch_assoc()) {
            $data[] = $row['hari'];
        }
    }
}

header('Content-Type: application/json');
echo json_encode($data);
?>
