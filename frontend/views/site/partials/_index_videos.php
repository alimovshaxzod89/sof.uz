<?php

use frontend\models\PostProvider;

$videoPosts = PostProvider::getTopVideos(4);
?>

<?php if (is_array($videoPosts) && count($videoPosts)): ?>
    <!---- Video --->
    <div class="video_blocks">
        <div class="latest_title">
            <div class="icon"></div>
            <h4 class="title_con"><?= __('Видео') ?></h4>
        </div>

        <div class="videos_back">

            <?php foreach ($videoPosts as $i => $post): ?>

                <div class="block_news">
                    <div class="<?= $i == 0 ? 'standart_first' : 'standart' ?>">
                        <a href="<?= $post->getViewUrl() ?>">
                            <div class="<?= $i == 0 ? 'block_video_first' : 'block_video' ?>"
                                 style='background-image: url("<?= $post->getCroppedImage(500, 350, 1) ?>")'>
                                <div class="play_btn"></div>
                            </div>
                        </a>
                    </div>
                    <div class="left-space">
                        <div class="date_post_bold">
                            <div class="calendar_icon"></div>
                            <div class="date_text"><?= $post->getShortFormattedDate() ?></div>
                        </div>
                        <div class="paragraph_bold">
                            <a href="<?= $post->getViewUrl() ?>">
                                <?= $post->title ?>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

        </div>

    </div>

<?php endif; ?>