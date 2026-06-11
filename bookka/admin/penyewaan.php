<?php
session_start();
require '../functions.php';

if(!isset($_SESSION["login"]) || $_SESSION["role"] !== "admin"){
    header("Location: ../login_user.php");
    exit;
}

$penyewaan = query("SELECT * FROM pinjaman ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Data Penyewaan</title>
    <link rel="stylesheet" href="../tampilan.css">
</head>
<body>

<div class="sidebar">
    <h2>Admin Panel</h2>
    <div class="welcome">Halo, <?php echo $_SESSION['nama']; ?></div>
    <ul class="nav-menu">
        <li><a href="index.php">📊 Dashboard</a></li>
        <li><a href="buku.php">📚 Buku</a></li>
        <li><a href="penyewaan.php" class="active">📋 Penyewaan</a></li>
        <li><a href="konfirmasi.php">✅ Konfirmasi</a></li>
        <li><a href="denda.php">💰 Denda</a></li>
    </ul>
    <div class="logout">
        <ul class="nav-menu">
            <li><a href="../logout.php">🚪 Logout</a></li>
        </ul>
    </div>
</div>

<div class="main-content">
    <div class="header">
        <h1>Data Penyewaan</h1>
        <div class="user-info">
            <span><?php echo $_SESSION['nama']; ?></span>
        </div>
    </div>

    <div class="table-container">
        <table border="1" cellpadding="10" cellspacing="0" width="100%">
            <tr bgcolor="#1a1a2e" style="color: white;">
                <th>No</th>
                <th>User</th>
                <th>Buku</th>
                <th>Tgl Pinjam</th>
                <th>Tgl Kembali</th>
                <th>Status</th>
                <th>Denda</th>
                <th>Aksi</th>
            </tr>
            
            <?php if(empty($penyewaan)): ?>
            <tr><td colspan="8" align="center">Belum ada data penyewaan</td><ei
            <?php else: ?>
                <?php $no = 1; foreach($penyewaan as $row): ?>
                <tr>
                    <td align="center"><?php echo $no++; ?></td>
                    <td><?php echo $row['username']; ?></td>
                    <td><?php echo $row['judul_buku']; ?></td>
                    <td align="center"><?php echo date('d/m/Y', strtotime($row['tanggal_penyewaan'])); ?></td>
                    <td align="center"><?php echo date('d/m/Y', strtotime($row['tanggal_kembali'])); ?></td>
                    <td align="center">
                        <?php 
                        if($row['status'] == 'menunggu') echo '⏳ Menunggu';
                        elseif($row['status'] == 'dipinjam') echo '📖 Dipinjam';
                        elseif($row['status'] == 'ditolak') echo '❌ Ditolak';
                        else echo '✅ Dikembalikan';
                        ?>
                     </nbsp;
                    <td align="right">Rp <?php echo number_format($row['denda'], 0, ',', '.'); ?></nbsp;
                    <td align="center">
                        <?php if($row['status'] == 'dipinjam'): ?>
                            <a href="kembalikan.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Kembalikan?')" style="background: #3498db; color: white; padding: 5px 10px; border-radius: 5px; text-decoration: none;">Kembalikan</a>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                     </nbsp;
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </table>
    </div>
</div>

</body>
</html>