<?php declare(strict_types=1);
require_once(__DIR__ . '/../../controllers/FavoriteController.php');
require_once(__DIR__ . '/../../controllers/SongController.php');


// Lấy danh sách bài hát đã nghe gần đây
$favoriteController = new FavoriteController();
$tracks = $favoriteController->getFavorites($_SESSION['user']['id']);
$playlists = [];

// Duyệt qua từng track và lấy thông tin bài hát
foreach ($tracks['tracks'] as $track) {
    $songController = new SongController();
    $song = $songController->playTrack($track);
    if ($song) {
        $playlists[] = [
            'id' => $track,
            'name' => $song->name ?? 'Unknown',
            'artist' => $song->artists[0]->name ?? 'Unknown Artist',
            'album' => $song->album->name ?? 'Unknown Album',
            'url' => $song->external_urls->spotify ?? '#',
            'images' => $song->album->images[0]->url ?? 'https://via.placeholder.com/150'
        ];
    }
}

?>

<link rel="stylesheet" href="/public/css/home.css">

<div class="screen-container">
    <div class="history-body">
        <?php if (!empty($playlists)): ?>
            <?php foreach ($playlists as $playlist): ?>
                <div class="playlist-card" onclick="playPlaylist('<?php echo $playlist['id']; ?>')">
                    <?php if (isset($playlist['images'])): ?>
                        <img src="<?php echo $playlist['images']; ?>" class="playlist-image" alt="Playlist-Art">
                    <?php else: ?>
                        <img src="/public/images/default.jpg" class="playlist-image" alt="Default-Art">
                    <?php endif; ?>
                    <p class="playlist-title"><?php echo htmlspecialchars($playlist['name']); ?></p>
                    <p class="playlist-subtitle"><?php echo $playlist['album']; ?> Songs</p>
                    <div class="playlist-fade">
                        <svg width="50" height="50" viewBox="0 0 24 24" fill="#E99D72">
                            <path xmlns="http://www.w3.org/2000/svg" d="M12,2A10,10,0,1,0,22,12,10,10,0,0,0,12,2ZM10,16.5v-9L16,12Z"/>
                        </svg>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <h1 style="font-size: 45px; color: #c4d0e3">No history list found</h1>
        <?php endif; ?>
    </div>
</div>

<script>
    // Hàm JavaScript để điều hướng đến trang player
    function playPlaylist(id) {
        window.location.href = '/app/views/home/home.php?page=player&id=' + id;
    }
</script>
