<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2017. ActiveMedia Solutions LLC
 * @author    Toir Tuychiyev <toir427@gmail.com>
 */

use frontend\models\PostProvider;
use yii\helpers\ArrayHelper;

/**
 * @var $gallery PostProvider[]
 * @var $info    PostProvider
 */

$big = ArrayHelper::remove($gallery, 0);
?>
<div class="photo__gallery-title">
    <div class="photo__gallery-title-col">
        <h2><?= __('Fotoxabarlar') ?></h2>

        <a href="<?= linkTo(['/photo']) ?>"><?= __('barchasi') ?></a>

        <a href="<?= linkTo(['/photo']) ?>" class="arrow-toggle-icon"><i class="icon arrow-right-link-icon"></i></a>
    </div><!-- End of photo__gallery-col-->
    <div class="photo__gallery-title-col">
        <h2><?= __('Infografika') ?></h2>

        <a href="<?= linkTo(['/infografika']) ?>"><?= __('barchasi') ?></a>
    </div><!-- End of photo__gallery-title-col-->
</div>
<div class="photo__gallery-body">
    <div class="flex-item">
        <?php if (!empty($big)): ?>
            <div class="news__item clickable-block is_big section-bg" data-bg-img="<?= $big->getImage(312,172) ?>">
                <p><a href="<?= $big->getViewUrl($big->category) ?>"><?= $big->title ?></a></p>
            </div><!-- End of news__item-->

            <div class="row-flex">
                <?php foreach ($gallery as $item): ?>
                    <div class="news__item clickable-block section-bg" data-bg-img="<?= $item->getImage(312,172) ?>">
                        <p><a href="<?= $item->getViewUrl($item->category) ?>"><?= $item->title ?></a></p>
                    </div><!-- End of news__item-->
                <?php endforeach; ?>
            </div><!-- End of row-flex-->
        <?php endif; ?>
    </div><!-- End of flex-item-->

    <div class="flex-item is_column">
        <?php if (!empty($info)): ?>
            <div class="news__item clickable-block is_big section-bg"
                 data-bg-img="<?= $info->getImage(400, 480) ?>">
                <p><a href="<?= $info->getViewUrl($info->category) ?>"><?= $info->title ?></a></p>
            </div><!-- End of news__item-->
        <?php endif; ?>
    </div><!-- End of flex-item-->
</div><!-- End of photo__gallery-body-->
