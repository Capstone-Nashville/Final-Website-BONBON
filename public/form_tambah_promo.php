<?php
define('ACCESS_ALLOWED', true);
require_once __DIR__ . '/../api/config/auth.php';
require_once __DIR__ . '/../api/config/koneksi.php';

if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'admin') {
    echo "<script>alert('❌ Akses hanya untuk admin'); window.location.href='beranda.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

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
            outline: 3px solid #d3293b; /* Outline warna merah */
            outline-offset: 6px;
            border-radius: 40%;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="bg-white shadow-md sticky top-0 z-50">
        <div class="flex justify-between items-center p-4">
            <!-- Centered Navbar Items -->
            <div class="flex-1 flex justify-center space-x-12">
                <a href="dashboard.php" id="dashboard" class="text-red-600 font-bold hover:text-red-800">Dashboard</a>
                <a href="edit_promo.php" id="edit_promo" class="text-red-600 font-bold hover:text-red-800">Edit Promosi</a>
                <a href="edit_menu.php" id="edit_menu" class="text-red-600 font-bold hover:text-red-800">Edit Menu</a>
                <a href="edit_lokasi.php" id="edit_lokasi" class="text-red-600 font-bold hover:text-red-800">Edit Lokasi</a>
            </div>
            <!-- Right-aligned Login Button -->
            <a href="logout.php" class="bg-red-600 text-white px-4 py-2 rounded-lg font-bold hover:bg-red-800">Keluar</a>
        </div>
    </nav>

    <!-- Main content -->
    <main class="p-0 w-full bg-red-600">

        <!-- Banner Image -->
        <header class="relative w-full overflow-hidden bg-red-600">
            <img src="images/bannerbonbon.svg" alt="Bonbon Banner" class="w-full h-auto">
        </header>

        <section class="px-4 text-center items-center justify-center text-white bg-red-600">
            <h2 class="text-4xl font-bold">Tambah promosi</h2>
        </section>

        <!-- Form Section -->
        <section class="bg-red-600 justify-center w-full py-12">
            <div class="flex justify-center items-center w-full p-8">
                <div class="bg-white p-8 rounded-xl shadow-xl w-full max-w-4xl">
                    <?php if (isset($_SESSION['flash_message'])): ?>
                        <div class="mx-auto max-w-2xl mt-4 mb-2 p-4 text-white text-center font-semibold rounded-lg 
                                    <?= str_starts_with($_SESSION['flash_message'], '✅') ? 'bg-green-600' : 'bg-red-600' ?>">
                            <?= $_SESSION['flash_message'] ?>
                        </div>
                        <?php unset($_SESSION['flash_message']); ?>
                    <?php endif; ?>
                    <form class="space-y-6" method="POST" action="../api/controller/aksi_tambah_promo.php" enctype="multipart/form-data">
                        <div>
                            <label for="link_postingan" class="block text-sm font-medium text-gray-600">Link Postingan</label>
                            <input type="url" id="link_postingan" name="link_postingan" class="w-full p-3 border border-gray-300 rounded-md mt-2" placeholder="Masukkan Link" required>
                        </div>
                        <div>
                            <label for="gambar_promo" class="block text-sm font-medium text-gray-600">Upload Gambar Promo</label>
                            <input type="file" id="gambar_promo" name="gambar_promo" class="w-full p-3 border border-gray-300 rounded-md mt-2" accept="image/*" required>
                        </div>
                        <div class="flex justify-end space-x-4">
                            <button type="submit" class="bg-red-600 text-white font-semibold py-3 px-6 rounded-md hover:bg-red-700">Tambahkan</button>
                            <a href="edit_promo.php" class="bg-gray-300 text-black font-semibold py-3 px-6 rounded-md hover:bg-gray-400">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </main>
        
    <!-- Transisi Wave Image dengan background -->
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
