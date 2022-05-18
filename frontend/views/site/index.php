<?php

use frontend\components\ScrollPager;
use frontend\components\View;
use frontend\models\PostProvider;
use yii\widgets\ListView;
use yii\widgets\Pjax;

/**
 * @var $this View
 * @var $mainPosts PostProvider[]
 * @var $photoPosts PostProvider[]
 */
$this->_canonical = Yii::$app->getHomeUrl();
$this->addBodyClass('home page-template page-template-page-modular page-template-page-modular-php page sidebar-none modular-title-1');
$limit = 10;
$photoPosts = PostProvider::getTopPhotos(10);
$videoPosts = PostProvider::getTopVideos(10);

$mahalliyCategory = \frontend\models\CategoryProvider::findOne(['slug' => 'ozbekiston']);
$mahalliyPosts = PostProvider::getPostsByCategory($mahalliyCategory, 5, false);
$mahalliyPost = array_shift($mahalliyPosts);
$mainPost = false;

$recommendedPosts = PostProvider::getPopularPosts(3);
$recommendedPosts = PostProvider::getTopPost(3);
?>

<div class="news_block">

    <div class="latest_news">

        <?php if ($mahalliyPost instanceof PostProvider): ?>

            <div class="latest_block">

                <div class="latest_title">
                    <div class="icon"></div>
                    <h4 class="title_con"><?= $mahalliyPost->category->name ?></h4>
                </div>

                <div class="whole_post">

                    <div class="latest_img" style='background-image: url("<?= $mahalliyPost->getCroppedImage(500, 350, 1) ?>")'>
                        <div class="first"></div>
                        <div class="second">
                            <div class="share"></div>
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

                    <div class="content">
                        <a href="<?= $mahalliyPost->getViewUrl() ?>">
                            <div class="title">
                                <?= $mahalliyPost->title ?>
                            </div>
                        </a>
                        <div class="date_post">
                            <div class="calendar_icon"></div>
                            <div class="date_text"><?= $mahalliyPost->getShortFormattedDate() ?></div>
                        </div>
                        <div class="paragraph">
                            <?= $mahalliyPost->info ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="mini_news">

            <div class="st_block">
                <?php foreach ($mahalliyPosts as $i => $post): ?>
                <div class="mini_post">
                    <a href="<?= $post->getViewUrl() ?>">
                        <div class="img_mini"
                             style='background-image: url("<?= $post->getCroppedImage(190, 100, 1) ?>")'>
                            <div></div>
                            <div class="tag"><?= $post->category->name ?></div>
                        </div>
                    </a>
                    <div class="text_mini">
                        <div class="date_post">
                            <div class="calendar_icon"></div>
                            <div class="date_text"><?= $post->getShortFormattedDate() ?></div>
                        </div>
                        <a href="<?= $post->getViewUrl() ?>"><p class="title_mini"><?= $post->title ?></p></a>
                    </div>
                </div>
                <?php if ($i == 1): ?>
            </div>
            <div class="nd_block">
                <?php endif; ?>

                <?php endforeach; ?>

            </div>
        </div>

        <div class="mini_news_nd">

            <div class="st_block">
                <div class="latest_title_nd">
                    <div class="icon"></div>
                    <h4 class="title_con">Тавсия Этамиз</h4>
                </div>
            </div>

            <div class="nd_block">

                <?php foreach ($recommendedPosts as $i => $post): ?>
                    <div class="<?= $i == 0 ? 'block_news_first' : ($i == 1 ? 'block_news_second' : 'block_news_third') ?>">
                        <a href="<?= $post->getViewUrl() ?>">
                            <div class="block_image"
                                 style='background-image: url("<?= $post->getCroppedImage(500, 350, 1) ?>")'>
                                <div></div>
                                <div class="tag_bigger"><?= $post->category->name ?></div>
                            </div>
                        </a>
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
                <?php endforeach; ?>
            </div>
        </div>

    </div>

    <?= $this->render('partials/_index_right_side') ?>

</div>

<?= $this->render('partials/_index_dolzarb') ?>

<?= $this->render('partials/_index_videos') ?>
