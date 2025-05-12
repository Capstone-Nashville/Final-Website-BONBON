<?php
define('ACCESS_ALLOWED', true);
require_once __DIR__ . '/../config/koneksi.php';

$id = $_GET['id'] ?? null;
if ($id) {
    $get = mysqli_fetch_assoc(mysqli_query($conn, "SELECT gambar_produk FROM menu WHERE id_menu = $id"));
    if ($get && file_exists("../../public/images_menu/" . $get['gambar_produk'])) {
        unlink("../../public/images_menu/" . $get['gambar_produk']);
    }

    mysqli_query($conn, "DELETE FROM menu WHERE id_menu = $id");
}
header("Location: ../../public/menu.php");
exit;