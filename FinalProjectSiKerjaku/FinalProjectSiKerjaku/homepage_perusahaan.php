<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'Perusahaan') {
    header("Location: login.php");
    exit();
}

$nama_perusahaan = $_SESSION['nama'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda Perusahaan - SikerjaKu</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            display: flex;
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
        }

        .sidebar a:hover,
        .sidebar a.active {
            background-color: #FBC02D;
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
    </style>
</head>
<body>
    <div class="sidebar">
        <a href="homepage_perusahaan.php">
            <img src="Logo SiKerjaKu.png" alt="SikerjaKu Logo">
        </a>
        <a href="profil_perusahaan.php">Profil Perusahaan</a>
        <a href="input_loker.php">Input Data Loker</a>
        <a href="daftar_pelamar.php">Daftar Pelamar</a>
        <a href="laporan_diterima.php">Laporan Pelamar Diterima</a>
        <a href="logout.php">Logout</a>
    </div>
    <div class="header">
        <div class="user-profile">
            <p>Selamat datang, <span><?php echo htmlspecialchars($nama_perusahaan); ?></span>!</p>
            <img src="<?php echo htmlspecialchars($_SESSION['foto'] ?? 'default-profile.png'); ?>" alt="Profile Picture">
        </div>
    </div>
    <div class="content">
        <h2>Selamat Datang di <span style="color: #9AA46F;">SiKerjaKu</span></h2>
    </div>
    <div class="footer">
        <p>&copy; 2024 SikerjaKu. All rights reserved.</p>
    </div>
</body>
</html>
