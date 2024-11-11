<?php declare(strict_types=1);

function renderQueue(array $tracks) { ?>
    <div class="queue-container flex">
        <div class="queue flex">
            <p class="upNext">Up Next</p>
            <div class="queue-list">
                <?php
                // Lặp qua danh sách các bài hát
                foreach ($tracks as $index => $track) {
                    $trackName = $track['track']['name'] ?? 'Unknown Track';
                    ?>
                    <div class="queue-item flex" onclick="setCurrentIndex(<?php echo $index; ?>)">
                        <p class="track-name"><?php echo htmlspecialchars($trackName); ?></p>
                        <p>0:30</p>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
<?php } ?>
