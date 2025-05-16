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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_menu = $_POST['id_menu'] ?? null;
    $nama = $_POST['nama_menu'] ?? '';
    $kategori = $_POST['kategori'] ?? '';
    $harga = $_POST['harga'] ?? '';
    $gambar_lama = $_POST['gambar_lama'] ?? '';
    $gambar = $gambar_lama;

    if (!$id_menu) {
        $_SESSION['flash_message'] = "❌ ID menu tidak ditemukan.";
        header("Location: ../../public/edit_menu.php");
        exit;
    }

    if (empty($nama) || strlen($nama) < 2) {
        $_SESSION['flash_message'] = "❌ Nama menu terlalu pendek.";
        header("Location: ../../public/form_edit_menu.php?id_menu=$id_menu");
        exit;
    }

    if (!is_numeric($harga) || $harga <= 0) {
        $_SESSION['flash_message'] = "❌ Harga tidak valid.";
        header("Location: ../../public/form_edit_menu.php?id_menu=$id_menu");
        exit;
    }

    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
        $allowed_ext = ['jpg', 'jpeg', 'png', 'svg'];
        $file_ext = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));
        $file_size = $_FILES['gambar']['size'];

        if (!in_array($file_ext, $allowed_ext)) {
            $_SESSION['flash_message'] = '❌ Format gambar tidak valid. Gunakan JPG, JPEG, PNG, atau SVG.';
            header("Location: ../../public/form_edit_menu.php?id_menu=$id_menu");
            exit;
        }

        if ($file_size > 2 * 1024 * 1024) {
            $_SESSION['flash_message'] = '❌ Ukuran gambar terlalu besar. Maksimal 2MB.';
            header("Location: ../../public/form_edit_menu.php?id_menu=$id_menu");
            exit;
        }

        $gambar = uniqid('menu_') . '.' . $file_ext;
        $upload_path = __DIR__ . '/../../public/images_menu/' . $gambar;
        move_uploaded_file($_FILES['gambar']['tmp_name'], $upload_path);
    }

    $query = "SELECT * FROM menu WHERE id_menu = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_menu);
    $stmt->execute();
    $result = $stmt->get_result();
    $data_lama = $result->fetch_assoc();

    $perubahan = (
        $nama !== $data_lama['nama_produk'] ||
        $kategori !== $data_lama['kategori'] ||
        $harga != $data_lama['harga'] ||
        $gambar !== $data_lama['gambar_produk']
    );

    if (!$perubahan) {
        $_SESSION['flash_message'] = 'ℹ️ Tidak ada perubahan yang dilakukan.';
        header("Location: ../../public/edit_menu.php");
        exit;
    }

    $update_query = "UPDATE menu SET nama_produk = ?, kategori = ?, harga = ?, gambar_produk = ? WHERE id_menu = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("ssisi", $nama, $kategori, $harga, $gambar, $id_menu);

    if ($update_stmt->execute()) {
        $_SESSION['flash_message'] = '✅ Menu berhasil diperbarui';
    } else {
        $_SESSION['flash_message'] = '❌ Gagal memperbarui menu';
    }

    header("Location: ../../public/edit_menu.php");
    exit;
}
?>
