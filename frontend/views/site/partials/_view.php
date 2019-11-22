<?php

/**
 * @var $this \frontend\components\View
 * @var $model \frontend\models\PostProvider
 */
?>
<div class="col-12">
    <article
            class="post post-list type-post status-publish format-standard has-post-thumbnail hentry category-design <?=$model->is_ad?'ad_post':''?>">
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
                        <?= $model->getShortFormattedDate() ?>
                    </span>
                    <span class="meta-date">
                        <i class="mdi mdi-eye"></i> <?= $model->getViewLabel() ?>
                    </span>
                    <?php if ($model->hasCategory()): ?>
                        <span class="meta-category">
                            <a href="<?= $model->category->getViewUrl() ?>">
                                <?= $model->category->name ?></a>
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
