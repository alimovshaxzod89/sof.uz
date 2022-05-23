<?php

use frontend\models\PostProvider;

/**
 * @var $this \frontend\components\View
 * @var $model \frontend\models\PostProvider
 * @var $similarPosts \frontend\models\PostProvider[]
 */

$similarPosts = $model->getSimilarPosts(4);
$title = 'O\'xshash maqolalar';
$lastNews = false;
if (count($similarPosts) < 2) {
    $title = 'So\'nggi yangiliklar';
    $similarPosts = \frontend\models\PostProvider::getLastPosts(4, false, [$model->_id]);
    $lastNews = true;
}
$needed = 4;
if (count($similarPosts) < $needed) {
    $needed -= count($similarPosts);
    $posts = PostProvider::getTopPost($needed);

    $posts = array_merge($similarPosts, $posts);
} else {
    $posts = $similarPosts;
}
?>

<div class="mini_news_nd">

    <div class="st_block">
        <div class="latest_title_nd">
            <div class="icon"></div>
            <h4 class="title_con"><?= __('Тавсия Этамиз') ?></h4>
        </div>
    </div>

    <div class="nd_block">

        <?php foreach ($posts as $i => $post): ?>
            <div class="<?= $i == 0 ? 'block_news_first' : ($i == 1 ? 'block_news_second' : 'block_news_third') ?>">
                <div class="block_image"
                     style='background-image: url("<?= $post->getCroppedImage(500, 350, 1) ?>")'>
                    <div></div>
                    <div class="tag_bigger"><?= $post->category->name ?></div>
                </div>
                <div class="date_post_bold">
                    <div class="calendar_icon"></div>
                    <div class="date_text"><?= $post->getShortFormattedDate() ?>, &nbsp;</div>
                    <div class="eye_icon"></div>
                    <div class="date_text"><?= $post->getViewLabel() ?></div>      
                </div>
                <div class="paragraph_bold">
                    <a href="<?= $post->getViewUrl() ?>">
                        <?= $post->title ?>
                    </a>
                </div>
            </div>
        <?php endforeach; ?>

    </div>
</div>