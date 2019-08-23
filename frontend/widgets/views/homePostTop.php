<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2017. ActiveMedia Solutions LLC
 * @author    Toir Tuychiyev <toir427@gmail.com>
 */

use frontend\models\PostProvider;

/**
 * @var $posts PostProvider[]
 * @var $big   PostProvider
 */
?>
<div class="main__news-big">
    <div class="news__item">
        <div class="news__item-media">
            <img src="<?= $big->getImage(532, 356) ?>" width="532"
                 height="356"
                 alt="<?= $big->image_caption ?>">
        </div><!-- End of news__item-media-->

        <div class="news__item-meta">
            <a href="<?= $big->category->getViewUrl() ?>"
               class="category"><?= $big->category->name ?></a>
            <span class="h-space"></span>
                <span class="date-time"><i
                        class="icon clock-icon"></i><?= $big->getShortFormattedDate() ?></span>
            <span class="h-space"></span>
                <span class="counters"><i class="icon comments-icon"></i><?= $big->comment_count ?><span
                        class="h-space"></span><i
                        class="icon eye-icon"></i><?= $big->views ?></span>
        </div><!-- End of news__item-meta-->

        <p class="title">
            <a href="<?= $big->getViewUrl($big->category) ?>"><?= $big->title ?></a>
        </p>

        <p><?= $big->info ?></p>
    </div><!-- End of news__item-->
</div><!-- End of main__news-big-->
<div class="main__news-thumb mini__news">
    <div class="main__news-thumb-title">
        <?= __('Important News') ?>
    </div><!-- End of main__news-title-->
    <?php if (empty($posts)): ?>
        <code><?= $this->context->emptyText ?></code>
    <?php else: ?>
        <?php foreach ($posts as $k => $post): ?>
            <div class="news__item <?= ($k == 0) ? 'is_photo' : '' ?>">
                <?php if ($k == 0): ?>
                    <div class="news__item-media">
                        <img src="<?= $post->getImage(250, 119) ?>" width="250"
                             height="119"
                             alt="<?= $post->image_caption ?>">
                    </div><!-- End of news__item-media-->
                <?php endif; ?>
                <div class="news__item-meta">
                    <span class="category">
                        <a href="<?= $post->category->getViewUrl() ?>">
                            <?= $post->category->name ?>
                        </a>
                    </span>
                    <span class="date-time">
                        <?= $post->getShortFormattedDate() ?>
                    </span>
                </div><!-- End of news__item-meta-->

                <p class="<?= ($k == 0) ? 'title' : '' ?>">
                    <a href="<?= $post->getViewUrl($post->category) ?>"><?= $post->title ?></a>
                </p>
            </div><!-- End of news__item-->
        <?php endforeach; ?>
    <?php endif; ?>
</div><!-- End of main__news-thumb-->