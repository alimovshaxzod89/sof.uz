<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2017. ActiveMedia Solutions LLC
 * @author    Rustam Mamadaminov <rmamdaminov@gmail.com>
 */

use frontend\models\PostProvider;

/**
 * @var $model PostProvider
 */
$item        = $model;
$hasTag      = $item->is_tagged;
$img         = $item->getImage($hasTag ? 710 : 205, $hasTag ? 380 : 150);
$exclude[]   = $item->_id;
$imgRendered = false;
?>
<article class="grid-post post type-post np-left <?= $hasTag ? 'post-tagged' : '' ?>">
    <div class=" ts-row cf">
        <?php if ($hasTag): $imgRendered = true; ?>
            <div class="col-12 tagged-img">
                <div class="post-thumb">
                    <a href="<?= $item->getViewUrl() ?>" class="image-link" data-pjax="0">
                        <img src="<?= $img ?>"
                             class="attachment-contentberg-main size-contentberg-main">
                    </a>
                </div>
            </div>
        <?php endif; ?>

        <div class="col-<?= !$imgRendered ? '9' : '12' ?> ">
            <div class="meta-title">
                <div class="post-meta post-meta-b">
                    <h2 class="post-title-alt">
                        <a href="<?= $url = $item->getViewUrl() ?>" data-pjax="0">
                            <?= $item->title ?>
                        </a>
                    </h2>
                    <div class="below">
                        <time class="post-date">
                            <?= $item->getShortFormattedDate() ?>
                        </time>
                        <span class="meta-sep"></span>
                        <span class="meta-item read-time">
                            <?= $item->getReadMinLabel() ?>
                        </span>
                        <span class="meta-sep"></span>
                        <span class="post-cat">
                            <a href=" <?= $item->category->getViewUrl() ?>"
                               class="category">
                                <?= $item->category->name ?>
                            </a>
                        </span>
                    </div>
                </div>
            </div>
            <div class="post-content post-excerpt cf">
                <p>
                    <?= $item->info ?>
                </p>
            </div>
        </div>

        <?php if (!$imgRendered): ?>
            <div class="col-3 no-940">
                <div class="post-thumb">
                    <a href="<?= $url ?>" class="image-link" data-pjax="0">

                        <?php if ($index < $load - $limit): ?>
                            <img src="<?= $img ?>"
                                 class=" attachment-contentberg-main size-contentberg-main"
                                 title="<?= $model->title ?>"
                            >
                        <?php else: ?>
                            <img data-src="<?= $img ?>"
                                 src="<?= $empty ?>"
                                 class="lazyload attachment-contentberg-main size-contentberg-main"
                                 title="<?= $model->title ?>"
                            >
                        <?php endif; ?>
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</article>
