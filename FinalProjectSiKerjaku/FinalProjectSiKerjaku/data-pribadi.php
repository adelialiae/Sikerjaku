<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'koneksi.php';

$user_id = $_SESSION['user_id'];
$current_page = basename($_SERVER['PHP_SELF']);

// Mendapatkan data dari tbl_pencari_kerja
$sql_pencari_kerja = "SELECT * FROM tbl_pencari_kerja WHERE id_pencaker='$user_id'";
$result_pencari_kerja = $conn->query($sql_pencari_kerja);
$pencari_kerja = $result_pencari_kerja->fetch_assoc();

// Mendapatkan daftar subkategori
$sql_subkategori = "SELECT id_subkategori, nama_subkategori FROM tbl_subkategori";
$result_subkategori = $conn->query($sql_subkategori);
$subkategori_list = [];
while ($row = $result_subkategori->fetch_assoc()) {
    $subkategori_list[] = $row;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $alamat = $_POST['alamat'];
    $kota = $_POST['kota'];
    $kode_pos = $_POST['kode_pos'];
    $agama = $_POST['agama'];
    $telepon = $_POST['telepon'];
    $hp = $_POST['hp'];
    $id_subkategori = $_POST['id_subkategori'];
    $foto = $_FILES['foto']['name'];

    if ($foto) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["foto"]["name"]);
        move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file);

        $sql_user_update = "UPDATE tbl_user SET foto='$target_file' WHERE id_user='$user_id'";
        $conn->query($sql_user_update);
        $_SESSION['foto'] = $target_file;
    }

    // Cek jika data sudah ada atau belum di tbl_pencaker
    $check_pencari_kerja = "SELECT id_pencaker FROM tbl_pencari_kerja WHERE id_pencaker='$user_id'";
    $result_check = $conn->query($check_pencari_kerja);

    if ($result_check->num_rows > 0) {
        // Jika data ada, update
        $sql_pencari_kerja_update = "UPDATE tbl_pencari_kerja SET nama='$nama', email='$email', alamat='$alamat', kota='$kota', kode_pos='$kode_pos', agama='$agama', telepon='$telepon', hp='$hp', id_subkategori='$id_subkategori' WHERE id_pencaker='$user_id'";
        $conn->query($sql_pencari_kerja_update);
    } else {
        // Jika data tidak ada, insert
        $sql_pencari_kerja_insert = "INSERT INTO tbl_pencari_kerja (id_pencaker, nama, email, alamat, kota, kode_pos, agama, telepon, hp, id_subkategori) VALUES ('$user_id', '$nama', '$email', '$alamat', '$kota', '$kode_pos', '$agama', '$telepon', '$hp', '$id_subkategori')";
        $conn->query($sql_pencari_kerja_insert);
    }

    // Refresh data pencaker
    $result_pencari_kerja = $conn->query($sql_pencari_kerja);
    $pencari_kerja = $result_pencari_kerja->fetch_assoc();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pribadi - SikerjaKu</title>
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
        max-width: 600px;
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

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
        color: #333;
    }

    .form-group input[type="text"],
    .form-group input[type="email"],
    .form-group input[type="file"],
    .form-group input[type="tel"] {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 6px;
    }

    .form-group button {
        background-color: #8C9550;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 4px;
        cursor: pointer;
    }

    .form-group button:hover {
        background-color: #617d4a;
    }

    .profile-picture {
        display: block;
        margin: 20px auto;
        max-width: 100%;
        border-radius: 8px;
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
            <p>Selamat datang, <span><?php echo htmlspecialchars($_SESSION['nama']); ?></span>!</p>
            <img src="<?php echo htmlspecialchars($_SESSION['foto'] ?? 'default-profile.png'); ?>" alt="Profile Picture">
        </div>
    </div>
    <div class="content">
        <div class="form-container">
            <h3>Biodata Pencari Kerja</h3>
            <form id="dataPribadiForm" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="nama">Nama Lengkap</label>
                    <input type="text" id="nama" name="nama" value="<?php echo htmlspecialchars($pencari_kerja['nama'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($pencari_kerja['email'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label for="alamat">Alamat</label>
                    <input type="text" id="alamat" name="alamat" value="<?php echo htmlspecialchars($pencari_kerja['alamat'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label for="kota">Kota</label>
                    <input type="text" id="kota" name="kota" value="<?php echo htmlspecialchars($pencari_kerja['kota'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label for="kode_pos">Kode Pos</label>
                    <input type="text" id="kode_pos" name="kode_pos" value="<?php echo htmlspecialchars($pencari_kerja['kode_pos'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label for="agama">Agama</label>
                    <input type="text" id="agama" name="agama" value="<?php echo htmlspecialchars($pencari_kerja['agama'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label for="telepon">Telepon</label>
                    <input type="tel" id="telepon" name="telepon" value="<?php echo htmlspecialchars($pencari_kerja['telepon'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label for="hp">HP</label>
                    <input type="tel" id="hp" name="hp" value="<?php echo htmlspecialchars($pencari_kerja['hp'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label for="id_subkategori">Subkategori</label>
                    <select id="id_subkategori" name="id_subkategori" required>
                        <?php foreach ($subkategori_list as $subkategori): ?>
                            <option value="<?php echo $subkategori['id_subkategori']; ?>" <?php echo (isset($pencari_kerja['id_subkategori']) && $pencari_kerja['id_subkategori'] == $subkategori['id_subkategori']) ? 'selected' : ''; ?>>
                                <?php echo $subkategori['nama_subkategori']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="foto">Upload Foto</label>
                    <input type="file" id="foto" name="foto" accept="image/*">
                </div>
                <div class="form-group">
                    <button type="submit">Simpan</button>
                </div>
            </form>
            <img id="profilePicture" class="profile-picture" src="<?php echo htmlspecialchars($_SESSION['foto'] ?? 'default-profile.png'); ?>" alt="Foto Profil">
        </div>
    </div>
    <div class="footer">
        <p>&copy; 2024 SikerjaKu. All rights reserved.</p>
    </div>
</body>
</html>
