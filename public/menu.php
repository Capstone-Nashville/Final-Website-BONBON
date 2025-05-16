<?php
session_start();

define('ACCESS_ALLOWED', true);
require_once __DIR__ . '/../api/config/koneksi.php';

$kategori_aktif = $_GET['kategori'] ?? 'semua';

$kategori_list = [
    "semua" => "Semua",
    "coffee-series" => "Coffee Series",
    "waffle-cone" => "Waffle Cone",
    "float" => "Float",
    "signature" => "Signature",
    "sundae" => "Sundae",
    "tea-series" => "Tea Series"
];

if ($kategori_aktif === 'semua') {
    $query = "SELECT * FROM menu ORDER BY kategori, nama_produk";
    $stmt = $conn->prepare($query);
} else {
    $query = "SELECT * FROM menu WHERE kategori = ? ORDER BY nama_produk";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $kategori_aktif);
}

$stmt->execute();
$result = $stmt->get_result();
$menus = $result->fetch_all(MYSQLI_ASSOC);

$is_logged_in = isset($_SESSION['id_user']);

function format_rupiah($angka) {
    return 'Rp ' . number_format($angka, 0, ',', '.');
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Menu BONBON</title>
    <link rel="icon" href="images/logo-bonbon.png" type="image/png" />
    <link rel="stylesheet" href="css/style.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet" />
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        .category-nav {
            display: flex;
            justify-content: center;
            gap: 24px;
            margin: 20px 0;
            flex-wrap: wrap;
        }
        .category-nav a {
            text-decoration: none;
            color: #ffffff;
            font-weight: bold;
            padding: 10px;
            border-bottom: 2px solid transparent;
            transition: border-bottom 0.3s ease;
        }
        .category-nav a:hover,
        .category-nav a.active {
            border-bottom: 2px solid #ffffff;
        }
        .active-link {
            outline: 3px solid #d3293b;
            outline-offset: 6px;
            border-radius: 40%;
        }
    </style>
</head>

<body class="bg-red-600">
    <nav class="bg-white shadow-md sticky top-0 z-50">
        <div class="flex justify-between items-center p-4">
            <div class="flex-1 flex justify-center space-x-12">
                <?php
                $navs = [
                    'beranda.php' => 'Beranda',
                    'franchise.php' => 'Franchise',
                    'menu.php' => 'Menu',
                    'lokasi.php' => 'Lokasi',
                    'ulasan.php' => 'Ulasan'
                ];
                foreach ($navs as $file => $label):
                    $id = pathinfo($file, PATHINFO_FILENAME);
                    $classes = "text-red-600 font-bold hover:text-red-800";
                    if ($id === 'menu') {
                        $classes .= " active-link";
                    }
                ?>
                    <a href="<?= $file ?>" id="<?= $id ?>" class="<?= $classes ?>"><?= $label ?></a>
                <?php endforeach; ?>
            </div>

            <?php if ($is_logged_in): ?>
                <a href="logout.php" class="bg-red-600 text-white px-4 py-2 rounded-lg font-bold hover:bg-red-800">Logout</a>
            <?php else: ?>
                <a href="login.php" class="bg-red-600 text-white px-4 py-2 rounded-lg font-bold hover:bg-red-800">Login</a>
            <?php endif; ?>
        </div>
    </nav>

    <header class="relative w-full overflow-hidden bg-white">
        <img src="images/bannerbonbon.svg" alt="Bonbon Banner" class="w-full h-auto" />
    </header>

    <header class="text-center py-16 text-red-600 bg-white">
        <h1 class="text-4xl font-bold">Menu</h1>
        <p class="mt-4 text-xl px-6 md:px-60 py-2">
            Nikmati Setiap Momen Manis dengan Bonbon Ice Cream, Tea & Coffee. Temukan Produk Favorit Anda dan Rasakan Kenikmatan
        </p>
    </header>

    <nav id="daftar-menu" class="category-nav text-white text-lg font-bold">
        <?php foreach ($kategori_list as $key => $label): ?>
            <a href="menu.php?kategori=<?= $key?>#daftar-menu" 
            class="category-link <?= $kategori_aktif === $key ? 'active' : '' ?>"
            <?= $kategori_aktif === $key ? 'aria-current="page"' : '' ?>>
                <?= $label ?>
            </a>
        <?php endforeach; ?>
    </nav>

    <section class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-3 gap-8 py-12 px-40">
        <?php if (empty($menus)): ?>
            <p class="text-white col-span-full text-center">Belum ada menu di kategori ini.</p>
        <?php else: ?>
            <?php foreach ($menus as $menu): ?>
                <div class="bg-white rounded-lg overflow-hidden shadow-lg p-6 transform transition duration-300 hover:scale-105">
                    <img src="images_menu/<?= htmlspecialchars($menu['gambar_produk'], ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($menu['nama_produk'], ENT_QUOTES, 'UTF-8') ?>" class="w-36 h-48 object-cover mx-auto">
                    <div class="text-left mt-4">
                        <p class="font-semibold text-xl text-black"><?= htmlspecialchars($menu['nama_produk'], ENT_QUOTES, 'UTF-8') ?></p>
                        <p class="text-red-600 font-bold text-base mt-2 border-2 border-red-600 rounded-full inline-block py-1 px-3">
                            <?= format_rupiah($menu['harga']) ?>
                        </p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </section>

    <div class="w-full overflow-hidden bg-red-600">
        <img src="images/wavebonbonflip.png" alt="Transisi" class="w-full h-auto -mt-1" />
    </div>

    <!-- Footer -->
    <footer class="bg-white text-black py-6 px-32">
        <div class="max-w-screen-xl mx-auto flex flex-col md:flex-row justify-between items-center md:items-start gap-8 border-b border-gray-200 pb-6">
            <div class="flex items-center gap-6">
                <img src="images/logo-bonbon.png" alt="BONBON Logo" class="w-24 h-24 object-contain" />
                <div class="text-left text-sm">
                    <p class="font-semibold">PT. Fren & Co Group</p>
                    <p>Jl. Sirad Salman No. 6A, Air Hitam,</p>
                    <p>Samarinda Ulu, 75124</p>
                </div>
            </div>

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

        <div class="text-center text-xs text-gray-600 mt-4">&copy; 2025 – BONBON. ALL RIGHTS RESERVED</div>
    </footer>

    <script>
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