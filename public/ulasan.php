<?php
define('ACCESS_ALLOWED', true);
require_once __DIR__ . '/../api/config/auth.php';
require_once __DIR__ . '/../api/config/koneksi.php';

if (isset($_SESSION['flash_message'])) {
    echo "<script>alert('" . $_SESSION['flash_message'] . "');</script>";
    unset($_SESSION['flash_message']);
}

$ulasan_result = mysqli_query($conn, 
    "SELECT ru.id_rating_ulasan, ru.rating, ru.ulasan, ru.user_id_user, u.email 
    FROM rating_ulasan ru 
    JOIN user u ON ru.user_id_user = u.id_user 
    ORDER BY ru.waktu_rating_ulasan DESC
");

$is_logged_in = isset($_SESSION['id_user']);

$user_ulasan_id = null;
if ($is_logged_in) {
    $user_id = $_SESSION['id_user'];
    $user_ulasan_query = mysqli_query($conn, "SELECT id_rating_ulasan FROM rating_ulasan WHERE user_id_user = '$user_id'");
    if (mysqli_num_rows($user_ulasan_query) > 0) {
        $user_ulasan_row = mysqli_fetch_assoc($user_ulasan_query);
        $user_ulasan_id = $user_ulasan_row['id_rating_ulasan'];
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ulasan BONBON</title>
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

<body class="bg-red-600">
    <!-- Navbar -->
    <nav class="bg-white shadow-md sticky top-0 z-50">
        <div class="flex justify-between items-center p-4">
            <div class="flex-1 flex justify-center space-x-12">
                <a href="beranda.php" id="home" class="text-red-600 font-bold hover:text-red-800">Beranda</a>
                <a href="franchise.php" id="franchise" class="text-red-600 font-bold hover:text-red-800">Franchise</a>
                <a href="menu.php" id="menu" class="text-red-600 font-bold hover:text-red-800">Menu</a>
                <a href="lokasi.php" id="Lokasi" class="text-red-600 font-bold hover:text-red-800">Lokasi</a>
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
            <img src="images/bannerbonbon.svg" alt="Bonbon Banner" class="w-full h-auto bg-red-600">
        </header>
    </main>

    <section class="text-center relative bg-red-600">
        <h2 class="text-white text-3xl font-bold mb-12">Tanggapan mereka tentang BONBON</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 max-w-screen-lg mx-auto">
            <?php while ($row = mysqli_fetch_assoc($ulasan_result)): ?>
                <div class="bg-white p-6 rounded-lg shadow-lg text-left">
                    <h3 class="text-xl font-bold text-red-600">
                        <?= htmlspecialchars($row['email'], ENT_QUOTES, 'UTF-8') ?>
                    </h3>
                    <div class="text-yellow-400 text-lg mt-2">
                        <div style="color: #ffc107;" class="text-lg mt-2">
                            <?= str_repeat('★', (int)$row['rating']) . str_repeat('☆', 5 - (int)$row['rating']) ?>
                        </div>
                    </div>
                    <p class="text-sm text-gray-700 mt-3">
                        <?= htmlspecialchars($row['ulasan'], ENT_QUOTES, 'UTF-8') ?>
                    </p>
                </div>
            <?php endwhile; ?>
        </div>

        <div class="w-full overflow-hidden bg-red-600">
            <img src="images/wavebonbonflip.png" alt="Transisi" class="w-full h-auto -mt-1" />
        </div>

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
    </section>

    <?php if ($is_logged_in && $_SESSION['role'] === 'pengunjung' && $user_ulasan_id): ?>
        <form action="form_edit_ulasan.php" method="GET" class="fixed bottom-6 right-10">
            <input type="hidden" name="id" value="<?= $user_ulasan_id ?>">
            <button type="submit" class="bg-white text-red-600 p-4 rounded-full shadow-lg hover:scale-105 transition duration-300">
                <span class="text-xl font-bold">+ Edit Ulasan</span>
            </button>
        </form>
    <?php elseif ($is_logged_in && $_SESSION['role'] === 'pengunjung'): ?>
        <form action="form_tambah_ulasan.php" method="POST" class="fixed bottom-6 right-10">
            <button type="submit" class="bg-white text-red-600 p-4 rounded-full shadow-lg hover:scale-105 transition duration-300">
                <span class="text-xl font-bold">+ Tambah Ulasan</span>
            </button>
        </form>
    <?php endif; ?>

    <script>
        // Highlight navigasi yang aktif berdasarkan halaman
        const pageMap = {
            'beranda.php': 'home',
            'franchise.php': 'franchise',
            'menu.php': 'menu',
            'lokasi.php': 'lokasi',
            'ulasan.php': 'ulasan',
        };
        const currentPage = window.location.pathname.split('/').pop();

        if (pageMap[currentPage]) {
            document.getElementById(pageMap[currentPage])?.classList.add('active-link');
        }
    </script>
</body>
</html>
