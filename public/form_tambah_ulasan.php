<?php
define('ACCESS_ALLOWED', true);
require_once __DIR__ . '/../api/config/auth.php';
require_once __DIR__ . '/../api/config/koneksi.php';

if ($_SESSION['role'] !== 'pengunjung') {
    echo "<script>alert('❌ Admin dilarang membuat ulasan'); window.location.href='beranda.php';</script>";
    exit;
}

$is_logged_in = isset($_SESSION['id_user']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Menu BONBON</title>
    <link rel="icon" href="images/logo-bonbon.png" type="image/png">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/loading.css">
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
        <header class="relative">
            <img src="images/bannerbonbon.svg" alt="Bonbon Banner" class="w-full h-auto bg-red-600">
        </header>
    </main>

    <section class="px-4 text-center items-center justify-center text-white bg-red-600">
        <h2 class="text-4xl font-bold">Tambah Ulasan</h2>
    </section>

    <!-- Testimonial Section -->
    <section class="bg-red-600 justify-center w-full py-10">
        <div class="flex justify-center items-center w-full p-8">
            <div class="bg-white p-8 rounded-xl shadow-xl w-full max-w-2xl">
                <form class="space-y-6" method="POST" action="../api/controller/aksi_tambah_ulasan.php" enctype="multipart/form-data">
                    <div>
                        <label for="rating" class="block text-sm font-medium text-gray-600">Rating</label>
                        <select name="rating" id="rating" class="w-full p-3 border border-gray-300 rounded-md mt-2" required>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                        </select>
                    </div>

                    <div>
                        <label for="ulasan" class="block text-sm font-medium text-gray-600">Ulasan</label>
                        <textarea id="ulasan" name="ulasan" class="w-full p-3 border border-gray-300 rounded-md mt-2" placeholder="Tulisakan Ulasan Anda" required></textarea>
                    </div>

                    <label class="flex items-center space-x-2">
                        <input type="checkbox" class="form-checkbox text-red-600" required>
                        <span class="text-sm text-gray-700">Saya menyatakan bahwa ulasan ini didasarkan pada pengalaman pribadi saya tentang produk atau layanan dari BONBON, dan saya tidak memiliki hubungan pribadi atau bisnis dengan BONBON atau menerima tawaran insentif atau pembayaran apapun untuk menulis ulasan ini. Saya memahami bahwa BONBON memiliki kebijakan untuk memastikan ulasan yang ditulis adalah asli dan tidak menyesatkan.</span>
                    </label>

                    <div class="flex justify-end space-x-4 mt-2">
                        <button type="submit" class="bg-red-600 text-white font-semibold py-3 px-6 rounded-md hover:bg-red-700"> Tambah Ulasan</button>
                        <a href="ulasan.php" class="bg-gray-300 text-black font-semibold py-3 px-6 rounded-md hover:bg-gray-400">Batal</a>
                    </div>                
                </form>
            </div>
        </div>
    </section>
        
    <!-- Transisi Wave Image dengan background -->
    <div class="w-full overflow-hidden bg-red-600">
        <img src="images/wavebonbonflip.png" alt="Transisi" class="w-full h-auto -mt-1">
    </div>
    
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
