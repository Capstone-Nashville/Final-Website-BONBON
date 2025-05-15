<?php
session_start();
define('ACCESS_ALLOWED', true);
require_once __DIR__ . '/../config/koneksi.php';

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    $_SESSION['flash_message'] = 'Email dan password harus diisi';
    header("Location: ../../public/login.php");
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

        $_SESSION['flash_message'] = 'Login berhasil sebagai ' . ucfirst($user['role']);

        if ($user['role'] === 'admin') {
            header("Location: ../../public/dashboard.php");
        } else {
            header("Location: ../../public/beranda.php");
        }
        exit;
    } else {
        $_SESSION['flash_message'] = '❌ Password salah';
        header("Location: ../../public/login.php");
        exit;
    }
} else {
    $_SESSION['flash_message'] = '❌ Email tidak ditemukan';
    header("Location: ../../public/login.php");
    exit;
}
?>
