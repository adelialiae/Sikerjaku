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
            height: 100vh;
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
    </style>
</head>
<body>
    <div class="form-container">
        <h3>Input Subkategori</h3>
        <form method="post" action="">
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
                <label for="nama_subkategori">Nama Subkategori</label>
                <input type="text" id="nama_subkategori" name="nama_subkategori" required>
            </div>
            <div class="form-group">
                <button type="submit">Simpan</button>
            </div>
        </form>
    </div>
</body>
</html>
