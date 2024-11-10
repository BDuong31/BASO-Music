<?php declare(strict_types=1); 
require_once('../components/audioPlayer/index.php');
require_once('../components/widgets/index.php');
require_once('../components/songCard/index.php');
require_once('../components/queue/index.php');

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - BASO Music</title>
    <link rel="icon" href="/public/images/logo.jpg">
    <link rel="stylesheet" href="/public/css/player.css">
</head>

<body>
<div class="main-body">
    <div class="sidebar-container">
        <img src="/public/images/logo.jpg" class="profile-img" alt="profile" />
        <div>
            <div class="btn-body">
                <i class="btn-icon btn-icon-feed"></i>
                <p class="btn-title">Feed</p>
            </div>
            <div class="btn-body">
                <i class="btn-icon btn-icon-trending"></i>
                <p class="btn-title">Trending</p>
            </div>
            <div class="btn-body active">
                <i class="btn-icon btn-icon-player"></i>
                <p class="btn-title">Player</p>
            </div>
        </div>
        <div class="btn-body">
                <i class="btn-icon btn-icon-signout"></i>
                <p class="btn-title">Sign out</p>
        </div>
    </div>
    <div class="screen-container flex">
        <div class="left-player-body">
            <!-- audioPlayer Start-->
            <?php
            // Dữ liệu mẫu để kiểm tra
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

            renderAudioPlayer($currentTrack, 0, $total);
            ?>
            <!-- audioPlayer End -->
             <!-- Sử dụng hàm renderWidgets -->
            <?php
            // Đặt token Spotify của bạn ở đây
            $token = 'YOUR_SPOTIFY_ACCESS_TOKEN';

            // Thay đổi $artistID thành ID của nghệ sĩ bạn muốn tìm kiếm
            $artistID = 'YOUR_ARTIST_ID';
            renderWidgets($artistID, $token);
            ?>
        </div>
        <div class="right-player-body">
            <!-- Sử dụng hàm renderSongCard -->
            <?php
            // Dữ liệu album mẫu để kiểm tra
            $album = [
                'name' => 'Sample Album',
                'artists' => [
                    ['name' => 'Artist 1'],
                ],
                'album_type' => 'album',
                'total_tracks' => 10,
                'release_date' => '2023-12-01',
                'images' => [
                    ['url' => '/public/images/logo.jpg']
                ]
            ];
            renderSongCard($album);
            ?>
            <?php
            // Dữ liệu mẫu
            $tracks = [
                ['track' => ['name' => 'Song 1']],
                ['track' => ['name' => 'Song 2']],
                ['track' => ['name' => 'Song 3']]
            ];

            // Hàm JavaScript giả định
            echo '<script>
            function setCurrentIndex(index) {
                console.log("Current index:", index);
            }
            </script>';

            // Gọi hàm renderQueue
            renderQueue($tracks, 'setCurrentIndex');
            ?>
        </div>
    </div>
</div>
</body>

</html>

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
