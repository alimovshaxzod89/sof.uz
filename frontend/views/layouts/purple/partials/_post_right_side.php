<?php

use frontend\models\PostProvider;

$posts = PostProvider::getPopularPosts(7);
$posts = PostProvider::getTopPost(7);
?>

<div class="post_popular_block">

    <div class="latest_title">
        <div class="icon"></div>
        <h4 class="title_con">Тавсия этамиз</h4>
    </div>


    <?php foreach ($posts as $i => $post): ?>
        <div class="mini_post_popular">
            <div class="text_popular">
                <div class="date_post_popular">
                    <div class="calendar_icon"></div>
                    <div class="date_text"><?= $post->getShortFormattedDate() ?></div>
                </div>
                <a href="<?= $post->getViewUrl() ?>"><p class="title_mini"><?= $post->title ?></p></a>
            </div>
        </div>
        <hr>
    <?php endforeach; ?>

</div>
