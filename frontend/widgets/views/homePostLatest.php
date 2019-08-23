<?php

use frontend\models\PostProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;

/**
 * @var $news    PostProvider[]
 * @var $audios  PostProvider[]
 * @var $photo   PostProvider
 */

$photo = ArrayHelper::remove($news, 0);
?>
<div class="latest__news-text">
    <div class="latest__news-text-title">
        <h2><?= __('So\'nggi xabarlar') ?></h2>
        <a href="<?= linkTo(['/yangiliklar']) ?>"><?= __('barchasi') ?></a>
    </div><!-- End of latest__news-text-title-->
    <?php if (!empty($photo)): ?>
        <div class="news__item is_photo clickable-block">
            <div class="media-info">
                <div class="media is_left">
                    <img src="<?= $photo->getImage(190, 140) ?>"
                         alt="<?= $photo->image_caption ?>">
                </div><!-- End of media-->

                <div class="info">
                    <p class="news__item-meta"><i
                            class="icon clock-icon is_smaller"></i><?= $photo->getShortFormattedDate() ?>
                    </p>

                    <p class="news__item-title">
                        <a href="<?= $photo->getViewUrl($photo->category) ?>"><?= $photo->title ?></a>
                    </p>

                    <p><?= StringHelper::truncateWords($photo->info, 16) ?></p>
                </div><!-- End of info-->
            </div><!-- End of media-info-->
        </div><!-- End of news__item-->
        <?php if (count($news)): ?>
            <div class="news__items">
                <?php foreach ($news as $item): ?>
                    <div class="news__item clickable-block">
                        <div class="row-info">
                            <p class="news__item-meta shrink is_left">
                                </i><?= $item->getShortFormattedDate() ?>
                            </p>

                            <p class="news__item-title auto">
                                <a href="<?= $item->getViewUrl($item->category) ?>">
                                    <?= $item->title ?>
                                </a>
                            </p>
                        </div><!-- End of row-info-->
                    </div><!-- End of news__item-->
                <?php endforeach; ?>
            </div><!-- End of news__items-->
        <?php endif; ?>
    <?php endif; ?>
</div><!-- End of latest__news-text-->
