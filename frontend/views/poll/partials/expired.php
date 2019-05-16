<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2017. ActiveMedia Solutions LLC
 * @author    Rustam Mamadaminov <rmamdaminov@gmail.com>
 */

use frontend\models\PollProvider;
use frontend\widgets\SocialSharer;
use yii\helpers\Url;

/**
 * Created by PhpStorm.
 * Date: 12/20/17
 * Time: 11:30 PM
 * @var PollProvider $model
 * @var string       $key
 * @var int          $index
 */
?>
<div class="questionnaire__item">
    <p class="title"><?= $model->question ?></p>

    <div class="meta">
    <span class="date-time"><i
                class="icon clock-icon is_smaller"></i><?= $model->getShortFormattedDate() ?></span>
        <span class="h-space"></span>
        <i class="icon comments-icon"></i><?= $model->votes ?>
    </div><!-- End of meta-->
    <?php if (mb_strlen($model->content) > 20): ?>
        <div class="poll-content">
            <?= $model->content ?>
        </div>
    <?php endif; ?>
    <div class="questionnaire-answers">
        <?php foreach ($model->getAllItems() as $pos => $item): ?>
            <div class="answer-item">
                <p class="title"><?= $item->answer ?></p>
                <div class="answer-progress">
                    <div class="progress" style="width: <?= $item->percent ?>%"></div>
                    <span><?= $item->percent ?>% (<?= $item->votes ?>)</span>
                </div><!-- End of answer-progress-->
            </div>
        <?php endforeach; ?>

    </div><!-- End of questionnaire-answers-->

    <?= SocialSharer::widget([
                                 'configuratorId' => 'socialShare',
                                 'wrapperTag'     => 'div',
                                 'linkWrapperTag' => false,
                                 'wrapperOptions' => ['class' => 'sidebar__share-links'],
                                 'url'            => Url::to(['/polls/all', 'id' => $model->id], true),
                                 'title'          => $model->question,
                             ]); ?>
</div>