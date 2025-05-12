<?php
session_start();

if (!isset($_SESSION['id_user'])) {
    echo "<script>alert('Silakan login terlebih dahulu'); window.location.href='login.php';</script>";
    exit;
}
?>