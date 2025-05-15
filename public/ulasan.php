<?php
define('ACCESS_ALLOWED', true);
require_once __DIR__ . '/../api/config/auth.php';
require_once __DIR__ . '/../api/config/koneksi.php';

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
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mitra BONBON</title>
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

<body class="bg-red-600">
    <!-- Navbar -->
    <nav class="bg-white shadow-md sticky top-0 z-50">
        <div class="flex justify-between items-center p-4">
            <!-- Centered Navbar Items -->
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

    <section class="text-center py-2 px-4 relative bg-red-600">
        <h2 class="text-white text-3xl font-bold mb-12">Tanggapan mereka tentang BONBON</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 max-w-screen-lg mx-auto">
            <?php while ($row = mysqli_fetch_assoc($ulasan_result)): ?>
                <div class="bg-white p-6 rounded-lg shadow-lg text-left">
                    <h3 class="text-xl font-bold text-red-600">
                        <?= htmlspecialchars($row['email']) ?>
                    </h3>
                    <div class="text-yellow-400 text-lg mt-2">
                        <div style="color: #ffc107;" class="text-lg mt-2">
                            <?php
                            $rating = (int)$row['rating'];
                            echo str_repeat('★', $rating) . str_repeat('☆', 5 - $rating);
                            ?>
                        </div>    
                    </div>
                    <p class="text-sm text-gray-700 mt-3">
                        <?= htmlspecialchars($row['ulasan']) ?>
                    </p>
                </div>
            <?php endwhile; ?>
        </div>
    </section>

    <?php if ($is_logged_in && $_SESSION['role'] === 'pengunjung' && $user_ulasan_id): ?>
        <form action="form_edit_ulasan.php" method="POST" class="fixed bottom-6 right-10">
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
        const currentUrl = window.location.pathname;
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
