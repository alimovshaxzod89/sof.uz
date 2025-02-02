<?php
/**
 * @var $this \frontend\components\View
 * @var $title string
 * @var $posts \frontend\models\PostProvider[]
 */
?>
<?php if (is_array($posts) && count($posts)): ?>
    <div class="widget widget_magsy_picks_widget">
        <h5 class="u-border-title"><?= $title ?></h5>
        <div class="picks-wrapper">
            <div class="icon" data-ickon="&#xf238" style="border-top-color: #00AA51; color: #fff;"></div>
            <div class="picked-posts owl">
                <?php foreach ($posts as $post): ?>
                    <article class="post">
                        <div class="entry-thumbnail">
                            <img src="<?= $post->getCroppedImage(300, 200, 1) ?>">
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