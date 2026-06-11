<?php
session_start();
require '../functions.php';

if(!isset($_SESSION["login"]) || $_SESSION["login"] !== true){
    header("Location: ../login_user.php");
    exit;
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $buku_id = $_POST['buku_id'];
    $judul_buku = $_POST['judul_buku'];
    $penulis = $_POST['penulis'];
    $tanggal_pinjam = $_POST['tanggal_pinjam'];
    $tanggal_kembali = $_POST['tanggal_kembali'];
    
    $username = $_SESSION['username'];
    $kelas = $_SESSION['kelas'] ?? '-';
    $gmail = $_SESSION['email'];
    
    // Insert ke tabel pinjaman
    $query = "INSERT INTO pinjaman (username, kelas, gmail, judul_buku, tanggal_kembali, tanggal_penyewaan, denda, status) 
              VALUES ('$username', '$kelas', '$gmail', '$judul_buku', '$tanggal_kembali', '$tanggal_pinjam', '0', 'menunggu')";
    
    mysqli_query($conn, $query);
    
    if(mysqli_affected_rows($conn) > 0){
        echo "<script>
            alert('Peminjaman berhasil! Menunggu konfirmasi admin.');
            document.location.href='penyewaan.php';
        </script>";
    } else {
        echo "<script>
            alert('Peminjaman gagal! Silahkan coba lagi.');
            document.location.href='katalog.php';
        </script>";
    }
} else {
    header("Location: katalog.php");
    exit;
}
?>