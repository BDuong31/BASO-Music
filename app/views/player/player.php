<?php declare(strict_types=1); 
require_once(__DIR__ . '/../components/audioPlayer/index.php');
require_once(__DIR__ . '/../components/widgets/index.php');
require_once(__DIR__ . '/../components/songCard/index.php');
require_once(__DIR__ . '/../components/queue/index.php');
?>
<link rel="stylesheet" href="/public/css/home.css">
<div class="screen-container flex">
    <div class="left-player-body">
        <!-- audioPlayer Start-->
        <?php
            $currentTrack = [
                'name' => 'Sample Track',
                'album' => [
                    'artists' => [['name' => 'Artist 1'], ['name' => 'Artist 2']],
                    'images' => [['url' => '/public/images/logo.jpg']]
                ]
            ];
            $total = [
                ['track' => ['preview_url' => 'https://www.soundhelix.com/examples/mp3/SoundHelix-Song-1.mp3']]
            ];

            if (function_exists('renderAudioPlayer')) {
                renderAudioPlayer($currentTrack, 0, $total);
            } else {
                echo "Function renderAudioPlayer not found.";
            }
        ?>
        <!-- audioPlayer End -->

        <!-- Sử dụng hàm renderWidgets -->
        <?php
            $token = 'YOUR_SPOTIFY_ACCESS_TOKEN';
            $artistID = 'YOUR_ARTIST_ID';
            if (function_exists('renderWidgets')) {
                renderWidgets($artistID, $token);
            } else {
                echo "Function renderWidgets not found.";
            }
        ?>
    </div>

    <div class="right-player-body">
        <!-- Sử dụng hàm renderSongCard -->
        <?php
            $album = [
                'name' => 'Sample Album',
                'artists' => [['name' => 'Artist 1']],
                'album_type' => 'album',
                'total_tracks' => 10,
                'release_date' => '2023-12-01',
                'images' => [['url' => '/public/images/logo.jpg']]
            ];

            if (function_exists('renderSongCard')) {
                renderSongCard($album);
            } else {
                echo "Function renderSongCard not found.";
            }
        ?>

        <!-- Sử dụng hàm renderQueue -->
        <?php
            $tracks = [
                ['track' => ['name' => 'Song 1']],
                ['track' => ['name' => 'Song 2']],
                ['track' => ['name' => 'Song 3']]
            ];

            echo '<script>
            function setCurrentIndex(index) {
                console.log("Current index:", index);
            }
            </script>';

            if (function_exists('renderQueue')) {
                renderQueue($tracks, 'setCurrentIndex');
            } else {
                echo "Function renderQueue not found.";
            }
        ?>
    </div>
</div>
