<?php
session_start();
require '../functions.php';

if(!isset($_SESSION["login"]) || $_SESSION["login"] !== true){
    header("Location: ../login_user.php");
    exit;
}

// Ambil data user terbaru
$user = query("SELECT * FROM users WHERE id = " . $_SESSION['id'])[0];

// Upload foto profil
if(isset($_POST["upload_foto"]) && $_FILES['foto']['name']){
    $foto = $_FILES['foto']['name'];
    $tmp = $_FILES['foto']['tmp_name'];
    
    $ekstensi = pathinfo($foto, PATHINFO_EXTENSION);
    $ekstensi_valid = ['jpg', 'jpeg', 'png', 'gif'];
    
    if(in_array(strtolower($ekstensi), $ekstensi_valid)){
        $nama_foto = time() . '_' . $foto;
        $folder = '../uploads/profil/';
        
        if(!file_exists($folder)){
            mkdir($folder, 0777, true);
        }
        
        move_uploaded_file($tmp, $folder . $nama_foto);
        
        if($user['foto'] && file_exists($folder . $user['foto'])){
            unlink($folder . $user['foto']);
        }
        
        mysqli_query($conn, "UPDATE users SET foto = '$nama_foto' WHERE id = " . $_SESSION['id']);
        echo "<script>alert('Foto profil berhasil diubah!'); window.location.href='profil.php';</script>";
        exit;
    }
}

// Update profil
if(isset($_POST["update_profil"])){
    $nama = htmlspecialchars($_POST["nama"]);
    $email = htmlspecialchars($_POST["email"]);
    $nohp = htmlspecialchars($_POST["nohp"]);
    
    // Update ke database
    mysqli_query($conn, "UPDATE users SET nama = '$nama', email = '$email', nohp = '$nohp' WHERE id = " . $_SESSION['id']);
    
    // Update session
    $_SESSION['nama'] = $nama;
    $_SESSION['email'] = $email;
    
    echo "<script>
        alert('Profil berhasil diupdate!');
        window.location.href='profil.php';
    </script>";
    exit;
}

// Ubah password
if(isset($_POST["ubah_password"])){
    $password_lama = $_POST["password_lama"];
    $password_baru = $_POST["password_baru"];
    $konfirmasi = $_POST["konfirmasi"];
    
    if(!password_verify($password_lama, $user['password'])){
        $error = "Password lama salah!";
    } elseif($password_baru != $konfirmasi){
        $error = "Password baru tidak cocok!";
    } else {
        $password_hash = password_hash($password_baru, PASSWORD_DEFAULT);
        mysqli_query($conn, "UPDATE users SET password = '$password_hash' WHERE id = " . $_SESSION['id']);
        echo "<script>alert('Password berhasil diubah! Silahkan login ulang.'); window.location.href='../logout.php';</script>";
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Profil Saya</title>
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
        <li><a href="profil.php" class="active">👤 Profil</a></li>
    </ul>
    <div class="logout">
        <ul class="nav-menu">
            <li><a href="../logout.php">🚪 Logout</a></li>
        </ul>
    </div>
</div>

<div class="main-content">
    <div class="header">
        <h1>Profil Saya</h1>
        <div class="user-info">
            <span><?php echo $_SESSION['nama']; ?></span>
        </div>
    </div>

    <?php if(isset($error)): ?>
        <div style="background: #ffebee; color: #e74c3c; padding: 10px; border-radius: 8px; margin-bottom: 20px;"><?php echo $error; ?></div>
    <?php endif; ?>

    <div class="profile-container">
        <div class="profile-header">
            <div class="profile-avatar">
                <?php if($user['foto'] && file_exists('../uploads/profil/' . $user['foto'])): ?>
                    <img src="../uploads/profil/<?php echo $user['foto']; ?>" style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover;">
                <?php else: ?>
                    <span style="font-size: 48px;">📷</span>
                <?php endif; ?>
            </div>
            <form method="POST" enctype="multipart/form-data" style="margin-top: 15px;">
                <input type="file" name="foto" accept="image/*" style="display: none;" id="upload_foto" onchange="this.form.submit()">
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('upload_foto').click();">Ganti Foto</button>
                <button type="submit" name="upload_foto" class="btn btn-primary" style="display: none;">Upload</button>
            </form>
        </div>
        
        <form method="POST">
            <div class="profile-info">
                <div class="info-row">
                    <div class="info-label">Nama Lengkap</div>
                    <div class="info-value">
                        <input type="text" name="nama" value="<?php echo htmlspecialchars($user['nama']); ?>" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 6px;">
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">Username</div>
                    <div class="info-value">: <?php echo htmlspecialchars($user['username']); ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Email</div>
                    <div class="info-value">
                        <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 6px;">
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">NIS / NISN</div>
                    <div class="info-value">: <?php echo htmlspecialchars($user['nisn']); ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label">NIK</div>
                    <div class="info-value">: <?php echo htmlspecialchars($user['nik']); ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label">No HP</div>
                    <div class="info-value">
                        <input type="text" name="nohp" value="<?php echo htmlspecialchars($user['nohp']); ?>" style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 6px;" placeholder="Contoh: 08123456789">
                        <small style="color: #888;">Masukkan nomor HP tanpa tanda hubung</small>
                    </div>
                </div>
            </div>
            <div class="profile-actions">
                <button type="submit" name="update_profil" class="btn btn-primary">✏️ Edit Profil</button>
                <button type="button" class="btn btn-secondary" onclick="showPasswordForm()">🔒 Ubah Password</button>
            </div>
        </form>
    </div>
</div>

<script>
function showPasswordForm() {
    var html = `
    <div style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 30px; border-radius: 12px; box-shadow: 0 0 20px rgba(0,0,0,0.2); z-index: 1000; width: 350px;">
        <h3 style="margin-bottom: 20px;">Ubah Password</h3>
        <form method="POST">
            <div class="form-group">
                <label>Password Lama</label>
                <input type="password" name="password_lama" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;" required>
            </div>
            <div class="form-group">
                <label>Password Baru</label>
                <input type="password" name="password_baru" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;" required>
            </div>
            <div class="form-group">
                <label>Konfirmasi Password</label>
                <input type="password" name="konfirmasi" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;" required>
            </div>
            <div style="display: flex; gap: 10px; margin-top: 20px;">
                <button type="submit" name="ubah_password" class="btn btn-primary" style="flex: 1;">Simpan</button>
                <button type="button" class="btn btn-secondary" style="flex: 1;" onclick="this.closest('div').remove(); this.closest('div').previousSibling?.remove(); this.remove();">Batal</button>
            </div>
        </form>
    </div>
    <div style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 999;" onclick="this.nextSibling?.remove(); this.remove();"></div>
    `;
    document.body.insertAdjacentHTML('beforeend', html);
}
</script>

</body>
</html>