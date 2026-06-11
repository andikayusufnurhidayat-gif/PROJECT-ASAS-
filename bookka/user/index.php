<?php
session_start();
require '../functions.php';

if(!isset($_SESSION["login"])){
    header("Location: ../login_user.php");
    exit;
}

if($_SESSION["role"] != "user"){
    header("Location: ../admin/index.php");
    exit;
}
$username = $_SESSION['username'];

$q1 = mysqli_query($conn,
    "SELECT COUNT(*) as total
    FROM pinjaman
    WHERE username='$username'"
);

$data1 = mysqli_fetch_assoc($q1);
$totalPinjam = $data1['total'];

$q2 = mysqli_query($conn,
    "SELECT COUNT(*) as total
    FROM pinjaman
    WHERE username='$username'
    AND status='diterima'"
);

$data2 = mysqli_fetch_assoc($q2);
$sedangDipinjam = $data2['total'];

$q3 = mysqli_query($conn,
    "SELECT SUM(denda) as total
    FROM pinjaman
    WHERE username='$username'"
);

$data3 = mysqli_fetch_assoc($q3);
$totalDenda = $data3['total'] ?? 0;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard User</title>
    <link rel="stylesheet" href="../tampilan.css">
</head>
<body>

<div class="sidebar">
    <h2>BOOKKA</h2>

    <div class="welcome">
        Halo, <?php echo $_SESSION['nama']; ?>
    </div>

    <ul class="nav-menu">
        <li><a href="index.php" class="active">🏠 Dashboard</a></li>
        <li><a href="katalog.php">📚 Katalog Buku</a></li>
        <li><a href="penyewaan.php">📖 Penyewaan Saya</a></li>
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

    <div>
        <h1>Dashboard User</h1>
        <p style="color:#6B7280;margin-top:5px;">
            Selamat datang kembali di BOOKKA
        </p>
    </div>

    <div class="user-info">
        👤 <?php echo $_SESSION['nama']; ?>
    </div>

</div>

    <div class="stats-container">

    <div class="card blue">
        <h3>Total Pinjaman</h3>
        <div class="number"><?php echo $totalPinjam; ?></div>
    </div>

    <div class="card green">
        <h3>Sedang Dipinjam</h3>
        <div class="number"><?php echo $sedangDipinjam; ?></div>
    </div>

    <div class="card red">
        <h3>Total Denda</h3>
        <div class="number">
            Rp <?php echo number_format($totalDenda,0,',','.'); ?>
        </div>
    </div>

</div>

    </div>

    <div class="content-card">
        <h2>Selamat Datang di BOOKKA</h2>

        <p>
            Gunakan menu di samping untuk melihat katalog buku,
            melakukan penyewaan, melihat riwayat, dan mengelola profil akun.
        </p>
    </div>

</div>

</body>
</html>