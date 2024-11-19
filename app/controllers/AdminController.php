<?php  declare(strict_types=1); 
require_once __DIR__ . '/../services/SpotifyService.php';

$spotifyService = new SpotifyService();
class AdminController{
    private SpotifyService $spotifyService;


    public function __construct() {
        try {
            $this->spotifyService = new SpotifyService();
        } catch (Exception $e) {
            echo "Lỗi khi khởi tạo SpotifyService: " . $e->getMessage();
            exit;
        }
    }

    // API tổng số album
    public function getTotalAlbums() {
        return $this->spotifyService->getTotalAlbums();
    }

    // API t��ng số bài hát
    public function getSongs() {
        return $this->spotifyService->fetchTracksDetails();
    }

    // API t��ng số nhạc singer
    public function getArtist() {
        return $this->spotifyService->fetchArtistsData();
    }
}
?>
