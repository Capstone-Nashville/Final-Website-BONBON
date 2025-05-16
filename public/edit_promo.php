<?php
define('ACCESS_ALLOWED', true);
require_once __DIR__ . '/../api/config/auth.php';
require_once __DIR__ . '/../api/config/koneksi.php';

if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'admin') {
    echo "<script>alert('❌ Akses hanya untuk admin'); window.location.href='beranda.php';</script>";
    exit;
}

$promo = mysqli_query($conn, "SELECT * FROM promo");

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
    <link rel="icon" href="images/logo-bonbon.png" type="image/png">
    <title>Edit Promosi BONBON</title>
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
                <a href="dashboard.php" id="dashboard" class="text-red-600 font-bold hover:text-red-800">Dashboard</a>
                <a href="edit_promo.php" id="edit_promo" class="text-red-600 font-bold hover:text-red-800">Edit Promosi</a>
                <a href="edit_menu.php" id="edit_menu" class="text-red-600 font-bold hover:text-red-800">Edit Menu</a>
                <a href="edit_lokasi.php" id="edit_lokasi" class="text-red-600 font-bold hover:text-red-800">Edit Lokasi</a>
            </div>

            <a href="logout.php" class="bg-red-600 text-white px-4 py-2 rounded-lg font-bold hover:bg-red-800">Logout</a>
        </div>
    </nav>

    <!-- Main content -->
    <main class="p-0">
        <!-- Banner Image -->
        <header class="relative">
            <img src="images/bannerbonbon.svg" alt="Bonbon Banner" class="w-full h-auto bg-red-600      ">
        </header>

    <section class="px-4 text-center text-white bg-red-600">
        <h2 class="text-4xl font-bold">Edit Promosi</h2>
    </section>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 py-12 px-40">
        <?php while ($row = mysqli_fetch_assoc($promo)): ?>
            <div class="bg-white shadow-lg rounded-xl p-4 flex flex-col items-center">
                <a href="<?= htmlspecialchars($row['link_postingan']) ?>" target="_blank">
                    <img src="images_promo/<?= htmlspecialchars($row['gambar_promo']) ?>" alt="Promo BONBON" class="w-full h-56 object-contain bg-white rounded-md mb-4 hover:opacity-80 transition duration-200">
                </a>
                <div class="w-full flex justify-center">
                    <form action="/bonbon/api/controller/aksi_hapus_promo.php" method="POST" onsubmit="return confirm('Apakah kamu yakin ingin menghapus promosi ini?');">
                        <input type="hidden" name="id_promo" value="<?= $row['id_promo'] ?>">
                        <button type="submit" class="bg-red-600 text-white py-2 px-4 rounded-md hover:scale-105">Hapus</button>
                    </form>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

    <div class="flex justify-center bg-red-600">
        <a href="form_tambah_promo.php" method="POST" class="bg-white text-red-600 font-semibold py-4 px-8 rounded-full shadow-xl hover:scale-105"> + Tambah Promosi</a>
    </div>

    <!-- Transisi-->
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
