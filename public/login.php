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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Register | BONBON</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-md">
        <!-- Tab Navigation -->
        <div class="flex justify-between mb-6">
            <button id="loginTab" class="text-red-600 font-semibold border-b-2 border-transparent hover:border-red-600 w-1/2 py-2">Login</button>
            <button id="registerTab" class="text-gray-500 font-semibold border-b-2 border-transparent hover:border-red-600 w-1/2 py-2">Register</button>
        </div>

        <!-- Login Form -->
        <form id="loginForm" action="/bonbon/api/controller/aksi_login.php" method="post" class="space-y-4">
            <div> 
                <label for="loginEmail" class="block text-sm font-medium text-gray-600">Email</label>
                <input type="email" id="loginEmail" name="loginEmail" class="w-full p-3 border border-gray-300 rounded-md mt-2" placeholder="Enter your email" required>
            </div>
            <div>
                <label for="loginPassword" class="block text-sm font-medium text-gray-600">Password</label>
                <input type="password" id="loginPassword" name="loginPassword" class="w-full p-3 border border-gray-300 rounded-md mt-2" placeholder="Enter your password" required>
            </div>
            <div class="flex items-center justify-between">
                <button type="submit" class="w-full bg-red-600 text-white font-semibold py-3 rounded-md hover:bg-red-700">Login</button>
            </div>
            <div class="text-center text-sm">
                <span class="text-gray-500">Don't have an account? </span>
                <button type="button" id="goToRegister" class="text-red-600 hover:underline">Sign up</button>
            </div>
        </form>

        <!-- Register Form -->
        <form id="registerForm" action="/bonbon/api/controller/aksi_register.php" method="post" class="space-y-4 hidden">
            <div>
                <label for="registerName" class="block text-sm font-medium text-gray-600">Full Name</label>
                <input type="text" id="registerName" name="registerName" class="w-full p-3 border border-gray-300 rounded-md mt-2" placeholder="Enter your full name" required>
            </div>
            <div>
                <label for="registerEmail" class="block text-sm font-medium text-gray-600">Email</label>
                <input type="email" id="registerEmail" name="registerEmail" class="w-full p-3 border border-gray-300 rounded-md mt-2" placeholder="Enter your email" required>
            </div>
            <div>
                <label for="registerPassword" class="block text-sm font-medium text-gray-600">Password</label>
                <input type="password" id="registerPassword" name="registerPassword" class="w-full p-3 border border-gray-300 rounded-md mt-2" placeholder="Enter your password" required>
            </div>
            <div>
                <label for="registerConfirmPassword" class="block text-sm font-medium text-gray-600">Confirm Password</label>
                <input type="password" id="registerConfirmPassword" name="registerConfirmPassword" class="w-full p-3 border border-gray-300 rounded-md mt-2" placeholder="Confirm your password" required>
            </div>
            <div class="flex items-center justify-between">
                <button type="submit" class="w-full bg-red-600 text-white font-semibold py-3 rounded-md hover:bg-red-700">Register</button>
            </div>
            <div class="text-center text-sm">
                <span class="text-gray-500">Already have an account? </span>
                <button type="button" id="goToLogin" class="text-red-600 hover:underline">Login</button>
            </div>
        </form>
    </div>

    <script>
        // Switch between login and register form
        document.getElementById('goToRegister').addEventListener('click', function () {
            document.getElementById('loginForm').classList.add('hidden');
            document.getElementById('registerForm').classList.remove('hidden');
            document.getElementById('loginTab').classList.remove('text-red-600');
            document.getElementById('loginTab').classList.add('text-gray-500');
            document.getElementById('registerTab').classList.add('text-red-600');
            document.getElementById('registerTab').classList.remove('text-gray-500');
        });

        document.getElementById('goToLogin').addEventListener('click', function () {
            document.getElementById('registerForm').classList.add('hidden');
            document.getElementById('loginForm').classList.remove('hidden');
            document.getElementById('registerTab').classList.remove('text-red-600');
            document.getElementById('registerTab').classList.add('text-gray-500');
            document.getElementById('loginTab').classList.add('text-red-600');
            document.getElementById('loginTab').classList.remove('text-gray-500');
        });
    </script>
</body>
</html>
