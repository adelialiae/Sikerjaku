<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'Admin') {
    header("Location: login.php");
    exit();
}

include 'koneksi.php';

$id_user = $_GET['id'];

// Menghapus catatan terkait di tabel tbl_lamaran
$sql_lamaran = "DELETE FROM tbl_lamaran WHERE user_id='$id_user'";
$conn->query($sql_lamaran);

// Menghapus catatan terkait di tabel tbl_pencari_kerja
$sql_pencari_kerja = "DELETE FROM tbl_pencari_kerja WHERE id_pencaker='$id_user'";
$conn->query($sql_pencari_kerja);

// Menghapus catatan terkait di tabel tbl_perusahaan
$sql_perusahaan = "DELETE FROM tbl_perusahaan WHERE kode_perusahaan='$id_user'";
$conn->query($sql_perusahaan);

// Setelah menghapus catatan terkait, hapus pengguna dari tabel tbl_user
$sql_user = "DELETE FROM tbl_user WHERE id_user='$id_user'";
if ($conn->query($sql_user) === TRUE) {
    header("Location: kelola_pengguna.php");
    exit();
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>
