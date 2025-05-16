<?php
define('ACCESS_ALLOWED', true);
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../config/csrf.php';

if (!verify_csrf_token($_POST['csrf_token_form'] ?? '')) {
    $_SESSION['flash_message'] = '⛔ Permintaan tidak valid (token).';
    header("Location: ../../public/login.php");
    exit;
}

if ($_SESSION['role'] !== 'pengunjung') {
    echo "<script>alert('❌ Hanya pengunjung yang bisa mengulas'); window.location.href='ulasan.php';</script>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_ulasan = $_POST['id_ulasan'] ?? null;
    $rating = $_POST['rating'] ?? null;
    $ulasan = $_POST['ulasan'] ?? null;
    $id_user = $_SESSION['id_user'];

    if (!$id_ulasan || !$rating || !$ulasan) {
        echo "<script>alert('⚠️ Data tidak lengkap'); window.history.back();</script>";
        exit;
    }

    if (str_word_count($ulasan) > 100) {
        $_SESSION['flash_message'] = '❌ Ulasan maksimal terdiri dari 100 kata';
        header("Location: ../../public/form_edit_ulasan.php?id=$id_ulasan");
        exit;
    } 

    $cek = $conn->prepare("SELECT * FROM rating_ulasan WHERE id_rating_ulasan = ? AND user_id_user = ?");
    $cek->bind_param("ii", $id_ulasan, $id_user);
    $cek->execute();
    $result = $cek->get_result();

    if ($result->num_rows === 0) {
        echo "<script>alert('❌ Anda tidak memiliki hak untuk mengedit ulasan ini'); window.location.href='/bonbon/public/ulasan.php';</script>";
        exit;
    }

    $stmt = $conn->prepare("UPDATE rating_ulasan SET rating = ?, ulasan = ?, waktu_rating_ulasan = NOW() WHERE id_rating_ulasan = ? AND user_id_user = ?");
    $stmt->bind_param("isii", $rating, $ulasan, $id_ulasan, $id_user);

    if ($stmt->execute()) {
        echo "<script>alert('✅ Ulasan berhasil diperbarui'); window.location.href='/bonbon/public/ulasan.php';</script>";
    } else {
        echo "<script>alert('❌ Gagal memperbarui ulasan'); window.history.back();</script>";
    }
    exit;
}
?>