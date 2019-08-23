<?php

use backend\components\View;
use yii\helpers\Html;

/* @var $this View */
/* @var $model common\models\Poll */

$this->title                   = $model->getShortTitle();
$this->params['breadcrumbs'][] = ['url' => ['poll/index'], 'label' => __('Manage Polls')];
$this->params['breadcrumbs'][] = $this->title;
$user                          = $this->context->_user();

?>

<div class="user-create">
    <div class="user-form">
        <div class="row">
            <div class="col col-md-9">
                <div class="panel panel-primary">
                    <div class="panel-body">
                        <div class="poll-question">
                            <?= $model->question ?>
                            <p class="poll-number"><?= __('Total votes: {count}', ['count' => $model->votes]) ?></p>
                        </div>
                        <div class="poll-content">
                            <?= $model->content ?>
                        </div>
                        <?php foreach ($model->getAllItems() as $pos => $item): ?>
                            <div class="poll-item <?= $item->active ? '' : 'item-inactive' ?>">
                                <p><?= $item->answer ?></p>
                                <span class="poll-percent-bar"></span>
                                <span class="poll-percent" style="width:<?= $item->percent ?>%"></span>
                            </div>
                            <p class="poll-number"><?= $item->percent ?>% / <?= $item->votes ?></p>
                        <?php endforeach; ?>
                    </div>
                    <div class="panel-footer">
                        <div class="text-right">
                            <?php if ($this->_user()->canAccessToResource('poll/edit')): ?>
                                <?= Html::a(__('Edit'), ['poll/edit', 'id' => $model->getId()], ['class' => 'btn btn-primary']) ?>
                            <?php endif; ?>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
