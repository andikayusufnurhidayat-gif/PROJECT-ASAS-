<?php
session_start();
require 'functions.php';

if(isset($_POST["register"])){

    if(register($_POST) > 0){

        echo "
        <script>
            alert('Register berhasil');
            document.location.href='login_user.php';
        </script>
        ";

    } else {

        echo "
        <script>
            alert('Register gagal');
        </script>
        ";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register - BOOKKA</title>
    <link rel="stylesheet" href="tampilan.css">
</head>
<body class="auth-container">
    <div class="auth-card">
        <h1>BOOKKA</h1>
        <div class="subtitle">Buat Akun Baru</div>
        
        <form method="POST">
            <div class="form-group">
                <label>NIK</label>
                <input type="text" name="nik" required>
            </div>
            
            <div class="form-group">
                <label>NISN</label>
                <input type="text" name="nisn" required>
            </div>
            
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama" required>
            </div>
            
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" required>
            </div>
            
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label>No HP</label>
                <input type="text" name="nohp" placeholder="Contoh: 08123456789" required>
            </div>
            
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            
            <div class="form-group">
                <label>Konfirmasi Password</label>
                <input type="password" name="konfirmasi" required>
            </div>
            
            <button type="submit" name="register" class="btn btn-primary">Register</button>
        </form>
        
        <div class="auth-footer">
            <p>Sudah punya akun? <a href="login_user.php">Login</a></p>
        </div>
    </div>
</body>
</html>