<?php
session_start();
require '../functions.php';

if(!isset($_SESSION["login"]) || $_SESSION["role"] !== "admin"){
    header("Location: ../login_user.php");
    exit;
}

$id = $_GET['id'];
mysqli_query($conn, "UPDATE pinjaman SET status = 'dipinjam' WHERE id = '$id'");

echo "<script>
    alert('Peminjaman disetujui!');
    window.location.href='konfirmasi.php';
</script>";
?>