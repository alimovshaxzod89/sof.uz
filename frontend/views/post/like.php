<?php
/**
 * @var $this \frontend\components\View
 * @var $similarPosts \frontend\models\PostProvider[]
 */
?>
<?php if (count($similarPosts)): ?>
    <div class="bottom-area">
        <div class="container medium">
            <div class="related-posts">
                <h3 class="u-border-title"><?= __('You might also like') ?></h3>
                <div class="row">
                    <?php foreach ($similarPosts as $similarPost) : ?>
                        <div class="col-lg-6">
                            <article class="post">
                                <div class="entry-media">
                                    <div class="placeholder">
                                        <a href="<?= $similarPost->getViewUrl() ?>">
                                            <img alt="<?= $similarPost->title ?>"
                                                 src="<?= $similarPost->getCroppedImage(220, 146, 1) ?>">
                                        </a>
                                    </div>
                                </div>

                                <div class="entry-wrapper">
                                    <header class="entry-header">
                                        <h4 class="entry-title">
                                            <a href="<?= $similarPost->getViewUrl() ?>" rel="bookmark">
                                                <?= $similarPost->title ?>
                                            </a>
                                        </h4>
                                        <div class="entry-meta">
                                            <span class="meta-category">
                                                <?= $similarPost->getShortFormattedDate() ?>
                                            </span>
                                            <span class="meta-date">
                                                <i class="mdi mdi-eye"></i> <?= $similarPost->getViewLabel() ?>
                                            </span>
                                            <?php if (is_array($similarPost->categories) && count($similarPost->categories)): ?>
                                                <span class="meta-category">
                                                    <?= $similarPost->metaCategoriesList() ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </header>
                                </div>
                            </article>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>