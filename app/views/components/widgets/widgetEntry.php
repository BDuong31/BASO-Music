<?php declare(strict_types=1); 
function renderWidgetEntry($title, $subtitle, $image){ ?>
<div class="entry-body flex">
      <img src="<? $image ?> " alt=<? $title ?> class="entry-image" />
      <div class="entry-right-body flex">
        <p class="entry-title"><? $title ?></p>
        <p class="entry-subtitle"><? $subtitle ?></p>
      </div>
    </div>
<?php } ?>