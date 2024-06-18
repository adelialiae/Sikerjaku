<?php
session_start();
session_destroy(); // Menghancurkan semua sesi
header("Location: login.php"); // Mengarahkan ke halaman login
exit();
?>
