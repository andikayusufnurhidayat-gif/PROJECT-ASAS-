<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = mysqli_connect("localhost", "root", "", "bookka");

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

function query($query) {
    global $conn;
    $result = mysqli_query($conn, $query);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}

/* =====================
   REGISTRASI FUNCTION
===================== */
function register($data){
    global $conn;

    $nik = htmlspecialchars($data["nik"]);
    $nisn = htmlspecialchars($data["nisn"]);
    $nama = htmlspecialchars($data["nama"]);
    $username = strtolower(stripslashes($data["username"]));
    $email = htmlspecialchars($data["email"]);
    $nohp = htmlspecialchars($data["nohp"]);
    $password = mysqli_real_escape_string($conn, $data["password"]);
    $konfirmasi = mysqli_real_escape_string($conn, $data["konfirmasi"]);

    if($password != $konfirmasi){
        return false;
    }

    // Cek username
    $cekUsername = mysqli_query($conn, "SELECT username FROM users WHERE username='$username'");
    if(mysqli_fetch_assoc($cekUsername)){
        return false;
    }

    // Cek email
    $cekEmail = mysqli_query($conn, "SELECT email FROM users WHERE email='$email'");
    if(mysqli_fetch_assoc($cekEmail)){
        return false;
    }

    // Cek NIK
    $cekNik = mysqli_query($conn, "SELECT nik FROM users WHERE nik='$nik'");
    if(mysqli_fetch_assoc($cekNik)){
        return false;
    }

    // Cek NISN
    $cekNisn = mysqli_query($conn, "SELECT nisn FROM users WHERE nisn='$nisn'");
    if(mysqli_fetch_assoc($cekNisn)){
        return false;
    }

    $password = password_hash($password, PASSWORD_DEFAULT);

    mysqli_query($conn,"
    INSERT INTO users (nik, nisn, nama, username, email, nohp, password, role)
    VALUES ('$nik', '$nisn', '$nama', '$username', '$email', '$nohp', '$password', 'user')
    ");

    return mysqli_affected_rows($conn);
}

/* =====================
   LOGIN FUNCTION
===================== */

function cekLogin($username, $password) {
    global $conn;
    
    $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE username = ?");
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if(mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        
        if(password_verify($password, $row["password"])) {
            return $row;
        }
    }
    
    return false;
}

/* =====================
   BUKU
===================== */

function tambahBuku($data) {
    global $conn;
    
    $judul = htmlspecialchars($data["judul"]);
    $penulis = htmlspecialchars($data["penulis"]);
    $penerbit = htmlspecialchars($data["penerbit"]);
    $sinopsis = htmlspecialchars($data["sinopsis"]);
    
    $query = "INSERT INTO buku VALUES ('', '$judul', '$penulis', '$penerbit', '$sinopsis')";
    mysqli_query($conn, $query);
    
    return mysqli_affected_rows($conn);
}

function ubahBuku($data) {
    global $conn;
    
    $id = $data["id"];
    $judul = htmlspecialchars($data["judul"]);
    $penulis = htmlspecialchars($data["penulis"]);
    $penerbit = htmlspecialchars($data["penerbit"]);
    $sinopsis = htmlspecialchars($data["sinopsis"]);
    
    $query = "UPDATE buku SET 
              judul = '$judul',
              penulis = '$penulis', 
              penerbit = '$penerbit',
              sinopsis = '$sinopsis'
              WHERE id = $id";
    
    mysqli_query($conn, $query);
    
    return mysqli_affected_rows($conn);
}

function hapusBuku($id) {
    global $conn;
    
    mysqli_query($conn, "DELETE FROM buku WHERE id = $id");
    
    return mysqli_affected_rows($conn);
}

/* =====================
   PINJAMAN
===================== */

function tambahPinjaman($data) {
    global $conn;
    
    $username = htmlspecialchars($data["username"]);
    $kelas = htmlspecialchars($data["kelas"]);
    $gmail = htmlspecialchars($data["gmail"]);
    $judul_buku = htmlspecialchars($data["judul_buku"]);
    $tanggal_kembali = htmlspecialchars($data["tanggal_kembali"]);
    $tanggal_penyewaan = htmlspecialchars($data["tanggal_penyewaan"]);
    
    $query = "INSERT INTO pinjaman VALUES ('', '$username', '$kelas', '$gmail', '$judul_buku', '$tanggal_kembali', '$tanggal_penyewaan', '0', 'menunggu')";
    mysqli_query($conn, $query);
    
    return mysqli_affected_rows($conn);
}

function ubahStatusPinjaman($id, $status) {
    global $conn;
    
    mysqli_query($conn, "UPDATE pinjaman SET status='$status' WHERE id='$id'");
    
    return mysqli_affected_rows($conn);
}

function updateDenda($id, $denda) {
    global $conn;
    
    mysqli_query($conn, "UPDATE pinjaman SET denda='$denda' WHERE id='$id'");
    
    return mysqli_affected_rows($conn);
}

function hapusPinjaman($id) {
    global $conn;
    
    mysqli_query($conn, "DELETE FROM pinjaman WHERE id='$id'");
    
    return mysqli_affected_rows($conn);
}

?>