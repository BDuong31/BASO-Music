<?php declare(strict_types=1); 
require_once('widgetCard.php');

// Hàm để gọi API sử dụng cURL
function fetchFromAPI($url, $token) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer $token"
    ]);
    $response = curl_exec($ch);
    curl_close($ch);
    return json_decode($response, true);
}

// Hàm để render Widgets
function renderWidgets($artistID, $token) {
    // Lấy nghệ sĩ tương tự
    $similar = [];
    if ($artistID) {
        $similarResponse = fetchFromAPI("https://api.spotify.com/v1/artists/$artistID/related-artists", $token);
        if (isset($similarResponse['artists'])) {
            $similar = array_slice($similarResponse['artists'], 0, 3);
        }
    }

    // Lấy playlist nổi bật
    $featured = [];
    $featuredResponse = fetchFromAPI("https://api.spotify.com/v1/browse/featured-playlists", $token);
    if (isset($featuredResponse['playlists']['items'])) {
        $featured = array_slice($featuredResponse['playlists']['items'], 0, 3);
    }

    // Lấy album mới phát hành
    $newRelease = [];
    $newReleaseResponse = fetchFromAPI("https://api.spotify.com/v1/browse/new-releases", $token);
    if (isset($newReleaseResponse['albums']['items'])) {
        $newRelease = array_slice($newReleaseResponse['albums']['items'], 0, 3);
    }

    // Render các thẻ WidgetCard
    echo '<div class="widgets-body flex">';
    renderWidgetCard("Similar Artists", $similar, [], []);
    renderWidgetCard("Made For You", [], $featured, []);
    renderWidgetCard("New Releases", [], [], $newRelease);
    echo '</div>';
}
?>
