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
$category = isset($this->params['category']) ? $this->params['category'] : false;
$img      = $model->getImage(370, 220);
?>
<div class="col-6">
    <article class="grid-post post type-post has-post-thumbnail">
        <div class="post-header cf">
            <?php if ($index % 6 == 0 || $index % 6 == 1): ?>
                <div class="post-thumb">
                    <a href="<?= $model->getViewUrl() ?>" class="image-link" data-pjax="0">
                        <?php if ($index < $load - $limit): ?>
                            <img src="<?= $img ?>"
                                 class="attachment-contentberg-grid size-contentberg-grid"
                                 title="<?= $model->title ?>"
                            >
                        <?php else: ?>
                            <img data-src="<?= $img ?>"
                                 src="<?= $empty ?>"
                                 class="attachment-contentberg-grid size-contentberg-grid lazyload"
                                 title="<?= $model->title ?>"
                            >
                        <?php endif; ?>
                    </a>
                </div>
            <?php endif; ?>
            <div class="meta-title meta-no-space">
                <div class="post-meta post-meta-b">
                    <h2 class="post-title-alt">
                        <a href="<?= $model->getViewUrl() ?>" data-pjax="0"><?= $model->title ?></a>
                    </h2>
                    <div class="below">
                        <time class="post-date"><?= $model->getShortFormattedDate() ?></time>
                        <span class="meta-sep"></span>
                        <span class="meta-item read-time"><?= $model->getReadMinLabel() ?></span>
                    </div>
                </div>
            </div>
        </div>
    </article>
</div>

