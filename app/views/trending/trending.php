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
    <div class="history-body">
        <?php if (!empty($songs)): ?>
            <?php foreach ($songs as $song): ?>
                <div class="playlist-card">
                    <?php if (isset($song['image'])): ?>
                        <img src="<?php echo $song['image']; ?>" class="playlist-image" alt="Playlist-Art">
                    <?php else: ?>
                        <img src="/public/images/default.jpg" class="playlist-image" alt="Default-Art">
                    <?php endif; ?>
                    <p class="playlist-title"><?php echo htmlspecialchars($song['name']); ?></p>
                   <p class="playlist-subtitle"><?php echo $song['album']; ?> Songs</p>
                    <div class="playlist-fade" onclick="playPlaylist('<?php echo $song['id']; ?>')">
                        <svg width="50" height="50" viewBox="0 0 24 24" fill="#E99D72">
                            <path xmlns="http://www.w3.org/2000/svg" d="M12,2A10,10,0,1,0,22,12,10,10,0,0,0,12,2ZM10,16.5v-9L16,12Z"/>
                        </svg>
                    </div>
                    <?php 
                    require_once __DIR__ . '/../../models/FavoriteSong.php';
                    $favoriteSong = new Favorite();
                    $isFavorite = $favoriteSong->isFavorite($_SESSION['user']['id'], $song['id']);
                    ?>
                    <div id="favorites" class="playlist-fade-left">
                        <i id="<?php echo $song['id']; ?>" role="favorites" class="<?php echo $isFavorite ? "icon-favorite-active" : "icon-favorite" ?>" onclick="<?php echo $isFavorite ? "deteleFavorite('" . $song['id'] . "')" : "addFavorite('" . $song['id'] . "')" ?>"></i>
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

    function addFavorite(id) {
        const icon = document.getElementById(id);
        icon.classList.remove('icon-favorite');
        icon.classList.add('icon-favorite-active');
        icon.setAttribute('onclick', `deteleFavorite('${id}')`);
        const data = {
            userId: <?php echo $_SESSION['user']['id']?>,
            trackId: id
        }

        fetch('http://basomusic.local/api/add-favorite', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data),
        })
        .then(response => response.json())
        .then(result =>{
            if (result.status === '00') {
                console.log('thêm thanh cong')
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });

    }
    function deteleFavorite(id) {
        const icon = document.getElementById(id);
        icon.classList.add('icon-favorite');
        icon.classList.remove('icon-favorite-active');
        icon.setAttribute('onclick', `addFavorite('${id}')`);
        const data = {
            userId: <?php echo $_SESSION['user']['id'];?>,
            trackId: id
        }
        // Thêm bài hát vào danh sách yêu thích
        fetch('http://basomusic.local/api/delete-favorite', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data),
        })
        .then(response => response.json())
        .then(result =>{
            if (result.status == '00'){
                console.log('xoa thanh cong')
            } else {
                console.log(result);
            }
        })
        .catch(error =>{
            console.error(error);
        })
    }

</script>
