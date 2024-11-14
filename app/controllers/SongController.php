<?php
declare(strict_types=1);
require_once __DIR__ . '/../services/SpotifyService.php';

class SongController
{
    private SpotifyService $spotifyService;


    public function __construct() {
        try {
            $this->spotifyService = new SpotifyService();
        } catch (Exception $e) {
            echo "Lỗi khi khởi tạo SpotifyService: " . $e->getMessage();
            exit;
        }
    }

    // Hàm lấy bài hát cụ thể và render trang
    public function playTrack(string $id): ? object {
        try {
            return $this->spotifyService->getTrackById($id);
        } catch (Exception $e) {
            echo "Lỗi khi lấy bài hát: " . $e->getMessage();
            return null;
        }
    }

    public function getAlbum(string $albumId): ?object {
        if (empty($albumId)) {
            echo "ID album không hợp lệ.";
            return null;
        }

        try {
            return $this->spotifyService->getAlbumById($albumId);
        } catch (Exception $e) {
            echo "Lỗi khi lấy thông tin album: " . $e->getMessage();
            return null;
        }
    }


    // Hàm lấy danh sách bài hát thịnh hành
    public function showTrendingTracks()
    {
        return $this->spotifyService->getTrendingTracks();
    }

    public function showNextSongs($currentTrackId)
    {
        return $this->spotifyService->getNextTracks($currentTrackId);

    }
}
?>
