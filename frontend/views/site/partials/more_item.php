<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2017. ActiveMedia Solutions LLC
 * @author    Rustam Mamadaminov <rmamdaminov@gmail.com>
 */

use frontend\models\PostProvider;

/**
 * Created by PhpStorm.
 * Date: 12/17/17
 * Time: 4:39 PM
 * @var PostProvider $model
 * @var int          $index
 */
?>
<div class="latest__news-text">
    <div class="news__item is_photo">
        <div class="media-info">
            <div class="media is_left">
                <img src="<?= $model->getImage(250, 145) ?>" width="250" height="145" alt="mirzoyoyev tibbiyot">
            </div><!-- End of media-->

            <div class="info">
                <p class="news__item-title">
                    <a href="<?= $model->getViewUrl() ?>"><?= $model->title ?></a>
                </p>

                <div class="news__item-meta">
                    <a href="<?= $model->category->getViewUrl() ?>"
                       class="category"><?= $model->category->name ?></a>
                    <span class="h-space"></span>
                    <span class="date-time"><i class="icon clock-icon"></i><?= $model->getShortFormattedDate() ?></span>
                    <span class="h-space"></span>
                    <span class="counters"><i class="icon comments-icon"></i><?= $model->comment_count ?><span
                                class="h-space"></span><i
                                class="icon eye-icon"></i><?= $model->views ?></span>
                </div><!-- End of news__item-meta-->
            </div><!-- End of info-->
        </div><!-- End of media-info-->
    </div><!-- End of news__item-->
</div><!-- End of latest__news-text-->