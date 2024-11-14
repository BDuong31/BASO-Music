<?php

declare(strict_types=1);
require_once('Request.php');
require_once('SpotifyWebAPIAuthException.php');
require_once('Session.php');
require_once('SpotifyWebAPIException.php');

class SpotifyWebAPI
{
    protected string $accessToken = '';
    protected array $lastResponse = [];
    protected array $options = [
        'auto_refresh' => false,
        'auto_retry' => false,
        'return_assoc' => false,
    ];
    protected ?Request $request = null;
    protected ?Session $session = null;

    public function __construct(array|object $options = [], ?Session $session = null, ?Request $request = null)
    {
        $this->setOptions($options);
        $this->setSession($session);

        $this->request = $request ?? new Request();
    }

    protected function authHeaders(array $headers = []): array
    {
        $accessToken = $this->session ? $this->session->getAccessToken() : $this->accessToken;

        if ($accessToken) {
            $headers = array_merge($headers, [
                'Authorization' => 'Bearer ' . $accessToken,
            ]);
        }

        return $headers;
    }

    protected function getSnapshotId(array|object $body): string|bool
    {
        $body = (array) $body;

        return $body['snapshot_id'] ?? false;
    }

    protected function idToUri(string|array $ids, string $type): string|array
    {
        $type = 'spotify:' . $type . ':';

        $ids = array_map(function ($id) use ($type) {
            if (substr($id, 0, strlen($type)) != $type && substr($id, 0, 7) != 'spotify') {
                $id = $type . $id;
            }

            return $id;
        }, (array) $ids);

        return count($ids) == 1 ? $ids[0] : $ids;
    }

    protected function sendRequest(
        string $method,
        string $uri,
        string|array $parameters = [],
        array $headers = []
    ): array {
        $this->request->setOptions([
            'return_assoc' => $this->options['return_assoc'],
        ]);

        try {
            $headers = $this->authHeaders($headers);

            return $this->request->api($method, $uri, $parameters, $headers);
        } catch (SpotifyWebAPIException $e) {
            if ($this->options['auto_refresh'] && $e->hasExpiredToken()) {
                $result = $this->session->refreshAccessToken();

                if (!$result) {
                    throw new SpotifyWebAPIException('Could not refresh access token.');
                }

                return $this->sendRequest($method, $uri, $parameters, $headers);
            } elseif ($this->options['auto_retry'] && $e->isRateLimited()) {
                ['headers' => $lastHeaders] = $this->request->getLastResponse();

                sleep((int) $lastHeaders['retry-after']);

                return $this->sendRequest($method, $uri, $parameters, $headers);
            }

            throw $e;
        }
    }

    protected function toCommaString(string|array $value): string
    {
        if (is_array($value)) {
            return implode(',', $value);
        }

        return $value;
    }

    protected function uriToId(string|array $uriIds, string $type): string|array
    {
        $type = 'spotify:' . $type . ':';

        $uriIds = array_map(function ($id) use ($type) {
            return str_replace($type, '', $id);
        }, (array) $uriIds);

        return count($uriIds) == 1 ? $uriIds[0] : $uriIds;
    }

    public function addMyAlbums(string|array $albums): bool
    {
        $albums = $this->uriToId($albums, 'album');
        $albums = json_encode([
            'ids' => (array) $albums,
        ]);

        $headers = [
            'Content-Type' => 'application/json',
        ];

        $uri = '/v1/me/albums';

        $this->lastResponse = $this->sendRequest('PUT', $uri, $albums, $headers);

        return $this->lastResponse['status'] == 200;
    }

    public function addMyEpisodes(string|array $episodes): bool
    {
        $episodes = $this->uriToId($episodes, 'episode');
        $episodes = json_encode([
            'ids' => (array) $episodes,
        ]);

        $headers = [
            'Content-Type' => 'application/json',
        ];

        $uri = '/v1/me/episodes';

        $this->lastResponse = $this->sendRequest('PUT', $uri, $episodes, $headers);

        return $this->lastResponse['status'] == 200;
    }

    public function addMyShows(string|array $shows): bool
    {
        $shows = $this->uriToId($shows, 'show');
        $shows = json_encode([
            'ids' => (array) $shows,
        ]);

        $headers = [
            'Content-Type' => 'application/json',
        ];

        $uri = '/v1/me/shows';

        $this->lastResponse = $this->sendRequest('PUT', $uri, $shows, $headers);

        return $this->lastResponse['status'] == 200;
    }

