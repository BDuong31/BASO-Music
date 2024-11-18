<?php declare(strict_types=1); 
require_once('sidebarButton.php');
?>
<?php function renderSideBar(){
?>
        <div class="sidebar-container">
            <!-- Hiển thị ảnh đại diện -->
            <a id="account" href="/home/account">
                <img role="account" src="<?php echo $_SESSION['user']['img'] ?>" class="profile-img" alt="profile">
            </a>
            <!-- Các nút sidebar -->
            <div>
                <?php
                renderSidebarButton('Trending', 'btn-icon-trending', '/home/trending');
                renderSidebarButton('Player', 'btn-icon-player', '/home/player');
                renderSidebarButton('Favorites', 'btn-icon-favorites', '/home/favorites');
                renderSidebarButton('history', 'btn-icon-history', '/home/history');
                ?>
            </div>
            
            <!-- Nút Sign Out -->
            <?php renderSidebarButton('Sign Out', 'btn-icon-logout', '/logout'); ?>
        </div>
<?php } ?>