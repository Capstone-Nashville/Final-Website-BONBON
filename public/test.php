<?php
// define('ACCESS_ALLOWED', true);

// echo "<h3>Debug Path</h3>";
// echo "__DIR__: " . __DIR__ . "<br>"; // C:\xampp\htdocs\bonbon\public
// echo "Real Path: " . realpath(__DIR__ . '/../api/config/koneksi.php') . "<br>";

// $possible_paths = [
//     '/../api/config/koneksi.php',
//     '/../../api/config/koneksi.php'
// ];

// require_once __DIR__ . '/../api/config/koneksi.php';
// echo "Koneksi berhasil!";

// // ngecek cocok atau gak cocok hash
// $original = 'ivocadou';
// $hash_from_db = '$2y$10$EuhnK/1XSPNiz3UfXICnI.6OSKS6j498BPDHOJAC2aAQR1X6t6V9u'; // ambil dari database

// if (password_verify($original, $hash_from_db)) {
//     echo "✔ Cocok";
// } else {
//     echo "❌ Tidak cocok";
// }

// // bikin password hash
// echo password_hash('ivocadou', PASSWORD_DEFAULT);

?>

<!DOCTYPE html>
<html>
  <head>
    <title>Google Maps Embed</title>
  </head>
  <body>
    <h2>Google Maps Embed Example</h2>
    <iframe
      src="https://dashboard-bonbon.streamlit.app/"
      width="600"   
      height="450"
      style="border: 0"
      allowfullscreen=""
      loading="lazy"
    ></iframe>
  </body>
</html>