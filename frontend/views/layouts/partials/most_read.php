<?php
/**
 * @var $this \frontend\components\View
 * @var $posts \frontend\models\PostProvider[]
 */
$posts = \frontend\models\PostProvider::getTopPosts();
?>
<?php if (is_array($posts) && count($posts)): ?>
    <div class="widget widget_magsy_picks_widget">
        <h5 class="widget-title"><?= __('Hand Picked Articles') ?></h5>
        <div class="picks-wrapper">
            <div class="icon" data-ickon="&#xf238" style="border-top-color: #EC7357; color: #FFF;"></div>
            <div class="picked-posts owl">
                <?php foreach ($posts as $post): ?>
                    <article class="post">
                        <div class="entry-thumbnail">
                            <img src="<?= $post->getCroppedImage(150, 150) ?>">
                        </div>
                        <header class="entry-header">
                            <h6 class="entry-title"><?= $post->title ?></h6>
                        </header>
                        <a class="u-permalink" href="<?= $post->getViewUrl() ?>"></a>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
<?php endif; ?>