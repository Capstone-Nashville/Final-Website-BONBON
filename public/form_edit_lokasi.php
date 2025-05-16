<?php
define('ACCESS_ALLOWED', true);
require_once __DIR__ . '/../api/config/auth.php';
require_once __DIR__ . '/../api/config/koneksi.php';
require_once __DIR__ . '/../api/config/csrf.php';

if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'admin') {
    echo "<script>alert('❌ Akses hanya untuk admin'); window.location.href='beranda.php';</script>";
    exit;
}

$id = $_GET['id'] ?? null;
if (!$id) {
    $_SESSION['flash_message'] = '❌ ID lokasi outlet tidak ditemukan';
    header("Location: edit_lokasi.php");
    exit;
}

$query = "SELECT * FROM lokasi_outlet WHERE id_lokasi_outlet = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$lokasi_outlet = $result->fetch_assoc();

if (!$lokasi_outlet) {
    $_SESSION['flash_message'] = '❌ Lokasi outlet tidak ditemukan';
    header("Location: edit_lokasi.php");
    exit;
}

[$jam_buka, $jam_tutup] = explode(' - ', $lokasi_outlet['jam_operasional']);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Lokasi BONBON</title>
    <link rel="icon" href="images/logo-bonbon.png" type="image/png">
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        .active-link {
            outline: 3px solid #d3293b;
            outline-offset: 6px;
            border-radius: 40%;
        }
    </style>
</head>

<body>
    <nav class="bg-white shadow-md sticky top-0 z-50">
        <div class="flex justify-between items-center p-4">
            <div class="flex-1 flex justify-center space-x-12">
                <a href="dashboard.php" id="dashboard" class="text-red-600 font-bold hover:text-red-800">Dashboard</a>
                <a href="edit_promo.php" id="edit_promo" class="text-red-600 font-bold hover:text-red-800">Edit Promosi</a>
                <a href="edit_menu.php" id="edit_menu" class="text-red-600 font-bold hover:text-red-800">Edit Menu</a>
                <a href="edit_lokasi.php" id="edit_lokasi" class="text-red-600 font-bold hover:text-red-800">Edit Lokasi</a>
            </div>
            <a href="logout.php" class="bg-red-600 text-white px-4 py-2 rounded-lg font-bold hover:bg-red-800">Logout</a>
        </div>
    </nav>

    <main class="p-0 w-full">
        <header class="relative w-full overflow-hidden bg-red-600">
            <img src="images/bannerbonbon.svg" alt="Bonbon Banner" class="w-full h-auto">
        </header>

        <section class="px-4 text-center items-center justify-center text-white bg-red-600">
            <h2 class="text-4xl font-bold">Edit Lokasi</h2>
        </section>

        <section class="bg-red-600 justify-center w-full">
            <div class="flex justify-center items-center w-full p-8">
                <div class="bg-white p-8 rounded-xl shadow-xl w-full max-w-4xl">

                    <?php if (isset($_SESSION['flash_message'])): ?>
                        <div class="mx-auto max-w-2xl mt-4 mb-2 p-4 text-white text-center font-semibold rounded-lg 
                                    <?= str_starts_with($_SESSION['flash_message'], '✅') ? 'bg-green-600' : 'bg-red-600' ?>">
                            <?= $_SESSION['flash_message'] ?>
                        </div>
                        <?php unset($_SESSION['flash_message']); ?>
                    <?php endif; ?>
                    
                    <form class="space-y-6" method="POST" action="../api/controller/aksi_edit_lokasi.php" enctype="multipart/form-data">
                        <input type="hidden" name="csrf_token_form" value="<?= $_SESSION['csrf_token'] ?>">
                        <input type="hidden" name="id_lokasi_outlet" value="<?= $lokasi_outlet['id_lokasi_outlet'] ?>">
                        <input type="hidden" name="gambar_lama" value="<?= $lokasi_outlet['gambar_outlet'] ?>">

                        <div>
                            <label for="nama_cabang" class="block text-sm font-medium text-gray-600">Nama Cabang</label>
                            <input type="text" id="nama_cabang" name="nama_cabang" value="<?= htmlspecialchars($lokasi_outlet['nama_outlet'], ENT_QUOTES, 'UTF-8') ?>" class="w-full p-3 border border-gray-300 rounded-md mt-2" required>
                        </div>

                        <div>
                            <label for="alamat" class="block text-sm font-medium text-gray-600">Alamat</label>
                            <input type="text" id="alamat" name="alamat" value="<?= htmlspecialchars($lokasi_outlet['alamat'], ENT_QUOTES, 'UTF-8') ?>" class="w-full p-3 border border-gray-300 rounded-md mt-2" required>
                        </div>

                        <div class="flex space-x-4">
                            <div class="flex-1">
                                <label for="jam_buka" class="block text-sm font-medium text-gray-600">Jam Buka</label>
                                <input type="time" id="jam_buka" name="jam_buka" value="<?= $jam_buka ?>" class="w-full p-3 border border-gray-300 rounded-md mt-2" required>
                            </div>
                            <div class="flex-1">
                                <label for="jam_tutup" class="block text-sm font-medium text-gray-600">Jam Tutup</label>
                                <input type="time" id="jam_tutup" name="jam_tutup" value="<?= $jam_tutup ?>" class="w-full p-3 border border-gray-300 rounded-md mt-2" required>
                            </div>
                        </div>
                        <div>
                            <label for="link_gmaps" class="block text-sm font-medium text-gray-600">Link Google Maps</label>
                            <input type="url" id="link_gmaps" name="link_gmaps" value="<?= htmlspecialchars($lokasi_outlet['link_gmaps'], ENT_QUOTES, 'UTF-8') ?>" class="w-full p-3 border border-gray-300 rounded-md mt-2" required>
                        </div>
                        <div>
                            <label for="gambar" class="block text-sm font-medium text-gray-600">Ganti Gambar</label>
                            <input type="file" id="gambar" name="gambar" accept="image/*" class="w-full p-3 border border-gray-300 rounded-md mt-2">
                            <p class="text-sm mt-2 text-gray-500">Gambar saat ini: <?= $lokasi_outlet['gambar_outlet'] ?></p>
                        </div>
                        <div class="flex justify-end space-x-4">
                            <button type="submit" class="bg-red-600 text-white font-semibold py-3 px-6 rounded-md hover:bg-red-700">Simpan Perubahan</button>
                            <a href="edit_lokasi.php" class="bg-gray-300 text-black font-semibold py-3 px-6 rounded-md hover:bg-gray-400">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </main>
    
    <!-- Transisi-->
    <div class="w-full overflow-hidden bg-red-600">
        <img src="images/wavebonbonflip.png" alt="Transisi" class="w-full h-auto -mt-1">
    </div>
    
</body>
</html>
