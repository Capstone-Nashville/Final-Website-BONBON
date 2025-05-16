<?php
session_start();
session_unset();
session_destroy();

echo "<script>alert('Anda telah berhasil logout.');</script>";
header("Location: beranda.php");
exit;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" href="images/logo-bonbon.png" type="image/png">
    <title>Logout | BONBON</title>
    
    <!-- Stylesheet -->
    <link rel="stylesheet" href="css/style.css"/>
    <link rel="stylesheet" href="css/loading.css"/>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; }
    </style>
</head>

<body>
    <div id="loading-overlay" class="fixed inset-0 flex items-center justify-center bg-white z-50">
        <div class="spinner-container"></div>
        <img src="images/logo.png" alt="BONBON Logo" class="w-32 h-32 object-contain z-10 ml-4">
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const loadingOverlay = document.getElementById('loading-overlay');
            const content = document.getElementById('content');

            loadingOverlay.style.display = 'none';
            content.style.display = 'block';

            document.querySelectorAll('a').forEach(link => {
                link.addEventListener('click', (e) => {
                const href = link.getAttribute('href');

                if (!href || href.startsWith('#') || href.startsWith('javascript:')) return;

                e.preventDefault();

                loadingOverlay.style.display = 'flex';
                content.style.display = 'none';

                setTimeout(() => {
                    window.location.href = href;
                }, 1000);
            });
            });
        });
    </script>
</body>
</html>