    public function addMyTracks(string|array $tracks): bool
    {
        $tracks = $this->uriToId($tracks, 'track');
        $tracks = json_encode([
            'ids' => (array) $tracks,
        ]);

        $headers = [
            'Content-Type' => 'application/json',
        ];

        $uri = '/v1/me/tracks';

        $this->lastResponse = $this->sendRequest('PUT', $uri, $tracks, $headers);

        return $this->lastResponse['status'] == 200;
    }

    public function addPlaylistTracks(
        string $playlistId,
        string|array $tracks,
        array|object $options = []
    ): string|bool {
        $options = array_merge((array) $options, [
            'uris' => (array) $this->idToUri($tracks, 'track')
        ]);

        $options = json_encode($options);

        $headers = [
            'Content-Type' => 'application/json',
        ];

        $playlistId = $this->uriToId($playlistId, 'playlist');

        $uri = '/v1/playlists/' . $playlistId . '/tracks';

        $this->lastResponse = $this->sendRequest('POST', $uri, $options, $headers);

        return $this->getSnapshotId($this->lastResponse['body']);
    }

    public function changeMyDevice(array|object $options): bool
    {
        $options = array_merge((array) $options, [
            'device_ids' => (array) $options['device_ids'],
        ]);

        $options = json_encode($options);

        $headers = [
            'Content-Type' => 'application/json',
        ];

        $uri = '/v1/me/player';

        $this->lastResponse = $this->sendRequest('PUT', $uri, $options, $headers);

        return $this->lastResponse['status'] == 204;
    }

    public function changeVolume(array|object $options): bool
    {
        $options = http_build_query($options, '', '&');

        // We need to manually append data to the URI since it's a PUT request
        $uri = '/v1/me/player/volume?' . $options;

        $this->lastResponse = $this->sendRequest('PUT', $uri);

        return $this->lastResponse['status'] == 204;
    }

    public function createPlaylist(string|array|object $userId, array|object $options = []): array|object
    {
        if (is_array($userId) || is_object($userId)) {
            trigger_error(
                'Calling SpotifyWebAPI::createPlaylist() without a user ID is deprecated.',
                E_USER_DEPRECATED
            );

            $options = $userId;
            $uri = '/v1/me/playlists';
        } else {
            $userId = $this->uriToId($userId, 'user');
            $uri = '/v1/users/' . $userId . '/playlists';
        }

        $options = json_encode($options);

        $headers = [
            'Content-Type' => 'application/json',
        ];

        $this->lastResponse = $this->sendRequest('POST', $uri, $options, $headers);

        return $this->lastResponse['body'];
    }

    public function currentUserFollows(string $type, string|array $ids): array
    {
        $ids = $this->uriToId($ids, $type);
        $ids = $this->toCommaString($ids);

        $options = [
            'ids' => $ids,
            'type' => $type,
        ];

        $uri = '/v1/me/following/contains';

        $this->lastResponse = $this->sendRequest('GET', $uri, $options);

        return $this->lastResponse['body'];
    }

    public function deleteMyAlbums(string|array $albums): bool
    {
        $albums = $this->uriToId($albums, 'album');
        $albums = json_encode([
            'ids' => (array) $albums,
        ]);

        $headers = [
            'Content-Type' => 'application/json',
        ];

        $uri = '/v1/me/albums';

        $this->lastResponse = $this->sendRequest('DELETE', $uri, $albums, $headers);

        return $this->lastResponse['status'] == 200;
    }

    public function deleteMyEpisodes(string|array $episodes): bool
    {
        $episodes = $this->uriToId($episodes, 'episode');
        $episodes = json_encode([
            'ids' => (array) $episodes,
        ]);

        $headers = [
            'Content-Type' => 'application/json',
        ];

        $uri = '/v1/me/episodes';

        $this->lastResponse = $this->sendRequest('DELETE', $uri, $episodes, $headers);

        return $this->lastResponse['status'] == 200;
    }

    public function deleteMyShows(string|array $shows): bool
    {
        $shows = $this->uriToId($shows, 'show');
        $shows = json_encode([
            'ids' => (array) $shows,
        ]);

        $headers = [
            'Content-Type' => 'application/json',
        ];

        $uri = '/v1/me/shows';

        $this->lastResponse = $this->sendRequest('DELETE', $uri, $shows, $headers);

        return $this->lastResponse['status'] == 200;
    }

    public function deleteMyTracks(string|array $tracks): bool
    {
        $tracks = $this->uriToId($tracks, 'track');
        $tracks = json_encode([
            'ids' => (array) $tracks,
        ]);

        $headers = [
            'Content-Type' => 'application/json',
        ];

        $uri = '/v1/me/tracks';

        $this->lastResponse = $this->sendRequest('DELETE', $uri, $tracks, $headers);

        return $this->lastResponse['status'] == 200;
    }

