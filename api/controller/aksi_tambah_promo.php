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
    $link = $_POST['link_postingan'] ?? '';
    $gambar = $_FILES['gambar_promo']['name'] ?? '';
    $tmp = $_FILES['gambar_promo']['tmp_name'] ?? '';

    if (empty($gambar)) {
        $_SESSION['flash_message'] = '❌ Harap unggah gambar promosi.';
        header("Location: ../../public/form_tambah_promo.php");
        exit;
    }

    $allowed_ext = ['jpg', 'jpeg', 'png', 'svg'];
    $file_ext = strtolower(pathinfo($gambar, PATHINFO_EXTENSION));
    if (!in_array($file_ext, $allowed_ext)) {
        $_SESSION['flash_message'] = '❌ Format gambar tidak valid. Gunakan JPG, JPEG, PNG, atau SVG.';
        header("Location: ../../public/form_tambah_promo.php");
        exit;
    }

    if ($_FILES['gambar_promo']['size'] > 2 * 1024 * 1024) { // 2MB
        $_SESSION['flash_message'] = '❌ Ukuran gambar terlalu besar. Maksimal 2MB.';
        header("Location: ../../public/form_tambah_promo.php");
        exit;
    }

    $upload_dir = __DIR__ . '/../../public/images_promo/';
    $unique_name = time() . '_' . basename($gambar);
    $upload_path = $upload_dir . $unique_name;

    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    if (!move_uploaded_file($tmp, $upload_path)) {
        $_SESSION['flash_message'] = '❌ Gagal mengunggah gambar.';
        header("Location: ../../public/form_tambah_promo.php");
        exit;
    }

    $query = "INSERT INTO promo (link_postingan, gambar_promo, user_id_user) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssi", $link, $unique_name, $user_id);

    if ($stmt->execute()) {
        $_SESSION['flash_message'] = '✅ Promosi berhasil ditambahkan!';
    } else {
        $_SESSION['flash_message'] = '❌ Gagal menyimpan data ke database.';
    }

    header("Location: ../../public/edit_promo.php");
    exit;
}
?>
