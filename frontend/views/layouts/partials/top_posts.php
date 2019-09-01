<?php
/**
 * @var $this \frontend\components\View
 * @var $posts \frontend\models\PostProvider[]
 */
?>
<?php if (is_array($posts) && count($posts)) : ?>
    <div class="widget-posts widget widget_magsy_posts_widget">
        <h5 class="u-border-title"><?= $title ?></h5>
        <div class="posts">
            <?php foreach ($posts as $post) : ?>
                <div>
                    <div class="entry-thumbnail">
                        <a class="u-permalink" href="<?= $post->getViewUrl() ?>"></a>
                        <img src="<?= $post->getCroppedImage(150, 150) ?>">
                    </div>
                    <header class="entry-header">
                        <h6 class="entry-title">
                            <a href="<?= $post->getViewUrl() ?>" rel="bookmark"><?= $post->title ?></a>
                        </h6>
                        <div class="entry-meta">
                            <span class="meta-date">
                                <time datetime="<?= $post->getPublishedTimeIso() ?>">
                                    <?= $post->getShortFormattedDate() ?>
                                </time>
                            </span>
                            <?php if (0 && $post->hasAuthor()): ?>
                                <span class="meta-date">
                                    <a href="<?= $post->author->getViewUrl() ?>">
                                        <?= $post->author->getFullName() ?>
                                    </a>
                                </span>
                            <?php endif; ?>
                        </div>
                    </header>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>