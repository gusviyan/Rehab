<?php
session_start();
require 'koneksi.php';

// Ambil data dari form
$nama = mysqli_real_escape_string($conn, $_POST['nama']);
$tgl_lahir = mysqli_real_escape_string($conn, $_POST['tgl_lahir']);
$nik = mysqli_real_escape_string($conn, $_POST['nik']);
$no_hp = mysqli_real_escape_string($conn, $_POST['no_hp']);
$dokter = mysqli_real_escape_string($conn, $_POST['dokter']);
$tgl_kunjungan = mysqli_real_escape_string($conn, $_POST['tgl_kunjungan']);

// Ambil kuota dokter dari database
$query_quota = "SELECT kuota FROM dokter_kuota WHERE dokter = '$dokter'";
$result_quota = $conn->query($query_quota);

$quota = ($result_quota->num_rows > 0) ? $result_quota->fetch_assoc()['kuota'] : 1;

// Cek jumlah appointment untuk dokter dan tanggal yang sama
$query_check = "SELECT COUNT(*) as total FROM appointments WHERE dokter = '$dokter' AND tgl_kunjungan = '$tgl_kunjungan'";
$result_check = $conn->query($query_check);
$count = ($result_check) ? $result_check->fetch_assoc()['total'] : 0;

if ($count >= $quota) {
    $_SESSION['error'] = "Kuota dokter sudah penuh untuk tanggal <b>" . date('d-m-Y', strtotime($tgl_kunjungan)) . "</b>. Silahkan reschedule.";
} else {
    // Simpan data ke database jika kuota masih tersedia
    $query_insert = "INSERT INTO appointments (nama, tgl_lahir, nik, no_hp, dokter, tgl_kunjungan) 
                     VALUES ('$nama', '$tgl_lahir', '$nik', '$no_hp', '$dokter', '$tgl_kunjungan')";
    
    if ($conn->query($query_insert) === TRUE) {
        $_SESSION['success'] = "
            <b>Appointment berhasil dibuat";

        // Simpan data dalam session untuk digunakan di status.php
        $_SESSION['nama'] = $nama;
        $_SESSION['tgl_lahir'] = $tgl_lahir;
        $_SESSION['no_hp'] = $no_hp;
        $_SESSION['dokter'] = $dokter;
        $_SESSION['tgl_kunjungan'] = $tgl_kunjungan;

    } else {
        $_SESSION['error'] = "Gagal membuat appointment. Error: " . $conn->error;
    }
}

header("Location: status.php");
exit();
?>
