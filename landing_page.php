<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landing Page - SikerjaKu</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            display: flex;
            height: 100vh;
            background-color: #476029;
            color: white;
        }
        .container {
            display: flex;
            flex-direction: row;
            width: 100%;
            align-items: center;
            justify-content: space-between;
        }
        .left-side {
            width: 50%;
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background-color: #476029;
        }
        .left-side img {
            position: absolute;
            top: 20px;
            left: 20px;
            width: 150px;
        }
        .left-side h1 {
            font-size: 48px;
            margin-bottom: 10px;
        }
        .left-side p {
            font-size: 18px;
            margin-bottom: 30px;
        }
        .right-side {
            width: 50%;
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background-color: white;
            color: #4CAF50;
        }
        .right-side h2 {
            font-size: 24px;
            margin-bottom: 20px;
        }
        .buttons {
            display: flex;
            gap: 20px;
        }
        .buttons a {
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 18px;
            background-color: #8BC34A;
            color: white;
            border: 2px solid #8BC34A;
            transition: background-color 0.3s, color 0.3s;
        }
        .buttons a:hover {
            background-color: white;
            color: #8BC34A;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="left-side">
            <img src="logosikerjakulanding.png" alt="SikerjaKu Logo">
            <h1>Temukan Pekerjaan Terbaikmu !!</h1>
            <p>Temukan peluang karir impian Anda dengan mudah dan cepat di SikerjaKu!</p>
        </div>
        <div class="right-side">
            <h2>Get Started</h2>
            <div class="buttons">
                <a href="login.php">Masuk</a>
                <a href="registrasi.php">Daftar</a>
            </div>
        </div>
    </div>
</body>
</html>
