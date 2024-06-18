<?php
include 'koneksi.php';
session_start();

$login_error = false;
$login_success = false;
$inactive_error = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $usertype = $_POST['usertype'];

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = $conn->prepare("SELECT * FROM tbl_user WHERE (username = ? OR email = ?) AND role = ?");
    $sql->bind_param("sss", $username, $username, $usertype);
    $sql->execute();
    $result = $sql->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($row['status'] == 0) {
            $inactive_error = true;
        } elseif ($password === $row['password']) {
            $_SESSION['user_id'] = $row['id_user'];
            $_SESSION['nama'] = $row['nama'];
            $_SESSION['role'] = $row['role'];
            $login_success = true;

            if ($_SESSION['role'] == 'Perusahaan') {
                header("Location: homepage_perusahaan.php");
            } elseif ($_SESSION['role'] == 'Admin') {
                header("Location: homepage_admin.php");
            } else {
                header("Location: homepage.php");
            }
            exit();
        } else {
            $login_error = true;
        }
    } else {
        $login_error = true;
    }

    $sql->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SikerjaKu</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f9f9f9;
            font-family: Poppins, sans-serif;
        }

        .login-container {
            background-color: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 300px;
        }

        .logo img {
            width: 150px;
            margin-bottom: 20px;
        }

        .login-form {
            margin-top: 20px;
        }

        .login-form h2 {
            margin-bottom: 20px;
            color: #476029;
        }

        .input-group {
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }

        .input-group label img {
            width: 24px;
            height: 24px;
            margin-right: 10px;
        }

        .input-group input, .input-group select {
            width: calc(100% - 34px);
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 10px;
        }

        .login-button {
            background-color: #476029;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 50px;
            cursor: pointer;
            width: 100%;
            margin-top: 20px;
        }

        .login-button:hover {
            background-color: #3b5023;
        }

        .register-link {
            margin-top: 20px;
            color: #777;
        }

        .register-link a {
            color: #476029;
            text-decoration: none;
        }

        .register-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo">
            <img src="Logo SiKerjaku.png" alt="SikerjaKu Logo">
        </div>
        <form class="login-form" method="post" action="">
            <h2>Masuk</h2>
            <div class="input-group">
                <label for="username">
                    <img src="username-icon.png" alt="username icon">
                </label>
                <input type="text" id="username" name="username" placeholder="Username or email" required>
            </div>
            <div class="input-group">
                <label for="password">
                    <img src="password-icon.png" alt="password icon">
                </label>
                <input type="password" id="password" name="password" placeholder="Password" required>
            </div>
            <div class="input-group">
                <label for="usertype">
                    <img src="usertype-icon.png" alt="usertype icon">
                </label>
                <select id="usertype" name="usertype" required>
                    <option value="" disabled selected>Role</option>
                    <option value="Pencari Kerja">Pencari Kerja</option>
                    <option value="Perusahaan">Perusahaan</option>
                    <option value="Admin">Admin</option>
                </select>
            </div>    
            <button type="submit" class="login-button">Masuk</button>
            <p class="register-link">Tidak memiliki akun? <a href="registrasi.php">Daftar Sekarang !!</a></p>
        </form>
    </div>
    <?php if ($login_success): ?>
    <script>
        alert("Login berhasil! Anda akan diarahkan ke halaman homepage.");
    </script>
    <?php elseif ($login_error): ?>
    <script>
        alert("Username atau password salah.");
    </script>
    <?php elseif ($inactive_error): ?>
    <script>
        alert("Akun Anda sudah tidak aktif.");
    </script>
    <?php endif; ?>
</body>
</html>
