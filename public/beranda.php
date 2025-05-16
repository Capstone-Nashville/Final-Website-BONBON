<?php
session_start();
define('ACCESS_ALLOWED', true);
require_once __DIR__ . '/../api/config/koneksi.php';

$is_logged_in = isset($_SESSION['id_user']);
$user_id = $is_logged_in ? $_SESSION['id_user'] : null;
$user_role = $is_logged_in ? ($_SESSION['role'] ?? '') : '';

if (isset($_SESSION['flash_message'])) {
    echo "<script>alert('" . $_SESSION['flash_message'] . "');</script>";
    unset($_SESSION['flash_message']);
}

$promo_result = mysqli_query($conn, "SELECT * FROM promo");

$ulasan_result = mysqli_query($conn, 
    "SELECT ru.rating, ru.ulasan, u.email 
    FROM rating_ulasan ru 
    JOIN user u ON ru.user_id_user = u.id_user 
    WHERE ru.rating >= 4 
    ORDER BY ru.waktu_rating_ulasan DESC 
    LIMIT 8"
);

$user_ulasan_id = null;
if ($is_logged_in) {
    $stmt = mysqli_prepare($conn, "SELECT id_rating_ulasan FROM rating_ulasan WHERE user_id_user = ?");
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $user_ulasan_id);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="icon" href="images/logo-bonbon.png" type="image/png" />
    <title>BONBON - Es Krim, Teh, dan Kopi</title>

    <!-- Stylesheet -->
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/loading.css" />
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap"
        rel="stylesheet"/>
    <style>
    body {
        font-family: 'Poppins', sans-serif;
    }
    .carousel-dot {
        width: 10px;
        height: 10px;
        background-color: white;
        border-radius: 50%;
        margin: 0 5px;
        transition: background-color 0.3s;
        cursor: pointer;
    }
    .active-dot {
        background-color: yellow;
    }
    .active-link {
        outline: 3px solid #d3293b;
        outline-offset: 6px;
        border-radius: 40%;
    }
    </style>

