<?php
session_start();

define('ACCESS_ALLOWED', true);
require_once __DIR__ . '/../api/config/koneksi.php';

$lokasi = mysqli_query($conn, "SELECT * FROM lokasi_outlet");

$is_logged_in = isset($_SESSION['id_user']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lokasi BONBON</title>
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
    <!-- Navbar -->
    <nav class="bg-white shadow-md sticky top-0 z-50">
        <div class="flex justify-between items-center p-4">
            <div class="flex-1 flex justify-center space-x-12">
                <a href="beranda.php" id="home" class="text-red-600 font-bold hover:text-red-800">Beranda</a>
                <a href="franchise.php" id="franchise" class="text-red-600 font-bold hover:text-red-800">Franchise</a>
                <a href="menu.php" id="menu" class="text-red-600 font-bold hover:text-red-800">Menu</a>
                <a href="lokasi.php" id="lokasi" class="text-red-600 font-bold hover:text-red-800">Lokasi</a> 
                <a href="ulasan.php" id="ulasan" class="text-red-600 font-bold hover:text-red-800">Ulasan</a>                   
            </div>
            <?php if ($is_logged_in): ?>
                <a href="logout.php" class="bg-red-600 text-white px-4 py-2 rounded-lg font-bold hover:bg-red-800">Logout</a>
            <?php else: ?>
                <a href="login.php" class="bg-red-600 text-white px-4 py-2 rounded-lg font-bold hover:bg-red-800">Login</a>
            <?php endif; ?>
        </div>
    </nav>

    <!-- Main content -->
    <main class="p-0">
        <!-- Banner Image -->
        <header class="relative">
            <img src="images/bannerbonbon.svg" alt="Bonbon Banner" class="w-full h-auto">
        </header>

        <body class="bg-red-600">

    <!-- Lokasi Section -->
    <section class="py-8 px-4 text-center text-red-600 bg-white">
        <h2 class="text-4xl font-bold  mb-4">Lokasi</h2>
        <p class="text-xl mb-8 px-60 py-4">Kunjungi Lokasi Bonbon Ice Cream dan Jadikan Hari Anda Lebih Manis Bersama Kami. Tempat Terbaik untuk Momen Terlezat!</p>
    </section>

    <section class="bg-red-600">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 py-12 px-40">
            <?php while ($row = mysqli_fetch_assoc($lokasi)): ?>
            <div class="location-card bg-white rounded-2xl overflow-hidden shadow-2xl p-4 block transform transition duration-300 hover:scale-105">
                <img src="images_lokasi/<?= htmlspecialchars($row['gambar_outlet']) ?>" alt="<?= htmlspecialchars($row['nama_outlet']) ?>" class="w-full h-48 object-cover">
                <div class="p-4 text-left">
                    <h3 class="font-bold text-2xl text-red-600"><?= htmlspecialchars(strtoupper($row['nama_outlet'])) ?></h3>
                    <p class="text-gray-700 text-sm"><?= htmlspecialchars($row['alamat']) ?></p>
                    <p class="text-lg font-semibold text-gray-800 mt-2">🕒 <?= htmlspecialchars($row['jam_operasional']) ?></p>
                    <a href="<?= htmlspecialchars($row['link_gmaps']) ?>" target="_blank" target="_blank" class="inline-block mt-4 px-4 py-2 bg-red-600 text-white rounded-full hover:bg-red-800">Lihat Lokasi</a>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </section>

    <!-- Transisi Wave Image dengan background -->
    <div class="w-full overflow-hidden bg-red-600">
        <img src="images/wavebonbonflip.png" alt="Transisi" class="w-full h-auto -mt-1">
    </div>

    <!-- Footer Section -->
    <footer class="bg-white text-black py-6 px-32">
        <div class="max-w-screen-xl mx-auto flex flex-col md:flex-row justify-between items-center md:items-start gap-8 border-b border-gray-200 pb-6">
            <!-- Logo dan Alamat -->
            <div class="flex items-center gap-6">
                <!-- Logo -->
                <img src="images/logo-bonbon.png" alt="BONBON Logo" class="w-24 h-24 object-contain">
                
                <!-- Alamat -->
                <div class="text-left text-sm">
                    <p class="font-semibold">PT. Fren & Co Group</p>
                    <p>Jl. Sirad Salman No. 6A, Air Hitam,</p>
                    <p>Samarinda Ulu, 75124</p>
                </div>
            </div>
        
            <!-- Sosial Media -->
            <div class="text-center md:text-right">
                <p class="text-sm font-semibold mb-2">Ikuti Kami</p>
                <div class="flex justify-center md:justify-end gap-4">
                    <a href="https://www.instagram.com/bonbon.smr" target="_blank" rel="noopener noreferrer" aria-label="Instagram Bonbon">
                        <img src="images/instagram.jpg" alt="Instagram Bonbon" class="w-12 h-12 object-contain hover:brightness-90 transition" />
                    </a>

                    <a href="https://www.tiktok.com/@bonbon.smr" target="_blank" rel="noopener noreferrer" aria-label="TikTok Bonbon">
                        <img src="images/tiktok.jpg" alt="TikTok Bonbon" class="w-12 h-12 object-contain hover:brightness-90 transition" />
                    </a>

                    <a href="https://wa.me/6282155358684" aria-label="WhatsApp Bonbon" target="_blank" rel="noopener noreferrer">
                        <img src="images/whatsapp.jpg" alt="WhatsApp Bonbon" class="w-12 h-12 object-contain hover:brightness-90 transition" />
                    </a>
                </div>
            </div>
        </div>
                <!-- Copyright -->
        <div class="text-center text-xs text-gray-600 mt-4">
            &copy; 2025 – BONBON. ALL RIGHTS RESERVED
        </div>
    </footer>

    <script>
        // Highlight link navigasi yang aktif
        const pageMap = {
            'beranda.php': 'home',
            'franchise.php': 'franchise',
            'menu.php': 'menu',
            'lokasi.php': 'lokasi',
            'ulasan.php': 'ulasan',
        };
        const currentPage = window.location.pathname.split('/').pop();
        if (pageMap[currentPage]) {
            const activeLink = document.getElementById(pageMap[currentPage]);
            if (activeLink) activeLink.classList.add('active-link');
        }
    </script>
</body>
</html>