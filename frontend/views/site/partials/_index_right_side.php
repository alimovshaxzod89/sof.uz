<?php
$posts = \frontend\models\PostProvider::getLastPosts(7);
?>

<div class="popular_news">

    <div class="latest_title">
        <div class="icon"></div>
        <h4 class="title_con"><?= __('Сўнгги янгиликлар') ?></h4>
    </div>

    <?php foreach ($posts as $i => $post): ?>
    <div class="mini_post_popular">
        <div class="text_popular">
            <div class="date_post_popular">
                <div class="calendar_icon"></div>
                <div class="date_text"><?= $post->getShortFormattedDate() ?></div>
            </div>
            <a href="<?= $post->getViewUrl() ?>"><p class="title_mini_popular"><?= $post->title ?></p></a>
        </div>
    </div>
    <hr>
    <?php endforeach; ?>

    <div class="mini_post_popular">
        <a href="">
            <!-- todo: bossa ishlaydigan qilish-->
            <div class="button_more">Ko'proq ko'rish</div>
            <!-- <div class="button_more">Кўпроқ кўриш</div>-->
        </a>
    </div>

<!--    <div class="reklama"><h4> Reklama </h4></div>-->

</div>
