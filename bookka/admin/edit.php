<?php
session_start();
require '../functions.php';

if(!isset($_SESSION["login"]) || $_SESSION["role"] !== "admin"){
    header("Location: ../login_user.php");
    exit;
}

$id = $_GET['id'];
$buku = query("SELECT * FROM buku WHERE id = $id")[0];

if(isset($_POST["submit"])){
    $judul = htmlspecialchars($_POST["judul"]);
    $penulis = htmlspecialchars($_POST["penulis"]);
    $penerbit = htmlspecialchars($_POST["penerbit"]);
    $sinopsis = htmlspecialchars($_POST["sinopsis"]);
    $stok = htmlspecialchars($_POST["stok"]);
    $gambar_lama = $buku['gambar'];
    
    // Upload gambar baru
    $gambar = $_FILES['gambar']['name'];
    $tmp = $_FILES['gambar']['tmp_name'];
    
    if($gambar){
        $ekstensi = pathinfo($gambar, PATHINFO_EXTENSION);
        $ekstensi_valid = ['jpg', 'jpeg', 'png', 'gif'];
        
        if(in_array(strtolower($ekstensi), $ekstensi_valid)){
            $nama_gambar = time() . '_' . $gambar;
            $folder = '../uploads/';
            
            if(!file_exists($folder)){
                mkdir($folder, 0777, true);
            }
            
            move_uploaded_file($tmp, $folder . $nama_gambar);
            
            if($gambar_lama != 'default.jpg' && file_exists($folder . $gambar_lama)){
                unlink($folder . $gambar_lama);
            }
        } else {
            $nama_gambar = $gambar_lama;
        }
    } else {
        $nama_gambar = $gambar_lama;
    }
    
    $query = "UPDATE buku SET 
              judul = '$judul',
              penulis = '$penulis',
              penerbit = '$penerbit',
              sinopsis = '$sinopsis',
              stok = '$stok',
              gambar = '$nama_gambar'
              WHERE id = $id";
    
    if(mysqli_query($conn, $query)){
        echo "<script>alert('Buku berhasil diupdate!'); window.location.href='buku.php';</script>";
    } else {
        echo "<script>alert('Gagal update!');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Buku</title>
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
        <h1>Edit Buku</h1>
        <div class="user-info">
            <span><?php echo $_SESSION['nama']; ?></span>
        </div>
    </div>

    <div class="form-container">
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Judul Buku</label>
                <input type="text" name="judul" value="<?php echo $buku['judul']; ?>" required>
            </div>
            <div class="form-group">
                <label>Penulis</label>
                <input type="text" name="penulis" value="<?php echo $buku['penulis']; ?>" required>
            </div>
            <div class="form-group">
                <label>Penerbit</label>
                <input type="text" name="penerbit" value="<?php echo $buku['penerbit']; ?>" required>
            </div>
            <div class="form-group">
                <label>Sinopsis</label>
                <textarea name="sinopsis" rows="5" required><?php echo $buku['sinopsis']; ?></textarea>
            </div>
            <div class="form-group">
                <label>Stok</label>
                <input type="number" name="stok" value="<?php echo $buku['stok']; ?>" min="0" required>
            </div>
            <div class="form-group">
                <label>Cover Buku</label>
                <?php if($buku['gambar'] && $buku['gambar'] != 'default.jpg'): ?>
                    <div style="margin-bottom: 10px;">
                        <img src="../uploads/<?php echo $buku['gambar']; ?>" width="80" height="100">
                    </div>
                <?php endif; ?>
                <input type="file" name="gambar" accept="image/*">
                <small>Kosongkan jika tidak ingin mengubah gambar</small>
            </div>
            <div class="form-group">
                <button type="submit" name="submit" class="btn btn-primary">Update</button>
                <a href="buku.php" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>

</body>
</html>