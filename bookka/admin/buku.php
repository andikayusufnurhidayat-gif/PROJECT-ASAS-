<?php
session_start();
require '../functions.php';

if(!isset($_SESSION["login"]) || $_SESSION["role"] !== "admin"){
    header("Location: ../login_user.php");
    exit;
}

$buku = query("SELECT * FROM buku ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kelola Buku</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            position: absolute;
            bottom: 20px;
            width: 100%;
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

        /* Button Styles */
        .btn {
            display: inline-block;
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s ease;
            margin-right: 10px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-edit {
            background: #28a745;
            color: white;
            padding: 6px 12px;
            font-size: 12px;
            border-radius: 4px;
        }

        .btn-edit:hover {
            background: #218838;
            transform: translateY(-1px);
        }

        .btn-delete {
            background: #dc3545;
            color: white;
            padding: 6px 12px;
            font-size: 12px;
            border-radius: 4px;
        }

        .btn-delete:hover {
            background: #c82333;
            transform: translateY(-1px);
        }

        /* Table Styles */
        .table-container {
            background: white;
            border-radius: 10px;
            overflow-x: auto;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table thead {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            color: white;
        }

        table th {
            padding: 15px;
            text-align: left;
            font-weight: 600;
            font-size: 14px;
        }

        table td {
            padding: 12px 15px;
            border-bottom: 1px solid #e9ecef;
            font-size: 14px;
        }

        table tr:hover {
            background: #f8f9fa;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .action-buttons a {
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .empty-row td {
            padding: 40px;
            text-align: center;
            color: #999;
            font-size: 16px;
        }

        /* Cover Image */
        .cover-img {
            width: 40px;
            height: 50px;
            object-fit: cover;
            border-radius: 4px;
        }

        .no-cover {
            font-size: 24px;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 80px;
            }
            
            .sidebar h2, .welcome, .nav-menu li a span {
                display: none;
            }
            
            .main-content {
                margin-left: 80px;
            }
            
            .nav-menu li a {
                text-align: center;
                padding: 12px;
            }
        }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>📚 Admin Panel</h2>
    <div class="welcome">Halo, <?php echo $_SESSION['nama']; ?></div>
    <ul class="nav-menu">
        <li><a href="index.php">📊 Dashboard</a></li>
        <li><a href="buku.php" class="active">📚 Buku</a></li>
        <li><a href="penyewaan.php">📋 Penyewaan</a></li>
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
        <h1>📚 Kelola Buku</h1>
        <div class="user-info">
            👤 <?php echo $_SESSION['nama']; ?>
        </div>
    </div>

    <div style="margin-bottom: 20px;">
        <a href="tambah.php" class="btn btn-primary">+ Tambah Buku</a>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Cover</th>
                    <th>Judul</th>
                    <th>Penulis</th>
                    <th>Penerbit</th>
                    <th>Stok</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($buku)): ?>
                    <tr class="empty-row">
                        <td colspan="7">📖 Belum ada data buku</td>
                    </tr>
                <?php else: ?>
                    <?php $no = 1; foreach($buku as $row): ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td>
                                <?php if($row['gambar'] && file_exists('../uploads/' . $row['gambar'])): ?>
                                    <img src="../uploads/<?php echo $row['gambar']; ?>" class="cover-img" alt="Cover">
                                <?php else: ?>
                                    <span class="no-cover">📖</span>
                                <?php endif; ?>
                            </td>
                            <td><strong><?php echo htmlspecialchars($row['judul']); ?></strong></td>
                            <td><?php echo htmlspecialchars($row['penulis']); ?></td>
                            <td><?php echo htmlspecialchars($row['penerbit']); ?></td>
                            <td>
                                <?php if($row['stok'] <= 5): ?>
                                    <span style="color: #ffc107;">⚠️ <?php echo $row['stok']; ?></span>
                                <?php elseif($row['stok'] <= 0): ?>
                                    <span style="color: #dc3545;">❌ <?php echo $row['stok']; ?></span>
                                <?php else: ?>
                                    <span style="color: #28a745;">✅ <?php echo $row['stok']; ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-edit">✏️ Edit</a>
                                    <a href="hapus.php?id=<?php echo $row['id']; ?>" class="btn btn-delete" onclick="return confirm('Yakin ingin menghapus buku ini?')">🗑️ Hapus</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>