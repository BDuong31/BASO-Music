<?php declare(strict_types=1); 
require_once('sidebarButton.php');
$token = 'YOUR_SPOTIFY_ACCESS_TOKEN';
?>
<?php function renderSideBar($token){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api.spotify.com/v1/me");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer $token"
    ]);
    $response = curl_exec($ch);
    curl_close($ch);
    $data = json_decode($response, true);
    $dataImage = $data['images'][0]['url'] ?? 'https://via.placeholder.com/150'; 
?>
        <div class="sidebar-container">
            <!-- Hiển thị ảnh đại diện -->
            <img src="<?php echo $dataImage; ?>" class="profile-img" alt="profile" />

            <!-- Các nút sidebar -->
            <div>
                <?php
                renderSidebarButton('Trending', 'btn-icon-trending', 'home.php?page=trending');
                renderSidebarButton('Player', 'btn-icon-player', 'home.php?page=player');
                renderSidebarButton('Favorites', 'btn-icon-favorites', 'home.php?page=favorites');
                renderSidebarButton('Library', 'btn-icon-library', 'home.php?page=library');
                ?>
            </div>
            
            <!-- Nút Sign Out -->
            <?php renderSidebarButton('Sign Out', 'btn-icon-logout', '#'); ?>
        </div>
<?php } ?>