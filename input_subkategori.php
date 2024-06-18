<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'Admin') {
    header("Location: login.php");
    exit();
}

include 'koneksi.php';

// Mendapatkan daftar kategori
$sql_kategori = "SELECT id_kategori, nama_kategori FROM tbl_kategori";
$result_kategori = $conn->query($sql_kategori);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_kategori = $_POST['id_kategori'];
    $nama_subkategori = $_POST['nama_subkategori'];
    
    $sql_subkategori_insert = "INSERT INTO tbl_subkategori (id_kategori, nama_subkategori) VALUES ('$id_kategori', '$nama_subkategori')";
    if ($conn->query($sql_subkategori_insert) === TRUE) {
        echo "<script>alert('Subkategori berhasil ditambahkan');</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan saat menambahkan subkategori: " . $conn->error . "');</script>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Subkategori - SikerjaKu</title>
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

        .form-group select,
        .form-group input[type="text"] {
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
        <a href="homepage_admin.php">
            <img src="Logo SiKerjaKu.png" alt="SikerjaKu Logo">
        </a>
        <a href="input_kategori.php">Input Kategori</a>
        <a href="input_subkategori.php" class="active">Input Subkategori</a>
        <a href="kelola_pengguna.php">Kelola Pengguna</a>
        <a href="logout.php" class="logout">Logout</a>
    </div>
    <div class="header">
        <div class="user-profile">
            <p>Selamat datang, <span><?php echo htmlspecialchars($_SESSION['nama']); ?></span>!</p>
        </div>
    </div>
    <div class="content">
        <div class="form-container">
            <h3>Input Subkategori</h3>
            <form method="post" action="">
                <div class="form-group">
                    <label for="id_kategori">Kategori</label>
                    <select id="id_kategori" name="id_kategori" required>
                        <?php if ($result_kategori->num_rows > 0): ?>
                            <?php while ($row_kategori = $result_kategori->fetch_assoc()): ?>
                                <option value="<?php echo htmlspecialchars($row_kategori['id_kategori']); ?>"><?php echo htmlspecialchars($row_kategori['nama_kategori']); ?></option>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <option value="">Tidak ada kategori tersedia</option>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="nama_subkategori">Nama Subkategori</label>
                    <input type="text" id="nama_subkategori" name="nama_subkategori" required>
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
