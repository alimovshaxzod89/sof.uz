<?php

use common\models\Ad;

/**
 * @var Ad $banner
 */
list($w, $h) = explode("x", $this->context->place);
?>

<div id="<?= $this->context->id ?>" class="banner banner-<?= $this->context->place ?>">
    <a href="<?= $banner->url ?>" target="_blank">
        <img src="<?= $banner->getImage($w, $h) ?>" width="<?= $w ?>" height="<?= $h ?>" alt="<?= $banner->title ?>">
    </a>
</div>
