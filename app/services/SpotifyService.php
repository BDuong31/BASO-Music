<?php declare(strict_types=1);
require_once __DIR__ . '/../../spotifyAPI/Request.php';
require_once __DIR__ . '/../../spotifyAPI/Session.php';
require_once __DIR__ . '/../../spotifyAPI/SpotifyWebAPI.php';
require_once __DIR__ . '/../../spotifyAPI/SpotifyWebAPIAuthException.php';
require_once __DIR__ . '/../../spotifyAPI/SpotifyWebAPIException.php';


class SpotifyService
{
    private $api;
    private $session;

    public function __construct()
    {
        $clientID = '738e89a131d74ec593c1eb89effe3a44';
        $clientSecret = 'a55158c4160f4be5b60e7f7a5aa4518c';
        $redirectURI = 'http://basomusic.local/config/callback.php';

        $this->session = new Session($clientID, $clientSecret, $redirectURI);

        if (isset($_SESSION['access_token'])) {
            $this->api = new SpotifyWebAPI();
            $this->api->setAccessToken($_SESSION['access_token']);
        } elseif (isset($_COOKIE['spotify_refresh_token'])) {
            $this->refreshAccessToken();
        } else {
            $this->authenticateUser();
        }
    }


    private function authenticateUser(): void
    {
        $options = [
            'scope' => [
                'user-read-email',
                'user-top-read',
                'playlist-read-private',
                'streaming',
            ],
        ];

        $authUrl = $this->session->getAuthorizeUrl($options);
        header('Location: ' . $authUrl);
        exit;
    }

    private function refreshAccessToken(): void
    {
        if (isset($_COOKIE['spotify_refresh_token'])) {
            $this->session->refreshAccessToken($_COOKIE['spotify_refresh_token']);
            $_SESSION['access_token'] = $this->session->getAccessToken();
        }
    }

    public function getTrendingSongs(): array
    {
        try {
            if (!$this->api) {
                return ['error' => 'Spotify API not initialized'];
            }

            $playlistId = '37i9dQZEVXbLdGSmz6xilI'; // Top 50 - Vietnam
            $playlist = $this->api->getPlaylistTracks($playlistId, ['limit' => 50]);

            $songs = [];
            foreach ($playlist->items as $item) {
                $track = $item->track;
                $songs[] = [
                    'id' => $track->id ?? 'Unknown ID',
                    'name' => $track->name ?? 'Unknown Name',
                    'artist' => $track->artists[0]->name ?? 'Unknown Artist',
                    'album' => $track->album->name ?? 'Unknown Album',
                    'url' => $track->external_urls->spotify ?? '#',
                    'image' => $track->album->images[0]->url ?? '/public/images/default.jpg',
                ];
            }

            return $songs;
        } catch (Exception $e) {
            return ['error' => 'Failed to fetch trending songs: ' . $e->getMessage()];
        }
    }

    public function getTrackById(string $trackID): object
    {
        try {
            return $this->api->getTrack($trackID);
        } catch (Exception $e) {
            throw new Exception("Lỗi khi lấy thông tin bài hát: " . $e->getMessage());
        }
    }

    public function getAlbumById(string $albumId): object {
        try {
            return $this->api->getAlbum($albumId);
        } catch (Exception $e) {
            throw new Exception("Lỗi khi lấy thông tin album: " . $e->getMessage());
        }
    }

    public function getTrendingTracks(): array
    {
        try {
            $newReleases = $this->api->getNewReleases(['limit' => 10, 'market' => 'VN']);
            return $newReleases->albums->items ?? [];
        } catch (Exception $e) {
            throw new Exception("Lỗi khi lấy danh sách bài hát thịnh hành: " . $e->getMessage());
        }
    }
 public function getRelatedArtists(string $artistID): array
    {
        try {
            return $this->api->getArtistRelatedArtists($artistID)->artists ?? [];
        } catch (Exception $e) {
            return ['error' => 'Failed to fetch related artists: ' . $e->getMessage()];
        }
    }

    // Lấy danh sách playlist nổi bật
    public function getFeaturedPlaylists(): array
    {
        try {
            $playlists = $this->api->getFeaturedPlaylists(['limit' => 20]);
            return $playlists->playlists->items ?? [];
        } catch (Exception $e) {
            return ['error' => 'Failed to fetch featured playlists: ' . $e->getMessage()];
        }
    }

    // Lấy danh sách album mới phát hành
    public function getNewReleases(): array
    {
        try {
            $newReleases = $this->api->getNewReleases(['limit' => 20, 'market' => 'VN']);
            return $newReleases->albums->items ?? [];
        } catch (Exception $e) {
            return ['error' => 'Failed to fetch new releases: ' . $e->getMessage()];
        }
    }

    public function getNextTracks(string $currentTrackId): array
{
    try {
        // Giả sử chúng ta lấy 50 bài hát phổ biến hoặc mới nhất
        $recommendedTracks = $this->api->getRecommendedTracks($currentTrackId);

        $tracks = [];
        foreach ($recommendedTracks->tracks as $track) {
                    // Thêm bài hát vào danh sách
                    $tracks[] = [
                        'id' => $track->id,
                        'name' => $track->name,
                        'artist' => $track->artists[0]->name,
                        'album' => $track->album->name,
                        'url' => $track->external_urls->spotify,
                        'image' => $track->album->images[0]->url,
                        'duration' => $track->duration_ms,
                    ];
            }
        return $tracks;

    } catch (Exception $e) {
        return ['error' => 'Failed to fetch random tracks: ' . $e->getMessage()];
    }
}


    
    
    
    

}
?>
