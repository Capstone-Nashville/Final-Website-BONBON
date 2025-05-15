<?php
session_start();
define('ACCESS_ALLOWED', true);
require_once __DIR__ . '/../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_menu = $_POST['id_menu'] ?? null;
    $nama = $_POST['nama_menu'] ?? '';
    $kategori = $_POST['kategori'] ?? '';
    $harga = $_POST['harga'] ?? '';
    $gambar_lama = $_POST['gambar_lama'] ?? '';

    if (empty($nama) || strlen($nama) < 2) {
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

    $query = "SELECT * FROM menu WHERE id_menu = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_menu);
    $stmt->execute();
    $result = $stmt->get_result();
    $data_lama = $result->fetch_assoc();

    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
        $gambar = $_FILES['gambar']['name'];
        $tmp = $_FILES['gambar']['tmp_name'];
        $upload_path = __DIR__ . '/../../public/images_menu/' . basename($gambar);
        move_uploaded_file($tmp, $upload_path);
    } else {
        $gambar = $gambar_lama;
    }

    $perubahan = ($nama !== $data_lama['nama_produk']) ||
                    ($kategori !== $data_lama['kategori']) ||
                    ($harga != $data_lama['harga']) ||
                    ($gambar !== $data_lama['gambar_produk']);

    if (!$perubahan) {
        $_SESSION['flash_message'] = 'ℹ️ Tidak ada perubahan yang dilakukan';
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