    public function deletePlaylistTracks(string $playlistId, array $tracks, string $snapshotId = ''): string|bool
    {
        $options = [];

        if ($snapshotId) {
            $options['snapshot_id'] = $snapshotId;
        }

        if (isset($tracks['positions'])) {
            $options['positions'] = $tracks['positions'];
        } else {
            $options['tracks'] = array_map(function ($track) {
                $track = (array) $track;

                if (isset($track['positions'])) {
                    $track['positions'] = (array) $track['positions'];
                }

                $track['uri'] = $this->idToUri($track['uri'], 'track');

                return $track;
            }, $tracks['tracks']);
        }

        $options = json_encode($options);

        $headers = [
            'Content-Type' => 'application/json',
        ];

        $playlistId = $this->uriToId($playlistId, 'playlist');

        $uri = '/v1/playlists/' . $playlistId . '/tracks';

        $this->lastResponse = $this->sendRequest('DELETE', $uri, $options, $headers);

        return $this->getSnapshotId($this->lastResponse['body']);
    }

    public function followArtistsOrUsers(string $type, string|array $ids): bool
    {
        $ids = $this->uriToId($ids, $type);
        $ids = json_encode([
            'ids' => (array) $ids,
        ]);

        $headers = [
            'Content-Type' => 'application/json',
        ];

        // We need to manually append data to the URI since it's a PUT request
        $uri = '/v1/me/following?type=' . $type;

        $this->lastResponse = $this->sendRequest('PUT', $uri, $ids, $headers);

        return $this->lastResponse['status'] == 204;
    }

    public function followPlaylist(string $playlistId, array|object $options = []): bool
    {
        $options = $options ? json_encode($options) : '';

        $headers = [
            'Content-Type' => 'application/json',
        ];

        $playlistId = $this->uriToId($playlistId, 'playlist');

        $uri = '/v1/playlists/' . $playlistId . '/followers';

        $this->lastResponse = $this->sendRequest('PUT', $uri, $options, $headers);

        return $this->lastResponse['status'] == 200;
    }

    public function getAlbum(string $albumId, array|object $options = []): array|object
    {
        $albumId = $this->uriToId($albumId, 'album');
        $uri = '/v1/albums/' . $albumId;

        $this->lastResponse = $this->sendRequest('GET', $uri, $options);

        return $this->lastResponse['body'];
    }

    public function getAlbums(array $albumIds, array|object $options = []): array|object
    {
        $albumIds = $this->uriToId($albumIds, 'album');
        $options = array_merge((array) $options, [
            'ids' => $this->toCommaString($albumIds),
        ]);

        $uri = '/v1/albums/';

        $this->lastResponse = $this->sendRequest('GET', $uri, $options);

        return $this->lastResponse['body'];
    }

    public function getAlbumTracks(string $albumId, array|object $options = []): array|object
    {
        $albumId = $this->uriToId($albumId, 'album');
        $uri = '/v1/albums/' . $albumId . '/tracks';

        $this->lastResponse = $this->sendRequest('GET', $uri, $options);

        return $this->lastResponse['body'];
    }

    public function getArtist(string $artistId): array|object
    {
        $artistId = $this->uriToId($artistId, 'artist');
        $uri = '/v1/artists/' . $artistId;

        $this->lastResponse = $this->sendRequest('GET', $uri);

        return $this->lastResponse['body'];
    }

    public function getArtists(array $artistIds): array|object
    {
        $artistIds = $this->uriToId($artistIds, 'artist');
        $artistIds = $this->toCommaString($artistIds);

        $options = [
            'ids' => $artistIds,
        ];

        $uri = '/v1/artists/';

        $this->lastResponse = $this->sendRequest('GET', $uri, $options);

        return $this->lastResponse['body'];
    }

    public function getArtistRelatedArtists(string $artistId): array|object
    {
        $artistId = $this->uriToId($artistId, 'artist');
        $uri = '/v1/artists/' . $artistId . '/related-artists';

        $this->lastResponse = $this->sendRequest('GET', $uri);

        return $this->lastResponse['body'];
    }

    public function getArtistAlbums(string $artistId, array|object $options = []): array|object
    {
        $options = (array) $options;

        if (isset($options['include_groups'])) {
            $options['include_groups'] = $this->toCommaString($options['include_groups']);
        }

        $artistId = $this->uriToId($artistId, 'artist');
        $uri = '/v1/artists/' . $artistId . '/albums';

        $this->lastResponse = $this->sendRequest('GET', $uri, $options);

        return $this->lastResponse['body'];
    }

