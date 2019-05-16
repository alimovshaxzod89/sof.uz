<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2017. ActiveMedia Solutions LLC
 * @author    Rustam Mamadaminov <rmamdaminov@gmail.com>
 */

use frontend\models\PostProvider;
use yii\helpers\Url;

/**
 * @var $posts PostProvider[]
 * @var $big   PostProvider[]
 */

$bigs = array_slice($posts, 0, 2);
if (count($posts) > 2) {
    $posts = array_slice($posts, 2);
} else {
    $posts = [];
}
?>
<?php if (count($posts) || count($bigs)): ?>
    <div class="latest__news-video-title">
        <h2><?= __('Videoxabarlar') ?></h2>
        <a class="box-all" href="<?= Url::to(['/video']) ?>"><?= __('barchasi') ?></a>
    </div><!-- End of latest__news-video-title-->

    <div class="latest__news-video-carousel">
        <?php if (count($bigs)): ?>
            <div class="row__big">
                <?php foreach ($bigs as $item): ?>
                    <div class="news__item clickable-block">
                        <div class="news__item-media">
                            <img src="<?= $item->getImage(392, 200) ?>" width="392"
                                 height="200"
                                 alt="<?= $item->image_caption ?>">
                        </div><!-- End of news__item-media-->

                        <p class="title"><a href="<?= $item->getViewUrl($item->category) ?>"><?= $item->title ?></a></p>
                    </div><!-- End of news__item-->
                <?php endforeach; ?>
            </div><!-- End of row-big-->
        <?php endif; ?>

        <div class="row__thumb">
            <?php foreach ($posts as $k => $post): ?>
                <div class="news__item clickable-block">
                    <div class="news__item-media">
                        <img src="<?= $post->getImage(312, 172) ?>"
                             width="184" height="90"
                             alt="">
                    </div><!-- End of news__item-media-->

                    <p class="title">
                        <a href="<?= $post->getViewUrl() ?>">
                            <?= $post->title ?>
                        </a>
                    </p>
                </div><!-- End of news__item-->
            <?php endforeach; ?>
        </div><!-- End of row-thumb-->
    </div><!-- End of latest__news-video-carousel-->
<?php endif; ?>