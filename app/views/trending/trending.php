<?php declare(strict_types=1);
require_once'../../controllers/TrendingController.php';

$controller = new TrendingController();
$songs = $controller->index();
// Kiểm tra kết quả
if (empty($songs)) {
    echo 'No songs found or an error occurred';
    exit;
}
?>

<link rel="stylesheet" href="/public/css/home.css">

<div class="screen-container">
    <div class="library-body">
        <?php if (!empty($songs)): ?>
            <?php foreach ($songs as $song): ?>
                <div class="playlist-card" onclick="playPlaylist('<?php echo $song['id']; ?>')">
                    <?php if (isset($song['image'])): ?>
                        <img src="<?php echo $song['image']; ?>" class="playlist-image" alt="Playlist-Art">
                    <?php else: ?>
                        <img src="/public/images/default.jpg" class="playlist-image" alt="Default-Art">
                    <?php endif; ?>
                    <p class="playlist-title"><?php echo htmlspecialchars($song['name']); ?></p>
                    <p class="playlist-subtitle"><?php echo $song['album']; ?> Songs</p>
                    <div class="playlist-fade">
                        <svg width="50" height="50" viewBox="0 0 24 24" fill="#E99D72">
                            <path xmlns="http://www.w3.org/2000/svg" d="M12,2A10,10,0,1,0,22,12,10,10,0,0,0,12,2ZM10,16.5v-9L16,12Z"/>
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
        window.location.href = '/app/views/home/home.php?page=player&id=' + id;
    }
</script>
