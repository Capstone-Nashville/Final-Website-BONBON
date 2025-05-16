<?php
session_start();
define('ACCESS_ALLOWED', true);
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../config/csrf.php';

if (!verify_csrf_token($_POST['csrf_token_form'] ?? '')) {
    $_SESSION['flash_message'] = '⛔ Permintaan tidak valid (token).';
    header("Location: ../../public/login.php");
    exit;
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    $_SESSION['flash_message'] = 'Email dan password harus diisi';
    header("Location: ../../public/login.php");
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['flash_message'] = 'Format email tidak valid';
    header("Location: ../../public/login.php");
    exit;
}

if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
}
if ($_SESSION['login_attempts'] >= 5) {
    $_SESSION['flash_message'] = '🚫 Terlalu banyak percobaan login. Coba lagi nanti.';
    header("Location: ../../public/login.php");
    exit;
}

$query = "SELECT * FROM user WHERE email = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($user = mysqli_fetch_assoc($result)) {
    if (password_verify($password, $user['password'])) {
        $_SESSION['id_user'] = $user['id_user'];
        $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['login_attempts'] = 0;

        $_SESSION['flash_message'] = 'Login berhasil sebagai ' . htmlspecialchars(ucfirst($user['role']), ENT_QUOTES, 'UTF-8');

        if ($user['role'] === 'admin') {
            header("Location: ../../public/dashboard.php");
        } else {
            header("Location: ../../public/beranda.php");
        }
        exit;
    } else {
        $_SESSION['login_attempts']++;
        $_SESSION['flash_message'] = '❌ Password salah';
        header("Location: ../../public/login.php");
        exit;
    }
} else {
    $_SESSION['login_attempts']++;
    $_SESSION['flash_message'] = '❌ Email tidak ditemukan';
    header("Location: ../../public/login.php");
    exit;
}
?>
