<?php
session_start();
require '../functions.php';

if(!isset($_SESSION["login"]) || $_SESSION["role"] !== "admin"){
    header("Location: ../login_user.php");
    exit;
}

$id = $_GET['id'];
mysqli_query($conn, "UPDATE pinjaman SET status = 'ditolak' WHERE id = '$id'");

echo "<script>
    alert('Peminjaman ditolak!');
    window.location.href='konfirmasi.php';
</script>";
?>