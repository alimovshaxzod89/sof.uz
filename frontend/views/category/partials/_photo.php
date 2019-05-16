<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2017. ActiveMedia Solutions LLC
 * @author    Rustam Mamadaminov <rmamdaminov@gmail.com>
 */

use frontend\models\PostProvider;

/**
 * @var int          $index
 * @var PostProvider $model
 */
?>
<div class="col-md-4 col-sm-6">
    <article class="entry card">
        <div class="entry__img-holder card__img-holder">
            <a href="<?= $model->getViewUrl() ?>"  data-pjax="0" >
                <div class="thumb-container thumb-70">
                    <img data-src="<?= $img = $model->getImage(320, 226) ?>" src="<?= $img ?>"
                         class="entry__img lazyloaded" alt="">
                </div>
            </a>
            <a href="#" class="entry__meta-category entry__meta-category--label entry__meta-category--align-in-corner entry__meta-category--violet">
                <?= $model->getShortFormattedDate() ?>
            </a>
        </div>

        <div class="entry__body card__body card__body-sm">
            <div class="entry__header">

                <h2 class="post-list-small__entry-title">
                    <a href="<?= $model->getViewUrl() ?>" data-pjax="0" >
                        <?= $model->title ?>
                    </a>
                </h2>

            </div>
        </div>
    </article>
</div>
