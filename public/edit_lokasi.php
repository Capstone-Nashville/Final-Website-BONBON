<?php
define('ACCESS_ALLOWED', true);
require_once __DIR__ . '/../api/config/auth.php';
require_once __DIR__ . '/../api/config/koneksi.php';

if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'admin') {
    echo "<script>alert('❌ Akses hanya untuk admin'); window.location.href='beranda.php';</script>";
    exit;
}

$lokasi = mysqli_query($conn, "SELECT * FROM lokasi_outlet");

if (isset($_SESSION['flash_message'])) {
    echo "<script>alert('" . $_SESSION['flash_message'] . "');</script>";
    unset($_SESSION['flash_message']);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lokasi BONBON - Es Krim, Teh, dan Kopi</title>
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
<body class="bg-white">
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

    <main class="p-0">
        <header class="relative">
            <img src="images/bannerbonbon.svg" alt="Bonbon Banner" class="w-full h-auto bg-red-600      ">
        </header>

    <section class="px-4 text-center text-white bg-red-600">
        <h2 class="text-4xl font-bold">Edit Lokasi</h2>
    </section>

    <section class="bg-red-600">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 py-12 px-40">
            <?php while ($row = mysqli_fetch_assoc($lokasi)): ?>
            <div class="location-card bg-white rounded-2xl overflow-hidden shadow-2xl p-4 block transform transition duration-300 hover:scale-105">
                <img src="images_lokasi/<?= htmlspecialchars($row['gambar_outlet']) ?>" alt="<?= htmlspecialchars($row['nama_outlet'], ENT_QUOTES, 'UTF-8') ?>" class="w-full h-48 object-cover">
                <div class="p-4 text-left">
                    <h3 class="font-bold text-2xl text-red-600"><?= htmlspecialchars(strtoupper($row['nama_outlet'], ENT_QUOTES, 'UTF-8')) ?></h3>
                    <p class="text-gray-700 text-sm"><?= htmlspecialchars($row['alamat'], ENT_QUOTES, 'UTF-8') ?></p>
                    <p class="text-lg font-semibold text-gray-800 mt-2">🕒 <?= htmlspecialchars($row['jam_operasional'], ENT_QUOTES, 'UTF-8') ?></p>
                    <a href="<?= htmlspecialchars($row['link_gmaps'], ENT_QUOTES, 'UTF-8') ?>" target="_blank" target="_blank" class="inline-block mt-4 px-4 py-2 bg-red-600 text-white rounded-full hover:bg-red-800">Lihat Lokasi</a>
                    <div class="mt-4 flex justify-center gap-2">
                    <a href="form_edit_lokasi.php?id=<?= $row['id_lokasi_outlet'] ?>" class="bg-yellow-400 text-white py-2 px-4 rounded-md hover:scale-105">Edit</a>
                        <form action="/bonbon/api/controller/aksi_hapus_lokasi.php" method="POST" onsubmit="return confirm('Apakah kamu yakin ingin menghapus outlet ini?');">
                            <input type="hidden" name="id_lokasi_outlet" value="<?= $row['id_lokasi_outlet'] ?>">
                            <button type="submit" class="bg-red-600 text-white py-2 px-4 rounded-md hover:scale-105">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </section>

        <div class="flex justify-center bg-red-600">
            <a href="form_tambah_lokasi.php" class="bg-white text-red-600 font-semibold py-4 px-8 rounded-full shadow-xl hover:scale-105"> + Tambah Lokasi
            </a>
        </div>

    <div class="w-full overflow-hidden bg-red-600">
        <img src="images/wavebonbonflip.png" alt="Transisi" class="w-full h-auto -mt-1">
    </div>

    <script>
        const currentUrl = window.location.pathname;
        if (currentUrl.includes('dashboard.php')) {
            document.getElementById('dashboard').classList.add('active-link');
        } else if (currentUrl.includes('edit_promo.php')) {
            document.getElementById('edit_promo').classList.add('active-link');
        } else if (currentUrl.includes('edit_menu.php')) {
            document.getElementById('edit_menu').classList.add('active-link');
        } else if (currentUrl.includes('edit_lokasi.php')) {
            document.getElementById('edit_lokasi').classList.add('active-link');
        }
    </script>
</body>
</html>