    public function getArtistTopTracks(string $artistId, array|object $options): array|object
    {
        $artistId = $this->uriToId($artistId, 'artist');
        $uri = '/v1/artists/' . $artistId . '/top-tracks';

        $this->lastResponse = $this->sendRequest('GET', $uri, $options);

        return $this->lastResponse['body'];
    }

    public function getAudioAnalysis(string $trackId): array|object
    {
        $trackId = $this->uriToId($trackId, 'track');
        $uri = '/v1/audio-analysis/' . $trackId;

        $this->lastResponse = $this->sendRequest('GET', $uri);

        return $this->lastResponse['body'];
    }

    public function getAudiobook(string $audiobookId, array|object $options = [])
    {
        $audiobookId = $this->uriToId($audiobookId, 'show');
        $uri = '/v1/audiobooks/' . $audiobookId;

        $this->lastResponse = $this->sendRequest('GET', $uri, $options);

        return $this->lastResponse['body'];
    }

    public function getAudiobooks(array $audiobookIds, array|object $options = [])
    {
        $audiobookIds = $this->uriToId($audiobookIds, 'show');
        $audiobookIds = $this->toCommaString($audiobookIds);

        $options = array_merge((array) $options, [
            'ids' => $this->toCommaString($audiobookIds),
        ]);

        $uri = '/v1/audiobooks/';

        $this->lastResponse = $this->sendRequest('GET', $uri, $options);

        return $this->lastResponse['body'];
    }

    public function getAudioFeatures(string $trackId): array|object
    {
        $trackId = $this->uriToId($trackId, 'track');
        $uri = '/v1/audio-features/' . $trackId;

        $this->lastResponse = $this->sendRequest('GET', $uri);

        return $this->lastResponse['body'];
    }

    public function getCategoriesList(array|object $options = []): array|object
    {
        $uri = '/v1/browse/categories';

        $this->lastResponse = $this->sendRequest('GET', $uri, $options);

        return $this->lastResponse['body'];
    }

    /**
     * Get a single category used to tag items in Spotify (on, for example, the Spotify player’s "Discover" tab).
     * https://developer.spotify.com/documentation/web-api/reference/#/operations/get-a-category
     *
     * @param string $categoryId ID of the category.
     *
     * @param array|object $options Optional. Options for the category.
     * - string locale Optional. Language to show category in, for example 'sv_SE'.
     * - string country Optional. ISO 3166-1 alpha-2 country code. Show category from this country.
     *
     * @return array|object The category. Type is controlled by the `return_assoc` option.
     */
    public function getCategory(string $categoryId, array|object $options = []): array|object
    {
        $uri = '/v1/browse/categories/' . $categoryId;

        $this->lastResponse = $this->sendRequest('GET', $uri, $options);

        return $this->lastResponse['body'];
    }

    public function getCategoryPlaylists(string $categoryId, array|object $options = []): array|object
    {
        $uri = '/v1/browse/categories/' . $categoryId . '/playlists';

        $this->lastResponse = $this->sendRequest('GET', $uri, $options);

        return $this->lastResponse['body'];
    }

    public function getChapter(string $chapterId, array|object $options = [])
    {
        $chapterId = $this->uriToId($chapterId, 'episode');
        $uri = '/v1/chapters/' . $chapterId;

        $this->lastResponse = $this->sendRequest('GET', $uri, $options);

        return $this->lastResponse['body'];
    }

    public function getChapters(array $chapterIds, array|object $options = [])
    {
        $chapterIds = $this->uriToId($chapterIds, 'episode');
        $options = array_merge((array) $options, [
            'ids' => $this->toCommaString($chapterIds),
        ]);

        $uri = '/v1/chapters/';

        $this->lastResponse = $this->sendRequest('GET', $uri, $options);

        return $this->lastResponse['body'];
    }

    public function getEpisode(string $episodeId, array|object $options = []): array|object
    {
        $episodeId = $this->uriToId($episodeId, 'episode');
        $uri = '/v1/episodes/' . $episodeId;

        $this->lastResponse = $this->sendRequest('GET', $uri, $options);

        return $this->lastResponse['body'];
    }

    public function getEpisodes(string|array $episodeIds, array|object $options = []): array|object
    {
        $episodeIds = $this->uriToId($episodeIds, 'episode');
        $options = array_merge((array) $options, [
            'ids' => $this->toCommaString($episodeIds),
        ]);

        $uri = '/v1/episodes/';

        $this->lastResponse = $this->sendRequest('GET', $uri, $options);

        return $this->lastResponse['body'];
    }

