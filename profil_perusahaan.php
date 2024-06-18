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

$sql_perusahaan = "SELECT * FROM tbl_perusahaan WHERE kode_perusahaan='$user_id'";
$result_perusahaan = $conn->query($sql_perusahaan);
$perusahaan = $result_perusahaan->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $kota = $_POST['kota'];
    $kode_pos = $_POST['kode_pos'];
    $email = $_POST['email'];
    $telepon = $_POST['telepon'];
    $nama_loker = $_POST['nama_loker'];
    $jabatan = $_POST['jabatan'];

    if ($perusahaan) {
        // Jika data perusahaan sudah ada, update data
        $sql_perusahaan_update = "UPDATE tbl_perusahaan SET 
                                    nama='$nama', 
                                    alamat='$alamat', 
                                    kota='$kota', 
                                    kode_pos='$kode_pos', 
                                    email='$email', 
                                    telepon='$telepon', 
                                    nama_loker='$nama_loker', 
                                    jabatan='$jabatan' 
                                    WHERE kode_perusahaan='$user_id'";
        if ($conn->query($sql_perusahaan_update) === TRUE) {
            echo "<script>alert('Profil perusahaan berhasil diperbarui');</script>";
        } else {
            echo "<script>alert('Terjadi kesalahan saat memperbarui profil perusahaan: " . $conn->error . "');</script>";
        }
    } else {
        // Jika data perusahaan belum ada, tambahkan data baru
        $sql_perusahaan_insert = "INSERT INTO tbl_perusahaan (kode_perusahaan, nama, alamat, kota, kode_pos, email, telepon, nama_loker, jabatan) 
                                  VALUES ('$user_id', '$nama', '$alamat', '$kota', '$kode_pos', '$email', '$telepon', '$nama_loker', '$jabatan')";
        if ($conn->query($sql_perusahaan_insert) === TRUE) {
            echo "<script>alert('Profil perusahaan berhasil ditambahkan');</script>";
        } else {
            echo "<script>alert('Terjadi kesalahan saat menambahkan profil perusahaan: " . $conn->error . "');</script>";
        }
    }

    // Handle file upload
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == UPLOAD_ERR_OK) {
        $target_dir = "uploadsper/";
        $target_file = $target_dir . basename($_FILES["foto"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is a actual image or fake image
        $check = getimagesize($_FILES["foto"]["tmp_name"]);
        if($check !== false) {
            // Check if file already exists
            if (file_exists($target_file)) {
                echo "<script>alert('Sorry, file already exists.');</script>";
            } else {
                // Check file size (5MB maximum)
                if ($_FILES["foto"]["size"] > 5000000) {
                    echo "<script>alert('Sorry, your file is too large.');</script>";
                } else {
                    // Allow certain file formats
                    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                    && $imageFileType != "gif" ) {
                        echo "<script>alert('Sorry, only JPG, JPEG, PNG & GIF files are allowed.');</script>";
                    } else {
                        // if everything is ok, try to upload file
                        if (move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file)) {
                            // Save the file path in the database
                            $sql_update_foto = "UPDATE tbl_perusahaan SET foto='$target_file' WHERE kode_perusahaan='$user_id'";
                            if ($conn->query($sql_update_foto) === TRUE) {
                                echo "<script>alert('Profil perusahaan berhasil diperbarui');</script>";
                                $_SESSION['foto'] = $target_file; // Update session variable
                            } else {
                                echo "<script>alert('Terjadi kesalahan saat memperbarui profil perusahaan: " . $conn->error . "');</script>";
                            }
                        } else {
                            echo "<script>alert('Sorry, there was an error uploading your file.');</script>";
                        }
                    }
                }
            }
        } else {
            echo "<script>alert('File is not an image.');</script>";
        }
    }

    // Refresh data perusahaan setelah operasi
    $result_perusahaan = $conn->query($sql_perusahaan);
    $perusahaan = $result_perusahaan->fetch_assoc();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Perusahaan - SikerjaKu</title>
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
        .form-group input[type="tel"],
        .form-group input[type="file"] {
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
        <div class="form-container">
            <h3>Profil Perusahaan</h3>
            <form method="post" action="" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="nama">Nama Perusahaan</label>
                    <input type="text" id="nama" name="nama" value="<?php echo htmlspecialchars($perusahaan['nama'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label for="alamat">Alamat</label>
                    <input type="text" id="alamat" name="alamat" value="<?php echo htmlspecialchars($perusahaan['alamat'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label for="kota">Kota</label>
                    <input type="text" id="kota" name="kota" value="<?php echo htmlspecialchars($perusahaan['kota'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label for="kode_pos">Kode Pos</label>
                    <input type="text" id="kode_pos" name="kode_pos" value="<?php echo htmlspecialchars($perusahaan['kode_pos'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($perusahaan['email'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label for="telepon">Telepon</label>
                    <input type="tel" id="telepon" name="telepon" value="<?php echo htmlspecialchars($perusahaan['telepon'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label for="nama_loker">Nama Loker</label>
                    <input type="text" id="nama_loker" name="nama_loker" value="<?php echo htmlspecialchars($perusahaan['nama_loker'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label for="jabatan">Jabatan</label>
                    <input type="text" id="jabatan" name="jabatan" value="<?php echo htmlspecialchars($perusahaan['jabatan'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label for="foto">Foto Profil</label>
                    <input type="file" id="foto" name="foto" accept="image/*">
                    <?php if (!empty($perusahaan['foto'])): ?>
                        <img src="<?php echo htmlspecialchars($perusahaan['foto']); ?>" alt="Foto Profil" style="width: 100px; height: 100px; display: block; margin-top: 10px;">
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <button type="submit">Simpan</button>
                </div>
            </form>
        </div>
    </div>
    <div class="footer">
        <p>&copy; 2024 SikerjaKu. All rights reserved.</p>
    </div>
</body>
</html>
