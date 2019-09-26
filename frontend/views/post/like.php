<?php
/**
 * @var $this \frontend\components\View
 * @var $model \frontend\models\PostProvider
 * @var $similarPosts \frontend\models\PostProvider[]
 */

$similarPosts = $model->getSimilarPosts(4);
$title        = 'O\'xshash maqolalar';
$lastNews     = false;
if (count($similarPosts) < 2) {
    $title        = 'So\'nggi yangiliklar';
    $similarPosts = \frontend\models\PostProvider::getLastPosts(6, false, [$model->_id]);
    $lastNews     = true;
}
?>
<?php if (count($similarPosts)): ?>
    <div class="bottom-area">
        <div class="container">
            <div class="related-posts">
                <h5 class="u-border-title"><?= __($title) ?></h5>
                <div class="row">
                    <?php foreach ($similarPosts as $i => $similarPost) : ?>
                        <div class="col-lg-6">
                            <article class="post">
                                <?php if (!$lastNews || $i < 2): ?>
                                    <div class="entry-media">
                                        <div class="placeholder">
                                            <a href="<?= $similarPost->getViewUrl() ?>">
                                                <img alt="<?= $similarPost->title ?>"
                                                     src="<?= $similarPost->getCroppedImage(370, 220) ?>">
                                            </a>
                                        </div>
                                    </div>
                                <?php endif; ?>

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