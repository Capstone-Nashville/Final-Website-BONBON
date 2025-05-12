<?php
define('ACCESS_ALLOWED', true);
require_once __DIR__ . '/../config/koneksi.php';

$id = $_GET['id_lokasi_outlet'];
$result = mysqli_query($koneksi, "SELECT * FROM lokasi_outlet WHERE id_lokasi_outlet = $id");
$data = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $jam = $_POST['jam'];

    if ($_FILES['gambar']['name']) {
        $gambar = $_FILES['gambar']['name'];
        move_uploaded_file($_FILES['gambar']['tmp_name'], "images/$gambar");
    } else {
        $gambar = $data['gambar_outlet'];
    }

    $query = "UPDATE lokasi_outlet SET nama_outlet=?, alamat=?, jam_operasional=?, gambar_outlet=? WHERE id_lokasi_outlet=?";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "ssssi", $nama, $alamat, $jam, $gambar, $id);
    mysqli_stmt_execute($stmt);

    header("Location: lokasi.php");
    exit;
}
?>

<form method="POST" enctype="multipart/form-data">
    <input type="text" name="nama" value="<?= $data['nama_outlet'] ?>" required><br>
    <input type="text" name="alamat" value="<?= $data['alamat'] ?>" required><br>
    <input type="text" name="jam" value="<?= $data['jam_operasional'] ?>" required><br>
    <img src="images/<?= $data['gambar_outlet'] ?>" width="100"><br>
    <input type="file" name="gambar" accept="image/*"><br>
    <button type="submit">Update</button>
</form>
