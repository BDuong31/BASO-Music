<?php declare(strict_types=1);
ini_set('session.save_path', '/home/weyyhewu/tmp');
    session_start();
    session_destroy();
    header('location: login');
    exit();
?>