<?php
// Mulai sesi jika belum dimulai
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cegah akses langsung ke file ini
if (!defined('ACCESS_ALLOWED')) {
    echo "<script>alert('❌ Akses langsung tidak diizinkan!'); window.location.href='/bonbon/public/beranda.php';</script>";
    exit;
}

// Cek apakah user sudah login
if (!isset($_SESSION['id_user'])) {
    echo "<script>alert('⚠️ Silakan login terlebih dahulu!'); window.location.href='/bonbon/public/login.php';</script>";
    exit;
}
?>
