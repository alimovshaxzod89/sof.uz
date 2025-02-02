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
            <h4 class="title_con"><?= __('Долзарб xабарлар') ?></h4>
        </div>

        <?php if ($mainPost instanceof PostProvider): ?>
        <a href="<?= $mainPost->getViewUrl() ?>">
        <div class="main_img" style='background-image: url("<?= $mainPost->getCroppedImage(500, 350, 1) ?>")'>
            
            <div class="first">
                <div class="big_tag"><?= $mainPost->category->name ?></div>
            </div>

            <div class="second" > 
                <a href="<?= $mainPost->getShortViewUrl() ?>">
                    <div class="second_for_main_link"></div>
                </a>
                <div class="second_for_s_media" style="display: flex; justify-content: space-between; width: 100%;">
                    <div class="share" style="cursor:pointer;" id="myBtn"></div>
                    <div class="social">
                        <?php
                        $urlEnCode = urlencode($mainPost->getShortViewUrl());
                        ?>
                        <a href="https://t.me/share/url?url=<?= $urlEnCode ?>" target="_blank">
                            <div class="tg"></div>
                        </a>
                        <a  href="<?= 'https://www.facebook.com/sharer.php?u=' . $urlEnCode ?>" target="_blank">
                            <div class="fc"></div>
                        </a>
                        <a href="https://twitter.com/intent/tweet?url=<?= $urlEnCode ?>" target="_blank">
                            <div class="insta"></div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        </a>

        <div class="main_text">
            <div class="date_post_bold">
                <div class="calendar_icon"></div>
                <div class="date_text"><?= $mainPost->getShortFormattedDate() ?>, &nbsp;</div>
                            <div class="eye_icon d-none"></div>
                            <div class="date_text d-none"><?= $mainPost->getViewLabel() ?></div>
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

            <div class="block_news">
                <a href="<?= $post->getViewUrl() ?>">
                    <div class="block_image_middle"
                         style='background-image: url("<?= $post->getCroppedImage(500, 350, 1) ?>")'>
                        <div></div>
                        <div class="tag_bigger"><?= $post->category->name ?></div>
                    </div>
                </a>
                <div class="date_post_bold">
                    <div class="calendar_icon"></div>
                    <div class="date_text"><?= $post->getShortFormattedDate() ?>, &nbsp;</div>
                    <div class="eye_icon d-none"></div>
                    <div class="date_text d-none"><?= $post->getViewLabel() ?></div>
                </div>
                <div class="paragraph_bold">
                    <a href="<?= $post->getViewUrl() ?>">
                        <?= $post->title ?>
                    </a>
                </div>
            </div>
            <?php if ($i == 1): ?>
        </div>
        <div class="nd_line">
            <?php endif; ?>

            <?php endforeach; ?>
        </div>
    </div>

</div>