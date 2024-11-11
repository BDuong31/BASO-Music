<?php declare(strict_types=1); 
function renderAlbumInfo($album) {
    // Lấy tên các nghệ sĩ từ album
    $artists = [];
    if (isset($album['artists'])) {
        foreach ($album['artists'] as $artist) {
            $artists[] = $artist['name'];
        }
    }
    $artistNames = implode(", ", $artists);

    // Hiển thị thông tin album
    ?>
    <div class="albumInfo-card">
        <!-- Tên album và nghệ sĩ -->
        <div class="albumName-container">
            <div class="marquee">
                <p><?php echo $album['name'] . " - " . $artistNames; ?></p>
            </div>
        </div>

        <!-- Thông tin chi tiết album -->
        <div class="album-info">
            <p><?php echo $album['name'] . " is an " . $album['album_type'] . " by " . $artistNames . " with " . $album['total_tracks'] . " track(s)"; ?></p>
        </div>

        <!-- Ngày phát hành -->
        <div class="album-release">
            <p>Release Date: <?php echo $album['release_date']; ?></p>
        </div>
    </div>
    <?php
}
?>
