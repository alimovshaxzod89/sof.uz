<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2017. ActiveMedia Solutions LLC
 * @author    Rustam Mamadaminov <rmamdaminov@gmail.com>
 */

use frontend\models\PollProvider;
use frontend\widgets\CheckBoList;
use frontend\widgets\SocialSharer;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/**
 * Created by PhpStorm.
 * Date: 12/20/17
 * Time: 11:30 PM
 * @var PollProvider $model
 * @var int          $index
 * @var string       $key
 * @var string       $id
 * @var string       $type
 */
?>
<div class="questionnaire__item">
    <p class="title"><?= $model->question ?></p>

    <div class="meta">
            <span class="date-time">
                <i class="icon clock-icon is_smaller"></i><?= $model->getShortFormattedDate() ?>
            </span>
        <span class="h-space"></span>
        <i class="icon comments-icon"></i><?= $model->votes ?>

    </div><!-- End of meta-->
    <?php if (mb_strlen($model->content) > 20): ?>
        <div class="poll-content">
            <?= $model->content ?>
        </div>
    <?php endif; ?>
    <?php ActiveForm::begin(['action' => ["poll/vote/$model->id"], 'options' => ['class' => 'poll__form', 'data-pjax' => 1]]) ?>
    <?= CheckBoList::widget(['name' => 'poll', 'items' => $model->answers, 'attribute' => 'answer']) ?>
    <div class="questionnaire-submit-share">
        <div class="submit">
            <button class="btn vote__button"><?= __('Javob berish') ?></button>
        </div><!-- End of submit-->

    </div>
    <?= SocialSharer::widget([
                                 'configuratorId' => 'socialShare',
                                 'wrapperTag'     => 'div',
                                 'linkWrapperTag' => false,
                                 'wrapperOptions' => ['class' => 'sidebar__share-links'],
                                 'url'            => Url::to(['/polls/all', 'id' => $model->id], true),
                                 'title'          => $model->question,
                             ]); ?><!-- End of questionnaire-submit-->
    <?php ActiveForm::end() ?>
</div>
