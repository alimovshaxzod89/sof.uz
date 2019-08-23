<?php

/**
 * @var $this \frontend\components\View
 * @var $model \frontend\models\PostProvider
 */
?>
<div class="col-12">
    <article
            class="post post-list type-post status-publish format-standard has-post-thumbnail hentry category-design">
        <div class="entry-media">
            <div class="placeholder" style="padding-bottom: 66.666666666667%;">
                <a href="<?= $model->getViewUrl() ?>">
                    <img src="<?= $model->getCroppedImage(220, 147) ?>" alt="<?= $model->title ?>">
                </a>
            </div>
        </div>
        <div class="entry-wrapper">
            <header class="entry-header">
                <div class="entry-meta">
                    <span class="meta-category">
                        <time datetime="<?= $model->getPublishedTimeIso() ?>">
                            <?= $model->getShortFormattedDate() ?>
                        </time>
                    </span>
                    <?php if (is_array($model->categories) && count($model->categories)): ?>
                        <span class="meta-category">
                            <?= $model->metaCategoriesList() ?>
                        </span>
                    <?php endif; ?>
                </div>

                <h2 class="entry-title">
                    <a href="<?= $model->getViewUrl() ?>" rel="bookmark">
                        <?= $model->title ?>
                    </a>
                </h2>
            </header>
            <div class="entry-excerpt u-text-format">
                <?= $model->info ?>
            </div>
        </div>
    </article>
</div>
