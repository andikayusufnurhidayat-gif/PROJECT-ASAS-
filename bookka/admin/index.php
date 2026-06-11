<?php
session_start();
require '../functions.php';

if(!isset($_SESSION["login"])){
    header("Location: ../login_admin.php");
    exit;
}

if($_SESSION["role"] != "admin"){
    header("Location: ../user/index.php");
    exit;
}

// Statistik
$totalBuku = mysqli_fetch_assoc(mysqli_query($conn,
"SELECT COUNT(*) as total FROM buku"))['total'] ?? 0;

$totalUser = mysqli_fetch_assoc(mysqli_query($conn,
"SELECT COUNT(*) as total FROM users WHERE role='user'"))['total'] ?? 0;

$totalPenyewaan = mysqli_fetch_assoc(mysqli_query($conn,
"SELECT COUNT(*) as total FROM pinjaman"))['total'] ?? 0;

$totalDenda = mysqli_fetch_assoc(mysqli_query($conn,
"SELECT SUM(denda) as total FROM pinjaman"))['total'] ?? 0;

if($totalDenda == NULL){
    $totalDenda = 0;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="../tampilan.css">
</head>
<body>

<div class="sidebar">

    <h2>BOOKKA</h2>

    <div class="welcome">
        Halo, <?php echo $_SESSION["nama"]; ?>
    </div>

    <ul class="nav-menu">
        <li><a href="index.php" class="active">📊 Dashboard</a></li>
        <li><a href="buku.php">📚 Kelola Buku</a></li>
        <li><a href="penyewaan.php">📖 Penyewaan</a></li>
        <li><a href="konfirmasi.php">✅ Konfirmasi</a></li>
        <li><a href="denda.php">💰 Denda</a></li>
        <li><a href="laporan.php">📈 Laporan</a></li>
    </ul>

    <div class="logout">
        <ul class="nav-menu">
            <li><a href="../logout.php">🚪 Logout</a></li>
        </ul>
    </div>

</div>

<div class="main-content">

    <div class="header">
        <h1>Dashboard Admin</h1>

        <div class="user-info">
            <span><?php echo $_SESSION["nama"]; ?></span>
        </div>
    </div>

    <div class="stats-container">

        <div class="card blue">
            <h3>Total Buku</h3>
            <div class="number">
                <?php echo $totalBuku; ?>
            </div>
        </div>

        <div class="card green">
            <h3>Total User</h3>
            <div class="number">
                <?php echo $totalUser; ?>
            </div>
        </div>

        <div class="card orange">
            <h3>Total Penyewaan</h3>
            <div class="number">
                <?php echo $totalPenyewaan; ?>
            </div>
        </div>

        <div class="card red">
            <h3>Total Denda</h3>
            <div class="number">
                Rp <?php echo number_format($totalDenda,0,',','.'); ?>
            </div>
        </div>

    </div>

    <div class="table-container">

        <table>

            <thead>
                <tr>
                    <th>Menu Cepat</th>
                    <th>Keterangan</th>
                </tr>
            </thead>

            <tbody>

                <tr>
                    <td>📚 Kelola Buku</td>
                    <td>Tambah, edit, dan hapus buku perpustakaan</td>
                </tr>

                <tr>
                    <td>📖 Penyewaan</td>
                    <td>Melihat seluruh data penyewaan buku</td>
                </tr>

                <tr>
                    <td>✅ Konfirmasi</td>
                    <td>Menyetujui atau menolak penyewaan</td>
                </tr>

                <tr>
                    <td>💰 Denda</td>
                    <td>Mengelola denda keterlambatan</td>
                </tr>

                <tr>
                    <td>📈 Laporan</td>
                    <td>Melihat statistik dan laporan penyewaan</td>
                </tr>

            </tbody>

        </table>

    </div>

</div>

</body>
</html>