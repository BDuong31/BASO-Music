<?php declare(strict_types=1);
require_once __DIR__ . '/../spotifyAPI/Request.php';
require_once __DIR__ . '/../spotifyAPI/Session.php';
require_once __DIR__ . '/../spotifyAPI/SpotifyWebAPI.php';
require_once __DIR__ . '/../spotifyAPI/SpotifyWebAPIAuthException.php';
require_once __DIR__ . '/../spotifyAPI/SpotifyWebAPIException.php';

session_start();

$session = new Session(
    '67d1e412d7c94907a2c140fa179a6672',
    'a7a5e2669cb042f9b67f2578f8dd9f21',
    'http://basomusic.local/config/callback.php'
);

$api = new SpotifyWebAPI();

if (isset($_GET['code'])) {
    // Yêu cầu mã truy cập
    $session->requestAccessToken($_GET['code']);
    $accessToken = $session->getAccessToken();
    $refreshToken = $session->getRefreshToken();

    // Lưu access token vào session
    $_SESSION['access_token'] = $accessToken;

    // Lưu refresh token vào cookie (thời hạn 1 năm)
    setcookie('spotify_refresh_token', $refreshToken, time() + (365 * 24 * 60 * 60), "/", "", false, true);

    // Chuyển hướng tới trang home
    header('Location: /home');
    exit;
} else {
    echo 'Authorization failed!';
}
?>
