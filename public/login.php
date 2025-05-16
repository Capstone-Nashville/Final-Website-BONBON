<?php
session_start();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" href="images/logo-bonbon.png" type="image/png">
    <title>Login | BONBON</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>
<body>

    <div id="content" class="flex h-screen">
        <div class="bg-white flex flex-col justify-center items-center w-1/3 h-full relative">
            <div class="absolute inset-0">
                <img src="images/fotobonbon.svg" alt="BONBON Logo" class="w-full h-full object-cover">
            </div>
        </div>

        <div class="bg-red-600 flex justify-center items-center w-2/3 p-8">
            <div class="bg-white p-8 rounded-xl shadow-xl w-full max-w-md">
                <?php if (isset($_SESSION['flash_message'])): ?>
                    <div class="mb-4 text-red-600 bg-red-200 border border-red-600 rounded-lg p-4">
                        <strong><?php echo $_SESSION['flash_message']; ?></strong>
                    </div>
                    <?php unset($_SESSION['flash_message']); ?>
                <?php endif; ?>

                <div class="mb-6 text-center">
                    <h2 class="text-2xl font-bold text-red-600">Login</h2>
                </div>

                <form class="space-y-4" method="POST" action="/bonbon/api/controller/aksi_login.php">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-600">Email</label>
                        <input type="email" id="email" name="email" class="w-full p-3 border border-gray-300 rounded-md mt-2" placeholder="Masukkan email anda" required>
                    </div>
                    <div class="relative">
                        <label for="password" class="block text-sm font-medium text-gray-600">Kata Sandi</label>
                        <input type="password" id="password" name="password" class="w-full p-3 pr-20 border border-gray-300 rounded-md mt-2" placeholder="Masukkan kata sandi anda" required>
                        <button type="button" onclick="togglePassword('password', this)"
                            class="absolute right-3 top-[63%] transform -translate-y-1/2 text-gray-500 hover:text-red-600">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" width="20">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.522 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.478 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                    <button type="submit" class="w-full bg-red-600 text-white font-semibold py-3 rounded-md hover:bg-red-700">Login</button>
                </form>
                
                <div class="text-center text-sm mt-4">
                    <span class="text-gray-500">Belum punya akun? </span>
                    <a href="register.php" class="text-red-600 hover:underline">Register</a>
                </div>

                <div class="text-center text-sm mt-4">
                    <span class="text-gray-500">Kembali ke </span>
                    <a href="beranda.php" class="text-red-600 hover:underline">Beranda</a>
                </div>
            </div>
        </div>
    </div>

    <script>
    function togglePassword(id, btn) {
        const input = document.getElementById(id);
        const icon = btn.querySelector('svg');

        if (input.type === "password") {
            input.type = "text";
            btn.classList.add('text-red-600');
        } else {
            input.type = "password";
            btn.classList.remove('text-red-600');
        }
    }
    </script>
</body>
</html>
