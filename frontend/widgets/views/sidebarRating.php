<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2018. ActiveMedia Solutions LLC
 * @author    Rustam Mamadaminov <rmamdaminov@gmail.com>
 */

use common\models\Post;
use frontend\models\PostProvider;

/**
 * @var $models \common\models\Rating[]
 */

?>
<div class="sidebar__most-read mini__news">

    <div class="main__news-thumb-title">
        <?= __('Reytinglar') ?>
    </div>

    <?php if (count($models)): ?>
        <?php foreach ($models as $k => $model): ?>
            <div class="news__item">
                <div class="news__item-meta">
                    <span class="views"><i class="icon eye-icon"></i> <?= $model->views ?></span>
                </div><!-- End of news__item-meta-->

                <p>
                    <a href="<?= $model->getViewUrl(false) ?>"><?= $model->title ?></a>
                </p>
            </div><!-- End of news__item-->
        <?php endforeach; ?>
    <?php endif; ?>
</div><!-- End of sidebar__most-read-->
