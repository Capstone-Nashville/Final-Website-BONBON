<?php
define('ACCESS_ALLOWED', true);

$koneksi_path = realpath(__DIR__ . '/../api/config/koneksi.php');
if ($koneksi_path) {
    require_once $koneksi_path;
} else {
    die("<script>alert('Koneksi ke database gagal!');</script>");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" href="images/logo-bonbon.png" type="image/png">
    <title>Login | BONBON</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>
<body>
    <div class="flex h-screen">

        <!-- Left Section -->
        <div class="bg-white flex flex-col justify-center items-center w-1/3 h-full relative ">
            <div class="absolute inset-0">
                <img src="images/fotobonbon.svg" alt="BONBON Logo" class="w-full h-full object-cover">
            </div>
        </div>

        <!-- Right Section (Login Form) -->
        <div class="bg-red-600 flex justify-center items-center w-2/3 p-8">
            <div class="bg-white p-8 rounded-xl shadow-xl w-full max-w-md">
                <div class="mb-6 text-center">
                    <h2 class="text-2xl font-bold text-red-600">Login</h2>
                </div>
                <form class="space-y-4" action="/bonbon/api/controller/aksi_login.php" method="post">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-600">Email</label>
                        <input type="email" id="email" name="email" class="w-full p-3 border border-gray-300 rounded-md mt-2" placeholder="Masukkan email anda" required>
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-600">Kata Sandi</label>
                        <input type="password" id="password" name="password" class="w-full p-3 border border-gray-300 rounded-md mt-2" placeholder="Masukkan password anda" required>
                    </div>
                    <button type="submit" class="w-full bg-red-600 text-white font-semibold py-3 rounded-md hover:bg-red-700">Login</button>
                </form>
                <div class="text-center text-sm mt-4">
                    <span class="text-gray-500">Belum punya akun? </span>
                    <a href="register.php" class="text-red-600 hover:underline">Register</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
