<?php declare(strict_types=1); 
require_once('sidebarButton.php');
?>
<?php function renderSideBar($token){
?>
        <div class="sidebar-container">
            <!-- Hiển thị ảnh đại diện -->
            <a id="account" href="/home/account">
                <img role="account" src="https://via.placeholder.com/150" class="profile-img" alt="profile" />
            </a>
            <!-- Các nút sidebar -->
            <div>
                <?php
                renderSidebarButton('Trending', 'btn-icon-trending', '/home/trending');
                renderSidebarButton('Player', 'btn-icon-player', '/home/player');
                renderSidebarButton('Favorites', 'btn-icon-favorites', '/home/favorites');
                renderSidebarButton('Library', 'btn-icon-library', '/home/library');
                ?>
            </div>
            
            <!-- Nút Sign Out -->
            <?php renderSidebarButton('Sign Out', 'btn-icon-logout', '/logout'); ?>
        </div>
<?php } ?>