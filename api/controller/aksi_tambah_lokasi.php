<?php
define('ACCESS_ALLOWED', true);
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'admin') {
        $_SESSION['flash_message'] = "❌ Akses ditolak.";
        header("Location: ../../public/login.php");
        exit;
    }

    $user_id = $_SESSION['id_user'];
    $nama = $_POST['nama_cabang'] ?? '';
    $alamat = trim($_POST['alamat'] ?? '');
    $jam_buka = $_POST['jam_buka'] ?? '';
    $jam_tutup = $_POST['jam_tutup'] ?? '';
    $link = $_POST['link_gmaps'] ?? '';
    $gambar = $_FILES['gambar']['name'] ?? '';
    $tmp = $_FILES['gambar']['tmp_name'] ?? '';

    if (empty($nama) || str_word_count($nama) < 2) {
        $_SESSION['flash_message'] = "❌ Nama cabang terlalu pendek.";
        header("Location: ../../public/form_tambah_lokasi.php");
        exit;
    }

    if (str_word_count($alamat) < 3) {
        $_SESSION['flash_message'] = '❌ Alamat minimal harus terdiri dari 3 kata';
        header("Location: ../../public/form_tambah_lokasi.php");
        exit;
    }

    $allowed_ext = ['jpg', 'jpeg', 'png', 'svg'];
    $file_ext = strtolower(pathinfo($gambar, PATHINFO_EXTENSION));
    if (!in_array($file_ext, $allowed_ext)) {
        $_SESSION['flash_message'] = '❌ Format gambar tidak valid. Gunakan JPG, JPEG, PNG, atau SVG.';
        header("Location: ../../public/form_tambah_lokasi.php");
        exit;
    }

    if ($_FILES['gambar']['size'] > 2 * 1024 * 1024) {
        $_SESSION['flash_message'] = '❌ Ukuran gambar terlalu besar. Maksimal 2MB.';
        header("Location: ../../public/form_tambah_lokasi.php");
        exit;
    }

    $upload_dir = __DIR__ . '/../../public/images_lokasi/';
    $unique_name = time() . '_' . basename($gambar);
    $upload_path = $upload_dir . $unique_name;

    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    if (!move_uploaded_file($tmp, $upload_path)) {
        $_SESSION['flash_message'] = '❌ Gagal mengunggah gambar';
        header("Location: ../../public/form_tambah_lokasi.php");
        exit;
    }

    $jam_operasional = $jam_buka . ' - ' . $jam_tutup;

    $query = "INSERT INTO lokasi_outlet (nama_outlet, alamat, jam_operasional, link_gmaps, gambar_outlet, user_id_user)
                VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssssi", $nama, $alamat, $jam_operasional, $link, $unique_name, $user_id);

    if ($stmt->execute()) {
        $_SESSION['flash_message'] = "✅ Lokasi '$nama' berhasil ditambahkan";
    } else {
        $_SESSION['flash_message'] = '❌ Terjadi kesalahan saat menyimpan ke database';
    }
    header("Location: ../../public/edit_lokasi.php");
    exit;
}   
?>