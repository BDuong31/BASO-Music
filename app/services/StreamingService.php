<?php declare(strict_types=1); 
require_once __DIR__ . '/../../spotifyAPI/Request.php';
require_once __DIR__ . '/../../spotifyAPI/Session.php';
require_once __DIR__ . '/../../spotifyAPI/SpotifyWebAPI.php';
require_once __DIR__ . '/../../spotifyAPI/SpotifyWebAPIAuthException.php';
require_once __DIR__ . '/../../spotifyAPI/SpotifyWebAPIException.php';
class MusicController {
    private SpotifyService $spotifyService;

    public function __construct(SpotifyService $spotifyService) {
        $this->spotifyService = $spotifyService;
    }

    public function playTrack(string $trackID) {
        $trackInfo = $this->spotifyService->getTrackById($trackID);
        include __DIR__ . '/../views/player.php';
    }
}
?>
?>
