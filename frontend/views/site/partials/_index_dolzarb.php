<?php


use frontend\models\PostProvider;

$mainPosts = PostProvider::getTopPost(5);
$mainPost = array_shift($mainPosts);

$posts = PostProvider::getLastPosts(7);
?>

<!-- Important News -->
<div class="section_important">

    <div class="big_post">

        <div class="latest_title">
            <div class="icon"></div>
            <h4 class="title_con">Долзарб Хабарлар</h4>
        </div>

        <?php if ($mainPost instanceof PostProvider): ?>

        <div class="main_img" style='background-image: url("<?= $mainPost->getCroppedImage(500, 350, 1) ?>")'>
            <div class="first">
                <div></div>
                <div class="big_tag"><?= $mainPost->category->name ?></div>
            </div>
            <div class="second">
                <a href="">
                    <div class="share"></div>
                </a>
                <div class="social">
                    <a href="">
                        <div class="tg"></div>
                    </a>
                    <a href="">
                        <div class="fc"></div>
                    </a>
                    <a href="">
                        <div class="insta"></div>
                    </a>
                </div>
            </div>
        </div>

        <div class="main_text">
            <div class="date_post_bold">
                <div class="calendar_icon"></div>
                <div class="date_text"><?= $mainPost->getShortFormattedDate() ?></div>
            </div>
            <a href="<?= $mainPost->getViewUrl() ?>">
                <div class="bigger_title"><?= $mainPost->title ?></div>
            </a>
            <div class="paragraph_body"><?= $mainPost->info ?></div>
        </div>

        <?php endif; ?>

    </div>


    <div class="post_blocks">
        <div class="st_line">

            <?php foreach ($mainPosts as $i => $post): ?>

            <div class="<?= $i % 2 == 0 ? 'block_news_first' : 'block_news' ?>">
                <div class="block_image_middle" style='background-image: url("<?= $post->getCroppedImage(500, 350, 1) ?>")'>
                    <div></div>
                    <div class="tag_bigger"><?= $post->category->name ?></div>
                </div>
                <div class="date_post_bold">
                    <div class="calendar_icon"></div>
                    <div class="date_text"><?= $post->getShortFormattedDate() ?></div>
                </div>
                <a href="<?= $post->getViewUrl() ?>">
                    <div class="paragraph_bold">
                        <?= $post->title ?>
                    </div>
                </a>
            </div>
            <?php if ($i == 1): ?>
        </div>
        <div class="nd_line">
            <?php endif; ?>

            <?php endforeach; ?>
        </div>
    </div>

</div>
