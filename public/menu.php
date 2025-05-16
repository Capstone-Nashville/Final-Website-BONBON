<?php
session_start();

define('ACCESS_ALLOWED', true);
require_once __DIR__ . '/../api/config/koneksi.php';

$kategori_aktif = $_GET['kategori'] ?? 'semua';

if ($kategori_aktif === 'semua') {
    $query = "SELECT * FROM menu ORDER BY kategori, nama_produk";
    $stmt = $conn->prepare($query);
} else {
    $query = "SELECT * FROM menu WHERE kategori = ? ORDER BY nama_produk";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $kategori_aktif);
}
$stmt->execute();
$result = $stmt->get_result();
$menus = $result->fetch_all(MYSQLI_ASSOC);

$is_logged_in = isset($_SESSION['id_user']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu BONBON</title>
    <link rel="icon" href="images/logo-bonbon.png" type="image/png">
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        .category-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px;
            font-size: 1.5rem;
            font-weight: bold;
            color:#ffffff
        }
        .category-nav {
            display: flex;
            justify-content: center;
            gap: 24px;
            margin-top: 20px;
        }
        .category-nav a {
            text-decoration: none;
            color:#ffffff;
            font-weight: bold;
            padding: 10px;
            border-bottom: 2px solid transparent;
            transition: border-bottom 0.3s ease;
        }
        .category-nav a:hover, .category-nav a.active {
            border-bottom: 2px solid #ffffff;
        }

        .active-link {
            outline: 3px solid #d3293b; /* Outline warna merah */
            outline-offset: 6px;
            border-radius: 40%;
        }
    </style>
</head>