    public function getFeaturedPlaylists(array|object $options = []): array|object
    {
        $uri = '/v1/browse/featured-playlists';

        $this->lastResponse = $this->sendRequest('GET', $uri, $options);

        return $this->lastResponse['body'];
    }

    public function getGenreSeeds(): array|object
    {
        $uri = '/v1/recommendations/available-genre-seeds';

        $this->lastResponse = $this->sendRequest('GET', $uri);

        return $this->lastResponse['body'];
    }

    public function getLastResponse(): array
    {
        return $this->lastResponse;
    }

    public function getMarkets(): array|object
    {
        $uri = '/v1/markets';

        $this->lastResponse = $this->sendRequest('GET', $uri);

        return $this->lastResponse['body'];
    }

    public function getMultipleAudioFeatures(string|array $trackIds): array|object
    {
        $trackIds = $this->uriToId($trackIds, 'track');
        $options = [
            'ids' => $this->toCommaString($trackIds),
        ];

        $uri = '/v1/audio-features';

        $this->lastResponse = $this->sendRequest('GET', $uri, $options);

        return $this->lastResponse['body'];
    }

    public function getMyCurrentTrack(array|object $options = []): array|object|null
    {
        $uri = '/v1/me/player/currently-playing';
        $options = (array) $options;

        if (isset($options['additional_types'])) {
            $options['additional_types'] = $this->toCommaString($options['additional_types']);
        }

        $this->lastResponse = $this->sendRequest('GET', $uri, $options);

        return $this->lastResponse['body'];
    }

    public function getMyDevices(): array|object
    {
        $uri = '/v1/me/player/devices';

        $this->lastResponse = $this->sendRequest('GET', $uri);

        return $this->lastResponse['body'];
    }

    public function getMyCurrentPlaybackInfo(array|object $options = []): array|object|null
    {
        $uri = '/v1/me/player';
        $options = (array) $options;

        if (isset($options['additional_types'])) {
            $options['additional_types'] = $this->toCommaString($options['additional_types']);
        }

        $this->lastResponse = $this->sendRequest('GET', $uri, $options);

        return $this->lastResponse['body'];
    }

    public function getMyPlaylists(array|object $options = []): array|object
    {
        $uri = '/v1/me/playlists';

        $this->lastResponse = $this->sendRequest('GET', $uri, $options);

        return $this->lastResponse['body'];
    }

    public function getMyQueue()
    {
        $uri = '/v1/me/player/queue';

        $this->lastResponse = $this->sendRequest('GET', $uri, []);

        return $this->lastResponse['body'];
    }

    public function getMyRecentTracks(array|object $options = []): array|object
    {
        $uri = '/v1/me/player/recently-played';

        $this->lastResponse = $this->sendRequest('GET', $uri, $options);

        return $this->lastResponse['body'];
    }

    public function getMySavedAlbums(array|object $options = []): array|object
    {
        $uri = '/v1/me/albums';

        $this->lastResponse = $this->sendRequest('GET', $uri, $options);

        return $this->lastResponse['body'];
    }

    public function getMySavedEpisodes(array|object $options = []): array|object
    {
        $uri = '/v1/me/episodes';

        $this->lastResponse = $this->sendRequest('GET', $uri, $options);

        return $this->lastResponse['body'];
    }

    public function getMySavedTracks(array|object $options = []): array|object
    {
        $uri = '/v1/me/tracks';

        $this->lastResponse = $this->sendRequest('GET', $uri, $options);

        return $this->lastResponse['body'];
    }

    public function getMySavedShows(array|object $options = []): array|object
    {
        $uri = '/v1/me/shows';

        $this->lastResponse = $this->sendRequest('GET', $uri, $options);

        return $this->lastResponse['body'];
    }

    public function getMyTop(string $type, array|object $options = []): array|object
    {
        $uri = '/v1/me/top/' . $type;

        $this->lastResponse = $this->sendRequest('GET', $uri, $options);

        return $this->lastResponse['body'];
    }

    public function getNewReleases(array|object $options = []): array|object
    {
        $uri = '/v1/browse/new-releases';

        $this->lastResponse = $this->sendRequest('GET', $uri, $options);

        return $this->lastResponse['body'];
    }

    public function getPlaylist(string $playlistId, array|object $options = []): array|object
    {
        $options = (array) $options;

        if (isset($options['fields'])) {
            $options['fields'] = $this->toCommaString($options['fields']);
        }

        if (isset($options['additional_types'])) {
            $options['additional_types'] = $this->toCommaString($options['additional_types']);
        }

        $playlistId = $this->uriToId($playlistId, 'playlist');

        $uri = '/v1/playlists/' . $playlistId;

        $this->lastResponse = $this->sendRequest('GET', $uri, $options);

        return $this->lastResponse['body'];
    }

