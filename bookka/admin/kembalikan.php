<?php
session_start();
require '../functions.php';

if(!isset($_SESSION["login"]) || $_SESSION["role"] !== "admin"){
    header("Location: ../login_user.php");
    exit;
}

$id = $_GET['id'];

// Ambil data peminjaman
$pinjaman = query("SELECT * FROM pinjaman WHERE id = $id")[0];

// Hitung denda jika telat
$tgl_kembali = new DateTime($pinjaman['tanggal_kembali']);
$sekarang = new DateTime();
$denda_per_hari = 2000;
$denda = 0;

if($sekarang > $tgl_kembali){
    $telat = $sekarang->diff($tgl_kembali)->days;
    $denda = $telat * $denda_per_hari;
}

// Update status dan denda
mysqli_query($conn, "UPDATE pinjaman SET status = 'dikembalikan', denda = '$denda' WHERE id = '$id'");

if($denda > 0){
    echo "<script>
        alert('Buku dikembalikan! Denda: Rp " . number_format($denda, 0, ',', '.') . "');
        document.location.href='penyewaan.php';
    </script>";
} else {
    echo "<script>
        alert('Buku dikembalikan tepat waktu!');
        document.location.href='penyewaan.php';
    </script>";
}
?>