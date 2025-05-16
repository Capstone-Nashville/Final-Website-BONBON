<?php
session_start();
define('ACCESS_ALLOWED', true);
require_once __DIR__ . '/../api/config/koneksi.php';

$is_logged_in = isset($_SESSION['id_user']);

if (isset($_SESSION['flash_message'])) {
    echo "<script>alert('" . $_SESSION['flash_message'] . "');</script>";
    unset($_SESSION['flash_message']);
}

$promo = mysqli_query($conn, "SELECT * FROM promo");

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
    <link rel="icon" href="images/logo-bonbon.png" type="image/png">
    <title>BONBON - Es Krim, Teh, dan Kopi</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
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
        }

        .active-dot {
            background-color: yellow;
        }

        .active-link {
            outline: 3px solid #d3293b; /* Outline warna merah */
            outline-offset: 6px;
            border-radius: 40%;
        }
    </style>
</head>

<body class="bg-white">

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
                <a href="logout.php" class="bg-red-600 text-white px-4 py-2 rounded-lg font-bold hover:bg-red-800">Logout</a>
            <?php else: ?>
                <a href="login.php" class="bg-red-600 text-white px-4 py-2 rounded-lg font-bold hover:bg-red-800">Login</a>
            <?php endif; ?>        
        </div>
    </nav>

    <main class="p-0">
        <header class="relative">
            <img src="images/bannerbonbon.svg" alt="Bonbon Banner" class="w-full h-auto">
        </header>

        <section class="flex justify-between items-center bg-white text-red-600 py-10 px-40">
            <div class="w-1/3">
                <h2 class="text-4xl font-bold">Nikmati Momen dengan Es Krim, Teh, dan Kopi dari BONBON!</h2>
                <a href="menu.php" class="bg-red-600 text-white font-bold px-6 py-2 rounded-full mt-4 inline-block">Lihat Menu</a>
            </div>
            <div class="w-1/2">
                <img src="images/menumitra.svg" alt="Header Menu" class="w-full h-auto">
            </div>
        </section>

        <div class="w-full overflow-hidden bg-red-600">
            <img src="images/wavebonbon.png" alt="Transisi" class="w-full h-auto -mt-1">
        </div>

        <section class="flex flex-col items-center bg-red-600 text-white py-8 px-40 ">
            <div class="w-full text-center mb-8">
                <h2 class="text-3xl font-bold">Jangan Lewatkan Promonya!</h2>
                <p class="text-xl mt-4">Yuk, cek instagram kami untuk selalu dapatkan informasi terbaru!</p>
            </div>

            <div class="w-full relative overflow-hidden">
                <div class="flex transition-transform duration-500" id="carousel">
                    <?php while ($row = mysqli_fetch_assoc($promo)): ?>
                    <div class="flex-shrink-0 w-full flex justify-center">
                    <a href="<?= htmlspecialchars($row['link_postingan'], ENT_QUOTES, 'UTF-8') ?>" target="_blank">
                        <img src="images_promo/<?= htmlspecialchars($row['gambar_promo'], ENT_QUOTES, 'UTF-8') ?>" alt="Promo" class="rounded-lg h-60 w-60 object-contain shadow-xl">
                    </a>
                    </div>
                    <?php endwhile; ?>
                </div>
                <button onclick="moveCarousel('prev')" class="absolute left-5 top-1/2 transform -translate-y-1/2 bg-white text-red-600 p-2 rounded-full shadow-lg z-10 hover:bg-red-100">&#10094;</button>
                <button onclick="moveCarousel('next')" class="absolute right-5 top-1/2 transform -translate-y-1/2 bg-white text-red-600 p-2 rounded-full shadow-lg z-10 hover:bg-red-100">&#10095;</button>
            </div>
            <?php
            $promo_result = mysqli_query($conn, "SELECT COUNT(*) as total FROM promo");
            $total = mysqli_fetch_assoc($promo_result)['total'];
            ?>
            <div class="flex justify-center mt-4">
                <?php for ($i = 0; $i < $total; $i++): ?>
                <button onclick="moveCarousel(<?= $i ?>)" class="carousel-dot <?= $i === 0 ? 'active-dot' : '' ?>"></button>
                <?php endfor; ?>
            </div>
        </section>

        <div class="w-full overflow-hidden bg-red-600">
            <img src="images/roundedbonbon.png" alt="Transisi" class="w-full h-auto -mt-1">
        </div>

        <section class="flex items-center px-40">
            <!-- Icon and Title Section -->
            <div class="flex items-center w-full">
                <img src="images/logo2.svg" alt="Bonbon Icon" class="w-auto h-auto mr-4">
            </div>

            <div class="w-full text-red-600 py-10">
                <p class="text-xl leading-relaxed">
                    BONBON hadir untuk memberikan pengalaman unik melalui berbagai pilihan es krim, teh, dan kopi yang dapat dinikmati oleh semua kalangan. Setiap produk kami dibuat dengan bahan berkualitas tinggi dan penuh kasih, agar setiap suapan dan seruput memberikan kepuasan yang tak terlupakan.
                </p>
                <a href="franchise.php" class="bg-red-600 text-white  hover:bg-red-800 font-bold px-6 py-2 rounded-full mt-4 inline-block">Gabung Franchise BONBON</a>
            </div>
        </section>

        <div class="w-full overflow-hidden bg-red-600">
            <img src="images/roundedbonbonflip.png" alt="Transisi" class="w-full h-auto -mt-1">
        </div>

    <section class="bg-red-600 text-white text-center">
        <h2 class="text-3xl font-bold py-4">Menu</h2>
        <p class="text-sm mt-4">Nikmati Setiap Momen Manis dengan Bonbon Ice Cream, Tea, and Coffee. Temukan Menu Favorit Anda dan Rasakan Kenikmatan!</p>
        <section class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-3 gap-8 py-8 px-40">
            <a href="menu.php?kategori=signature" class="block transform transition duration-300 hover:scale-105">
                <div class="bg-white rounded-lg overflow-hidden shadow-lg p-4 text-center">
                <img src="images/summer.svg" alt="Signature" class="w-36 h-48 object-cover mx-auto">
                <h3 class="text-xl font-semibold text-red-600 mt-4">Signature</h3>
                </div>
            </a>
            <a href="menu.php?kategori=float" class="block transform transition duration-300 hover:scale-105">
                <div class="bg-white rounded-lg overflow-hidden shadow-lg p-4 text-center">
                <img src="images/mango.svg" alt="Signature" class="w-36 h-48 object-cover mx-auto">
                <h3 class="text-xl font-semibold text-red-600 mt-4">Float</h3>
                </div>
            </a>
            <a href="menu.php?kategori=coffee-series" class="block transform transition duration-300 hover:scale-105">
                <div class="bg-white rounded-lg overflow-hidden shadow-lg p-4 text-center">
                <img src="images/boba.svg" alt="Signature" class="w-36 h-48 object-cover mx-auto">
                <h3 class="text-xl font-semibold text-red-600 mt-4">Coffee Series</h3>
                </div>
            </a>
            <a href="menu.php?kategori=tea-series" class="block transform transition duration-300 hover:scale-105">
                <div class="bg-white rounded-lg overflow-hidden shadow-lg p-4 text-center">
                <img src="images/tea.svg" alt="Signature" class="w-36 h-48 object-cover mx-auto">
                <h3 class="text-xl font-semibold text-red-600 mt-4">Tea Series</h3>
                </div>
            </a>
            <a href="menu.php?kategori=waffle-cone" class="block transform transition duration-300 hover:scale-105">
                <div class="bg-white rounded-lg overflow-hidden shadow-lg p-4 text-center">
                <img src="images/strobericone.svg" alt="Signature" class="w-36 h-48 object-cover mx-auto">
                <h3 class="text-xl font-semibold text-red-600 mt-4">Waffle Cone</h3>
                </div>
            </a>
            <a href="menu.php?kategori=sundae" class="block transform transition duration-300 hover:scale-105">
                <div class="bg-white rounded-lg overflow-hidden shadow-lg p-4 text-center">
                <img src="images/stroberisundae.svg" alt="Signature" class="w-36 h-48 object-cover mx-auto">
                <h3 class="text-xl font-semibold text-red-600 mt-4">Sundae</h3>
                </div>
            </a>
    </section>

    <section class="text-center py-20 px-4 relative bg-red-600">
        <h2 class="text-white text-3xl font-bold mb-6">Tanggapan Mereka tentang BONBON</h2>
        <div class="relative overflow-hidden px-20 max-w-screen-lg mx-auto">
            <div id="testimonial-carousel" class="flex transition-transform duration-500 space-x-6">
                <?php while ($row = mysqli_fetch_assoc($ulasan_result)): ?>
                    <a href="ulasan.php" class="w-[300px] bg-white p-6 rounded-lg shadow-lg text-left shrink-0">
                        <h3 class="text-xl font-bold text-red-600">
                            <?= htmlspecialchars($row['email'], ENT_QUOTES, 'UTF-8') ?>
                        </h3>
                        <div style="color: #ffc107;" class="text-lg mt-2">
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
            <button onclick="moveTestimonial('prev')" class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-black text-white p-2 rounded-full shadow-lg z-10 hover:bg-red-100">
            &#10094;
            </button>
            <button onclick="moveTestimonial('next')" class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-black text-white p-2 rounded-full shadow-lg z-10 hover:bg-red-100">
            &#10095;
            </button>
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
    <?php else: ?>
        <form action="form_tambah_ulasan.php" method="POST" class="fixed bottom-6 right-10">
            <button type="submit" class="bg-white text-red-600 p-4 rounded-full shadow-lg hover:scale-105 transition duration-300">
                <span class="text-xl font-bold">+ Tambah Ulasan</span>
            </button>
        </form>
    <?php endif; ?>

    <div class="w-full overflow-hidden bg-red-600">
        <img src="images/wavebonbonflip.png" alt="Transisi" class="w-full h-auto -mt-1">
    </div>

    <footer class="bg-white text-black py-6 px-32">
        <div class="max-w-screen-xl mx-auto flex flex-col md:flex-row justify-between items-center md:items-start gap-8 border-b border-gray-200 pb-6">
            <div class="flex items-center gap-6">
                <img src="images/logo-bonbon.png" alt="BONBON Logo" class="w-24 h-24 object-contain">
                <div class="text-left text-sm">
                    <p class="font-semibold">PT. Fren & Co Group</p>
                    <p>Jl. Sirad Salman No. 6A, Air Hitam,</p>
                    <p>Samarinda Ulu, 75124</p>
                </div>
            </div>
        
            <div class="text-center md:text-right">
                <p class="text-sm font-semibold mb-2">Ikuti Kami</p>
                <div class="flex justify-center md:justify-end gap-4">

                    <a href="https://www.instagram.com/bonbon.smr?utm_source=ig_web_button_share_sheet&igsh=ZDNlZDc0MzIxNw==" target="_blank">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor" class="w-12 h-12 text-red-600 hover:text-red-800 transition">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M7.5 3h9A4.5 4.5 0 0121 7.5v9A4.5 4.5 0 0116.5 21h-9A4.5 4.5 0 013 16.5v-9A4.5 4.5 0 017.5 3z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11.25a3.75 3.75 0 11-6 0 3.75 3.75 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 6.75h.008v.008h-.008z" />
                        </svg>
                    </a>

                    <a href="https://www.tiktok.com/@bonbon.smr?is_from_webapp=1&sender_device=pc" target="_blank">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" class="w-12 h-12 text-red-600 fill-current">
                            <path d="M41,15.4c-3.1,0-6-1-8.4-2.7v14.1c0,7.3-5.9,13.2-13.2,13.2S6.2,34.1,6.2,26.8s5.9-13.2,13.2-13.2c1,0,2,.1,3,.5v5.5
                            c-1-.5-2-.7-3-.7c-4.3,0-7.8,3.5-7.8,7.8s3.5,7.8,7.8,7.8s7.8-3.5,7.8-7.8V4h5.6c0.2,3.1,1.9,5.9,4.6,7.4C37.8,13.6,39.4,14.3,41,15.4z"/>
                        </svg>
                    </a>

                    <a href="tel:+6282155358684" target="_blank">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" class="w-12 h-12 text-red-600 fill-current">
                            <path d="M16.003 3C9.383 3 4 8.383 4 15.003c0 2.522.748 4.867 2.021 6.83L4 29l7.42-2.02A11.94 11.94 0 0016.003 27C22.623 27 28 21.618 28 15S22.623 3 16.003 3zm0 2c5.513 0 10 4.486 10 10 0 5.513-4.487 10-10 10a9.94 9.94 0 01-5.076-1.378l-.356-.215-4.434 1.208 1.218-4.366-.224-.36A9.94 9.94 0 016.003 15c0-5.514 4.486-10 10-10zm4.59 5.62c-.31-.154-1.826-.9-2.11-.996-.283-.095-.49-.143-.697.154-.208.296-.797.997-.978 1.202-.18.207-.36.233-.67.078-.31-.156-1.31-.483-2.49-1.54-.92-.82-1.54-1.83-1.72-2.14-.18-.31-.02-.48.135-.634.14-.14.31-.36.467-.54.154-.18.207-.31.31-.517.103-.208.05-.39-.026-.543-.077-.154-.697-1.678-.954-2.31-.25-.62-.5-.537-.697-.547h-.596c-.206 0-.538.077-.82.38-.282.31-1.074 1.05-1.074 2.55s1.1 2.96 1.253 3.164c.154.207 2.14 3.27 5.186 4.59.724.312 1.29.498 1.733.637.728.232 1.39.2 1.916.12.585-.09 1.82-.744 2.08-1.464.256-.72.256-1.336.18-1.464-.078-.128-.28-.207-.585-.36z"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <div class="text-center text-xs text-gray-600 mt-4">
            &copy; 2025 – BONBON. ALL RIGHTS RESERVED
        </div>
    </footer>
    </main>

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
            } else {
                currentIndex = direction;
            }

            const offset = -currentIndex * slideWidth;
            carousel.style.transform = `translateX(${offset}px)`;

            updateCarouselDots();
        }

        function updateCarouselDots() {
            const dots = document.querySelectorAll('.carousel-dot');
            dots.forEach((dot, index) => {
                if (index === currentIndex) {
                    dot.classList.add('active-dot');
                } else {
                    dot.classList.remove('active-dot');
                }
            });
        }

        updateCarouselDots();

        let testimonialIndex = 0;
    
        function moveTestimonial(direction) {
        const carousel = document.getElementById('testimonial-carousel');
        const cards = carousel.children;
        const cardWidth = cards[0].offsetWidth + 24; // +gap-6 (24px)
        const maxIndex = cards.length - 1;
    
        if (direction === 'next') {
            testimonialIndex = Math.min(testimonialIndex + 1, maxIndex);
        } else {
            testimonialIndex = Math.max(testimonialIndex - 1, 0);
        }
    
        const offset = -testimonialIndex * cardWidth;
        carousel.style.transform = `translateX(${offset}px)`;
        }

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
            document.getElementById('Ulasan').classList.add('active-link');
        }
    </script>
</body>
</html>