    public function getPlaylistImage(string $playlistId): array|object
    {
        $playlistId = $this->uriToId($playlistId, 'playlist');

        $uri = '/v1/playlists/' . $playlistId . '/images';

        $this->lastResponse = $this->sendRequest('GET', $uri);

        return $this->lastResponse['body'];
    }

    public function getPlaylistTracks(string $playlistId, array|object $options = []): array|object
    {
        $options = (array) $options;

        if (isset($options['fields'])) {
            $options['fields'] = $this->toCommaString($options['fields']);
        }

        if (isset($options['additional_types'])) {
            $options['additional_types'] = $this->toCommaString($options['additional_types']);
        }

        $playlistId = $this->uriToId($playlistId, 'playlist');

        $uri = '/v1/playlists/' . $playlistId . '/tracks';

        $this->lastResponse = $this->sendRequest('GET', $uri, $options);

        return $this->lastResponse['body'];
    }

    public function getRecommendations(array|object $options = []): array|object
    {
        $options = (array) $options;

        array_walk($options, function (&$value, $key) {
            if (substr($key, 0, 5) == 'seed_') {
                $value = $this->toCommaString($value);
            }
        });

        $uri = '/v1/recommendations';

        $this->lastResponse = $this->sendRequest('GET', $uri, $options);

        return $this->lastResponse['body'];
    }

