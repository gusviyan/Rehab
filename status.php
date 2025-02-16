<?php
session_start();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Appointment</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script> 
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            width: 600px;
            text-align: center;
        }
        .header {
            display: flex;
            align-items: center;
            justify-content: center;
            border-bottom: 2px solid #ddd;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header img {
            height: 60px;
            margin-right: 15px;
        }
        .title {
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .info-table td {
            padding: 8px;
            border: 1px solid #ddd;
        }
        .info-table td:first-child {
            font-weight: bold;
            background: #f8f8f8;
            width: 40%;
        }
        .footer {
            margin-top: 20px;
            font-size: 12px;
            color: #555;
        }
        .timestamp {
            font-size: 12px;
            color: #777;
            margin-top: 10px;
            text-align: right;
        }
        .btn {
            margin-top: 15px;
            padding: 10px 15px;
            font-size: 14px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .back-btn {
            background-color: #423f90;
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            display: inline-block;
            border-radius: 5px;
        }
        .error-box {
            color: red;
            font-weight: bold;
            background: #ffdddd;
            padding: 15px;
            margin-bottom: 15px;
            border: 2px solid red;
            border-radius: 5px;
        }
        .success-box {
            color: green;
            font-weight: bold;
            background: #ddffdd;
            padding: 15px;
            margin-bottom: 15px;
            border: 2px solid green;
            border-radius: 5px;
        }
        .warning-icon {
            font-size: 40px;
            color: red;
            margin-bottom: 10px;
        }
        .credit {
            margin-top: 20px;
            font-size: 12px;
            color: #555;
            text-align: center;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container" id="bukti-appointment">
        <!-- Header Rumah Sakit -->
        <div class="header">
            <img src="logo.png" alt="Logo RS">
            <div>
                <div class="title">RS Permata Pamulang</div>
                <div>Jl. Siliwangi no 1A, Pamulang</div>
                <div>Telp: (021) 74704999</div>
            </div>
        </div>

        <!-- **TAMPILKAN ERROR JIKA KUOTA PENUH** -->
        <?php if (isset($_SESSION['error'])): ?>
            <div class="error-box">
                <div class="warning-icon">⚠️</div>
                <?= $_SESSION['error']; ?>
            </div>
            <a href="index.html" class="back-btn">Kembali ke Halaman Utama</a>
            <?php unset($_SESSION['error']); ?>
        
        <!-- **TAMPILKAN DATA JIKA BERHASIL** -->
        <?php elseif (isset($_SESSION['success'])): ?>
            <div class="success-box"><?= $_SESSION['success']; ?></div>

            <h3>Bukti Appointment Rehab Medik</h3>

            <table class="info-table">
                <tr>
                    <td>Nama Pasien</td>
                    <td><?= $_SESSION['nama']; ?></td>
                </tr>
                <tr>
                    <td>Tanggal Lahir</td>
                    <td><?= date('d-m-Y', strtotime($_SESSION['tgl_lahir'])); ?></td>
                </tr>
                <tr>
                    <td>No Tlp (WA)</td>
                    <td><?= $_SESSION['no_hp']; ?></td>
                </tr>
                <tr>
                    <td>Dokter</td>
                    <td><?= $_SESSION['dokter']; ?></td>
                </tr>
                <tr>
                    <td>Tanggal Kunjungan</td>
                    <td><?= date('d-m-Y', strtotime($_SESSION['tgl_kunjungan'])); ?></td>
                </tr>
            </table>

            <div class="timestamp">
                <p><i>Created: <?= date('d-m-Y H:i:s'); ?></i></p>
            </div>

            <button id="simpanBukti" class="btn">Simpan Bukti</button>
            <a href="index.html" class="back-btn">Kembali ke Halaman Utama</a>
            
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <!-- **Footer Credit** -->
        <div class="credit">
            <p>© 2025 Gusviyan - SI RS Permata Pamulang | All Rights Reserved</p>
        </div>
    </div>

    <script>
    document.getElementById('simpanBukti')?.addEventListener('click', function() {
        let saveBtn = document.getElementById('simpanBukti');
        let backBtn = document.querySelector('.back-btn');

        saveBtn.style.display = 'none';
        backBtn.style.display = 'none';

        html2canvas(document.getElementById('bukti-appointment'), {
            backgroundColor: "#ffffff"
        }).then(canvas => {
            let link = document.createElement('a');
            link.href = canvas.toDataURL('image/jpeg', 0.9);
            link.download = 'bukti_appointment.jpg';
            link.click();

            saveBtn.style.display = 'inline-block';
            backBtn.style.display = 'inline-block';
        });
    });
    </script>

</body>
</html>
