<?php
session_start();
define('ACCESS_ALLOWED', true);
require_once __DIR__ . '/../config/koneksi.php';

$nama_lengkap = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$konfirmasi = $_POST['confirmPassword'] ?? '';
$role = 'pengunjung';

if (empty($nama_lengkap) || empty($email) || empty($password) || empty($konfirmasi)) {
    $_SESSION['flash_message'] = '⚠️ Semua field harus diisi';
    header("Location: ../../public/register.php");
    exit;
}

if (strlen($password) < 8) {
    $_SESSION['flash_message'] = '⚠️ Password minimal harus 8 karakter';
    header("Location: ../../public/register.php");
    exit;
}

if (!preg_match('/^(?=.*[A-Z])(?=.*[0-9])(?=.*[\W_]).{8,}$/', $password)) {
    $_SESSION['flash_message'] = '⚠️ Password harus mengandung huruf besar, angka, dan simbol';
    header("Location: ../../public/register.php");
    exit;
}

if (preg_match('/\s/', $email) || preg_match('/\s/', $password)) {
    $_SESSION['flash_message'] = '❌ Tidak boleh ada spasi di nama, email, atau password';
    header("Location: ../../public/register.php");
    exit;
}

if ($password !== $konfirmasi) {
    $_SESSION['flash_message'] = '❌ Konfirmasi password tidak cocok';
    header("Location: ../../public/register.php");
    exit;
}

$cek_stmt = $conn->prepare("SELECT id_user FROM user WHERE email = ?");
$cek_stmt->bind_param("s", $email);
$cek_stmt->execute();
$cek_stmt->store_result();

if ($cek_stmt->num_rows > 0) {
    $_SESSION['flash_message'] = '⚠️ Email sudah terdaftar';
    header("Location: ../../public/register.php");
    exit;
}

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO user (nama_lengkap, email, password, role) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $nama_lengkap, $email, $hashed_password, $role);

if ($stmt->execute()) {
    $_SESSION['flash_message'] = 'Registrasi berhasil! Silakan login.';
    header("Location: ../../public/login.php");
    exit;
} else {
    $_SESSION['flash_message'] = 'Registrasi gagal. Silakan coba lagi.';
    header("Location: ../../public/register.php");
    exit;
}
?>