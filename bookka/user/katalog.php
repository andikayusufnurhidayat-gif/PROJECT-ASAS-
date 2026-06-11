<?php
session_start();
require '../functions.php';

if(!isset($_SESSION["login"]) || $_SESSION["login"] !== true){
    header("Location: ../login_user.php");
    exit;
}

// PINJAM LANGSUNG
if(isset($_GET['pinjam'])){
    $id = $_GET['pinjam'];
    $buku = query("SELECT * FROM buku WHERE id = $id")[0];
    
    $username = $_SESSION['username'];
    $kelas = $_SESSION['kelas'] ?? '-';
    $gmail = $_SESSION['email'];
    
    mysqli_query($conn, "INSERT INTO pinjaman (username, kelas, gmail, judul_buku, tanggal_penyewaan, tanggal_kembali, denda, status) 
              VALUES ('$username', '$kelas', '$gmail', '{$buku['judul']}', '".date('Y-m-d')."', '".date('Y-m-d', strtotime('+7 days'))."', '0', 'menunggu')");
    
    echo "<script>alert('Berhasil pinjam!'); window.location.href='penyewaan.php';</script>";
    exit;
}

if(isset($_GET['keyword']) && $_GET['keyword'] != ''){
    $keyword = mysqli_real_escape_string($conn, $_GET['keyword']);

    $buku = query("
        SELECT * FROM buku
        WHERE judul LIKE '%$keyword%'
        OR penulis LIKE '%$keyword%'
        OR penerbit LIKE '%$keyword%'
        ORDER BY id DESC
    ");
}else{
    $buku = query("SELECT * FROM buku ORDER BY id DESC");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Katalog Buku</title>
    <link rel="stylesheet" href="../tampilan.css">
    <style>
  .book-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:25px;
}

.search-box{
    width:300px;
}

.search-box input{
    width:100%;
    padding:12px 16px;
    border:1px solid #D1D5DB;
    border-radius:12px;
    outline:none;
    background:#fff;
}

.book-grid{
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(250px,1fr));
    gap:25px;
}

.book-card{
    background:#FFFFFF;
    border-radius:20px;
    overflow:hidden;
    box-shadow:0 4px 20px rgba(0,0,0,.05);
    transition:.3s;
}

.book-card:hover{
    transform:translateY(-8px);
}

.book-cover{
    height:260px;
    background:#E5E7EB;
    overflow:hidden;
}

.book-cover img{
    width:100%;
    height:100%;
    object-fit:cover;
}

.book-info{
    padding:20px;
}

.book-title{
    font-size:18px;
    font-weight:700;
    color:#111827;
    margin-bottom:8px;
}

.book-author{
    color:#6B7280;
    font-size:14px;
    margin-bottom:10px;
}

.book-stock{
    display:inline-block;
    background:#D1FAE5;
    color:#059669;
    padding:6px 12px;
    border-radius:20px;
    font-size:12px;
    font-weight:600;
    margin-bottom:18px;
}

.button-group{
    display:flex;
    gap:10px;
}

.btn-detail{
    flex:1;
    text-align:center;
    text-decoration:none;
    background:#F3F4F6;
    color:#111827;
    padding:10px;
    border-radius:10px;
    font-weight:600;
}

.btn-detail:hover{
    background:#E5E7EB;
}

.btn-pinjam{
    flex:1;
    text-align:center;
    text-decoration:none;
    background:#4F46E5;
    color:#fff;
    padding:10px;
    border-radius:10px;
    font-weight:600;
}

.btn-pinjam:hover{
    background:#4338CA;
}

.empty-cover{
    height:100%;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:70px;
}
</style>
</head>
<body>

<div class="sidebar">
    <h2>BOOKKA</h2>
    <div class="welcome">Selamat datang, <?php echo $_SESSION['nama']; ?></div>
    <ul class="nav-menu">
        <li><a href="index.php">📊 Dashboard</a></li>
        <li><a href="katalog.php" class="active">📚 Katalog Buku</a></li>
        <li><a href="penyewaan.php">📋 Penyewaan Saya</a></li>
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
            <h1>Katalog Buku</h1>
            <p style="color:#6B7280; margin-top:5px;">
                Temukan dan pinjam buku favoritmu
            </p>
        </div>

        <div class="user-info">
            👤 <?php echo $_SESSION['nama']; ?>
        </div>
    </div>

    <div class="book-header">
        <form method="GET" class="search-box">
            <input
                type="text"
                name="keyword"
                placeholder="Cari judul, penulis, penerbit..."
                value="<?php echo $_GET['keyword'] ?? ''; ?>"
            >
        </form>
    </div>

    <div class="book-grid">

        <?php if(empty($buku)): ?>

            <div style="
                background:#fff;
                padding:40px;
                border-radius:20px;
                text-align:center;
                color:#6B7280;
                box-shadow:0 4px 20px rgba(0,0,0,.05);
                grid-column:1/-1;
            ">
                📚 Buku tidak ditemukan
            </div>

        <?php else: ?>

            <?php foreach($buku as $row): ?>

                <div class="book-card">

                    <div class="book-cover">
                        <?php if(!empty($row['gambar']) && file_exists('../uploads/'.$row['gambar'])): ?>
                            <img src="../uploads/<?php echo $row['gambar']; ?>">
                        <?php else: ?>
                            <div class="empty-cover">📖</div>
                        <?php endif; ?>
                    </div>

                    <div class="book-info">

                        <div class="book-title">
                            <?php echo $row['judul']; ?>
                        </div>

                        <div class="book-author">
                            <?php echo $row['penulis']; ?>
                        </div>

                        <div class="book-stock">
                            Stok: <?php echo $row['stok']; ?>
                        </div>

                        <div class="button-group">

                            <a href="detail_buku.php?id=<?php echo $row['id']; ?>"
                               class="btn-detail">
                                Detail
                            </a>

                           <a href="penyewaan.php?id=<?php echo $row['id']; ?>" class="btn-pinjam">
    Sewa Buku
</a>

                        </div>

                    </div>

                </div>

            <?php endforeach; ?>

        <?php endif; ?>

    </div>

</div>

</body>
</html>
</div>

</body>
</html>