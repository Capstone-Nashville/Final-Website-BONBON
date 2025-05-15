<?php
define('ACCESS_ALLOWED', true);
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/koneksi.php';

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