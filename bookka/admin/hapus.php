<?php
session_start();
require '../functions.php';

if(!isset($_SESSION["login"]) || $_SESSION["role"] !== "admin"){
    header("Location: ../login_user.php");
    exit;
}

$id = $_GET['id'];

// Hapus data buku
mysqli_query($conn, "DELETE FROM buku WHERE id = $id");

if(mysqli_affected_rows($conn) > 0){
    echo "<script>
        alert('Buku berhasil dihapus!');
        window.location.href='buku.php';
    </script>";
} else {
    echo "<script>
        alert('Buku gagal dihapus!');
        window.location.href='buku.php';
    </script>";
}
?>