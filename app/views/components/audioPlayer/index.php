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
            <?php renderProgressCircle(0.1, $isPlaying, 300, '#C96850', $imageUrl); ?>
        </div>
        <audio id="audio-player" src="<?php echo htmlspecialchars($audioSrc); ?>"></audio>

        <div class="player-right-body flex">
            <p class="song-title"><?php echo htmlspecialchars($currentTrack->name); ?></p>
            <p class="song-artist"><?php echo htmlspecialchars($artists); ?></p>
            <div class="player-right-bottom flex">
                <div class="song-duration flex">
                    <p id="current-duration" class="duration">0:00</p>
                    <?php renderWaveAnimation($isPlaying); ?>
                    <p id="song-duration" class="duration"></p>
                </div>
                <?php renderControls($isPlaying, 'nextTrack()', 'prevTrack()'); ?>
            </div>
        </div>
    </div>
    <script defer>
        document.addEventListener('DOMContentLoaded', async() => {
            const audio = document.getElementById('audio-player');
            const playPauseBtn = document.getElementById('play-pause-btn');
            const Circle_1 = document.getElementById('circle-1');
            const Circle_2 = document.getElementById('circle-2');
            const progressCircle_2 = document.getElementById('progresscirler-2');
            const durationElement = document.getElementById('song-duration');
            const currentTimeElement = document.getElementById('current-duration');

            if (!audio || !progressCircle_2) {
                console.error("Phần tử audio hoặc vòng tròn tiến trình không tồn tại.");
                return;
            }

            // Lấy bán kính và tính chu vi của vòng tròn
            const radius = progressCircle_2.getAttribute('r');
            const circumference = 2 * Math.PI * radius;

            progressCircle_2.setAttribute('stroke-dasharray', circumference);
            progressCircle_2.setAttribute('stroke-dashoffset', circumference);

            let isPlaying = false;
            let interval;

            // Hàm định dạng thời gian từ giây sang phút:giây
            function formatTime(seconds) {
                const minutes = Math.floor(seconds / 60);
                const secs = Math.floor(seconds % 60);
                return `${minutes}:${secs < 10 ? '0' : ''}${secs}`;
            }

            // Hàm cập nhật tiến trình
            function updateProgress() {
                const { duration, currentTime } = audio;
                if (duration) {
                    const percentage = (currentTime / duration) * 100;
                    const offset = circumference - (percentage / 100) * circumference;
                    progressCircle_2.setAttribute('stroke-dashoffset', offset);

                    // Cập nhật thời gian hiện tại
                    currentTimeElement.textContent = formatTime(currentTime);
                }
            }

            // Hàm cập nhật thời lượng khi bài hát được tải
            function updateDuration() {
                const duration = audio.duration;
                if (duration && !isNaN(duration)) {
                    durationElement.textContent = formatTime(duration);
                } else {
                    console.error('Không thể lấy thời lượng của tệp audio');
                }
                console.log(duration);
            }

            // Hàm phát hoặc tạm dừng nhạc
            function playPause() {
                if (isPlaying) {
                    isPlaying = false;
                    audio.pause();
                    clearInterval(interval);
                    pauseEffects();
                } else {
                    isPlaying = true;
                    audio.play().catch(error => console.error("Lỗi khi phát nhạc:", error));
                    interval = setInterval(updateProgress, 500);
                    playEffects();
                }
                updatePlayPauseButton();
            }

            function updatePlayPauseButton() {
                if (playPauseBtn) {
                    playPauseBtn.innerHTML = isPlaying ? '<?php echo getPauseIcon(); ?>' : '<?php echo getPlayIcon(); ?>';
                }
            }

            // Hiệu ứng khi phát nhạc
            function playEffects() {
                for (let i = 0; i <= 13; i++) {
                    const wave = document.getElementById('waveAnimation_' + (i + 1));
                    if (wave) wave.classList.add('active');
                }
                if (Circle_1) Circle_1.classList.add('active');
                if (Circle_2) Circle_2.classList.add('active');

            }

            // Hiệu ứng khi tạm dừng nhạc
            function pauseEffects() {
                for (let i = 0; i <= 13; i++) {
                    const wave = document.getElementById('waveAnimation_' + (i + 1));
                    if (wave) wave.classList.remove('active');
                }
                if (Circle_1) Circle_1.classList.remove('active');
                if (Circle_2) Circle_2.classList.remove('active');
            }

            // Xử lý khi bài hát kết thúc
            function resetPlayer() {
                clearInterval(interval);
                progressCircle_2.setAttribute('stroke-dashoffset', circumference);
                currentTimeElement.textContent = "0:00";
                isPlaying = false;
                updatePlayPauseButton();
                pauseEffects();
            }

            if (audio) {
                // Cập nhật tổng thời lượng khi metadata đã tải
                audio.addEventListener('loadedmetadata', function () {
                    updateDuration();
                    console.log('Metadata loaded, duration:', audio.duration);
                });
                // Cập nhật thời gian hiện tại khi phát nhạc
                audio.addEventListener('timeupdate', updateProgress);

                // Đặt lại khi bài hát kết thúc
                audio.addEventListener('ended', resetPlayer);

                // Dừng cập nhật khi tạm dừng
                audio.addEventListener('pause', () => {
                    clearInterval(interval);
                    pauseEffects();
                });
            }

            if (playPauseBtn) {
                playPauseBtn.addEventListener('click', playPause);
            }
            await updateDuration();
        });
</script>



<?php } ?>
