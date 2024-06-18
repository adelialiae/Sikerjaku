<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$nama_user = $_SESSION['nama'];
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Curriculum Vitae - SikerjaKu</title>
    <style>
        body {
            margin: 0;
            font-family: 'Arial', sans-serif;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
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
            background-color: #495057;
        }

        .content {
            margin-left: 220px;
            padding: 80px 20px 20px 20px;
            flex-grow: 1;
            transition: margin-left 0.3s;
        }

        .footer {
            background-color: #343a40;
            color: white;
            padding: 10px;
            text-align: center;
            position: fixed;
            width: calc(100% - 220px);
            bottom: 0;
            left: 220px;
            transition: left 0.3s;
        }

        .cv-container {
            max-width: 800px;
            margin: auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .cv-container h3 {
            margin-bottom: 20px;
            color: #343a40;
            text-align: center;
        }

        .section-title {
            font-size: 18px;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 10px;
        }

        .cv-section {
            margin-bottom: 20px;
        }

        .cv-section p {
            margin: 5px 0;
        }

        .upload-form {
            margin-bottom: 20px;
            text-align: center;
        }

        .upload-form input[type="file"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            margin-bottom: 10px;
        }

        .upload-form button {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            background-color: #476029;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .upload-form button:hover {
            background-color: #3b5023;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 60px;
            }

            .sidebar a {
                padding: 10px 5px;
            }

            .sidebar a span {
                display: none;
            }

            .header {
                left: 60px;
                width: calc(100% - 60px);
            }

            .content {
                margin-left: 60px;
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
        <div class="cv-container">
            <h3>Curriculum Vitae</h3>

            <!-- Form untuk mengunggah file CV -->
            <div class="upload-form">
                <form action="upload_cv.php" method="post" enctype="multipart/form-data">
                    <input type="file" name="cv_file" accept=".pdf, .doc, .docx" required>
                    <button type="submit" name="upload_cv">Upload CV</button>
                </form>
            </div>

            <?php
            // Tampilkan pratinjau CV jika file CV telah diunggah
            if (isset($_SESSION['cv_file'])) {
                echo "<div class='cv-section'>";
                echo "<div class='section-title'>Preview CV</div>";
                // Tampilkan nama file CV
                echo "<p><strong>File Name:</strong> " . $_SESSION['cv_file']['name'] . "</p>";
                // Tampilkan pratinjau file jika file tersebut adalah PDF
                if (strtolower(pathinfo($_SESSION['cv_file']['path'], PATHINFO_EXTENSION)) == 'pdf') {
                    echo "<embed src='" . $_SESSION['cv_file']['path'] . "' width='100%' height='500px' type='application/pdf'>";
                }
                echo "</div>";
            }
            ?>
            
            <!-- Tambahkan bagian lain dari CV seperti yang sebelumnya -->
        </div>
    </div>
    <div class="footer">
        <p>&copy; 2024 SikerjaKu. All rights reserved.</p>
    </div>
</body>
</html>
