<?php

use backend\components\View;
use backend\widgets\AceEditorWidget;
use common\models\Admin;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model common\models\Post */
/* @var $type string */
/* @var $user Admin */

$this->title                   = $model->getTitleView();
$this->params['breadcrumbs'][] = ['url' => ['post/index'], 'label' => __('Manage Posts')];
$this->params['breadcrumbs'][] = $this->title;
$model->content                = json_encode($model->getAttributes(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
?>
<div class="post-create">
    <div class="post-form">
        <?php $form = ActiveForm::begin(['enableAjaxValidation' => true, 'enableClientValidation' => true, 'validateOnSubmit' => false, 'options' => ['id' => 'post_form']]); ?>
        <div class="row">
            <div class="col col-md-12">
                <div class="panel panel-primary">
                    <?= $form->field($model, 'content')
                             ->widget(AceEditorWidget::className(), [
                                 'readOnly'         => true,
                                 'mode'             => 'json',
                                 'containerOptions' => [
                                     'style' => 'width: 100%; min-height: 800px',
                                 ],
                             ]) ?>
                </div>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
