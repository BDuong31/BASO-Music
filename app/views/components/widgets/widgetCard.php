<?php declare(strict_types=1); 
require_once('widgetEntry.php');

// Hàm để render một mục Widget Card
function renderWidgetCard($title, $similar = [], $featured = [], $newRelease = []) {
    echo '<div class="widgetcard-body">';
    echo '<p class="widget-title">' . $title . '</p>';

    if (!empty($similar)) {
        foreach ($similar as $artist) {
            renderWidgetEntry(
                $artist['name'],
                $artist['followers']['total'] . ' Followers',
                $artist['images'][2]['url']
            );
        }
    } elseif (!empty($featured)) {
        foreach ($featured as $playlist) {
            renderWidgetEntry(
                $playlist['name'],
                $playlist['tracks']['total'] . ' Songs',
                $playlist['images'][0]['url']
            );
        }
    } elseif (!empty($newRelease)) {
        foreach ($newRelease as $album) {
            renderWidgetEntry(
                $album['name'],
                $album['artists'][0]['name'],
                $album['images'][2]['url']
            );
        }
    }

    // Nút mũi tên điều hướng
    echo '
    <div class="widget-fade">
        <div class="fade-button">' . getChevronRightIcon() . '</div>
    </div>';

    echo '</div>';
}

function getChevronRightIcon() {
    return '
    <svg width="24" height="24" viewBox="0 0 24 24" fill="#c4d0e3" xmlns="http://www.w3.org/2000/svg">
        <path d="M9.29 6.71a1 1 0 0 1 1.42 0L15.59 12l-4.88 4.88a1 1 0 0 1-1.42-1.42L13.17 12l-3.88-3.88a1 1 0 0 1 0-1.41z"/>
    </svg>';
}
?>
