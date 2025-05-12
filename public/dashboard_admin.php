<?php
session_start();

if (!isset($_SESSION['id_user'])) {
    echo "<script>alert('Silakan login terlebih dahulu'); window.location.href='login.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html>
  <head>
    <title>Streamlit App Embed</title>
  </head>
  <body>
    <h2>Streamlit App Embed Example</h2>
    <iframe
      src="https://dashboard-bonbon.streamlit.app/?embed=true&embed_options=show_toolbar&embed_options=dark_theme"
      width="100%"
      height="900"
      style="border: none"
      loading="lazy"
    ></iframe>
  </body>
</html>