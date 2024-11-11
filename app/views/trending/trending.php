<?php declare(strict_types=1);

// Hàm để gọi API của Spotify và lấy danh sách playlists
function fetchPlaylists($token) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api.spotify.com/v1/me/playlists");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer $token"
    ]);
    $response = curl_exec($ch);
    curl_close($ch);
    $data = json_decode($response, true);
    return $data['items'] ?? [];
}

// Token truy cập Spotify (Cần thay thế bằng token hợp lệ của bạn)
$token = 'YOUR_SPOTIFY_ACCESS_TOKEN';
$playlists = fetchPlaylists($token);
$playlists = [
    [
        'id' => '1',
        'name' => 'Top Hits 2024',
        'tracks' => ['total' => 45],
        'images' => [['url' => 'https://via.placeholder.com/150']]
    ],
    [
        'id' => '2',
        'name' => 'Chill Vibes',
        'tracks' => ['total' => 32],
        'images' => [['url' => 'https://via.placeholder.com/150']]
    ],
    [
        'id' => '3',
        'name' => 'Workout Mix',
        'tracks' => ['total' => 50],
        'images' => [['url' => 'https://via.placeholder.com/150']]
    ],
    [
        'id' => '4',
        'name' => 'Relaxing Instrumentals',
        'tracks' => ['total' => 20],
        'images' => [['url' => 'https://via.placeholder.com/150']]
    ],
    [
        'id' => '5',
        'name' => 'Party Anthems',
        'tracks' => ['total' => 60],
        'images' => [['url' => 'https://via.placeholder.com/150']]
    ]
];
?>

<link rel="stylesheet" href="/public/css/home.css">

<div class="screen-container">
    <div class="library-body">
        <?php if (!empty($playlists)): ?>
            <?php foreach ($playlists as $playlist): ?>
                <div class="playlist-card" onclick="playPlaylist('<?php echo $playlist['id']; ?>')">
                    <?php if (isset($playlist['images'][0]['url'])): ?>
                        <img src="<?php echo $playlist['images'][0]['url']; ?>" class="playlist-image" alt="Playlist-Art">
                    <?php else: ?>
                        <img src="/public/images/default.jpg" class="playlist-image" alt="Default-Art">
                    <?php endif; ?>
                    <p class="playlist-title"><?php echo htmlspecialchars($playlist['name']); ?></p>
                    <p class="playlist-subtitle"><?php echo $playlist['tracks']['total']; ?> Songs</p>
                    <div class="playlist-fade">
                        <svg width="50" height="50" viewBox="0 0 24 24" fill="#E99D72">
                            <circle cx="12" cy="12" r="10"/>
                        </svg>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No playlists found</p>
        <?php endif; ?>
    </div>
</div>

<script>
    // Hàm JavaScript để điều hướng đến trang player
    function playPlaylist(id) {
        window.location.href = '/player.php?id=' + id;
    }
</script>
