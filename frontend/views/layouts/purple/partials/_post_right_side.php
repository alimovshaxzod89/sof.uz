<?php

use frontend\widgets\Banner;
use frontend\models\PostProvider;

$posts = PostProvider::getPopularPosts(5);
$posts = PostProvider::getTopPost(5);
?>

<div class="post_popular_block">

    <div class="latest_title">
        <div class="icon"></div>
        <h4 class="title_con"><?= __('Тавсия Этамиз') ?></h4>
    </div>


    <?php foreach ($posts as $i => $post): ?>
        <div class="mini_post_popular">
            <div class="text_popular">
                <div class="date_post_popular">
                    <div class="calendar_icon"></div>
                    <div class="date_text"><?= $post->getShortFormattedDate() ?>, &nbsp;</div>
                    <div class="eye_icon d-none"></div>
                    <div class="date_text d-none"><?= $post->getViewLabel() ?></div> 
                </div>
                <a href="<?= $post->getViewUrl() ?>"><p class="title_mini"><?= $post->title ?></p></a>
            </div>
        </div>
        <hr>
    <?php endforeach; ?>

    <?= Banner::widget([
        'place'   => 'before_sidebar',
        'options' => ['class' => 'ads-wrapper']
    ]) ?>

</div>
