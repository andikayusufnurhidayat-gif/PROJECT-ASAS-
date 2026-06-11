<?php
session_start();
require '../functions.php';

if(!isset($_SESSION["login"]) || $_SESSION["login"] !== true){
    header("Location: ../login_user.php");
    exit;
}

$username = $_SESSION['username'];
$penyewaan = query("SELECT * FROM pinjaman WHERE username = '$username' AND status != 'dikembalikan' ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Penyewaan Saya</title>
    <link rel="stylesheet" href="../tampilan.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f2f5;
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 280px;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            color: white;
            padding: 20px 0;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
        }

        .sidebar h2 {
            text-align: center;
            padding: 20px;
            font-size: 24px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 20px;
        }

        .welcome {
            text-align: center;
            padding: 10px 20px;
            margin: 10px 20px;
            background: rgba(255,255,255,0.1);
            border-radius: 8px;
            font-size: 14px;
        }

        .nav-menu {
            list-style: none;
            padding: 0;
            margin-top: 20px;
        }

        .nav-menu li {
            margin: 5px 0;
        }

        .nav-menu li a {
            display: block;
            padding: 12px 25px;
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 16px;
        }

        .nav-menu li a:hover {
            background: rgba(255,255,255,0.1);
            padding-left: 35px;
        }

        .nav-menu li a.active {
            background: rgba(255,255,255,0.2);
            border-left: 4px solid #667eea;
        }

        .logout {
            margin-top: 50px;
        }

        /* Main Content Styles */
        .main-content {
            margin-left: 280px;
            flex: 1;
            padding: 20px;
        }

        .header {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .header h1 {
            color: #333;
            font-size: 24px;
        }

        .user-info {
            color: #666;
            font-weight: 500;
        }

        /* Rental Card Styles */
        .rental-card {
            background: white;
            border-radius: 12px;
            margin-bottom: 20px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            display: flex;
            gap: 20px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .rental-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        .rental-cover {
            width: 120px;
            height: 160px;
            background: #f8f9fa;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .rental-cover img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .rental-info {
            flex: 1;
        }

        .rental-title {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 8px;
            color: #333;
        }

        .rental-author {
            color: #888;
            font-size: 14px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .rental-dates {
            display: flex;
            gap: 30px;
            margin: 15px 0;
            background: #f8f9fa;
            padding: 12px 15px;
            border-radius: 8px;
        }

        .date-box {
            text-align: left;
        }

        .date-label {
            font-size: 11px;
            color: #888;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }

        .date-value {
            font-size: 14px;
            font-weight: bold;
            color: #333;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
        }

        .status-menunggu { 
            background: #fff3e0; 
            color: #e67e22; 
        }

        .status-dipinjam { 
            background: #e3f2fd; 
            color: #3498db; 
        }

        .status-ditolak {
            background: #ffebee; 
            color: #e74c3c;
        }

        .status-selesai {
            background: #e8f5e9; 
            color: #2ecc71;
        }

        .denda-box {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #e9ecef;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .denda-amount {
            color: #e74c3c;
            font-weight: bold;
            font-size: 18px;
        }

        .empty-state {
            text-align: center;
            padding: 50px;
            background: white;
            border-radius: 12px;
            color: #999;
            font-size: 16px;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 80px;
            }
            
            .sidebar h2, .welcome, .nav-menu li a {
                font-size: 12px;
                text-align: center;
            }
            
            .nav-menu li a span {
                display: none;
            }
            
            .main-content {
                margin-left: 80px;
            }
            
            .rental-card {
                flex-direction: column;
            }
            
            .rental-dates {
                flex-direction: column;
                gap: 10px;
            }
            
            .rental-cover {
                align-self: center;
            }
        }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>📚 BOOKKA</h2>
    <div class="welcome">Selamat datang, <?php echo $_SESSION['nama']; ?></div>
    <ul class="nav-menu">
        <li><a href="index.php">📊 Dashboard</a></li>
        <li><a href="katalog.php">📚 Katalog Buku</a></li>
        <li><a href="penyewaan.php" class="active">📋 Penyewaan Saya</a></li>
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
        <h1>📋 Penyewaan Saya</h1>
        <div class="user-info">
            👤 <?php echo $_SESSION['nama']; ?>
        </div>
    </div>

    <?php if(empty($penyewaan)): ?>
        <div class="empty-state">
            📭 Belum ada penyewaan
        </div>
    <?php else: ?>
        <?php foreach($penyewaan as $row): 
            // AMBIL GAMBAR BUKU DARI DATABASE
            $judul_buku = mysqli_real_escape_string($conn, $row['judul_buku']);
            $query_buku = "SELECT gambar FROM buku WHERE judul = '$judul_buku' LIMIT 1";
            $result_buku = mysqli_query($conn, $query_buku);
            
            $gambar_url = null;
            
            if($result_buku && mysqli_num_rows($result_buku) > 0){
                $data_buku = mysqli_fetch_assoc($result_buku);
                $nama_gambar = $data_buku['gambar'];
                
                // Pastikan gambar tidak kosong dan bukan default
                if(!empty($nama_gambar) && $nama_gambar != 'default.jpg' && $nama_gambar != ''){
                    $path_gambar = '../uploads/' . $nama_gambar;
                    if(file_exists($path_gambar)){
                        $gambar_url = $path_gambar;
                    } else {
                        // Coba cek di folder lain jika perlu
                        $path_gambar_alt = 'uploads/' . $nama_gambar;
                        if(file_exists($path_gambar_alt)){
                            $gambar_url = $path_gambar_alt;
                        }
                    }
                }
            }
        ?>
        <div class="rental-card">
            <div class="rental-cover">
                <?php if($gambar_url): ?>
                    <img src="<?php echo $gambar_url; ?>" alt="Cover Buku: <?php echo htmlspecialchars($row['judul_buku']); ?>">
                <?php else: ?>
                    <img src="../uploads/default-cover.jpg" alt="Default Cover" style="opacity: 0.5;">
                <?php endif; ?>
            </div>
            <div class="rental-info">
                <div class="rental-title"><?php echo htmlspecialchars($row['judul_buku']); ?></div>
                <div class="rental-author">
                    <span>👤 Peminjam:</span> 
                    <strong><?php echo $_SESSION['nama']; ?></strong>
                </div>
                
                <div class="rental-dates">
                    <div class="date-box">
                        <div class="date-label">📅 Tanggal Pinjam</div>
                        <div class="date-value"><?php echo date('d/m/Y', strtotime($row['tanggal_penyewaan'])); ?></div>
                    </div>
                    <div class="date-box">
                        <div class="date-label">📅 Tanggal Kembali</div>
                        <div class="date-value"><?php echo date('d/m/Y', strtotime($row['tanggal_kembali'])); ?></div>
                    </div>
                </div>
                
                <div class="denda-box">
                    <div>
                        <?php 
                        if($row['status'] == 'menunggu'){
                            echo '<span class="status-badge status-menunggu">⏳ Menunggu Konfirmasi</span>';
                        } elseif($row['status'] == 'dipinjam'){
                            echo '<span class="status-badge status-dipinjam">📖 Sedang Dipinjam</span>';
                        } elseif($row['status'] == 'ditolak'){
                            echo '<span class="status-badge status-ditolak">❌ Ditolak</span>';
                        } else {
                            echo '<span class="status-badge status-selesai">✅ Selesai</span>';
                        }
                        ?>
                    </div>
                    <div>
                        <span style="color: #666;">💰 Total Denda:</span>
                        <span class="denda-amount">Rp <?php echo number_format($row['denda'], 0, ',', '.'); ?></span>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

</body>
</html>