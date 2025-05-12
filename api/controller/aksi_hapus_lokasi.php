<?php
require_once 'auth.php';
require_once __DIR__ . '/../api/config/koneksi.php';

$id = $_GET['id'];
mysqli_query($koneksi, "DELETE FROM lokasi_outlet WHERE id_lokasi_outlet = $id");
header("Location: lokasi.php");
exit;
