<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2017. ActiveMedia Solutions LLC
 * @author    Toir Tuychiyev <toir427@gmail.com>
 */

use frontend\models\PostProvider;
use yii\helpers\Url;
use yii\widgets\Pjax;

/**
 * @var $this \frontend\widgets\SidebarTrending
 * @var $post PostProvider
 */
?>

<h3 class="block-title"><span><?= __('Trending News') ?></span></h3>

<div id="post-slide" class="owl-carousel owl-theme post-slide">
    <?php foreach ($posts->batch(2) as $row): ?>
        <div class="item">
            <?php foreach ($row as $post): ?>
                <div class="post-overaly-style text-center clearfix">
                    <div class="post-thumb">
                        <a href="<?= $post->getViewUrl() ?>">
                            <img class="img-fluid"
                                 src="<?= $post->getImage(330, 260) ?: $this->getImageUrl('news/tech/gadget1.jpg') ?>"
                                 alt=""/>
                        </a>
                    </div><!-- Post thumb end -->

                    <div class="post-content">
                        <?php if ($post->category): ?>
                            <a class="post-cat"
                               href="<?= $post->category->getViewUrl() ?>"><?= $post->category->name ?></a>
                        <?php endif; ?>
                        <h2 class="post-title">
                            <a href="<?= $post->getViewUrl() ?>"><?= $post->getShortTitle() ?></a>
                        </h2>
                        <div class="post-meta">
                            <span class="post-date"><?= $post->getShortFormattedDate() ?></span>
                        </div>
                    </div><!-- Post content end -->
                </div><!-- Post Overaly Article 1 end -->
            <?php endforeach; ?>
        </div><!-- Item 1 end -->
    <?php endforeach; ?>

</div><!-- Post slide carousel end -->