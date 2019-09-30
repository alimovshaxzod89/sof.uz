<?php
/**
 * @var $this \frontend\components\View
 * @var $posts \frontend\models\PostProvider[]
 */
$category = \common\models\Category::findOne(['_id' => new \MongoDB\BSON\ObjectId(\common\models\Post::AUTHOR_CATEGORY)])
?>
<?php if (is_array($posts) && count($posts) && $category) : ?>
    <div class="widget-posts widget widget_magsy_posts_widget">
        <h5 class="u-border-title"><?= $category->name ?></h5>
        <div class="posts pb20">
            <?php foreach ($posts as $post) : ?>
                <div>
                    <?php if ($post->hasAuthor()): ?>
                        <div class="entry-thumbnail">
                            <a class="u-permalink" href="<?= $post->author->getViewUrl() ?>"></a>
                            <img src="<?= $post->hasAuthor() ? $post->author->getCroppedImage(150, 150, 1) : $post->getCroppedImage(150, 150, 1) ?>">
                        </div>
                    <?php endif; ?>
                    <header class="entry-header">
                        <h6 class="entry-title">
                            <a href="<?= $post->getViewUrl() ?>" rel="bookmark"><?= $post->title ?></a>
                        </h6>
                        <div class="entry-meta">
                            <?php if ($post->hasAuthor()): ?>
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
        <a href="<?= $category->getViewUrl() ?>" class="infinite-scroll-button button load-button w100"><?= __('Barchasi') ?></a>
    </div>
<?php endif; ?>