<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'Admin') {
    header("Location: login.php");
    exit();
}

include 'koneksi.php';

// Query untuk menghitung jumlah pelamar
$sql_pelamar = "SELECT COUNT(*) as jumlah_pelamar FROM tbl_pencari_kerja";
$result_pelamar = $conn->query($sql_pelamar);
$row_pelamar = $result_pelamar->fetch_assoc();
$jumlah_pelamar = $row_pelamar['jumlah_pelamar'];

// Query untuk menghitung jumlah lowongan
$sql_lowongan = "SELECT COUNT(*) as jumlah_lowongan FROM tbl_loker";
$result_lowongan = $conn->query($sql_lowongan);
$row_lowongan = $result_lowongan->fetch_assoc();
$jumlah_lowongan = $row_lowongan['jumlah_lowongan'];

// Query untuk menghitung jumlah perusahaan
$sql_perusahaan = "SELECT COUNT(*) as jumlah_perusahaan FROM tbl_perusahaan";
$result_perusahaan = $conn->query($sql_perusahaan);
$row_perusahaan = $result_perusahaan->fetch_assoc();
$jumlah_perusahaan = $row_perusahaan['jumlah_perusahaan'];

$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage Admin - SikerjaKu</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            height: 100vh;
        }

        .sidebar {
            width: 220px;
            background-color: #F5F5D3;
            padding-top: 20px;
            position: fixed;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            box-sizing: border-box;
        }

        .sidebar img {
            width: 150px;
            margin-bottom: 20px;
        }

        .sidebar a {
            text-decoration: none;
            color: #476029;
            padding: 15px;
            display: block;
            width: 100%;
            text-align: center;
            box-sizing: border-box;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background-color: #FBC02D;
            box-sizing: border-box;
        }

        .header {
            background-color: #8C9550;
            color: white;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: calc(100% - 220px);
            position: fixed;
            top: 0;
            left: 220px;
            height: 60px;
        }

        .user-profile {
            display: flex;
            align-items: center;
            color: white;
        }

        .user-profile img {
            width: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .user-profile p {
            margin: 0;
            margin-right: 20px;
        }

        .content {
            margin-left: 220px;
            padding: 80px 20px 20px 20px;
            flex-grow: 1;
            background-color: #f0f0f0;
        }

        .statistik {
            display: flex;
            justify-content: space-around;
            margin-top: 20px;
        }

        .statistik div {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .statistik div h3 {
            margin: 0 0 10px 0;
            color: #476029;
        }

        .statistik div p {
            margin: 0;
            font-size: 24px;
            color: #8C9550;
        }

        .footer {
            background-color: #476029;
            color: white;
            padding: 10px;
            text-align: center;
            position: fixed;
            width: calc(100% - 220px);
            bottom: 0;
            left: 220px;
        }

        .chart-container {
            position: relative;
            height: 40vh;
            width: 80vw;
            margin: 20px auto; /* Center horizontally */
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .chart {
            width: 100%;
            height: 100%;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="sidebar">
        <a href="homepage_admin.php">
            <img src="Logo SiKerjaKu.png" alt="SikerjaKu Logo">
        </a>
        <a href="input_kategori.php">Input Kategori</a>
        <a href="input_subkategori.php">Input Subkategori</a>
        <a href="kelola_pengguna.php">Kelola Pengguna</a>
        <a href="logout.php" class="logout">Logout</a>
    </div>
    <div class="header">
        <div class="user-profile">
            <p>Selamat datang, <span><?php echo htmlspecialchars($_SESSION['nama']); ?></span>!</p>
        </div>
    </div>
    <div class="content">
        <h2>Statistik Sistem</h2>
        <div class="statistik">
            <div>
                <h3>Jumlah Pelamar</h3>
                <p><?php echo $jumlah_pelamar; ?></p>
            </div>
            <div>
                <h3>Jumlah Lowongan</h3>
                <p><?php echo $jumlah_lowongan; ?></p>
            </div>
            <div>
                <h3>Jumlah Perusahaan</h3>
                <p><?php echo $jumlah_perusahaan; ?></p>
            </div>
        </div>
        <!-- Tambahkan elemen canvas untuk grafik -->
        <div class="chart-container">
            <canvas id="myChart" class="chart"></canvas>
        </div>
    </div>
    <div class="footer">
        <p>&copy; 2024 SikerjaKu. All rights reserved.</p>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var ctx = document.getElementById('myChart').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Pelamar', 'Lowongan', 'Perusahaan'],
                    datasets: [{
                        label: 'Jumlah',
                        data: [<?php echo $jumlah_pelamar; ?>, <?php echo $jumlah_lowongan; ?>, <?php echo $jumlah_perusahaan; ?>],
                        backgroundColor: [
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 159, 64, 0.2)'
                        ],
                        borderColor: [
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>
