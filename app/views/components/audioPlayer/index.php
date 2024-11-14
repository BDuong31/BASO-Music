<?php declare(strict_types=1);
require_once('controls.php');
require_once('progressCircle.php');
require_once('waveAnimation.php');

function renderAudioPlayer(object $currentTrack, int $currentIndex, array $total) {
    // Lấy URL preview từ danh sách bài hát
    $audioSrc = $total[$currentIndex]['preview_url'] ?? null;

    // Kiểm tra nếu URL preview không tồn tại
    if (!$audioSrc) {
        echo "<p>Không có preview cho bài hát này.</p>";
        return;
    }

    // Lấy danh sách nghệ sĩ
    $artists = implode(" | ", array_map(function($artist) {
        return $artist->name;
    }, $currentTrack->album->artists));

    // Lấy URL hình ảnh
    $imageUrl = $currentTrack->album->images[0]->url ?? '/public/images/default.jpg';

    // Khởi tạo biến $isPlaying
    $isPlaying = false;
    ?>

    <div class="player-body flex">
        <div class="player-left-body">
            <?php renderProgressCircle(30, $isPlaying, 300, '#C96850', $imageUrl); ?>
        </div>
        <div class="player-right-body flex">
            <p class="song-title"><?php echo htmlspecialchars($currentTrack->name); ?></p>
            <p class="song-artist"><?php echo htmlspecialchars($artists); ?></p>
            <div class="player-right-bottom flex">
                <div class="song-duration flex">
                    <p id="current-duration" class="duration">0:00</p>
                    <?php renderWaveAnimation($isPlaying); ?>
                    <p class="duration">0:30</p>
                </div>
                <?php renderControls($isPlaying, 'nextTrack()', 'prevTrack()'); ?>
            </div>
        </div>
        <audio id="audio-player" src="<?php echo htmlspecialchars($audioSrc); ?>"></audio>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
    const audio = document.getElementById('audio-player');
    const playPauseBtn = document.getElementById('play-pause-btn');
    const progressCircle_1 = document.getElementById('circle-1');
    const progressCircle_2 = document.getElementById('circle-2');
    let isPlaying = false;
    let interval;

    function updateProgress() {
        const currentTime = Math.floor(audio.currentTime);
        const durationElement = document.getElementById('current-duration');
        if (durationElement) {
            durationElement.textContent = `0:${currentTime < 10 ? '0' + currentTime : currentTime}`;
        }
    }

    function playPause() {
        if (isPlaying) {
            isPlaying = false;
            audio.pause();
            clearInterval(interval);
            pauseEffects();
        } else {
            isPlaying = true;
            audio.play().catch(error => console.error("Lỗi khi phát nhạc:", error));
            interval = setInterval(updateProgress, 1000);
            playEffects();
        }
        updatePlayPauseButton();
    }

    function updatePlayPauseButton() {
        if (playPauseBtn) {
            if (isPlaying) {
                playPauseBtn.classList.add('active');
                playPauseBtn.innerHTML = '<?php echo getPauseIcon(); ?>';
            } else {
                playPauseBtn.classList.remove('active');
                playPauseBtn.innerHTML = '<?php echo getPlayIcon(); ?>';
            }
        }
    }

    function playEffects() {
        for (let i = 0; i <= 13; i++) {
            const wave = document.getElementById('waveAnimation_' + (i + 1));
            if (wave) wave.classList.add('active');
        }
        if (progressCircle_1) progressCircle_1.classList.add('active');
        if (progressCircle_2) progressCircle_2.classList.add('active');
    }

    function pauseEffects() {
        for (let i = 0; i <= 13; i++) {
            const wave = document.getElementById('waveAnimation_' + (i + 1));
            if (wave) wave.classList.remove('active');
        }
        if (progressCircle_1) progressCircle_1.classList.remove('active');
        if (progressCircle_2) progressCircle_2.classList.remove('active');
    }

    function nextTrack() {
        console.log("Next track");
    }

    function prevTrack() {
        console.log("Previous track");
    }

    if (audio) {
        audio.addEventListener('ended', nextTrack);
        audio.addEventListener('timeupdate', updateProgress);
    }

    if (playPauseBtn) {
        playPauseBtn.addEventListener('click', playPause);
    }
    updatePlayPauseButton();
});

    </script>
<?php } ?>
