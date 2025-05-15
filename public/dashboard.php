<?php
define('ACCESS_ALLOWED', true);
require_once __DIR__ . '/../api/config/auth.php';
require_once __DIR__ . '/../api/config/koneksi.php';

if (!isset($_SESSION['id_user']) || $_SESSION['role'] !== 'admin') {
    echo "<script>alert('❌ Akses hanya untuk admin'); window.location.href='beranda.php';</script>";
    exit;
}

if (isset($_SESSION['flash_message'])) {
    echo "<script>alert('" . $_SESSION['flash_message'] . "');</script>";
    unset($_SESSION['flash_message']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="images/logo-bonbon.png" type="image/png">
    <title>Dashboard Penjualan BONBON</title>
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
            <!-- Centered Navbar Items -->
            <div class="flex-1 flex justify-center space-x-12">
                <a href="dashboard.php" id="dashboard" class="text-red-600 font-bold hover:text-red-800">Dashboard</a>
                <a href="edit_promo.php" id="edit_promo" class="text-red-600 font-bold hover:text-red-800">Edit Promosi</a>
                <a href="edit_menu.php" id="edit_menu" class="text-red-600 font-bold hover:text-red-800">Edit Menu</a>
                <a href="edit_lokasi.php" id="edit_lokasi" class="text-red-600 font-bold hover:text-red-800">Edit Lokasi</a>   
            </div>
            <!-- Right-aligned Login Button -->
            <a href="logout.php" class="bg-red-600 text-white px-4 py-2 rounded-lg font-bold hover:bg-red-800">Logout</a>
        </div>
    </nav>

<body>
    <iframe
    src="https://dashboard-bonbon.streamlit.app/?embed=true&embed_options=show_toolbar&embed_options=dark_theme"
    width="100%"
    height="900"
    style="border: none"
    loading="lazy"
    ></iframe>
    <script>
        const currentUrl = window.location.pathname;
        // Find the link corresponding to the current page and add the 'active-link' class
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