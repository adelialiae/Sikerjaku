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

        .cv-container {
            max-width: 800px;
            margin: auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .cv-container h3 {
            margin-bottom: 20px;
            color: #476029;
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
            <div class="cv-section">
                <div class="section-title">Personal Information</div>
                <p><strong>Name:</strong> John Doe</p>
                <p><strong>Address:</strong> 123 Main Street, City, Country</p>
                <p><strong>Phone:</strong> +123 456 7890</p>
                <p><strong>Email:</strong> john.doe@example.com</p>
            </div>

            <div class="cv-section">
                <div class="section-title">Professional Summary</div>
                <p>A highly motivated and experienced software engineer with a strong background in developing scalable web applications and working in agile environments.</p>
            </div>

            <div class="cv-section">
                <div class="section-title">Work Experience</div>
                <p><strong>Software Engineer</strong> at PT. ABC (2020 - Present)</p>
                <ul>
                    <li>Developed and maintained web applications using JavaScript, HTML, CSS, and React.</li>
                    <li>Collaborated with cross-functional teams to define, design, and ship new features.</li>
                </ul>
                <p><strong>Junior Developer</strong> at PT. XYZ (2018 - 2020)</p>
                <ul>
                    <li>Assisted in the development of internal tools and applications.</li>
                    <li>Participated in code reviews and contributed to the improvement of coding standards.</li>
                </ul>
            </div>

            <div class="cv-section">
                <div class="section-title">Education</div>
                <p><strong>Bachelor of Science in Computer Science</strong> - University of Example (2014 - 2018)</p>
            </div>

            <div class="cv-section">
                <div class="section-title">Skills</div>
                <p>JavaScript, React, HTML, CSS, Node.js, Agile Methodologies, Git, Unit Testing</p>
            </div>

            <div class="cv-section">
                <div class="section-title">Certifications</div>
                <p>Certified JavaScript Developer - Example Certification Authority</p>
            </div>

            <div class="cv-section">
                <div class="section-title">Projects</div>
                <p><strong>Project Name:</strong> E-commerce Platform</p>
                <ul>
                    <li>Developed a fully functional e-commerce platform using MERN stack.</li>
                    <li>Implemented features such as product listing, shopping cart, and payment gateway integration.</li>
                </ul>
            </div>

            <div class="cv-section">
                <div class="section-title">Languages</div>
                <p>English (Fluent), Indonesian (Native)</p>
            </div>

            <div class="cv-section">
                <div class="section-title">References</div>
                <p>Available upon request.</p>
            </div>
        </div>
    </div>
    <div class="footer">
        <p>&copy; 2024 SikerjaKu. All rights reserved.</p>
    </div>
</body>
</html>