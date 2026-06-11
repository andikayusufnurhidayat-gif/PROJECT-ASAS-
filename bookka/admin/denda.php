<?php
session_start();
require '../functions.php';

if(!isset($_SESSION["login"]) || $_SESSION["role"] !== "admin"){
    header("Location: ../login_user.php");
    exit;
}

$denda = query("SELECT * FROM pinjaman WHERE denda > 0 ORDER BY denda DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Denda Keterlambatan</title>
    <link rel="stylesheet" href="../tampilan.css">
</head>
<body>

<div class="sidebar">
    <h2>Admin Panel</h2>
    <div class="welcome">Halo, <?php echo $_SESSION['nama']; ?></div>
    <ul class="nav-menu">
        <li><a href="index.php">📊 Dashboard</a></li>
        <li><a href="buku.php">📚 Buku</a></li>
        <li><a href="penyewaan.php">📋 Penyewaan</a></li>
        <li><a href="konfirmasi.php">✅ Konfirmasi</a></li>
        <li><a href="denda.php" class="active">💰 Denda</a></li>
    </ul>
    <div class="logout">
        <ul class="nav-menu">
            <li><a href="../logout.php">🚪 Logout</a></li>
        </ul>
    </div>
</div>

<div class="main-content">
    <div class="header">
        <h1>Denda Keterlambatan</h1>
        <div class="user-info">
            <span><?php echo $_SESSION['nama']; ?></span>
        </div>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>User</th>
                    <th>Buku</th>
                    <th>Terlambat</th>
                    <th>Denda/Hari</th>
                    <th>Total Denda</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($denda)): ?>
                <tr>
                    <td colspan="7" align="center" style="padding: 40px;">
                        ✅ Tidak ada denda
                    </nbsp;
                </tr>
                <?php else: ?>
                    <?php $no = 1; foreach($denda as $row): 
                        $tgl_kembali = new DateTime($row['tanggal_kembali']);
                        $sekarang = new DateTime();
                        $terlambat = $sekarang > $tgl_kembali ? $sekarang->diff($tgl_kembali)->days : 0;
                    ?>
                    <tr>
                        <td align="center"><?php echo $no++; ?></td>
                        <td><?php echo $row['username']; ?></td>
                        <td><?php echo $row['judul_buku']; ?></nbsp;
                        <td align="center"><?php echo $terlambat; ?> Hari</nbsp;
                        <td align="right">Rp 2.000</nbsp;
                        <td align="right">Rp <?php echo number_format($row['denda'], 0, ',', '.'); ?></nbsp;
                        <td align="center">
                            <?php if($row['denda'] > 0 && $row['status'] != 'dikembalikan'): ?>
                                <span class="badge badge-danger">Belum Bayar</span>
                            <?php else: ?>
                                <span class="badge badge-success">Lunas</span>
                            <?php endif; ?>
                        </nbsp;
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>