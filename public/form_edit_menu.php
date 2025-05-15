<?php
define('ACCESS_ALLOWED', true);
require_once __DIR__ . '/../api/config/auth.php';
require_once __DIR__ . '/../api/config/koneksi.php';

if ($_SESSION['role'] !== 'admin') {
    echo "<script>alert('❌ Akses hanya untuk admin'); window.location.href='beranda.php';</script>";
    exit;
}

$id = $_GET['id'] ?? null;
if (!$id) {
    $_SESSION['flash_message'] = '❌ ID menu tidak ditemukan';
    header("Location: edit_menu.php");
    exit;
}

$query = "SELECT * FROM menu WHERE id_menu = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$menu = $result->fetch_assoc();

if (!$menu) {
    $_SESSION['flash_message'] = '❌ Menu tidak ditemukan';
    header("Location: edit_menu.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Menu BONBON</title>
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
            <a href="logout.php" class="bg-red-600 text-white px-4 py-2 rounded-lg font-bold hover:bg-red-800">Logout</a>
        </div>
    </nav>

    <!-- Main content -->
    <main class="p-0 w-full">

        <!-- Banner Image -->
        <header class="relative w-full overflow-hidden bg-red-600">
            <img src="images/bannerbonbon.svg" alt="Bonbon Banner" class="w-full h-auto">
        </header>

        <!-- Lokasi Section -->
        <section class="px-4 text-center items-center justify-center text-white bg-red-600">
            <h2 class="text-4xl font-bold">Edit Menu</h2>
        </section>

        <!-- Form Section -->
        <section class="bg-red-600 justify-center w-full">
            <div class="flex justify-center items-center w-full p-8">
                <div class="bg-white p-8 rounded-xl shadow-xl w-full max-w-4xl">

                    <?php if (isset($_SESSION['flash_message'])): ?>
                        <div class="mx-auto max-w-2xl mt-4 mb-2 p-4 text-white text-center font-semibold rounded-lg 
                                    <?= str_starts_with($_SESSION['flash_message'], '✅') ? 'bg-green-600' : 'bg-red-600' ?>">
                            <?= $_SESSION['flash_message'] ?>
                        </div>
                        <?php unset($_SESSION['flash_message']); ?>
                    <?php endif; ?>

                    <form class="space-y-6" method="POST" action="../api/controller/aksi_edit_menu.php" enctype="multipart/form-data">
                        <input type="hidden" name="id_menu" value="<?= $menu['id_menu'] ?>">
                        <input type="hidden" name="gambar_lama" value="<?= $menu['gambar_produk'] ?>">

                        <div>
                            <label for="nama_menu" class="block text-sm font-medium text-gray-600">Nama Menu</label>
                            <input type="text" id="nama_menu" name="nama_menu" value="<?= htmlspecialchars($menu['nama_produk']) ?>" class="w-full p-3 border border-gray-300 rounded-md mt-2" required>
                        </div>

                        <div>
                            <label for="kategori" class="block text-sm font-medium text-gray-600">Kategori</label>
                            <select id="kategori" name="kategori" class="w-full p-3 border border-gray-300 rounded-md mt-2" required>
                                <?php
                                $kategori_list = ['coffee-series', 'waffle-cone', 'float', 'signature', 'sundae', 'tea-series'];
                                foreach ($kategori_list as $kategori) {
                                    $selected = $menu['kategori'] === $kategori ? 'selected' : '';
                                    echo "<option value=\"$kategori\" $selected>" . ucfirst(str_replace('-', ' ', $kategori)) . "</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div>
                            <label for="harga" class="block text-sm font-medium text-gray-600">Harga</label>
                            <input type="number" id="harga" name="harga" value="<?= $menu['harga'] ?>" class="w-full p-3 border border-gray-300 rounded-md mt-2" required>
                        </div>

                        <div>
                            <label for="gambar" class="block text-sm font-medium text-gray-600">Ganti Gambar</label>
                            <input type="file" id="gambar" name="gambar" accept="image/*" class="w-full p-3 border border-gray-300 rounded-md mt-2">
                            <p class="text-sm mt-2 text-gray-500">Gambar saat ini: <?= $menu['gambar_produk'] ?></p>
                        </div>

                        <div class="flex justify-end space-x-4">
                            <button type="submit" class="bg-red-600 text-white font-semibold py-3 px-6 rounded-md hover:bg-red-700">Simpan Perubahan</button>
                            <a href="edit_menu.php" class="bg-gray-300 text-black font-semibold py-3 px-6 rounded-md hover:bg-gray-400">Batal</a>
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
    
</body>

</html>
