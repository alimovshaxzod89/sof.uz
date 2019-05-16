<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2017. ActiveMedia Solutions LLC
 * @author    Rustam Mamadaminov <rmamdaminov@gmail.com>
 */

use common\models\Poll;
use frontend\widgets\CheckBoList;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/**
 * @var $poll Poll
 */
$voted = $poll->hasUserVoted(\Yii::$app->controller->getUserId());

?>
<div id="poll_widget" class="sidebar__vote <?= $voted ? 'is_result' : '' ?>">
    <?php if ($voted): ?>
        <h2 class="sidebar__vote-title"><?= $poll->question ?></h2>
        <div class="poll__answers">
            <?php foreach ($poll->getAllItems() as $pos => $item): ?>
                <div class="vote__progress">
                    <p><?= $item->answer ?></p>

                    <div class="vote__progress-bar">
                        <div class="progress" style="width: <?= $item->percent ?>%"></div>
                        <span><?= $item->votes ?> (<?= $item->percent ?>%)</span>
                    </div><!-- End of answer-progress-->
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <h2 class="sidebar__vote-title"><?= $poll->question ?></h2>

        <?php ActiveForm::begin(['enableClientScript' => false, 'action' => ["poll/vote/$poll->id", 'sidebar' => 1], 'options' => ['class' => 'poll__form', 'data-pjax' => 1]]) ?>
        <?= CheckBoList::widget([
                                    'name'      => 'poll',
                                    'id'        => 'poll_variants',
                                    'items'     => $poll->answers,
                                    'attribute' => 'answer',
                                ]) ?>
        <p class="continue">
            <a class="vote__button"><?= __('Javob berish') ?></a>
        </p>
        <?php ActiveForm::end() ?>
    <?php endif; ?>
    <p class="more"><a data-pjax="0" href="<?= Url::to(['/polls/all']) ?>"><?= __('Barcha so\'rovnomalar') ?></a></p>
</div>
