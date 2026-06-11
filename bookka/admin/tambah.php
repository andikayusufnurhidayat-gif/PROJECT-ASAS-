<?php
session_start();
require '../functions.php';

if(!isset($_SESSION["login"]) || $_SESSION["role"] !== "admin"){
    header("Location: ../login_user.php");
    exit;
}

if(isset($_POST["submit"])){
    $judul = $_POST["judul"];
    $penulis = $_POST["penulis"];
    $penerbit = $_POST["penerbit"];
    $sinopsis = $_POST["sinopsis"];
    $stok = $_POST["stok"];
    
    // UPLOAD GAMBAR
    $gambar = $_FILES['gambar']['name'];
    $tmp = $_FILES['gambar']['tmp_name'];
    $error = $_FILES['gambar']['error'];
    
    if($error === 0){
        $ekstensi = pathinfo($gambar, PATHINFO_EXTENSION);
        $nama_baru = time() . '.' . $ekstensi;
        $tujuan = '../uploads/' . $nama_baru;
        
        if(move_uploaded_file($tmp, $tujuan)){
            $nama_gambar = $nama_baru;
        } else {
            $nama_gambar = 'default.jpg';
        }
    } else {
        $nama_gambar = 'default.jpg';
    }
    
    $query = "INSERT INTO buku (judul, penulis, penerbit, sinopsis, stok, gambar) 
              VALUES ('$judul', '$penulis', '$penerbit', '$sinopsis', '$stok', '$nama_gambar')";
    
    if(mysqli_query($conn, $query)){
        echo "<script>alert('Buku berhasil ditambahkan!'); window.location.href='buku.php';</script>";
    } else {
        echo "<script>alert('Gagal: " . mysqli_error($conn) . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Buku</title>
    <link rel="stylesheet" href="../tampilan.css">
</head>
<body>

<div class="sidebar">
    <h2>Admin Panel</h2>
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
        <h1>Tambah Buku</h1>
        <div class="user-info">
            <span><?php echo $_SESSION['nama']; ?></span>
        </div>
    </div>

    <div class="form-container">
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Judul Buku</label>
                <input type="text" name="judul" required>
            </div>
            <div class="form-group">
                <label>Penulis</label>
                <input type="text" name="penulis" required>
            </div>
            <div class="form-group">
                <label>Penerbit</label>
                <input type="text" name="penerbit" required>
            </div>
            <div class="form-group">
                <label>Sinopsis</label>
                <textarea name="sinopsis" rows="5" required></textarea>
            </div>
            <div class="form-group">
                <label>Stok</label>
                <input type="number" name="stok" value="10" required>
            </div>
            <div class="form-group">
                <label>Cover Buku</label>
                <input type="file" name="gambar" accept="image/*">
            </div>
            <div class="form-group">
                <button type="submit" name="submit" class="btn btn-primary">Simpan</button>
                <a href="buku.php" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

</body>
</html>