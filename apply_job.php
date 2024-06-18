<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'koneksi.php';

$user_id = $_SESSION['user_id'];
$job_id = $_POST['job_id'];
$apply_date = date('Y-m-d');

// Memasukkan data lamaran ke tabel `tbl_lamaran`
$sql = "INSERT INTO tbl_lamaran (user_id, job_id, apply_date) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iis", $user_id, $job_id, $apply_date);

if ($stmt->execute()) {
    $_SESSION['message'] = "Berhasil melamar pekerjaan!";
} else {
    $_SESSION['message'] = "Gagal melamar pekerjaan!";
}

$stmt->close();
$conn->close();

// Redirect kembali ke halaman daftar lowongan dengan pesan notifikasi
header("Location: daftar-lowongan.php");
exit();
?>
