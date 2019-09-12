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
            <div class="placeholder">
                <a data-pjax="0" href="<?= $model->getViewUrl() ?>">
                    <img src="<?= $model->getCroppedImage(220, 147) ?>" alt="<?= $model->title ?>">
                </a>
            </div>
        </div>
        <div class="entry-wrapper">
            <header class="entry-header">
                <h2 class="entry-title">
                    <a data-pjax="0" href="<?= $model->getViewUrl() ?>" rel="bookmark">
                        <?= $model->title ?>
                    </a>
                </h2>
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
                    <?php if (0 && $model->hasAuthor()): ?>
                        <span class="meta-category">
                            <a data-pjax="0" href="<?= $model->author->getViewUrl() ?>">
                                <?= $model->author->getFullName() ?>
                            </a>
                        </span>
                    <?php endif; ?>
                </div>
            </header>
            <div class="entry-excerpt u-text-format">
                <?= $model->getInfoView() ?>
            </div>
        </div>
    </article>
</div>
