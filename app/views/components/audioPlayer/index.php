<?php declare(strict_types=1);
require_once('controls.php');
require_once('progressCircle.php');
require_once('waveAnimation.php');

function renderAudioPlayer($currentTrack, $currentIndex, $total) {
    $audioSrc = $total[$currentIndex]['track']['preview_url'];
    $artists = implode(" | ", array_map(function($artist) {
        return $artist['name'];
    }, $currentTrack['album']['artists']));
    $imageUrl = $currentTrack['album']['images'][0]['url'];
    ?>

    <div class="player-body flex">
        <div class="player-left-body">
            <?php renderProgressCircle(30, true, 300, '#C96850',$imageUrl); ?>
        </div>
        <div class="player-right-body flex">
            <p class="song-title"><?php echo $currentTrack['name']; ?></p>
            <p class="song-artist"><?php echo $artists; ?></p>
            <div class="player-right-bottom flex">
                <div class="song-duration flex">
                    <p id="current-duration" class="duration">0:00</p>
                    <?php renderWaveAnimation(true); ?>
                    <p class="duration">0:30</p>
                </div>
                <?php renderControls(true, 'nextTrack()', 'prevTrack()', 'togglePlay()'); ?>
            </div>
        </div>
        <audio id="audio-player" src="<?php echo $audioSrc; ?>"></audio>
    </div>

    <script>
        const audio = document.getElementById('audio-player');
        let isPlaying = false;
        let interval;

        function updateProgress() {
            const currentTime = Math.round(audio.currentTime);
            document.getElementById('current-duration').textContent = `0:${currentTime < 10 ? '0' + currentTime : currentTime}`;
        }

        function playPause() {
            if (isPlaying) {
                audio.pause();
                clearInterval(interval);
            } else {
                audio.play();
                interval = setInterval(updateProgress, 1000);
            }
            isPlaying = !isPlaying;
        }

        function nextTrack() {
            console.log("Next track");
        }

        function prevTrack() {
            console.log("Previous track");
        }

        document.addEventListener('DOMContentLoaded', () => {
            audio.addEventListener('ended', nextTrack);
        });
    </script>
<?php } ?>
