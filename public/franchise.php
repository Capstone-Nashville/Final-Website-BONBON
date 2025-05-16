<?php
session_start();
$is_logged_in = isset($_SESSION['id_user']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="icon" href="images/logo-bonbon.png" type="image/png" />
    <title>Franchisee BONBON</title>
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/loading.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet" />
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
    <div id="loading-overlay" style="display:none;" class="fixed inset-0 flex items-center justify-center bg-white z-50">
        <div class="spinner-container"></div>
        <img src="images/logo.png" alt="BONBON Logo" class="w-20 h-20 object-contain ml-4" />
    </div>

    <div id="content">
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
                <img src="images/bannerbonbon.svg" alt="Bonbon Banner" class="w-full h-auto" />
            </header>

            <section class="relative bg-white text-red-600 px-32 py-16">
                <div class="flex justify-between items-center">
                    <div class="w-60 overflow-hidden rounded-xl">
                        <img src="images/owner.jpg" alt="Foto Owner BONBON" class="w-full h-auto object-cover" />
                    </div>
                    <div class="w-2/3 text-left px-8">
                        <h1 class="text-4xl md:text-4xl font-bold mb-4">Bergabunglah Menjadi Franchisee BONBON!</h1>
                        <p class="text-lg md:text-xl">
                            BONBON hadir untuk memberikan pengalaman tak terlupakan melalui berbagai pilihan es krim, teh, dan kopi terbaik yang menggabungkan rasa manis, segar, dan nikmat dalam satu tempat.
                            BONBON menawarkan menu, mulai dari es krim klasik hingga varian kopi kekinian, semua disajikan dengan cita rasa yang telah disesuaikan dengan selera masyarakat Indonesia.
                        </p>
                    </div>
                </div>
            </section>

            <div class="w-full overflow-hidden bg-red-600">
                <img src="images/wavebonbon.png" alt="Transisi" class="w-full h-auto -mt-1" />
            </div>

            <section id="keuntungan" class="bg-red-600 py-16 px-8 md:px-32">
                <h2 class="text-3xl font-semibold text-center text-white mb-12">Keuntungan Menjadi Franchise BONBON</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div class="bg-white shadow-lg rounded-lg p-6 text-center">
                        <div class="mb-4">
                            <img src="images/team.png" alt="Manajemen" class="w-32 h-32 mx-auto" />
                        </div>
                        <h3 class="text-xl font-semibold text-red-600 mb-2">Pelatihan dan Manajemen Terstandarisasi</h3>
                        <p class="text-sm text-gray-700">
                            Kami menyediakan pelatihan profesional bagi karyawan serta sistem manajemen dan tata kelola usaha yang telah terverifikasi dan distandarisasi.
                            Ini mencakup pengelolaan stok, pelayanan pelanggan, hingga pencatatan keuangan.
                        </p>
                    </div>

                    <div class="bg-white shadow-lg rounded-lg p-6 text-center">
                        <div class="mb-4">
                            <img src="images/consulting.png" alt="Konsultasi Gratis" class="w-32 h-32 mx-auto" />
                        </div>
                        <h3 class="text-xl font-semibold text-red-600 mb-2">Konsultasi Gratis dan Pendampingan Bisnis</h3>
                        <p class="text-sm text-gray-700">
                            Tim BONBON menyediakan konsultasi gratis yang bisa diakses kapan saja.
                            Mulai dari strategi pemasaran, manajemen usaha, hingga troubleshooting operasional.
                        </p>
                    </div>

                    <!-- Card 3 -->
                    <div class="bg-white shadow-lg rounded-lg p-6 text-center">
                        <div class="mb-4">
                            <img src="images/social-media.png" alt="Promosi Digital" class="w-32 h-32 mx-auto" />
                        </div>
                        <h3 class="text-xl font-semibold text-red-600 mb-2">Pengelolaan Media Sosial untuk Promosi Digital</h3>
                        <p class="text-sm text-gray-700">
                            Kami juga membantu franchisee dalam pengelolaan media sosial, termasuk pelatihan pembuatan konten dan strategi promosi digital.
                        </p>
                    </div>
                </div>
            </section>

            <div class="w-full overflow-hidden bg-red-600">
                <img src="images/roundedbonbon.png" alt="Transisi" class="w-full h-auto -mt-1" />
            </div>

            <section class="py-4 px-20 bg-white text-center">
                <div class="flex justify-between items-center">
                    <div class="w-1/3 py-1 px-6">
                        <img src="images/headermenu.svg" alt="Header Menu" class="w-full h-auto" />
                    </div>
                    <div class="w-2/3 text-left">
                        <h2 class="text-3xl font-semibold text-red-600 mb-6">Bergabunglah Sekarang dan Mulai Bisnis Anda!</h2>
                        <p class="text-lg mb-8 text-red-600">
                            Jangan lewatkan kesempatan untuk bekerja sama dengan BONBON, menjadi franchisee kami dan nikmati keuntungan yang luar biasa.
                        </p>
                        <a href="https://wa.me/6282155358684" id= "contact" class="bg-red-600 text-white font-bold px-8 py-3 rounded-full hover:bg-red-800 transition">
                            Daftar Sekarang
                        </a>
                    </div>
                </div>
            </section>

            <div class="w-full overflow-hidden bg-red-600">
                <img src="images/roundedbonbonflip.png" alt="Transisi" class="w-full h-auto -mt-1" />
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
        </main>
    </div>

    <script>
        const loadingOverlay = document.getElementById('loading-overlay');
        const content = document.getElementById('content');

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

        document.querySelector('#contact').addEventListener('click', function(e) {
            e.preventDefault();
            loadingOverlay.style.display = 'flex';
            content.style.display = 'none'; 

            const url = this.href;
            setTimeout(() => {
                window.location.href = url; 
            }, 1000);
            });

        window.addEventListener('pageshow', (event) => {
            if (event.persisted) {
                loadingOverlay.style.display = 'none';
                loadingOverlay.classList.remove('flex');
                content.style.display = 'block';
            } else {
                loadingOverlay.style.display = 'flex';
                loadingOverlay.classList.add('flex');
                content.style.display = 'none';

                setTimeout(() => {
                    loadingOverlay.style.display = 'none';
                    loadingOverlay.classList.remove('flex');
                    content.style.display = 'block';
                }, 1000);
            }
        });
    </script>
</body>
</html>
