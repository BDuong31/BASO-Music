<?php declare(strict_types=1); 
function renderControls($isPlaying, $handleNext, $handlePrev) {
    $playPauseIcon = $isPlaying ? getPauseIcon() : getPlayIcon();
?>

    <div class="controls-wrapper flex">
        <div class="action-btn flex" onclick="<?php echo $handlePrev; ?>">
            <?php echo getSkipBackIcon(); ?>
        </div>
        <div class="<?php echo $isPlaying ? 'play-pause-btn flex active' : 'play-pause-btn flex'; ?>" onclick="<?php echo "togglePlay()"; ?>">
            <?php echo $playPauseIcon; ?>
        </div>
        <div class="action-btn flex" onclick="<?php echo $handleNext; ?>">
            <?php echo getSkipForwardIcon(); ?>
        </div>
    </div>
    <?php
}

function getPlayIcon() {
    return '<svg width="35" height="35" viewBox="0 0 24 24" fill="#C4D0E3"><path d="M8 5v14l11-7z"></path></svg>';
}

function getPauseIcon() {
    return '<svg width="35" height="35" viewBox="0 0 24 24" fill="#C4D0E3"><path d="M6 19h4V5H6zm8-14v14h4V5z"></path></svg>';
}

function getSkipBackIcon() {
    return '<svg width="35" height="35" viewBox="0 0 24 24" fill="#C4D0E3"><path d="M19 20L9 12l10-8v16zM5 19h2V5H5v14z"></path></svg>';
}

function getSkipForwardIcon() {
    return '<svg width="35" height="35" viewBox="0 0 24 24" fill="#C4D0E3"><path d="M5 4l10 8-10 8V4zm14 0v16h-2V4h2z"></path></svg>';
}
?>

<script>
    // Hàm JavaScript để điều khiển Play/Pause
    function togglePlay() {
        console.log("Toggle Play/Pause");
    }

    function nextTrack() {
        console.log("Next Track");
    }

    function prevTrack() {
        console.log("Previous Track");
    }
</script>