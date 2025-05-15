<?php
define('ACCESS_ALLOWED', true);
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/koneksi.php';

if ($_SESSION['role'] !== 'pengunjung') {
    echo "<script>alert('❌ Hanya pengunjung yang bisa mengulas'); window.location.href='ulasan.php';</script>";
    exit;
}

$rating = $_POST['rating'] ?? null;
$ulasan = $_POST['ulasan'] ?? null;
$id_user = $_SESSION['id_user'];

if (!$rating || !$ulasan) {
    echo "<script>alert('⚠️ Data tidak lengkap'); window.history.back();</script>";
    exit;
}

if (str_word_count($ulasan) > 100) {
    $_SESSION['flash_message'] = '❌ Alamat maksimal terdiri dari 100 kata';
    header("Location: ../../public/form_tambah_ulasan.php");
    exit;
}


if (basename(__FILE__) === 'aksi_tambah_ulasan.php') {
    $cek = $conn->prepare("SELECT * FROM rating_ulasan WHERE user_id_user = ?");
    $cek->bind_param("i", $id_user);
    $cek->execute();
    $cek_result = $cek->get_result();

    if ($cek_result->num_rows > 0) {
        echo "<script>alert('❌ Anda sudah pernah memberikan ulasan'); window.location.href='/bonbon/public/ulasan.php';</script>";
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO rating_ulasan (rating, ulasan, waktu_rating_ulasan, user_id_user) VALUES (?, ?, NOW(), ?)");
    $stmt->bind_param("isi", $rating, $ulasan, $id_user);

    if ($stmt->execute()) {
        echo "<script>alert('✅ Ulasan berhasil ditambahkan'); window.location.href='/bonbon/public/ulasan.php';</script>";
    } else {
        echo "<script>alert('❌ Gagal menambahkan ulasan'); window.history.back();</script>";
    }
    exit;
}
?>