<body class="bg-red-600">
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

    <!-- Banner -->
    <header class="relative w-full overflow-hidden bg-white">
        <img src="images/bannerbonbon.svg" alt="Bonbon Banner" class="w-full h-auto">
    </header>

    <!-- Header -->
    <header class="text-center py-16 text-red-600 bg-white">
        <h1 class="text-4xl font-bold">Menu</h1>
        <p class="mt-4 text-xl px-6 md:px-60 py-2">Nikmati Setiap Momen Manis dengan BONBON Ice Cream, Tea & Coffee. Temukan Produk Favorit Anda dan Rasakan Kenikmatan!</p>
    </header>

    <!-- Navigation Categories -->
    <nav class="category-nav flex justify-center gap-6 py-6 flex-wrap text-white text-lg font-bold">
        <?php
        $kategori_list = [
            "semua" => "Semua",
            "coffee-series" => "Coffee Series",
            "waffle-cone" => "Waffle Cone",
            "float" => "Float",
            "signature" => "Signature",
            "sundae" => "Sundae",
            "tea-series" => "Tea Series"
        ];
        ?>
        <?php foreach ($kategori_list as $key => $label): ?>
            <a href="menu.php?kategori=<?= $key ?>" class="category-link text-white text-lg font-bold <?= $kategori_aktif === $key ? 'active' : '' ?>">
                <?= $label ?>
            </a>
        <?php endforeach; ?>
    </nav>

    <!-- Menu Grid -->
    <section class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-3 gap-8 py-12 px-40">
        <?php if (empty($menus)): ?>
            <p class="text-white col-span-full">Belum ada menu di kategori ini.</p>
        <?php else: ?>
            <?php foreach ($menus as $menu): ?>
                <div class="bg-white rounded-lg overflow-hidden shadow-lg p-6 transform transition duration-300 hover:scale-105">
                    <img src="images_menu/<?= htmlspecialchars($menu['gambar_produk'], ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($menu['nama_produk'], ENT_QUOTES, 'UTF-8') ?>" class="w-36 h-48 object-cover mx-auto">
                    <div class="text-left mt-4">
                        <p class="font-semibold text-xl text-black"><?= htmlspecialchars($menu['nama_produk'], ENT_QUOTES, 'UTF-8') ?></p>
                        <p class="text-red-600 font-bold text-base mt-2 border-2 border-red-600 rounded-full inline-block py-1 px-3">
                            Rp <?= number_format($menu['harga'], 0, ',', '.') ?>
                        </p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
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
                    <!-- Ikon Instagram -->
                    <a href="https://www.instagram.com/bonbon.smr?utm_source=ig_web_button_share_sheet&igsh=ZDNlZDc0MzIxNw==" target="_blank">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor" class="w-12 h-12 text-red-600 hover:text-red-800 transition">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M7.5 3h9A4.5 4.5 0 0121 7.5v9A4.5 4.5 0 0116.5 21h-9A4.5 4.5 0 013 16.5v-9A4.5 4.5 0 017.5 3z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11.25a3.75 3.75 0 11-6 0 3.75 3.75 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75h.008v.008h-.008z" />
                        </svg>
                    </a>
                    <!-- TikTok Icon SVG -->
                    <a href="https://www.tiktok.com/@bonbon.smr?is_from_webapp=1&sender_device=pc" target="_blank">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" class="w-12 h-12 text-red-600 fill-current">
                            <path d="M41,15.4c-3.1,0-6-1-8.4-2.7v14.1c0,7.3-5.9,13.2-13.2,13.2S6.2,34.1,6.2,26.8s5.9-13.2,13.2-13.2c1,0,2,.1,3,.5v5.5
                            c-1-.5-2-.7-3-.7c-4.3,0-7.8,3.5-7.8,7.8s3.5,7.8,7.8,7.8s7.8-3.5,7.8-7.8V4h5.6c0.2,3.1,1.9,5.9,4.6,7.4C37.8,13.6,39.4,14.3,41,15.4z"/>
                        </svg>
                    </a>
                    <!-- Ikon WA -->
                    <a href="tel:+6282155358684" target="_blank">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" class="w-12 h-12 text-red-600 fill-current">
                            <path d="M16.003 3C9.383 3 4 8.383 4 15.003c0 2.522.748 4.867 2.021 6.83L4 29l7.42-2.02A11.94 11.94 0 0016.003 27C22.623 27 28 21.618 28 15S22.623 3 16.003 3zm0 2c5.513 0 10 4.486 10 10 0 5.513-4.487 10-10 10a9.94 9.94 0 01-5.076-1.378l-.356-.215-4.434 1.208 1.218-4.366-.224-.36A9.94 9.94 0 016.003 15c0-5.514 4.486-10 10-10zm4.59 5.62c-.31-.154-1.826-.9-2.11-.996-.283-.095-.49-.143-.697.154-.208.296-.797.997-.978 1.202-.18.207-.36.233-.67.078-.31-.156-1.31-.483-2.49-1.54-.92-.82-1.54-1.83-1.72-2.14-.18-.31-.02-.48.135-.634.14-.14.31-.36.467-.54.154-.18.207-.31.31-.517.103-.208.05-.39-.026-.543-.077-.154-.697-1.678-.954-2.31-.25-.62-.5-.537-.697-.547h-.596c-.206 0-.538.077-.82.38-.282.31-1.074 1.05-1.074 2.55s1.1 2.96 1.253 3.164c.154.207 2.14 3.27 5.186 4.59.724.312 1.29.498 1.733.637.728.232 1.39.2 1.916.12.585-.09 1.82-.744 2.08-1.464.256-.72.256-1.336.18-1.464-.078-.128-.28-.207-.585-.36z"/>
                        </svg>
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
        // Get the current URL
        const currentUrl = window.location.pathname;
        
        // Find the link corresponding to the current page and add the 'active-link' class
        if (currentUrl.includes('beranda.php')) {
            document.getElementById('home').classList.add('active-link');
        } else if (currentUrl.includes('franchise.php')) {
            document.getElementById('franchise').classList.add('active-link');
        } else if (currentUrl.includes('menu.php')) {
            document.getElementById('menu').classList.add('active-link');
        } else if (currentUrl.includes('lokasi.php')) {
            document.getElementById('lokasi').classList.add('active-link');
        } else if (currentUrl.includes('ulasan.php')) {
            document.getElementById('ulasan').classList.add('active-link');
        }
    </script>
</body>
</html>