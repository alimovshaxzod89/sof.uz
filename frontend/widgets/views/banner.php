<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2017. ActiveMedia Solutions LLC
 * @author    Rustam Mamadaminov <rmamdaminov@gmail.com>
 */

use common\models\Ad;

/**
 * Created by PhpStorm.
 * Date: 12/27/17
 * Time: 7:16 PM
 * @var Ad $banner
 */
list($w, $h) = explode("x", $this->context->place);
?>

<div id="<?= $this->context->id ?>" class="banner banner-<?= $this->context->place ?>">
    <a href="<?= $banner->url ?>" target="_blank">
        <img src="<?= $banner->getImage($w, $h) ?>" width="<?= $w ?>" height="<?= $h ?>" alt="<?= $banner->title ?>">
    </a>
</div>
