<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2017. ActiveMedia Solutions LLC
 * @author    Rustam Mamadaminov <rmamdaminov@gmail.com>
 */

use common\models\Post;
use frontend\models\PostProvider;

/**
 * @var $reads    PostProvider[]
 * @var $comments PostProvider[]
 */

?>
<div class="sidebar__most-read mini__news">

    <div class="main__news-thumb-title">
        <?= __('Muhim') ?>
    </div>

    <?php if (count($reads)): ?>
        <?php foreach ($reads as $k => $post): ?>
            <div class="news__item">
                <div class="news__item-meta">
                <span class="category"><a
                        href="<?= $post->category->getViewUrl() ?>"><?= $post->category->name ?></a></span>
                    <span class="views"><i class="icon eye-icon"></i> <?= $post->views ?></span>
                </div><!-- End of news__item-meta-->

                <p class="">
                    <a href="<?= $post->getViewUrl($post->category) ?>"><?= $post->title ?></a>
                </p>
            </div><!-- End of news__item-->
        <?php endforeach; ?>
    <?php endif; ?>
</div><!-- End of sidebar__most-read-->
