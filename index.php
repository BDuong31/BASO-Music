<?php declare(strict_types=1);
session_start();
if (empty($_SESSION['username'])){
    header('Location: /app/views/auth/login.php');
    exit();
} else {
    header('Location: /app/views/home/home.php');
    exit();
}   
?>
