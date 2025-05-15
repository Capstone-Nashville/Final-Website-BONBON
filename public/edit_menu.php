<?php
define('ACCESS_ALLOWED', true);
require_once __DIR__ . '/../api/config/auth.php';
require_once __DIR__ . '/../api/config/koneksi.php';

if ($_SESSION['role'] !== 'admin') {
    echo "<script>alert('❌ Akses hanya untuk admin'); window.location.href='beranda.php';</script>";
    exit;
}

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

if (isset($_SESSION['flash_message'])) {
    echo "<script>alert('" . $_SESSION['flash_message'] . "');</script>";
    unset($_SESSION['flash_message']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Menu BONBON</title>
    <link rel="icon" href="images/logo-bonbon.png" type="image/png" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="css/style.css" />
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

    <header class="relative w-full overflow-hidden bg-red-600">
        <img src="images/bannerbonbon.svg" alt="Bonbon Banner" class="w-full h-auto">
    </header>

    <section class="px-4 text-center text-white bg-red-600">
        <h2 class="text-4xl font-bold">Edit Menu</h2>
    </section>

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
            <a href="edit_menu.php?kategori=<?= $key ?>"
                    class="category-link text-white text-lg font-bold <?= $kategori_aktif === $key ? 'active' : '' ?>">
                <?= $label ?>
            </a>
        <?php endforeach; ?>
    </nav>

    <section class="mb-12">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-3 gap-8 py-12 px-40">
                <?php if (empty($menus)): ?>
                    <p class="text-white col-span-full">Belum ada menu di kategori ini.</p>
                <?php else: ?>
                    <?php foreach ($menus as $menu): ?>
                        <div class="bg-white rounded-lg overflow-hidden shadow-lg p-6 transform transition duration-300 hover:scale-105">
                            <img src="images_menu/<?= htmlspecialchars($menu['gambar_produk']) ?>" alt="<?= htmlspecialchars($menu['nama_produk']) ?>" class="w-36 h-48 object-cover mx-auto">
                            <div class="text-left mt-4">
                                <p class="font-semibold text-xl text-black"><?= htmlspecialchars($menu['nama_produk']) ?></p>
                                <p class="text-red-600 font-bold text-base mt-2 border-2 border-red-600 rounded-full inline-block py-1 px-3">
                                    Rp <?= number_format($menu['harga'], 0, ',', '.') ?>
                                </p>
                                <div class="mt-4 flex justify-center gap-2">
                                    <a href="form_edit_menu.php?id=<?= $menu['id_menu'] ?>" class="bg-yellow-400 text-white py-2 px-4 rounded-md hover:scale-105">Edit</a>
                                    <form action="/bonbon/api/controller/aksi_hapus_menu.php" method="POST" onsubmit="return confirm('Apakah kamu yakin ingin menghapus menu ini?');">
                                        <input type="hidden" name="id_menu" value="<?= $menu['id_menu'] ?>">
                                        <button type="submit" class="bg-red-600 text-white py-2 px-4 rounded-md hover:scale-105">Hapus</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <div class="flex justify-center mb-12">
        <a href="form_tambah_menu.php" class="bg-white text-red-600 font-semibold py-4 px-8 rounded-full shadow-xl hover:scale-105">+ Tambah Menu</a>
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
