<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2017. ActiveMedia Solutions LLC
 * @author    Rustam Mamadaminov <rmamdaminov@gmail.com>
 */

use frontend\models\PostProvider;

/**
 * Created by PhpStorm.
 * Date: 12/16/17
 * Time: 2:42 PM
 */

/** @var  $model PostProvider */
?>
<div class="latest__news-text">
    <div class="news__item is_photo clickable-block">
        <div class="media-info">
            <div class="media is_left">
                <img src="<?= $model->getImage(180, 110) ?>" width="150" height="145"
                     alt="<?= $model->image_caption ?>">
            </div><!-- End of media-->

            <div class="info">
                <p class="news__item-meta">
                    <i class="icon clock-icon is_smaller"></i>
                    <?= $model->getShortFormattedDate() ?>
                    &nbsp;&nbsp;<span class="views"><i class="icon eye-icon"></i><?=$model->views?></span>
                </p>

                <p class="news__item-title"><a
                        href="<?= $model->getAuthorPostUrl() ?>"><?= $model->title ?></a></p>
            </div><!-- End of info-->
        </div><!-- End of media-info-->
    </div><!-- End of news__item-->
</div><!-- End of latest__news-text-->

