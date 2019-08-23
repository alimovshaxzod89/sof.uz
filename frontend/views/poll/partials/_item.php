<?php

use common\models\Poll;
use yii\helpers\Url;

/**
 * @var $model Poll
 */
?>
<p class="title">
    <a data-pjax="0" href="<?= Url::current(['id' => $model->getId()]) ?>">
        <?= $model->question ?>
    </a>
</p>

<div class="meta nb">
    <span class="date-time">
        <i class="icon clock-icon is_smaller"></i>
        <?= $model->getShortFormattedDate()?>
    </span>
    <span class="h-space"></span>
    <i class="icon comments-icon"></i><?= $model->votes ?>
</div><!-- End of meta-->
