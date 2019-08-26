<?php

use common\models\Customer;
use common\models\product\model\_Model;
use common\models\product\Type;
use common\models\Review;
use common\models\Vendor;
use marqu3s\summernote\Summernote;
use trntv\filekit\widget\Upload;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii2mod\chosen\ChosenSelect;


$this->registerJs('
$(\'#review-title\').blur(function () {
    if ($(\'#review-url\').val().length < 2)$(\'#review-url\').val(convertToSlug($(this).val()));
});
');

$this->title                   = $model->isNewRecord ? __('Create Review') : $model->title;
$this->params['breadcrumbs'][] = ['url' => ['review/index'], 'label' => __('Manage Reviews')];
$this->params['breadcrumbs'][] = $this->title;
$user                          = $this->context->_user();


?>
<div class="review-edit">
    <div class="admin-form">
        <?php $form = ActiveForm::begin([
                                            'enableAjaxValidation' => false,
                                        ]); ?>
        <div class="row">
            <div class="col col-md-9">
                <div class="panel panel-primary">
                    <div class="panel-heading border">
                        <h4><?= __('Review Information') ?></h4>
                    </div>
                    <div class="panel-body">

                        <?= $form->field($model, '_product')->widget(ChosenSelect::className(), [
                            'items'         => _Model::getModelOptions(),
                            'pluginOptions' => ['width' => '100%', 'allow_single_deselect' => true, 'search_contains' => true, 'disable_search' => false],
                        ]) ?>

                        <?= $form->field($model, 'title')->textInput(['maxlength' => true,])->label() ?>
                        <?= $form->field($model, 'url')->textInput(['maxlength' => true,])->label() ?>
                        <?= $form->field($model, 'content')->widget(
                            Summernote::className(),
                            [
                                'options' => [
                                    'class' => 'form-control summernote',
                                ],
                            ]
                        ) ?>
                    </div>
                </div>
                <div class="col col-md-3"></div>
            </div>
            <div class="col col-md-3">
                <div class="panel panel-default">
                    <div class="panel-heading"><h4><?= __('Settings') ?></h4></div>
                    <div class="panel-body">

                        <?= $form->field($model, '_user')->widget(ChosenSelect::className(), [
                            'items'         => Customer::getModelOptions(),
                            'pluginOptions' => ['width' => '100%', 'allow_single_deselect' => true, 'search_contains' => true, 'disable_search' => false],
                        ]) ?>

                        <?= $form->field($model, '_vendor')->widget(ChosenSelect::className(), [
                            'items'         => Vendor::getModelOptions(),
                            'pluginOptions' => ['width' => '100%', 'allow_single_deselect' => true, 'search_contains' => true, 'disable_search' => false],
                        ]) ?>

                        <?= $form->field($model, 'status')->widget(ChosenSelect::className(), [
                            'items'         => Review::getStatusOptions(),
                            'pluginOptions' => ['width' => '100%', 'allow_single_deselect' => true, 'disable_search' => true],
                        ]) ?>

                        <?= $form->field($model, 'image')->widget(
                            Upload::className(),
                            [
                                'url'              => ['file-storage/upload'],
                                'acceptFileTypes'  => new JsExpression('/(\.|\/)(jpe?g|png)$/i'),
                                'sortable'         => true,
                                'maxFileSize'      => 10 * 1024 * 1024, // 10 MiB
                                'maxNumberOfFiles' => 1,
                                'clientOptions'    => [],
                            ]
                        )->label(false); ?>
                    </div>
                    <div class="panel-footer  text-right">
                        <?php if ($model->getId()): ?>
                            <?= Html::a(__('Delete'), [
                                'review/delete',
                                'id' => $model->getId()
                            ], ['class' => 'btn btn-danger btn-delete']) ?>
                        <?php endif; ?>

                        <?= Html::submitButton(__('Save'), ['class' => 'btn btn-primary']) ?>
                    </div>
                </div>
            </div>
            <?php ActiveForm::end(); ?>

        </div>

    </div>