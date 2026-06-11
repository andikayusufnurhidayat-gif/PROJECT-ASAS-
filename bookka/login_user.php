<?php
session_start();
session_regenerate_id(true);
require 'functions.php';


if(isset($_POST["login"])){
    $username = $_POST["username"];
    $password = $_POST["password"];

    $user = cekLogin($username, $password);

    if($user){
        // Hapus session lama
        session_unset();
        
        // Set session baru
        $_SESSION["login"] = true;
        $_SESSION["id"] = $user["id"];
        $_SESSION["nama"] = $user["nama"];
        $_SESSION["username"] = $user["username"];
        $_SESSION["email"] = $user["email"];
        $_SESSION["role"] = $user["role"];
        $_SESSION["kelas"] = $user["kelas"] ?? '-';
        $_SESSION["nisn"] = $user["nisn"];
        $_SESSION["nik"] = $user["nik"];
        $_SESSION["nohp"] = $user["nohp"];

       if($user["role"] === "admin"){
    header("Location: ./admin/index.php");
    exit;
}

if($user["role"] === "user"){
    header("Location: ./user/index.php");
    exit;
}

    } else {
        $error = "Username atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login User</title>
    <link rel="stylesheet" href="tampilan.css">
</head>
<body class="auth-container">
    <div class="auth-card">
        <h1>BOOKKA</h1>
        <div class="subtitle">Login Akun</div>
        
        <?php if(isset($error)): ?>
            <p style="color: red; text-align: center;">Username atau password salah!</p>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" name="login" class="btn btn-primary">Login</button>
        </form>
        
        <div class="auth-footer">
            <p>Belum punya akun? <a href="register.php">Daftar disini</a></p>
            <p><a href="login_admin.php">Login sebagai Admin</a></p>
        </div>
    </div>
</body>
</html>