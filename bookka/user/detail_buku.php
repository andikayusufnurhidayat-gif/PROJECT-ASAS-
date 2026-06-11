<?php
session_start();
require '../functions.php';

if(!isset($_SESSION["login"]) || $_SESSION["login"] !== true){
    header("Location: ../login_user.php");
    exit;
}

$id = $_GET['id'];
$buku = query("SELECT * FROM buku WHERE id = $id")[0];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Detail Buku</title>
    <link rel="stylesheet" href="../tampilan.css">
</head>
<body>

<div class="sidebar">
    <h2>BOOKKA</h2>
    <div class="welcome">Selamat datang, <?php echo $_SESSION['nama']; ?></div>
    <ul class="nav-menu">
        <li><a href="index.php">📊 Dashboard</a></li>
        <li><a href="katalog.php">📚 Katalog Buku</a></li>
        <li><a href="penyewaan.php">📋 Penyewaan Saya</a></li>
        <li><a href="riwayat.php">📜 Riwayat</a></li>
        <li><a href="profil.php">👤 Profil</a></li>
    </ul>
    <div class="logout">
        <ul class="nav-menu">
            <li><a href="../logout.php">🚪 Logout</a></li>
        </ul>
    </div>
</div>

<div class="main-content">
    <div class="header">
        <h1>Detail Buku</h1>
        <div class="user-info">
            <span><?php echo $_SESSION['nama']; ?></span>
        </div>
    </div>

    <div style="display: flex; gap: 40px; background: white; padding: 30px; border-radius: 12px;">
        <!-- GAMBAR -->
        <div style="text-align: center; width: 250px;">
            <?php if($buku['gambar'] && file_exists('../uploads/' . $buku['gambar'])): ?>
                <img src="../uploads/<?php echo $buku['gambar']; ?>" style="width: 100%; border-radius: 12px;">
            <?php else: ?>
                <div style="background:#f0f0f0; padding: 50px; border-radius: 12px;">📖</div>
            <?php endif; ?>
        </div>
        
        <!-- DETAIL BUKU -->
        <div style="flex: 1;">
            <h2><?php echo $buku['judul']; ?></h2>
            <p><strong>Penulis:</strong> <?php echo $buku['penulis']; ?></p>
            <p><strong>Penerbit:</strong> <?php echo $buku['penerbit']; ?></p>
            <p><strong>Stok:</strong> <?php echo $buku['stok']; ?></p>
            <p><strong>Sinopsis:</strong><br><?php echo nl2br($buku['sinopsis']); ?></p>
            
            <a href="katalog.php" class="btn btn-secondary">Kembali</a>
        </div>
    </div>
</div>

</body>
</html>