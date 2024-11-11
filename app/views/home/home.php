<?php declare(strict_types=1); 
session_start();
require_once('../components/sidebar/index.php');

// Kiểm tra xem token đã có trong session chưa
// if (!isset($_SESSION['token'])) {
//     header("Location: login.php");
//     exit();
// }

//$token = $_SESSION['token'];

// Lấy trang hiện tại từ URL
$page = $_GET['page'] ?? 'library';
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
    <!-- Sidebar -->
    <div class="main-body">
        <?php renderSidebar($token); ?>
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
                case 'library':
                    include('../library/library.php');
                    break;
                case 'account':
                    include('../account/profile.php');
                    break;
                default:
            }
        ?>
    </div>
</body>

</html>