<?php
define('ACCESS_ALLOWED', true);
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_menu = $_POST['id_menu'] ?? null;

    if (!$id_menu) {
        $_SESSION['flash_message'] = '❌ ID menu tidak valid';
        header("Location: ../../public/edit_menu.php");
        exit;
    }

    $select = $conn->prepare("SELECT gambar_produk FROM menu WHERE id_menu = ?");
    $select->bind_param("i", $id_menu);
    $select->execute();
    $result = $select->get_result();
    
    if ($row = $result->fetch_assoc()) {
        $gambar = $row['gambar_produk'];
        $path = __DIR__ . '/../../public/images_menu/' . $gambar;

        if (file_exists($path)) {
            unlink($path);
        }
    }

    $query = "DELETE FROM menu WHERE id_menu = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_menu);

    if ($stmt->execute()) {
        $_SESSION['flash_message'] = '✅ Menu berhasil dihapus';
    } else {
        $_SESSION['flash_message'] = '❌ Gagal menghapus menu';
    }

    header("Location: ../../public/edit_menu.php");
    exit;
} else {
    http_response_code(403);
    echo "Forbidden";
}
?>