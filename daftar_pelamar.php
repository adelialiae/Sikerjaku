<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'Perusahaan') {
    header("Location: login.php");
    exit();
}

$nama_perusahaan = $_SESSION['nama'];

include 'koneksi.php';

$user_id = $_SESSION['user_id'];
$current_page = basename($_SERVER['PHP_SELF']);

$sql_pelamar = "SELECT lp.id_lamaran, pj.nama, pj.email, pj.telepon, lk.posisi, lp.status 
                FROM tbl_lamaran lp
                JOIN tbl_pencari_kerja pj ON lp.user_id = pj.id_pencaker
                JOIN tbl_loker lk ON lp.job_id = lk.kode_loker
                WHERE lk.kode_perusahaan = ?";
$stmt_pelamar = $conn->prepare($sql_pelamar);
$stmt_pelamar->bind_param("i", $user_id);
$stmt_pelamar->execute();
$result_pelamar = $stmt_pelamar->get_result();

$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pelamar - SikerjaKu</title>
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

        .btn-status {
            background-color: #8C9550;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 4px;
        }

        .btn-status:hover {
            background-color: #617d4a;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <a href="homepage_perusahaan.php">
            <img src="Logo SiKerjaKu.png" alt="SikerjaKu Logo">
        </a>
        <a href="profil_perusahaan.php" class="<?php echo ($current_page == 'profil_perusahaan.php') ? 'active' : ''; ?>">Profil Perusahaan</a>
        <a href="input_loker.php" class="<?php echo ($current_page == 'input_loker.php') ? 'active' : ''; ?>">Input Data Loker</a>
        <a href="daftar_pelamar.php" class="<?php echo ($current_page == 'daftar_pelamar.php') ? 'active' : ''; ?>">Daftar Pelamar</a>
        <a href="laporan_diterima.php" class="<?php echo ($current_page == 'laporan_diterima.php') ? 'active' : ''; ?>">Laporan Pelamar Diterima</a>
        <a href="logout.php" class="logout">Logout</a>
    </div>
    <div class="header">
        <div class="user-profile">
            <p>Selamat datang, <span><?php echo htmlspecialchars($nama_perusahaan); ?></span>!</p>
            <img src="<?php echo htmlspecialchars($_SESSION['foto'] ?? 'default-profile.png'); ?>" alt="Profile Picture">
        </div>
    </div>
    <div class="content">
        <h3>Daftar Pelamar</h3>
        <table>
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Nama Pelamar</th>
                    <th>Email</th>
                    <th>Telepon</th>
                    <th>Posisi</th>
                    <th>Status</th>
                    <th>Ubah Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result_pelamar->num_rows > 0): ?>
                    <?php $i = 1; ?>
                    <?php while ($row = $result_pelamar->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $i++; ?></td>
                            <td><?php echo htmlspecialchars($row['nama']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['telepon']); ?></td>
                            <td><?php echo htmlspecialchars($row['posisi']); ?></td>
                            <td><?php echo htmlspecialchars($row['status']); ?></td>
                            <td>
                                <form method="POST" action="ubah_status.php">
                                    <input type="hidden" name="lamaran_id" value="<?php echo $row['id_lamaran']; ?>">
                                    <select name="status">
                                        <option value="Diterima" <?php echo ($row['status'] == 'Diterima') ? 'selected' : ''; ?>>Diterima</option>
                                        <option value="Ditolak" <?php echo ($row['status'] == 'Ditolak') ? 'selected' : ''; ?>>Ditolak</option>
                                        <option value="Menunggu" <?php echo ($row['status'] == 'Menunggu') ? 'selected' : ''; ?>>Menunggu</option>
                                    </select>
                                    <button type="submit" class="btn-status">Ubah</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">Tidak ada pelamar yang ditemukan.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="footer">
        <p>&copy; 2024 SikerjaKu. All rights reserved.</p>
    </div>
</body>
</html>
