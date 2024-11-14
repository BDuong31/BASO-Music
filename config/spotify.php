<?php declare(strict_types=1);
require_once __DIR__ . '/../spotifyAPI/Request.php';
require_once __DIR__ . '/../spotifyAPI/Session.php';
require_once __DIR__ . '/../spotifyAPI/SpotifyWebAPI.php';
require_once __DIR__ . '/../spotifyAPI/SpotifyWebAPIAuthException.php';
require_once __DIR__ . '/../spotifyAPI/SpotifyWebAPIException.php';

$session = new Session(
    '738e89a131d74ec593c1eb89effe3a44',
    'a55158c4160f4be5b60e7f7a5aa4518c',
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

