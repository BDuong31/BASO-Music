<?php declare(strict_types=1); 
session_start();
require_once('../components/sidebar/index.php');

// Kiểm tra xem token đã có trong session chưa
if (!isset($_SESSION['user'])) {
     header("Location: login");
     exit();
}

if ($_SESSION['user']['role_id'] == 1){
    header("Location: admin");
    exit();
}

//$token = $_SESSION['token'];

// Lấy trang hiện tại từ URL
$page = $_GET['page'] ?? 'player';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - BASO Music</title>
    <link rel="icon" href="/public/images/logo.jpg">
    <link rel="stylesheet" href="/public/css/home.css">
</head>

<body>
    <div id="loading">
      <div id="loading-center">
            <div id="loading-circle"></div>
      </div>
   </div>
    <!-- Sidebar -->
    <div class="main-body">
        <?php renderSidebar(); ?>
        <?php
            switch ($page) {
                case 'trending':
                    include('../trending/trending.php');
                    break;
                case 'player':
                    include(__DIR__.'/../player/player.php');
                    break;
                case 'favorites':
                    include('../favorites/favorites.php');
                    break;
                case 'history':
                    include('../history/history.php');
                    break;
                case 'account':
                    include('../account/profile.php');
                    break;
                default:
            }
        ?>
    </div>
    <script>
        // Wait until the DOM is fully loaded
        document.addEventListener('DOMContentLoaded', () => {
                    // Hide the loader
                    const loader = document.getElementById('loading');
                    if (loader) {
                        loader.style.display = 'none';
                    }
                });
    </script>
</body>

</html>