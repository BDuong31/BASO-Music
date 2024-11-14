<?php declare(strict_types=1); 
function renderWidgetEntry($title, $subtitle, $image){ ?>
<div class="entry-body flex">
      <img src="<?php echo $image ?>" alt="<?php echo $title ?>" class="entry-image">
      <div class="entry-right-body flex">
        <p class="entry-title"><?php echo $title ?></p>
        <p class="entry-subtitle"><?php echo $subtitle ?></p>
      </div>
    </div>
<?php } ?>