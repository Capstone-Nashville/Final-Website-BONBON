<?php
define('ACCESS_ALLOWED', true);
session_start();

require_once __DIR__ . '/../config/koneksi.php';

$nama_lengkap = $_POST['registerName'] ?? '';
$email = $_POST['registerEmail'] ?? '';
$password = $_POST['registerPassword'] ?? '';
$konfirmasi = $_POST['registerConfirmPassword'] ?? '';
$role = 'pengunjung';

// Validasi sederhana
if (empty($nama_lengkap) || empty($email) || empty($password) || empty($konfirmasi)) {
    echo "<script>alert('Semua field harus diisi'); window.location.href='../../public/login.php';</script>";
    exit;
}

if ($password !== $konfirmasi) {
    echo "<script>alert('Konfirmasi password tidak cocok'); window.location.href='../../public/login.php';</script>";
    exit;
}

// Cek apakah email sudah terdaftar
$cek_query = "SELECT id_user FROM user WHERE email = ?";
$cek_stmt = $conn->prepare($cek_query);
$cek_stmt->bind_param("s", $email);
$cek_stmt->execute();
$cek_stmt->store_result();

if ($cek_stmt->num_rows > 0) {
    echo "<script>alert('Email sudah terdaftar'); window.location.href='../../public/login.php';</script>";
    exit;
}

// Hash password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Simpan user ke database
$query = "INSERT INTO user (nama_lengkap, email, password, role) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("ssss", $nama_lengkap, $email, $hashed_password, $role);

if ($stmt->execute()) {
    echo "<script>alert('Registrasi berhasil! Silakan login.'); window.location.href='../../public/login.php';</script>";
} else {
    echo "<script>alert('Registrasi gagal!'); window.location.href='../../public/login.php';</script>";
}
?>
