<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!defined('ACCESS_ALLOWED')) {
    echo "<script>alert('❌ Akses langsung tidak diizinkan!'); window.location.href='/bonbon/public/beranda.php';</script>";
    exit;
}

if (!isset($_SESSION['id_user'])) {
    echo "<script>alert('⚠️ Silakan login terlebih dahulu!'); window.location.href='/bonbon/public/login.php';</script>";
    exit;
}
?>
