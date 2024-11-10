<?php declare(strict_types=1); 
    function renderAlbumImage($url){?>
        <div class="albumImage flex">
            <img src="<?php echo $url ?>" alt="album art" class="albumImage-art"/>
            <div class="albumImage-shadow">
                <img src="<?php echo $url ?>" alt="shadow" class="albumImage-shadow" />
            </div>
        </div>
    <?php } ?>
?>
