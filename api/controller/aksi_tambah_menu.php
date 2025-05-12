<?php
define('ACCESS_ALLOWED', true);
require_once __DIR__ . '/../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama_produk'];
    $kategori = $_POST['kategori'];
    $harga = $_POST['harga'];
    $gambar = $_FILES['gambar']['name'];
    $tmp = $_FILES['gambar']['tmp_name'];

    // Sementara pakai user_id_user tetap (admin)
    $user_id = 11;

    $target_path = __DIR__ . '/../../public/images_menu/' . $gambar;
    move_uploaded_file($tmp, $target_path);

    $query = "INSERT INTO menu (nama_produk, kategori, harga, gambar_produk, user_id_user)
                VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ssisi", $nama, $kategori, $harga, $gambar, $user_id);
    mysqli_stmt_execute($stmt);

    header("Location: ../../public/menu.php");
    exit;
}
?>

<form method="POST" enctype="multipart/form-data">
    <input type="text" name="nama_produk" placeholder="Nama Produk" required><br>
    <input type="text" name="kategori" placeholder="Alamat" required><br>
    <input type="number" name="harga" placeholder="20000" required><br>
    <input type="file" name="gambar" accept="image/*" required><br>
    <button type="submit">Simpan</button>
</form>

