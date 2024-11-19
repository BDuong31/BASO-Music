<?php declare(strict_types=1); 
require_once(__DIR__ . '/../components/audioPlayer/index.php');
require_once(__DIR__ . '/../components/widgets/index.php');
require_once(__DIR__ . '/../components/songCard/index.php');
require_once(__DIR__ . '/../components/queue/index.php');
require_once(__DIR__ . '/../../controllers/SongController.php');

?>
<link rel="stylesheet" href="/public/css/home.css">
<div class="screen-container flex">
    <div class="left-player-body">
        <!-- audioPlayer Start-->
        <?php
            $userId = $_SESSION['user']['id'];
            $id = $_GET['id'] ?? '';
            if ($id != '') {
                $songController = new SongController();

                $currentTrack = $songController->playTrack($id);
                if ($currentTrack && function_exists('renderAudioPlayer')) {
                    // Chuẩn bị dữ liệu cho player
                    $total = [
                        ['preview_url' => $currentTrack->preview_url],
                    ];
                }
            } else {
                $currentTrack = (object) [
                    'name' => 'BASO Music',
                    'album' => (object) [
                        'artists' => [
                            (object) ['name' => 'Nhóm 10'],
                            (object) ['name' => 'BDương']
                        ],
                        'images' => [
                            (object) ['url' => '/public/images/logo.jpg']
                        ]
                    ]
                ];
                
                $total = [
                    ['preview_url' => '/public/ys-bao.m4a']
                ];
            }
            renderAudioPlayer($currentTrack, 0, $total);
        ?>
        <!-- audioPlayer End -->

        <!-- Sử dụng hàm renderWidgets -->
        <?php
            // Kiểm tra nếu có thông tin về artistID từ $currentTrack
            $artistID = $currentTrack->album->artists[0]->id ?? '5dfZ5uSmzR7VQK0udbAVpf';
            // Gọi hàm renderWidgets với artistID
            if (function_exists('renderWidgets')) {
                renderWidgets($artistID);
            } else {
                echo "Function renderWidgets not found.";
            }
        ?>
    </div>

    <div class="right-player-body">
        <!-- Sử dụng hàm renderSongCard -->
        <?php
            $albumId = $currentTrack->album->id ?? '';
            if ($albumId != '') {
                $albums = $songController->getAlbum($albumId);
                $artists = [];
                if (isset($albums->artists) && is_array($albums->artists)) {
                    foreach ($albums->artists as $artist) {
                        $artists[] = $artist->name;
                    }
                }
                $artistNames = implode(", ", $artists);
        
                $album = [
                    'name' => $albums->name,
                    'artists' => [['name' => $artistNames]],
                    'album_type' => $albums->album_type,
                    'total_tracks' => $albums->total_tracks,
                    'release_date' => $albums->release_date,
                    'images' => $albums->images[0]->url,
                ];
            } else {
                $album = [
                    'name' => 'BASO Music - Nhóm 10',
                    'artists' => [['name' => 'BDương']],
                    'album_type' => 'Web Music',
                    'total_tracks' => '5',
                    'release_date' => '13-11-2024',
                    'images' => '/public/images/logo.jpg'
                ];
            }

            if (function_exists('renderSongCard')) {
                 renderSongCard($album);
            } else {
                 echo "Function renderSongCard not found.";
            }
        ?>

        <!-- Sử dụng hàm renderQueue -->
        <?php
            if (isset($songController)){
                $id = $_GET['id'] ?? '';
                $nextSongID= $currentTrack->album->id;
                $tracks = $songController->showNextSongs($id);
            } else {
                $tracks = [
                    [
                        'name' => 'Chưa mở bài hát',
                        'images' => '/public/images/logo.jpg',
                        'duration' => ''
                    ]
                ];
            }
            echo '<script>
            function setCurrentIndex(id) {
                window.location.href = "/app/views/home/home.php?page=player&id=" + id;
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
<script>
        data = {
            "userId": <?php echo $_SESSION['user']['id'] ?>,
            "trackId": "<?php echo $_GET['id'] ?>"
        }
        fetch('http://basomusic.local/api/add-history', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => {
            // Kiểm tra nếu response không phải JSON
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json(); // Chuyển đổi sang JSON nếu hợp lệ
        })
        .then(result => {
            // Xử lý kết quả trả về từ API
            if (result.status === '00') {
                console.log('Thêm vào lịch sử thành công');
            } else {
                console.error(result.message || 'Cập nhật thất bại');
            }
        })
        .catch(error => {
            // Log lỗi chi tiết
            console.error('Error during fetch:', error);
        });

</script>
