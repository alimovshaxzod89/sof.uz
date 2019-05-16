<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2018. ActiveMedia Solutions LLC
 * @author    Rustam Mamadaminov <rmamdaminov@gmail.com>
 */

use frontend\models\PostProvider;
use yii\helpers\StringHelper;
use yii\helpers\Url;

/**
 * Created by PhpStorm.
 * Date: 12/17/17
 * Time: 4:39 PM
 * @var \common\models\Rating $model
 * @var int                   $index
 */
?>
<div class="news__item clickable-block">
    <div class="news__item-media">
        <img src="<?= \common\models\MongoModel::getCropImage($model->image, 420, 200, \Imagine\Image\ManipulatorInterface::THUMBNAIL_OUTBOUND) ?>"
             alt="<?= $model->title ?>">
    </div><!-- End of news__item-media-->

    <div class="news__item-meta">
        <span class="date-time">
            <i class="icon clock-icon is_smaller"></i>
            <?= $model->year ?>
        </span>
    </div>
    <p class="title">
        <a href="<?= $model->getViewUrl(false) ?>"><?= $model->title ?></a>
    </p>
</div>