<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'Perusahaan') {
    header("Location: login.php");
    exit();
}

include 'koneksi.php';

$user_id = $_SESSION['user_id'];
$current_page = basename($_SERVER['PHP_SELF']);

// Mendapatkan daftar kategori
$sql_kategori = "SELECT id_kategori, nama_kategori FROM tbl_kategori";
$result_kategori = $conn->query($sql_kategori);

// Mendapatkan daftar subkategori
$sql_subkategori = "SELECT id_subkategori, id_kategori, nama_subkategori FROM tbl_subkategori";
$result_subkategori = $conn->query($sql_subkategori);
$subkategori_list = [];
while ($row = $result_subkategori->fetch_assoc()) {
    $subkategori_list[$row['id_kategori']][] = $row;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $posisi = $_POST['posisi'];
    $deskripsi = $_POST['deskripsi'];
    $tgl_loker = $_POST['tgl_loker'];
    $tgl_exp = $_POST['tgl_exp'];
    $id_kategori = $_POST['id_kategori'];
    $id_subkategori = $_POST['id_subkategori'];

    // Tambahkan kode debugging
    echo "Kode Perusahaan: " . $user_id;

    $sql_loker_insert = "INSERT INTO tbl_loker (kode_perusahaan, id_subkategori, posisi, deskripsi, tgl_loker, tgl_exp, status) 
                         VALUES ('$user_id', '$id_subkategori', '$posisi', '$deskripsi', '$tgl_loker', '$tgl_exp', 1)";
    if ($conn->query($sql_loker_insert) === TRUE) {
        echo "<script>alert('Lowongan pekerjaan berhasil ditambahkan');</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan saat menambahkan lowongan pekerjaan: " . $conn->error . "');</script>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Data Loker - SikerjaKu</title>
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
        .form-group input[type="date"],
        .form-group select,
        .form-group textarea {
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
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var subkategoriList = <?php echo json_encode($subkategori_list); ?>;
            
            document.getElementById("id_kategori").addEventListener("change", function() {
                var kategoriId = this.value;
                var subkategoriSelect = document.getElementById("id_subkategori");
                subkategoriSelect.innerHTML = "";
                
                if (subkategoriList[kategoriId]) {
                    subkategoriList[kategoriId].forEach(function(subkategori) {
                        var option = document.createElement("option");
                        option.value = subkategori.id_subkategori;
                        option.textContent = subkategori.nama_subkategori;
                        subkategoriSelect.appendChild(option);
                    });
                }
            });
        });
    </script>
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
            <p>Selamat datang, <span><?php echo htmlspecialchars($_SESSION['nama']); ?></span>!</p>
            <img src="<?php echo htmlspecialchars($_SESSION['foto'] ?? 'default-profile.png'); ?>" alt="Profile Picture">
        </div>
    </div>
    <div class="content">
        <div class="form-container">
            <h3>Input Data Loker</h3>
            <form method="post" action="">
                <div class="form-group">
                    <label for="posisi">Posisi</label>
                    <input type="text" id="posisi" name="posisi" required>
                </div>
                <div class="form-group">
                    <label for="deskripsi">Deskripsi</label>
                    <textarea id="deskripsi" name="deskripsi" rows="4" required></textarea>
                </div>
                <div class="form-group">
                    <label for="tgl_loker">Tanggal Loker</label>
                    <input type="date" id="tgl_loker" name="tgl_loker" required>
                </div>
                <div class="form-group">
                    <label for="tgl_exp">Tanggal Expired</label>
                    <input type="date" id="tgl_exp" name="tgl_exp" required>
                </div>
                <div class="form-group">
                    <label for="id_kategori">Kategori</label>
                    <select id="id_kategori" name="id_kategori" required>
                        <option value="" disabled selected>Pilih Kategori</option>
                        <?php while ($row = $result_kategori->fetch_assoc()): ?>
                            <option value="<?php echo $row['id_kategori']; ?>"><?php echo $row['nama_kategori']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="id_subkategori">Subkategori</label>
                    <select id="id_subkategori" name="id_subkategori" required>
                        <option value="" disabled selected>Pilih Subkategori</option>
                        <!-- Options will be populated dynamically based on kategori -->
                    </select>
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
