<?php

use backend\widgets\checkbo\CheckBoAsset;
use common\models\Poll;
use frontend\components\ScrollPager;
use frontend\components\View;
use frontend\models\PollProvider;
use frontend\widgets\SidebarPost;
use frontend\widgets\SidebarTrending;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\widgets\ListView;
use yii\widgets\Pjax;

/**
 * @var $this           View
 * @var $dataProvider   ActiveDataProvider
 * @var $type           string
 * @var $id             string
 * @var $model          Poll
 */
CheckBoAsset::register($this);
$this->title = __('So\'rovnomalar');
$limit       = 5;
if ($model):
    $this->title      = strip_tags($model->question);
    $this->_canonical = Url::to(['/polls/all/' . $model->getId()], true);
endif;
?>
<div class="main__content article">
    <?= \frontend\widgets\Alert::widget() ?>
    <p class="catalog__nav">
        <a href="<?= linkTo(['polls/all']) ?>"
           class="btn <?= $type == 'all' ? 'is_active' : ''; ?>"><?= __('Barchasi') ?></a>

        <a href="<?= linkTo(['polls/active']) ?>"
           class="btn <?= $type == 'active' ? 'is_active' : ''; ?>"><?= __('Faol') ?></a>

        <a href="<?= linkTo(['polls/expired']) ?>"
           class="btn <?= $type == 'expired' ? 'is_active' : ''; ?>"><?= __('Nofaol') ?></a>
    </p>

    <?php Pjax::begin(['enablePushState' => false, 'timeout' => 10000]) ?>
    <div class="questionnaire">

        <?php if ($model): ?>
            <?php if ($model->hasUserVoted($this->context->getUserId()) || $model->status == Poll::STATUS_EXPIRE): ?>
                <?= $this->render('partials/expired', ['model' => $model]) ?>
            <?php else: ?>
                <?= $this->render('partials/active', ['model' => $model]) ?>
            <?php endif; ?>
        <?php endif; ?>

        <?= ListView::widget([
                                 'dataProvider' => PollProvider::dataProvider($condition, $limit),
                                 'options'      => ['tag' => false],
                                 'layout'       => "{items}\n{pager}",
                                 'itemView'     => 'partials/_item',
                                 'itemOptions'  => ['class' => 'questionnaire__item'],
                                 'pager'        => [
                                     'perLoad' => $limit,
                                     'class'   => ScrollPager::class,
                                 ],
                             ]) ?>
    </div>
    <?php Pjax::end() ?>
</div><!-- End of main__content-->

<div class="main__sidebar">
    <?= SidebarPost::widget([]) ?>
    <?= SidebarTrending::widget() ?>
</div><!-- End of main__sidebar-->
