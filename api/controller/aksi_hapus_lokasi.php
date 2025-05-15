<?php
define('ACCESS_ALLOWED', true);
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_lokasi_outlet = $_POST['id_lokasi_outlet'] ?? null;

    if (!$id_lokasi_outlet) {
        $_SESSION['flash_message'] = '❌ ID lokasi tidak valid';
        header("Location: ../../public/edit_lokasi.php");
        exit;
    }

    $select = $conn->prepare("SELECT gambar_outlet FROM lokasi_outlet WHERE id_lokasi_outlet = ?");
    $select->bind_param("i", $id_lokasi_outlet);
    $select->execute();
    $result = $select->get_result();

    if ($row = $result->fetch_assoc()) {
        $gambar = $row['gambar_outlet'];
        $path = __DIR__ . '/../../public/images_lokasi/' . $gambar;

        if (file_exists($path)) {
            unlink($path);
        }
    }

    $query = "DELETE FROM lokasi_outlet WHERE id_lokasi_outlet = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_lokasi_outlet);

    if ($stmt->execute()) {
        $_SESSION['flash_message'] = '✅ Lokasi berhasil dihapus';
    } else {
        $_SESSION['flash_message'] = '❌ Gagal menghapus lokasi';
    }

    header("Location: ../../public/edit_lokasi.php");
    exit;
} else {
    http_response_code(403);
    echo "Forbidden";
}
?>
