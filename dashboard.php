<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'koneksi.php';

$nama_user = $_SESSION['nama'];
$user_id = $_SESSION['user_id'];

// Mendapatkan nama file halaman yang sedang aktif
$current_page = basename($_SERVER['PHP_SELF']);

// Mendapatkan daftar lowongan pekerjaan yang telah dilamar oleh pengguna
$sql_lamaran = "SELECT l.*, p.nama AS nama_perusahaan, s.nama_subkategori 
                FROM tbl_lamaran lam
                JOIN tbl_loker l ON lam.job_id = l.kode_loker
                JOIN tbl_perusahaan p ON l.kode_perusahaan = p.kode_perusahaan
                JOIN tbl_subkategori s ON l.id_subkategori = s.id_subkategori
                WHERE lam.user_id = ?";
$stmt_lamaran = $conn->prepare($sql_lamaran);
$stmt_lamaran->bind_param("i", $user_id);
$stmt_lamaran->execute();
$result_lamaran = $stmt_lamaran->get_result();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SikerjaKu</title>
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
            color: yellow;
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
            padding: 15px 1px;
            display: block;
            width: 100%;
            text-align: center;
            box-sizing: border-box;
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

        .header .logo {
            height: 10px;
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

        .user-profile p span {
            font-weight: bold;
        }

        .logout {
            color: white;
            text-decoration: none;
            padding: 10px;
            text-align: center;
            width: 100%;
            margin-top: 10px;
            display: block;
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

        .dashboard-content {
            margin-top: 20px;
        }

        .dashboard-content h3 {
            color: #476029;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #8C9550;
            color: white;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <a href="homepage.php">
            <img src="Logo SiKerjaKu.png" alt="SikerjaKu Logo">
        </a>
        <a href="dashboard.php" class="<?php echo ($current_page == 'dashboard.php') ? 'active' : ''; ?>">Dashboard</a>
        <a href="data-pribadi.php" class="<?php echo ($current_page == 'data-pribadi.php') ? 'active' : ''; ?>">Data Pribadi</a>
        <a href="daftar-lowongan.php" class="<?php echo ($current_page == 'daftar-lowongan.php') ? 'active' : ''; ?>">Daftar Lowongan</a>
        <a href="cv.php" class="<?php echo ($current_page == 'cv.php') ? 'active' : ''; ?>">Curriculum Vitae</a>
        <a href="notifikasi.php" class="<?php echo ($current_page == 'notifikasi.php') ? 'active' : ''; ?>">Pemberitahuan & History</a>
        <a href="login.php" class="logout">Logout</a>
    </div>
    <div class="header">
        <div class="user-profile">
            <p>Selamat datang, <span><?php echo htmlspecialchars($nama_user); ?></span>!</p>
            <img src="<?php echo htmlspecialchars($_SESSION['foto'] ?? 'default-profile.png'); ?>" alt="Profile Picture">
        </div>
    </div>
    <div class="content">
        <h2>Selamat Datang di <span style="color: #9AA46F;">SiKerjaKu</span></h2>
        <div class="dashboard-content">
            <h3>Status Lowongan Pekerjaan yang Dilamar</h3>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Perusahaan</th>
                        <th>Lowongan</th>
                        <th>Pekerjaan</th>
                        <th>Exp Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result_lamaran->num_rows > 0): ?>
                        <?php $no = 1; ?>
                        <?php while ($row = $result_lamaran->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo htmlspecialchars($row['nama_perusahaan']); ?></td>
                                <td><?php echo htmlspecialchars($row['posisi']); ?></td>
                                <td><?php echo htmlspecialchars($row['deskripsi']); ?></td>
                                <td><?php echo htmlspecialchars($row['tgl_exp']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">Belum ada lowongan yang dilamar</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="footer">
        <p>&copy; 2024 SikerjaKu. All rights reserved.</p>
    </div>
</body>
</html>
