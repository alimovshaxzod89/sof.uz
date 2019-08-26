<?php

use backend\components\View;
use common\models\Comment;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii2mod\chosen\ChosenSelect;

/* @var $this View */
/* @var $model Comment */

$this->title                   = $model->isNewRecord ? __('Create Comment') : $model->getId();
$this->params['breadcrumbs'][] = ['url' => ['comment/index'], 'label' => __('Manage Comments')];
$this->params['breadcrumbs'][] = $this->title;
$user                          = $this->context->_user();

?>

<div class="user-create">
    <div class="user-form">
        <?php $form = ActiveForm::begin(['enableAjaxValidation' => true]); ?>
        <div class="row">
            <div class="col col-md-9">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h4><?= __('Comment Information') ?></h4>
                    </div>
                    <div class="panel-body">
                        <?= $form->field($model, 'text', [])->textarea(['rows' => 6]) ?>
                        <?= __('Post:') ?>
                        <?= Html::a($model->post->title, ['post/edit', 'id' => $model->post->getId()]) ?>
                    </div>

                </div>
            </div>
            <div class="col col-md-3 page_settings">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4><?= __('Settings') ?></h4>
                    </div>
                    <div class="panel-body">
                        <?= __('User') . Html::a($model->user->full_name, ['user/update', 'id' => $model->user->getId()], ['class' => 'btn btn-default']) ?>
                        <?= $form->field($model, 'status')->widget(ChosenSelect::className(), [
                            'items'         => Comment::getStatusArray(),
                            'pluginOptions' => ['width' => '100%', 'allow_single_deselect' => true, 'disable_search' => true],
                        ]) ?>
                    </div>
                    <div class="panel-footer">
                        <div class="text-right">
                            <?php if ($model->getId()): ?>
                                <?= Html::a(__('Delete'), [
                                        'page/delete',
                                        'id' => $model->getId()
                                ], ['class' => 'btn btn-danger', 'data-confirm' => __('Are you sure to delete?')]) ?>
                            <?php endif; ?>
                            <?= Html::submitButton(__('Save'), ['class' => 'btn btn-primary']) ?>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
