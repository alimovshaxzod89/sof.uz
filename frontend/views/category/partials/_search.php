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
$img      = $model->getImage(205, 165);
$hasImage = true;
$url      = $model->getViewUrl();
$info     = $model->getHighlightedByTag($search);
?>

<article class="grid-post post type-post np-left">
    <div class=" ts-row cf">
        <div class="col-<?= $hasImage ? 9 : 12 ?>">
            <div class="meta-title">
                <div class="post-meta post-meta-b">
                    <h2 class="post-title-alt">
                        <a href="<?= $url ?>" data-pjax="0">
                            <?= $model->highlightKeywords($model->title, $search, false) ?>
                        </a>
                    </h2>
                    <div class="below">
                        <time class="post-date">
                            <?= $model->getShortFormattedDate() ?>
                        </time>
                        <span class="meta-sep"></span>
                        <span class="post-cat">
                            <a href=" <?= $model->category->getViewUrl() ?>"
                               class="category">
                                <?= $model->category->name ?>
                            </a>
                        </span>
                    </div>
                </div>
            </div>
            <div class="post-content post-excerpt cf">
                <p>
                    <?= $info ?: $model->info ?>
                </p>
            </div>
        </div>

        <?php if ($hasImage): ?>
            <div class="col-3 no-940">
                <div class="post-thumb">
                    <a href="<?= $url ?>" class="image-link" data-pjax="0">
                        <img src="<?= $img ?>"
                             class="attachment-contentberg-main size-contentberg-main">

                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</article>

