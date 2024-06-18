<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'Perusahaan') {
    header("Location: login.php");
    exit();
}

include 'koneksi.php';

$lamaran_id = $_POST['lamaran_id'];
$status = $_POST['status'];

if ($status == 'Diterima') {
    $tgl_diterima = date('Y-m-d');
    $sql = "UPDATE tbl_lamaran SET status = ?, tgl_diterima = ? WHERE id_lamaran = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $status, $tgl_diterima, $lamaran_id);
} else {
    $sql = "UPDATE tbl_lamaran SET status = ?, tgl_diterima = NULL WHERE id_lamaran = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $lamaran_id);
}

if ($stmt->execute()) {
    $_SESSION['message'] = "Status lamaran berhasil diubah!";
} else {
    $_SESSION['message'] = "Gagal mengubah status lamaran.";
}

$stmt->close();
$conn->close();

header("Location: daftar_pelamar.php");
exit();
?>