    public function getRecommendedTracks(string $trackId): array|object
    {
        $trackId = $this->uriToId($trackId, 'track');
        $options = [
            'seed_tracks' => $trackId,
            'limit' => 10, // Lấy tối đa 5 bài hát gợi ý
        ];
        array_walk($options, function (&$value, $key) {
            if (substr($key, 0, 5) == 'seed_') {
                $value = $this->toCommaString($value);
            }
        });

        $uri = '/v1/recommendations';

        $this->lastResponse = $this->sendRequest('GET', $uri, $options);

        return $this->lastResponse['body'];
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    public function getShow(string $showId, array|object $options = []): array|object
    {
        $showId = $this->uriToId($showId, 'show');
        $uri = '/v1/shows/' . $showId;

        $this->lastResponse = $this->sendRequest('GET', $uri, $options);

        return $this->lastResponse['body'];
    }

    public function getShowEpisodes(string $showId, array|object $options = []): array|object
    {
        $showId = $this->uriToId($showId, 'show');
        $uri = '/v1/shows/' . $showId . '/episodes';

        $this->lastResponse = $this->sendRequest('GET', $uri, $options);

        return $this->lastResponse['body'];
    }

    public function getShows(string|array $showIds, array|object $options = []): array|object
    {
        $showIds = $this->uriToId($showIds, 'show');
        $options = array_merge((array) $options, [
            'ids' => $this->toCommaString($showIds),
        ]);

        $uri = '/v1/shows/';

        $this->lastResponse = $this->sendRequest('GET', $uri, $options);

        return $this->lastResponse['body'];
    }

    public function getTrack(string $trackId, array|object $options = []): array|object
    {
        $trackId = $this->uriToId($trackId, 'track');
        $uri = '/v1/tracks/' . $trackId;

        $this->lastResponse = $this->sendRequest('GET', $uri, $options);

        return $this->lastResponse['body'];
    }

    public function getTracks(array $trackIds, array|object $options = []): array|object
    {
        $trackIds = $this->uriToId($trackIds, 'track');
        $options = array_merge((array) $options, [
            'ids' => $this->toCommaString($trackIds),
        ]);

        $uri = '/v1/tracks/';

        $this->lastResponse = $this->sendRequest('GET', $uri, $options);

        return $this->lastResponse['body'];
    }

    public function getUser(string $userId): array|object
    {
        $userId = $this->uriToId($userId, 'user');
        $uri = '/v1/users/' . $userId;

        $this->lastResponse = $this->sendRequest('GET', $uri);

        return $this->lastResponse['body'];
    }

    public function getUserFollowedArtists(array|object $options = []): array|object
    {
        $options = (array) $options;

        if (!isset($options['type'])) {
            $options['type'] = 'artist'; // Undocumented until more values are supported.
        }

        $uri = '/v1/me/following';

        $this->lastResponse = $this->sendRequest('GET', $uri, $options);

        return $this->lastResponse['body'];
    }

    public function getUserPlaylists(string $userId, array|object $options = []): array|object
    {
        $userId = $this->uriToId($userId, 'user');
        $uri = '/v1/users/' . $userId . '/playlists';

        $this->lastResponse = $this->sendRequest('GET', $uri, $options);

        return $this->lastResponse['body'];
    }

    public function me(): array|object
    {
        $uri = '/v1/me';

        $this->lastResponse = $this->sendRequest('GET', $uri);

        return $this->lastResponse['body'];
    }

    public function myAlbumsContains(string|array $albums): array
    {
        $albums = $this->uriToId($albums, 'album');
        $albums = $this->toCommaString($albums);

        $options = [
            'ids' => $albums,
        ];

        $uri = '/v1/me/albums/contains';

        $this->lastResponse = $this->sendRequest('GET', $uri, $options);

        return $this->lastResponse['body'];
    }

    public function myEpisodesContains(string|array $episodes): array
    {
        $episodes = $this->uriToId($episodes, 'episode');
        $episodes = $this->toCommaString($episodes);

        $options = [
            'ids' => $episodes,
        ];

        $uri = '/v1/me/episodes/contains';

        $this->lastResponse = $this->sendRequest('GET', $uri, $options);

        return $this->lastResponse['body'];
    }

    public function myShowsContains(string|array $shows): array
    {
        $shows = $this->uriToId($shows, 'show');
        $shows = $this->toCommaString($shows);

        $options = [
            'ids' => $shows,
        ];

        $uri = '/v1/me/shows/contains';

        $this->lastResponse = $this->sendRequest('GET', $uri, $options);

        return $this->lastResponse['body'];
    }

    public function myTracksContains(string|array $tracks): array
    {
        $tracks = $this->uriToId($tracks, 'track');
        $tracks = $this->toCommaString($tracks);

        $options = [
            'ids' => $tracks,
        ];

        $uri = '/v1/me/tracks/contains';

        $this->lastResponse = $this->sendRequest('GET', $uri, $options);

        return $this->lastResponse['body'];
    }

    public function next(string $deviceId = ''): bool
    {
        $uri = '/v1/me/player/next';

        // We need to manually append data to the URI since it's a POST request
        if ($deviceId) {
            $uri = $uri . '?device_id=' . $deviceId;
        }

        $this->lastResponse = $this->sendRequest('POST', $uri);

        return $this->lastResponse['status'] == 204;
    }

    public function pause(string $deviceId = ''): bool
    {
        $uri = '/v1/me/player/pause';

        // We need to manually append data to the URI since it's a PUT request
        if ($deviceId) {
            $uri = $uri . '?device_id=' . $deviceId;
        }

        $this->lastResponse = $this->sendRequest('PUT', $uri);

        return $this->lastResponse['status'] == 204;
    }

    /**
     * Start playback for the current user.
     * https://developer.spotify.com/documentation/web-api/reference/#/operations/start-a-users-playback
     *
     * @param string $deviceId Optional. ID of the device to play on.
     * @param array|object $options Optional. Options for the playback.
     * - string context_uri Optional. URI of the context to play, for example an album.
     * - array uris Optional. Spotify track URIs to play.
     * - object offset Optional. Indicates from where in the context playback should start.
     * - int position_ms. Optional. Indicates the position to start playback from.
     *
     * @return bool Whether the playback was successfully started.
     */
    public function play(string $deviceId = '', array|object $options = []): bool
    {
        $options = $options ? json_encode($options) : '';

        $headers = [
            'Content-Type' => 'application/json',
        ];

        $uri = '/v1/me/player/play';

        if ($deviceId) {
            $uri = $uri . '?device_id=' . $deviceId;
        }

        $this->lastResponse = $this->sendRequest('PUT', $uri, $options, $headers);

        return $this->lastResponse['status'] == 204;
    }

    public function previous(string $deviceId = ''): bool
    {
        $uri = '/v1/me/player/previous';

        if ($deviceId) {
            $uri = $uri . '?device_id=' . $deviceId;
        }

        $this->lastResponse = $this->sendRequest('POST', $uri);

        return $this->lastResponse['status'] == 204;
    }

    public function queue(string $trackUri, string $deviceId = ''): bool
    {
        $uri = '/v1/me/player/queue?uri=' . $this->idToUri($trackUri, 'track');

        if ($deviceId) {
            $uri = $uri . '&device_id=' . $deviceId;
        }

        $this->lastResponse = $this->sendRequest('POST', $uri);

        return $this->lastResponse['status'] == 204;
    }

    public function reorderPlaylistTracks(string $playlistId, array|object $options): string|bool
    {
        $options = json_encode($options);

        $headers = [
            'Content-Type' => 'application/json',
        ];

        $playlistId = $this->uriToId($playlistId, 'playlist');

        $uri = '/v1/playlists/' . $playlistId . '/tracks';

        $this->lastResponse = $this->sendRequest('PUT', $uri, $options, $headers);

        return $this->getSnapshotId($this->lastResponse['body']);
    }

    public function repeat(array|object $options): bool
    {
        $options = http_build_query($options, '', '&');

        $uri = '/v1/me/player/repeat?' . $options;

        $this->lastResponse = $this->sendRequest('PUT', $uri);

        return $this->lastResponse['status'] == 204;
    }

    public function replacePlaylistTracks(string $playlistId, string|array $tracks): bool
    {
        $tracks = $this->idToUri($tracks, 'track');
        $tracks = json_encode([
            'uris' => (array) $tracks,
        ]);

        $headers = [
            'Content-Type' => 'application/json',
        ];

        $playlistId = $this->uriToId($playlistId, 'playlist');

        $uri = '/v1/playlists/' . $playlistId . '/tracks';

        $this->lastResponse = $this->sendRequest('PUT', $uri, $tracks, $headers);

        return $this->lastResponse['status'] == 200;
    }

    public function search(string $query, string|array $type, array|object $options = []): array|object
    {
        $options = array_merge((array) $options, [
            'q' => $query,
            'type' => $this->toCommaString($type),
        ]);

        $uri = '/v1/search';

        $this->lastResponse = $this->sendRequest('GET', $uri, $options);

        return $this->lastResponse['body'];
    }

    public function seek(array|object $options): bool
    {
        $options = http_build_query($options, '', '&');

        $uri = '/v1/me/player/seek?' . $options;

        $this->lastResponse = $this->sendRequest('PUT', $uri);

        return $this->lastResponse['status'] == 204;
    }

    public function setAccessToken(string $accessToken): self
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    public function setOptions(array|object $options): self
    {
        $this->options = array_merge($this->options, (array) $options);

        return $this;
    }

    public function setSession(?Session $session): self
    {
        $this->session = $session;

        return $this;
    }

    public function shuffle(array|object $options): bool
    {
        $options = array_merge((array) $options, [
            'state' => $options['state'] ? 'true' : 'false',
        ]);

        $options = http_build_query($options, '', '&');

        $uri = '/v1/me/player/shuffle?' . $options;

        $this->lastResponse = $this->sendRequest('PUT', $uri);

        return $this->lastResponse['status'] == 204;
    }

    public function unfollowArtistsOrUsers(string $type, string|array $ids): bool
    {
        $ids = $this->uriToId($ids, $type);
        $ids = json_encode([
            'ids' => (array) $ids,
        ]);

        $headers = [
            'Content-Type' => 'application/json',
        ];

        $uri = '/v1/me/following?type=' . $type;

        $this->lastResponse = $this->sendRequest('DELETE', $uri, $ids, $headers);

        return $this->lastResponse['status'] == 204;
    }

    public function unfollowPlaylist(string $playlistId): bool
    {
        $playlistId = $this->uriToId($playlistId, 'playlist');
        $uri = '/v1/playlists/' . $playlistId . '/followers';

        $this->lastResponse = $this->sendRequest('DELETE', $uri);

        return $this->lastResponse['status'] == 200;
    }

    public function updatePlaylist(string $playlistId, array|object $options): bool
    {
        $options = json_encode($options);

        $headers = [
            'Content-Type' => 'application/json',
        ];

        $playlistId = $this->uriToId($playlistId, 'playlist');

        $uri = '/v1/playlists/' . $playlistId;

        $this->lastResponse = $this->sendRequest('PUT', $uri, $options, $headers);

        return $this->lastResponse['status'] == 200;
    }

    public function updatePlaylistImage(string $playlistId, string $imageData): bool
    {
        $playlistId = $this->uriToId($playlistId, 'playlist');

        $uri = '/v1/playlists/' . $playlistId . '/images';

        $this->lastResponse = $this->sendRequest('PUT', $uri, $imageData);

        return $this->lastResponse['status'] == 202;
    }

    public function usersFollowPlaylist(string $playlistId, array|object $options): array
    {
        $options = (array) $options;

        if (isset($options['ids'])) {
            $options['ids'] = $this->uriToId($options['ids'], 'user');
            $options['ids'] = $this->toCommaString($options['ids']);
        }

        $playlistId = $this->uriToId($playlistId, 'playlist');

        $uri = '/v1/playlists/' . $playlistId . '/followers/contains';

        $this->lastResponse = $this->sendRequest('GET', $uri, $options);

        return $this->lastResponse['body'];
    }
}
