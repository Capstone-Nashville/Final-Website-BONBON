<?php
define('ACCESS_ALLOWED', true);
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_lokasi_outlet = $_POST['id_lokasi_outlet'] ?? null;
    $nama = $_POST['nama_cabang'] ?? '';
    $alamat = trim($_POST['alamat'] ?? '');
    $jam_buka = $_POST['jam_buka'] ?? '';
    $jam_tutup = $_POST['jam_tutup'] ?? '';
    $link = $_POST['link_gmaps'] ?? '';
    $gambar_lama = $_POST['gambar_lama'] ?? '';

    if (empty($nama) || str_word_count($nama) < 2) {
        $_SESSION['flash_message'] = '❌ Nama cabang terlalu pendek';
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

    $jam = $jam_buka . ' - ' . $jam_tutup;

    $query = "SELECT * FROM lokasi_outlet WHERE id_lokasi_outlet = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_lokasi_outlet);
    $stmt->execute();
    $result = $stmt->get_result();
    $data_lama = $result->fetch_assoc();

    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
        $gambar = $_FILES['gambar']['name'];
        $tmp = $_FILES['gambar']['tmp_name'];
        $upload_path = __DIR__ . '/../../public/images_lokasi/' . basename($gambar);
        move_uploaded_file($tmp, $upload_path);
    } else {
        $gambar = $gambar_lama;
    }

    $perubahan = ($nama !== $data_lama['nama_outlet']) ||
                    ($alamat !== $data_lama['alamat']) ||
                    ($jam !== $data_lama['jam_operasional']) ||
                    ($link !== $data_lama['link_gmaps']) ||
                    ($gambar !== $data_lama['gambar_outlet']);

    if (!$perubahan) {
        $_SESSION['flash_message'] = 'ℹ️ Tidak ada perubahan yang dilakukan';
        header("Location: ../../public/edit_lokasi.php");
        exit;
    }

    $update_query = "UPDATE lokasi_outlet SET nama_outlet = ?, alamat = ?, jam_operasional = ?, link_gmaps = ?, gambar_outlet = ? WHERE id_lokasi_outlet = ?";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("sssssi", $nama, $alamat, $jam, $link, $gambar, $id_lokasi_outlet);

    if ($update_stmt->execute()) {
        $_SESSION['flash_message'] = '✅ Lokasi berhasil diperbarui';
    } else {
        $_SESSION['flash_message'] = '❌ Gagal memperbarui lokasi';
    }
    header("Location: ../../public/edit_lokasi.php");
    exit;
}
?>