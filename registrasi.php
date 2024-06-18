<?php
include 'koneksi.php';

$registrasi_berhasil = false;

function generateUserId($role, $conn) {
    switch ($role) {
        case 'Pencari Kerja':
            $prefix = 'PKJ';
            break;
        case 'Perusahaan':
            $prefix = 'PRS';
            break;
        case 'Admin':
            $prefix = 'ADM';
            break;
        default:
            return null;
    }

    $sql = "SELECT id_user FROM tbl_user WHERE id_user LIKE '$prefix%' ORDER BY id_user DESC LIMIT 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $lastId = (int)substr($row['id_user'], 3) + 1;
    } else {
        $lastId = 1;
    }

    return $prefix . str_pad($lastId, 2, '0', STR_PAD_LEFT);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password']; // Menyimpan password dalam bentuk teks biasa
    $role = $_POST['role'];

    $id_user = generateUserId($role, $conn);

    if ($id_user) {
        $sql = "INSERT INTO tbl_user (id_user, nama, email, username, password, role) VALUES ('$id_user', '$nama', '$email', '$username', '$password', '$role')";

        if ($conn->query($sql) === TRUE) {
            $registrasi_berhasil = true;
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Role tidak valid";
    }
}


$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background-color: white;
            padding: 60px 60px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 500px;
        }

        .logo h1 {
            font-size: 24px;
            margin: 0;
            color: #4CAF50;
        }

        .logo .si {
            color: #6BA544;
        }

        .logo .kerja {
            color: #6BA544;
        }

        .logo .ku {
            color: #6BA544;
        }

        h2 {
            margin: 20px 0;
            color: #476029;
        }

        form input, form select, form button {
            width: 100%;
            padding: 18px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 10px;
        }

        form input:focus, form select:focus {
            border-color: #476029;
        }

        form button {
            background-color: #476029;
            color: white;
            border: none;
            cursor: pointer;
        }

        form button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">
            <img src="Logo SiKerjaku.png" alt="SikerjaKu Logo">
        </div>
        <h2>Registrasi</h2>
        <form class="registration-form" method="post" action="">
            <input type="text" name="nama" placeholder="Nama Lengkap" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <select name="role" required>
                <option value="" disabled selected>Role</option>
                <option value="Pencari Kerja">Pencari Kerja</option>
                <option value="Perusahaan">Perusahaan</option>
                <option value="Admin">Admin</option>
            </select>
            <button type="submit">Daftar</button>
            <p class="login-link">Sudah memiliki Akun? <a href="login.php"> Login Sekarang</a></p>
        </form>

    </div>

    <?php if ($registrasi_berhasil): ?>
    <script>
        alert("Registrasi berhasil! Anda akan diarahkan ke halaman login.");
        window.location.href = "login.php"; // Ubah sesuai dengan nama file login Anda
    </script>
    <?php endif; ?>
</body>
</html>