</head>
    <body class="bg-white">
        <div id="loading-overlay" class="fixed inset-0 flex items-center justify-center bg-white z-50">
            <div class="spinner-container"></div>
            <img src="images/logo.png" alt="BONBON Logo" class="w-20 h-20 object-contain ml-4" />
        </div>

        <div id="content" style="display:none;">
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
                        <a href="logout.php" id="btn-logout" class="bg-red-600 text-white px-4 py-2 rounded-lg font-bold hover:bg-red-800">Logout</a>
                    <?php else: ?>
                        <a href="login.php" id="btn-login" class="bg-red-600 text-white px-4 py-2 rounded-lg font-bold hover:bg-red-800">Login</a>
                    <?php endif; ?>
                </div>
            </nav>

            <main class="p-0">
                <header class="relative">
                    <img src="images/bannerbonbon.svg" alt="Bonbon Banner" class="w-full h-auto" />
                </header>

            <section class="flex justify-between items-center bg-white text-red-600 py-10 px-40">
                <div class="w-1/3">
                    <h2 class="text-4xl font-bold">
                        Nikmati Momen dengan Es Krim, Teh, dan Kopi dari BONBON!
                    </h2>
                    <a href="menu.php" class="bg-red-600 text-white font-bold px-6 py-2 rounded-full mt-4 inline-block">
                        Lihat Menu
                    </a>
                </div>
                <div class="w-1/2">
                    <img src="images/menumitra.svg" alt="Header Menu" class="w-full h-auto" />
                </div>
            </section>

            <div class="w-full overflow-hidden bg-red-600">
                <img src="images/wavebonbon.png" alt="Transisi" class="w-full h-auto -mt-1" />
            </div>

            <section class="flex flex-col items-center bg-red-600 text-white py-8 px-40">
                <div class="w-full text-center mb-8">
                    <h2 class="text-3xl font-bold">Jangan Lewatkan Promonya!</h2>
                    <p class="text-xl mt-4">Yuk, cek instagram kami untuk selalu dapatkan informasi terbaru!</p>
                </div>

                <div class="w-full relative overflow-hidden">
                    <div class="flex transition-transform duration-500" id="carousel">
                        <?php while ($row = mysqli_fetch_assoc($promo_result)): ?>
                            <div class="flex-shrink-0 w-full flex items-center justify-center">
                                <a href="<?= htmlspecialchars($row['link_postingan'], ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener noreferrer" 
                                class="flex items-center justify-center w-1/2 mx-auto">
                                    <img src="images_promo/<?= htmlspecialchars($row['gambar_promo']) ?>" alt="Promo" 
                                        class="rounded-lg w-full h-auto object-contain shadow-xl" />
                                </a>
                            </div>
                        <?php endwhile; ?>
                    </div>
                    <button onclick="moveCarousel('prev')" class="absolute left-5 top-1/2 transform -translate-y-1/2 bg-white text-red-600 p-2 rounded-full shadow-lg z-10 hover:bg-red-100" aria-label="Previous Promo">&#10094;</button>
                    <button onclick="moveCarousel('next')" class="absolute right-5 top-1/2 transform -translate-y-1/2 bg-white text-red-600 p-2 rounded-full shadow-lg z-10 hover:bg-red-100" aria-label="Next Promo">&#10095;</button>
                </div>

                <?php
                $total_promo = mysqli_num_rows($promo_result);
                ?>
                <div class="flex justify-center mt-4">
                    <?php for ($i = 0; $i < $total_promo; $i++): ?>
                        <button
                        onclick="moveCarousel(<?= $i ?>)"
                        class="carousel-dot <?= $i === 0 ? 'active-dot' : '' ?>"
                        aria-label="Promo slide <?= $i + 1 ?>"
                        ></button>
                    <?php endfor; ?>
                </div>
            </section>

            <div class="w-full overflow-hidden bg-red-600">
                <img src="images/roundedbonbon.png" alt="Transisi" class="w-full h-auto -mt-1" />
            </div>

            <section class="flex items-center px-40">
                <div class="flex items-center w-full">
                    <img src="images/logo2.svg" alt="Bonbon Icon" class="w-auto h-auto mr-4" />
                </div>
                <div class="w-full text-red-600 py-10">
                    <p class="text-xl leading-relaxed">
                        BONBON hadir untuk memberikan pengalaman unik melalui berbagai pilihan es krim, teh, dan kopi yang dapat dinikmati oleh semua kalangan.
                        Setiap produk kami dibuat dengan bahan berkualitas tinggi dan penuh kasih, agar setiap suapan dan seruput memberikan kepuasan yang tak terlupakan.
                    </p>
                    <a href="franchise.php" class="bg-red-600 text-white hover:bg-red-800 font-bold px-6 py-2 rounded-full mt-4 inline-block">
                        Gabung Franchise BONBON
                    </a>
                </div>
            </section>

            <div class="w-full overflow-hidden bg-red-600">
                <img src="images/roundedbonbonflip.png" alt="Transisi" class="w-full h-auto -mt-1" />
            </div>

            <section class="bg-red-600 text-white text-center">
                <h2 class="text-3xl font-bold py-4">Menu</h2>
                <p class="text-sm mt-4">
                    Nikmati Setiap Momen Manis dengan BONBON Ice Cream, Tea, and Coffee. Temukan Menu Favorit Anda dan Rasakan Kenikmatan!
                </p>
                <section id="daftar-menu" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-3 gap-8 py-8 px-40">
                    <?php
                    $menus = [
                        ['kategori' => 'signature', 'img' => 'summer.svg', 'title' => 'Signature'],
                        ['kategori' => 'float', 'img' => 'mango.svg', 'title' => 'Float'],
                        ['kategori' => 'coffee-series', 'img' => 'boba.svg', 'title' => 'Coffee Series'],
                        ['kategori' => 'tea-series', 'img' => 'tea.svg', 'title' => 'Tea Series'],
                        ['kategori' => 'waffle-cone', 'img' => 'strobericone.svg', 'title' => 'Waffle Cone'],
                        ['kategori' => 'sundae', 'img' => 'stroberisundae.svg', 'title' => 'Sundae'],
                    ];

                    foreach ($menus as $menu) : ?>
                        <a href="menu.php?kategori=<?= $menu['kategori'] ?>#daftar-menu" class="block transform transition duration-300 hover:scale-105">
                            <div class="bg-white rounded-lg overflow-hidden shadow-lg p-4 text-center">
                                <img src="images/<?= $menu['img'] ?>" alt="<?= $menu['title'] ?>" class="w-36 h-48 object-cover mx-auto" />
                                <h3 class="text-xl font-semibold text-red-600 mt-4"><?= $menu['title'] ?></h3>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </section>
            </section>

            <section class="text-center py-20 px-4 relative bg-red-600">
                <h2 class="text-white text-3xl font-bold mb-6">Tanggapan Mereka tentang BONBON</h2>
                <div class="relative overflow-hidden px-20 max-w-screen-lg mx-auto">
                    <div id="testimonial-carousel" class="flex transition-transform duration-500 space-x-6" aria-live="polite">
                        <?php while ($row = mysqli_fetch_assoc($ulasan_result)) : ?>
                            <a href="ulasan.php" class="w-[300px] bg-white p-6 rounded-lg shadow-lg text-left shrink-0" tabindex="0">
                                <h3 class="text-xl font-bold text-red-600">
                                    <?= htmlspecialchars($row['email'], ENT_QUOTES, 'UTF-8') ?>
                                </h3>
                                <div style="color: #ffc107;" class="text-lg mt-2" aria-label="Rating: <?= (int)$row['rating'] ?> out of 5 stars">
                                    <?php
                                    $rating = (int)$row['rating'];
                                    echo str_repeat('★', $rating) . str_repeat('☆', 5 - $rating);
                                    ?>
                                </div>
                                <p class="text-sm text-gray-700 mt-3">
                                    <?= htmlspecialchars($row['ulasan'], ENT_QUOTES, 'UTF-8') ?>
                                </p>
                            </a>
                        <?php endwhile; ?>
                    </div>
                    <button onclick="moveTestimonial('prev')" aria-label="Previous Testimonial" class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-black text-white p-2 rounded-full shadow-lg z-10 hover:bg-red-100">
                        &#10094;
                    </button>
                    <button onclick="moveTestimonial('next')" aria-label="Next Testimonial" class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-black text-white p-2 rounded-full shadow-lg z-10 hover:bg-red-100">
                        &#10095;
                    </button>
                </div>
            </section>

            <?php if ($is_logged_in && $user_role === 'pengunjung' && $user_ulasan_id): ?>
                <form action="form_edit_ulasan.php" method="GET" class="fixed bottom-6 right-10">
                    <input type="hidden" name="id" value="<?= $user_ulasan_id ?>" />
                    <button type="submit" class="bg-white text-red-600 p-4 rounded-full shadow-xl hover:scale-105 transition duration-300">
                        <span class="text-xl font-bold">+ Edit Ulasan</span>
                    </button>
                </form>
            <?php elseif ($is_logged_in && $user_role === 'pengunjung'): ?>
                <form action="form_tambah_ulasan.php" method="POST" class="fixed bottom-6 right-10">
                    <button type="submit" class="bg-white text-red-600 p-4 rounded-full shadow-2xl hover:scale-105 transition duration-300">
                        <span class="text-xl font-bold">+ Tambah Ulasan</span>
                    </button>
                </form>
            <?php else: ?>
                <form action="form_tambah_ulasan.php" method="POST" class="fixed bottom-6 right-10">
                    <button type="submit" class="bg-white text-red-600 p-4 rounded-full shadow-2xl hover:scale-105 transition duration-300">
                        <span class="text-xl font-bold">+ Tambah Ulasan</span>
                    </button>
                </form>
            <?php endif; ?>

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
            </main>
        </div>

        <script>
            let currentIndex = 0;

            function moveCarousel(direction) {
                const carousel = document.getElementById('carousel');
                const slides = carousel.children.length;
                const slideWidth = carousel.children[0].offsetWidth;

                if (direction === 'next') {
                    currentIndex = (currentIndex + 1) % slides;
                } else if (direction === 'prev') {
                    currentIndex = (currentIndex - 1 + slides) % slides;
                } else if (typeof direction === 'number') {
                    currentIndex = direction;
                }

                const offset = -currentIndex * slideWidth;
                carousel.style.transform = `translateX(${offset}px)`;
                updateCarouselDots();
            }

            function updateCarouselDots() {
                document.querySelectorAll('.carousel-dot').forEach((dot, i) => {
                    dot.classList.toggle('active-dot', i === currentIndex);
                });
            }

            updateCarouselDots();

            let testimonialIndex = 0;

            function moveTestimonial(direction) {
                const carousel = document.getElementById('testimonial-carousel');
                const cards = carousel.children;
                const cardWidth = cards[0].offsetWidth + 24; 
                const maxIndex = cards.length - 1;

                if (direction === 'next') {
                    testimonialIndex = Math.min(testimonialIndex + 1, maxIndex);
                } else if (direction === 'prev') {
                    testimonialIndex = Math.max(testimonialIndex - 1, 0);
                }

                const offset = -testimonialIndex * cardWidth;
                carousel.style.transform = `translateX(${offset}px)`;
            }

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

            const loadingOverlay = document.getElementById('loading-overlay');
            const content = document.getElementById('content');

            function showLoadingAndRedirect(url) {
                loadingOverlay.style.display = 'flex';
                loadingOverlay.classList.add('flex');
                content.style.display = 'none';

                setTimeout(() => {
                    window.location.href = url;
                }, 1000);
            }

            const btnLogin = document.getElementById('btn-login');
            if (btnLogin) {
                btnLogin.addEventListener('click', function(e) {
                    e.preventDefault();
                    showLoadingAndRedirect(this.href);
                });
            }

            const btnLogout = document.getElementById('btn-logout');
            if (btnLogout) {
                btnLogout.addEventListener('click', function(e) {
                    e.preventDefault();
                    showLoadingAndRedirect(this.href);
                });
            }

            document.addEventListener('DOMContentLoaded', function () {
                console.log('✅ DOMContentLoaded triggered');

                const loadingOverlay = document.getElementById('loading-overlay');
                const content = document.getElementById('content');

                console.log('⏳ Menampilkan loading overlay...');
                setTimeout(() => {
                    console.log('✅ Loading selesai, menampilkan konten...');
                    loadingOverlay.style.display = 'none';
                    loadingOverlay.classList.remove('flex');
                    content.style.display = 'block';
                }, 1000);
            });
        </script>
    </body>
</html>