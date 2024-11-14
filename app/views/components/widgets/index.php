<?php declare(strict_types=1);
require_once('widgetCard.php');
require_once __DIR__ . '/../../../services/SpotifyService.php';

// Hàm để render Widgets
function renderWidgets($artistID) {
    // Khởi tạo dịch vụ SpotifyService
    $spotifyService = new SpotifyService();

    // Lấy danh sách nghệ sĩ tương tự
    $similarArtists = $spotifyService->getRelatedArtists($artistID);
    $featuredPlaylists = $spotifyService->getFeaturedPlaylists();
    $newReleases = $spotifyService->getNewReleases();


    // Kiểm tra và xử lý các dữ liệu trả về
    $similar = [];
    if (!empty($similarArtists)) {
        foreach ($similarArtists as $artist) {
            $similar[] = [
                'name' => $artist->name ?? 'Unknown Artist',
                'followers' => $artist->followers->total ?? 0,
                'images' => isset($artist->images[2]) ? $artist->images[2]->url : '/public/images/default.jpg',

            ];
        }
    }

    $featured = [];
    if (!empty($featuredPlaylists)) {
        foreach ($featuredPlaylists as $playlist) {
            $featured[] = [
                'name' => $playlist->name ?? 'Unknown Playlist',
                'tracks' => $playlist->tracks->total?? 0,

                'images' => $playlist->images[0]->url ?? '/public/images/default.jpg',
            ];
        }
    }

    $newRelease = [];
    if (!empty($newReleases)) {
        foreach ($newReleases as $album) {
            $newRelease[] = [
                'name' => $album->name ?? 'Unknown Album',
                'artists' => $album->artists[0]->name,
                'images' => $album->images[2]->url ?? '/public/images/default.jpg',
                ];
        }
    }

    // Render các thẻ WidgetCard
    echo '<div class="widgets-body flex">';
    renderWidgetCard("Similar Artists", $similar, [], []);
    renderWidgetCard("Made For You", [], $featured, []);
    renderWidgetCard("New Releases", [], [], $newRelease);
    echo '</div>';
}
