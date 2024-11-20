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
        $clientID = '67d1e412d7c94907a2c140fa179a6672';
        $clientSecret = 'a7a5e2669cb042f9b67f2578f8dd9f21';
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
// public function getTotalAlbums(): int
// {
//     try {
//         $result = $this->api->search('album', 'album', ['limit' => 50]); // Tìm kiếm album với limit=1
//         if (isset($result->albums->total)) {
//             return (int) $result->albums->total;
//         }
//         return 0;
//     } catch (Exception $e) {
//         error_log('Error fetching total albums: ' . $e->getMessage());
//         return 0;
//     }
// }
public function getTotalAlbums(): int
{
    try {
        $totalAlbums = 0;

        foreach (range('A', 'A') as $char) {
            // Gửi yêu cầu tới Spotify API để lấy tổng số album bắt đầu với ký tự $char
            $result = $this->api->search($char, 'album', ['limit' => 50]);

            if (isset($result->albums->total)) {
                $totalAlbums += (int) $result->albums->total; // Cộng dồn số album
            }
        }

        return $totalAlbums; // Trả về tổng số album
    } catch (Exception $e) {
        // Ghi lại lỗi nếu có
        error_log('Error fetching total albums from A to Z: ' . $e->getMessage());
        return 0;
    }
}


    public function fetchArtistsData(): array
    {
        try {
            $totalArtists = 0;
            $allArtistsDetails = [];

            // Lặp qua từng ký tự từ A-Z
            foreach (range('A', 'A') as $char) {
                // Gửi yêu cầu tới Spotify API để lấy danh sách nghệ sĩ
                $result = $this->api->search($char, 'artist', ['limit' => 50]); // Lấy tối đa 50 nghệ sĩ đầu tiên

                // Lấy tổng số nghệ sĩ cho mỗi ký tự
                if (isset($result->artists->total)) {
                    $totalArtists += (int)$result->artists->total;
                }

                // Lấy chi tiết nghệ sĩ
                if (isset($result->artists->items) && is_array($result->artists->items)) {
                    foreach ($result->artists->items as $artist) {
                        $allArtistsDetails[] = [
                            'name' => $artist->name ?? 'Unknown', // Tên nghệ sĩ
                            'popularity' => $artist->popularity ?? 0, // Mức độ phổ biến
                            'genres' => $artist->genres ?? [], // Thể loại nhạc
                            'image' => $artist->images[0]->url ?? 'https://scontent.fhan5-7.fna.fbcdn.net/v/t1.30497-1/453178253_471506465671661_2781666950760530985_n.png?stp=dst-png_s480x480&_nc_cat=1&ccb=1-7&_nc_sid=136b72&_nc_eui2=AeEfPnyP8C4e1w-JpsoXwdNfWt9TLzuBU1Ba31MvO4FTUIyNLz6wfg2QJcaVSVr3II0wi61_tv-XBJ4L5ExstyF6&_nc_ohc=bYLP8ChJYTYQ7kNvgFq8VUy&_nc_zt=24&_nc_ht=scontent.fhan5-7.fna&_nc_gid=AvMs8PSnONcYY94Ul7lqOLf&oh=00_AYB3YX9VnQz65A0V3DYs2JEGVoN13nhsIqhOsQLOy4M4CQ&oe=67628DBA', // Ảnh đại diện
                        ];
                    }
                }

                // Thêm thời gian chờ để tránh bị giới hạn bởi API rate limit
                sleep(1);
            }

            // Trả về tổng số nghệ sĩ và thông tin chi tiết
            return [
                'total_artists' => $totalArtists,
                'artist_details' => $allArtistsDetails,
            ];
        } catch (Exception $e) {
            // Ghi lại lỗi nếu có
            error_log('Error fetching artists data from A to Z: ' . $e->getMessage());
            return [
                'total_artists' => 0,
                'artist_details' => [],
            ];
        }
    }

    public function fetchTracksDetails(): array
{
    try {
        $allTracksDetails = [];

        // Lặp qua từng ký tự từ A-Z
        foreach (range('A', 'A') as $char) {
            // Gửi yêu cầu tới Spotify API để lấy danh sách bài hát
            $result = $this->api->search($char, 'track', ['limit' => 50]); // Lấy tối đa 50 bài hát đầu tiên

            // Lấy chi tiết bài hát
            if (isset($result->tracks->items) && is_array($result->tracks->items)) {
                foreach ($result->tracks->items as $track) {
                    $allTracksDetails[] = [
                        'track_name' => $track->name ?? 'Unknown', // Tên bài hát
                        'artist_name' => $track->artists[0]->name ?? 'Unknown', // Tên nghệ sĩ đầu tiên
                        'artist_image' => $track->artists[0]->images[0]->url ?? 'Unknown', //
                        'release_date' => $track->album->release_date ?? 'Unknown', // Ngày phát hành
                    ];
                }
            }

            // Thêm thời gian chờ để tránh bị giới hạn bởi API rate limit
            sleep(1);
        }

        // Trả về tổng số bài hát và thông tin chi tiết
        return $allTracksDetails;
    } catch (Exception $e) {
        // Ghi log lỗi nếu xảy ra ngoại lệ
        error_log('Error fetching tracks details: ' . $e->getMessage());
        return $allTracksDetails=[];
    }
}

}
?>
