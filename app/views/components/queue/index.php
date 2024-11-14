<?php declare(strict_types=1);
function convertDuration($duration_ms) {
    $seconds = floor($duration_ms / 1000);
    $minutes = floor($seconds / 60);
    $seconds = $seconds % 60;
    // Đảm bảo hiển thị số giây với 2 chữ số
    return sprintf("%d:%02d", $minutes, $seconds);
}


function renderQueue($tracks) { ?>
    <div class="queue-container flex">
        <div class="queue flex">
            <p class="upNext">Up Next</p>
            <div class="queue-list">
                <?php
                foreach ($tracks as $index => $track) {
                    $trackName = $track['name'] ?? 'Unknown Track';
                    $trackImage = $track['image'] ?? '/public/images/logo.jpg';
                    $trackDuration = $track['duration'] ?? '';
                    

                    ?>
                    <div class="queue-item flex" onclick="setCurrentIndex(<?php echo $index; ?>)">
                        <img src="<?php echo htmlspecialchars($trackImage); ?>" width="24px">
                        <p class="track-name"><?php echo htmlspecialchars($trackName); ?></p>
                        <p class="track-duration"><?php echo htmlspecialchars($trackDuration == '' ? '' : convertDuration($trackDuration)); ?></p>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
<?php } ?>
