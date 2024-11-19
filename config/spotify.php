<?php declare(strict_types=1);
require_once __DIR__ . '/../spotifyAPI/Request.php';
require_once __DIR__ . '/../spotifyAPI/Session.php';
require_once __DIR__ . '/../spotifyAPI/SpotifyWebAPI.php';
require_once __DIR__ . '/../spotifyAPI/SpotifyWebAPIAuthException.php';
require_once __DIR__ . '/../spotifyAPI/SpotifyWebAPIException.php';

$session = new Session(
    '67d1e412d7c94907a2c140fa179a6672',
    'a7a5e2669cb042f9b67f2578f8dd9f21',
    'http://basomusic.local/config/callback.php'
);

$options = [
    'scope' => [
        'user-read-email',
        'user-top-read',
        'playlist-read-private',
        'streaming',
        'playlist-read-collaborative',
        'user-read-playback-state',
        'user-modify-playback-state'
    ],
];

// Chuyển hướng người dùng đến trang xác thực của Spotify
header('Location: ' . $session->getAuthorizeUrl($options));
exit;
?>

