<?php declare(strict_types=1);
session_start();
if (empty($_SESSION['username'])){
    header('Location: login');
    exit();
} else {
    header('Location: home');   
    exit();
}   
?>
