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
    $nama = trim($_POST['nama_menu'] ?? '');
    $kategori = $_POST['kategori'] ?? '';
    $harga = $_POST['harga'] ?? '';
    $gambar = $_FILES['gambar']['name'] ?? '';
    $tmp = $_FILES['gambar']['tmp_name'] ?? '';

    if (empty($nama) || str_word_count($nama) < 2) {
        $_SESSION['flash_message'] = "❌ Nama menu terlalu pendek.";
        header("Location: ../../public/form_tambah_menu.php");
        exit;
    }

    if (!is_numeric($harga) || $harga <= 0) {
        $_SESSION['flash_message'] = "❌ Harga tidak valid.";
        header("Location: ../../public/form_tambah_menu.php");
        exit;
    }

    if (empty($gambar)) {
        $_SESSION['flash_message'] = "❌ Gambar tidak dipilih.";
        header("Location: ../../public/form_tambah_menu.php");
        exit;
    }

    $allowed_ext = ['jpg', 'jpeg', 'png', 'svg'];
    $file_ext = strtolower(pathinfo($gambar, PATHINFO_EXTENSION));
    if (!in_array($file_ext, $allowed_ext)) {
        $_SESSION['flash_message'] = '❌ Format gambar tidak valid. Gunakan JPG, JPEG, PNG, atau SVG.';
        header("Location: ../../public/form_tambah_promo.php");
        exit;
    }

    if ($_FILES['gambar_promo']['size'] > 2 * 1024 * 1024) {
        $_SESSION['flash_message'] = '❌ Ukuran gambar terlalu besar. Maksimal 2MB.';
        header("Location: ../../public/form_tambah_promo.php");
        exit;
    }

    $upload_dir = __DIR__ . '/../../public/images_menu/';
    $unique_name = time() . '_' . basename($gambar);
    $upload_path = $upload_dir . $unique_name;

    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    if (move_uploaded_file($tmp, $upload_path)) {
        $query = "INSERT INTO menu (nama_produk, kategori, harga, gambar_produk, user_id_user)
                    VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssisi", $nama, $kategori, $harga, $unique_name, $user_id);

        if ($stmt->execute()) {
            $_SESSION['flash_message'] = "✅ Menu berhasil ditambahkan!";
        } else {
            $_SESSION['flash_message'] = "❌ Gagal menyimpan menu ke database.";
        }
    } else {
        $_SESSION['flash_message'] = "❌ Upload gambar gagal.";
    }
    header("Location: ../../public/edit_menu.php");
    exit;
}
?>

