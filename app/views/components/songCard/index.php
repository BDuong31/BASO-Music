<?php declare(strict_types=1); 
require_once('albumImage.php');
require_once('albumInfo.php');

// Hàm để render Song Card
function renderSongCard($album) {
    ?>
    <div class="songCard-body flex">
        <?php renderAlbumImage($album['images'] ?? ''); ?>
        <?php renderAlbumInfo($album); ?>
    </div>
    <?php
}
?>


