<?php
define('ACCESS_ALLOWED', true);
require_once __DIR__ . '/../api/config/auth.php';
require_once __DIR__ . '/../api/config/koneksi.php';
require_once __DIR__ . '/../api/config/csrf.php';

if ($_SESSION['role'] !== 'pengunjung') {
    echo "<script>alert('❌ Admin dilarang membuat ulasan'); window.location.href='beranda.php';</script>";
    exit;
}

$is_logged_in = isset($_SESSION['id_user']);

$id_ulasan = $_GET['id'] ?? null;
$id_user = $_SESSION['id_user'];

if (!$id_ulasan) {
    echo "<script>alert('❌ ID ulasan tidak ditemukan'); window.location.href='ulasan.php';</script>";
    exit;
}

$stmt = $conn->prepare("SELECT * FROM rating_ulasan WHERE id_rating_ulasan = ? AND user_id_user = ?");
$stmt->bind_param("ii", $id_ulasan, $id_user);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    echo "<script>alert('❌ Anda tidak memiliki hak mengedit ulasan ini'); window.location.href='ulasan.php';</script>";
    exit;
}

$data = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Ulasan BONBON</title>
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
                
                <?php if (isset($_SESSION['flash_message'])): ?>
                    <div class="mx-auto max-w-2xl mt-4 mb-2 p-4 text-white text-center font-semibold rounded-lg 
                                <?= str_starts_with($_SESSION['flash_message'], '✅') ? 'bg-green-600' : 'bg-red-600' ?>">
                        <?= $_SESSION['flash_message'] ?>
                    </div>
                    <?php unset($_SESSION['flash_message']); ?>
                <?php endif; ?>

                <form class="space-y-6" method="POST" action="../api/controller/aksi_edit_ulasan.php" enctype="multipart/form-data">
                    <input type="hidden" name="id_ulasan" value="<?= $data['id_rating_ulasan'] ?>">
                    <input type="hidden" name="csrf_token_form" value="<?= $_SESSION['csrf_token'] ?>">

                    <div>
                        <label for="rating" class="block text-sm font-medium text-gray-600">Rating</label>
                        <select name="rating" id="rating" class="w-full p-3 border border-gray-300 rounded-md mt-2" required>
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <option value="<?= $i ?>" <?= ($i == $data['rating']) ? 'selected' : '' ?>><?= $i ?></option>
                        <?php endfor; ?>
                        </select>
                    </div>

                    <div>
                        <label for="ulasan" class="block text-sm font-medium text-gray-600">Ulasan</label>
                        <textarea id="ulasan" name="ulasan" class="w-full p-3 border border-gray-300 rounded-md mt-2" required><?= htmlspecialchars($data['ulasan'], ENT_QUOTES, 'UTF-8') ?></textarea>
                    </div>

                    <label class="flex items-center space-x-2">
                        <input type="checkbox" class="form-checkbox text-red-600" required>
                        <span class="text-sm text-gray-700">Saya menyatakan bahwa ulasan ini didasarkan pada pengalaman pribadi saya tentang produk atau layanan dari BONBON, dan saya tidak memiliki hubungan pribadi atau bisnis dengan BONBON atau menerima tawaran insentif atau pembayaran apapun untuk menulis ulasan ini. Saya memahami bahwa BONBON memiliki kebijakan untuk memastikan ulasan yang ditulis adalah asli dan tidak menyesatkan.</span>
                    </label>
                    
                    <div class="flex justify-end space-x-4 mt-2">
                        <button type="submit" class="bg-red-600 text-white font-semibold py-3 px-6 rounded-md hover:bg-red-700">Simpan Perubahan</button>
                        <a href="ulasan.php" class="bg-gray-300 text-black font-semibold py-3 px-6 rounded-md hover:bg-gray-400">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </section>
        
    <!-- Transisi -->
    <div class="w-full overflow-hidden bg-red-600">
        <img src="images/wavebonbonflip.png" alt="Transisi" class="w-full h-auto -mt-1">
    </div>
    
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
