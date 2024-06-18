<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$nama_user = $_SESSION['nama'];
$user_id = $_SESSION['user_id'];
$current_page = basename($_SERVER['PHP_SELF']);

include 'koneksi.php';

$sql_diterima = "SELECT p.nama AS nama_perusahaan, l.posisi, lp.tgl_diterima, lp.status
                 FROM tbl_lamaran lp
                 JOIN tbl_loker l ON lp.job_id = l.kode_loker
                 JOIN tbl_perusahaan p ON l.kode_perusahaan = p.kode_perusahaan
                 WHERE lp.user_id = ? AND lp.status = 'Diterima'";
$stmt_diterima = $conn->prepare($sql_diterima);
$stmt_diterima->bind_param("i", $user_id);
$stmt_diterima->execute();
$result_diterima = $stmt_diterima->get_result();

$sql_history = "SELECT p.nama AS nama_perusahaan, l.posisi, lp.status
                FROM tbl_lamaran lp
                JOIN tbl_loker l ON lp.job_id = l.kode_loker
                JOIN tbl_perusahaan p ON l.kode_perusahaan = p.kode_perusahaan
                WHERE lp.user_id = ?";
$stmt_history = $conn->prepare($sql_history);
$stmt_history->bind_param("i", $user_id);
$stmt_history->execute();
$result_history = $stmt_history->get_result();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History dan Pemberitahuan - SikerjaKu</title>
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

        .logout:hover {
            background-color: #FBC02D;
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
        .form-container {
            max-width: 800px;
            margin: auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .form-container h3 {
            margin-bottom: 20px;
            color: #476029;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
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

        .table-title {
            margin-top: 40px;
            font-weight: bold;
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
        <a href="logout.php" class="logout">Logout</a>
    </div>
    <div class="header">
        <div class="user-profile">
            <p>Selamat datang, <span><?php echo htmlspecialchars($nama_user); ?></span>!</p>
            <img src="<?php echo htmlspecialchars($_SESSION['foto'] ?? 'default-profile.png'); ?>" alt="Profile Picture">
        </div>
    </div>
    <div class="content">
        <div class="form-container">
            <h3>Daftar History dan Pemberitahuan</h3>
            
            <h4 class="table-title">Lamaran yang Diterima</h4>
            <table>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama Perusahaan</th>
                        <th>Pekerjaan</th>
                        <th>Tgl Diterima</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result_diterima->num_rows > 0): ?>
                        <?php $i = 1; ?>
                        <?php while ($row = $result_diterima->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $i++; ?></td>
                                <td><?php echo htmlspecialchars($row['nama_perusahaan']); ?></td>
                                <td><?php echo htmlspecialchars($row['posisi']); ?></td>
                                <td><?php echo htmlspecialchars($row['tgl_diterima']); ?></td>
                                <td><?php echo htmlspecialchars($row['status']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">Tidak ada lamaran yang diterima.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <h4 class="table-title">History Lamaran</h4>
            <table>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama Perusahaan</th>
                        <th>Pekerjaan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result_history->num_rows > 0): ?>
                        <?php $i = 1; ?>
                        <?php while ($row = $result_history->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $i++; ?></td>
                                <td><?php echo htmlspecialchars($row['nama_perusahaan']); ?></td>
                                <td><?php echo htmlspecialchars($row['posisi']); ?></td>
                                <td><?php echo htmlspecialchars($row['status']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4">Tidak ada history lamaran.</td>
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
