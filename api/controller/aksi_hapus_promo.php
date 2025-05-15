<?php
define('ACCESS_ALLOWED', true);
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_promo = $_POST['id_promo'] ?? null;

    if (!$id_promo) {
        $_SESSION['flash_message'] = '❌ ID promosi tidak valid';
        header("Location: ../../public/edit_promo.php");
        exit;
    }

    $query = "SELECT gambar_promo FROM promo WHERE id_promo = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_promo);
    $stmt->execute();
    $result = $stmt->get_result();
    $promo = $result->fetch_assoc();

    if ($promo) {
        $filepath = __DIR__ . '/../../public/images_promo/' . $promo['gambar_promo'];
        if (file_exists($filepath)) {
            unlink($filepath); // Hapus file dari server
        }

        $delete_query = "DELETE FROM promo WHERE id_promo = ?";
        $delete_stmt = $conn->prepare($delete_query);
        $delete_stmt->bind_param("i", $id_promo);

        if ($delete_stmt->execute()) {
            $_SESSION['flash_message'] = '✅ Promosi berhasil dihapus.';
        } else {
            $_SESSION['flash_message'] = '❌ Gagal menghapus promosi dari database.';
        }
    } else {
        $_SESSION['flash_message'] = '❌ Promosi tidak ditemukan.';
    }

    header("Location: ../../public/edit_promo.php");
    exit;
} else {
    http_response_code(403);
    echo "Forbidden";
}
?>