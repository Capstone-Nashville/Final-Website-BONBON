<?php
define('ACCESS_ALLOWED', true);

session_start();
require_once __DIR__ . '/../config/koneksi.php';

$email = $_POST['loginEmail'] ?? '';
$password = $_POST['loginPassword'] ?? '';

if (empty($email) || empty($password)) {
    echo "<script>alert('Email dan password harus diisi'); window.location.href='../../public/login.php';</script>";
    exit;
}

$query = "SELECT * FROM user WHERE email = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($user = mysqli_fetch_assoc($result)) {
    if (password_verify($password, $user['password'])) {
        $_SESSION['id_user'] = $user['id_user'];
        $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
        $_SESSION['role'] = $user['role'];

        if ($user['role'] === 'admin') {
            echo "<script>alert('Login berhasil sebagai Admin'); window.location.href='../../public/dashboard_admin.php';</script>";
        } else {
            echo "<script>alert('Login berhasil sebagai Pengunjung'); window.location.href='../../public/dashboard_pengunjung.php';</script>";
        }
    } else {
        echo "<script>alert('Password salah'); window.location.href='../../public/login.php';</script>";
    }
} else {
    echo "<script>alert('Email tidak ditemukan'); window.location.href='../../public/login.php';</script>";
}
?>
