<?php

use frontend\models\PostProvider;
use yii\helpers\StringHelper;
use yii\helpers\Url;

/**
 * @var $posts PostProvider[]
 */

?>
<div class="selected__news-title">
    <h2>
        <?= __('Muharrir tanlovi') ?></a>
        <!--<a href="#"><? /*= __('O\'quvchilar tanlovi') */ ?></a>-->
    </h2>
    <a class="box-all" href="<?= Url::to(['/muharrir-tanlovi']) ?>"><?= __('barchasi') ?></a>
</div>

<div class="news__items">
    <?php foreach ($posts as $k => $item): ?>
        <div class="news__item">
            <div class="news__item-media">
                <img src="<?= $item->getImage(312, 172) ?>"
                     width="312"
                     height="172"
                     alt="<?= $item->image_caption ?>">

                <a href="<?= $item->category->getViewUrl() ?>" class="badge"><?= $item->category->name ?></a>
            </div><!-- End of news__item-media-->
            <div class="news__item-body">
                <p class="title"><a href="<?= $item->getViewUrl($item->category) ?>"><?= $item->title ?></a></p>
                <?php $strlen = 200 - 1.5 * mb_strlen($item->title); ?>

                <p><?= StringHelper::truncateWords($item->info, $strlen / 7 + 1.6 * substr_count($item->title, ' ')) ?></p>
            </div><!-- End of news__item-body-->

            <div class="news__item-meta">

                <i class="icon icon-comments-hover"></i>

                <?= $item->comment_count ?>

                <span class="h-space"></span>

                <i class="icon icon-eye-hover"></i>

                <?= $item->views ?>
            </div><!-- End of news__item-meta-->
        </div><!-- End of news_item-->
    <?php endforeach; ?>
</div><!-- End of news_items-->