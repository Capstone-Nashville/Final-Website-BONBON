<?php
define('ACCESS_ALLOWED', true);
require_once __DIR__ . '/../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $jam = $_POST['jam'];
    $link = $_POST['link_gmaps'];
    $gambar = $_FILES['gambar']['name'];
    $tmp = $_FILES['gambar']['tmp_name'];

    $target_path = __DIR__ . '/../../public/images_lokasi/' . $gambar;
    move_uploaded_file($tmp, $target_path);

    $query = "INSERT INTO lokasi_outlet (nama_outlet, alamat, jam_operasional, link_gmaps, gambar_outlet, user_id_user)
                VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    // mysqli_stmt_bind_param($stmt, "sssssi", $nama, $alamat, $jam, $link, $gambar, $_SESSION['id_user']);
    mysqli_stmt_bind_param($stmt, "sssssi", $nama, $alamat, $jam, $link, $gambar, $id_user);
    $id_user = 11; //sementara hardcode
    mysqli_stmt_execute($stmt);

    header("Location: lokasi.php");
    exit;
}
?>

<form method="POST" enctype="multipart/form-data">
    <input type="text" name="nama" placeholder="Nama Outlet" required><br>
    <input type="text" name="alamat" placeholder="Alamat" required><br>
    <input type="time" name="jam" placeholder="Jam Operasional" required><br>
    <input type="url" name="link_gmaps" placeholder="Link Google Maps" required><br>
    <input type="file" name="gambar" accept="image/*" required><br>
    <button type="submit">Simpan</button>
</form>
