<?php

use common\components\Config;
use common\models\Admin;
use trntv\filekit\widget\Upload;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;
use yii2mod\chosen\ChosenSelect;

/* @var $this yii\web\View */
/* @var $model common\models\Admin */

$this->title                   = __('Create Administrator');
$this->params['breadcrumbs'][] = ['url' => ['admin/index'], 'label' => __('Manage Administrators')];
$this->params['breadcrumbs'][] = $this->title;
$js = <<<JS
    $('#admin-full_name').blur(function () {
        if ($('#admin-slug').val().length < 2) $('#admin-slug').val(convertToSlug($(this).val()));
    });
JS;
$this->registerJs($js);
?>
<div class="row">
    <div class="col-md-offset-2 col-md-8">
        <div class="panel">
            <?php $form = ActiveForm::begin(['enableAjaxValidation' => true]); ?>
            <div class="panel-heading border ">
                <h4><?= __('Account Information') ?></h4>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col col-md-7">
                        <?= $form->field($model, 'login')->textInput(['maxlength' => true,])->label() ?>
                        <?= $form->field($model, 'full_name')->textInput(['maxlength' => true]) ?>
                        <?= $form->field($model, 'slug')->textInput(['maxlength' => true]) ?>
                        <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
                        <?= $form->field($model, 'telephone')
                                 ->textInput([
                                                 'maxlength' => true,
                                                 'class'     => 'mobile-phone form-control'
                                             ]) ?>
                    </div>
                    <div class="col col-md-5">
                        <?= $form->field($model, 'image')
                                 ->widget(Upload::className(), [
                                     'url'              => ['file-storage/upload', 'type' => 'post-image'],
                                     'acceptFileTypes'  => new JsExpression('/(\.|\/)(jpe?g|png)$/i'),
                                     'sortable'         => true,
                                     'maxFileSize'      => 10 * 1024 * 1024, // 10 MiB
                                     'maxNumberOfFiles' => 1,
                                     'multiple'         => false,
                                     'clientOptions'    => [],
                                 ]); ?>
                        <?= $form->field($model, 'status')
                                 ->widget(ChosenSelect::className(), [
                                     'items'         => Admin::getStatusOptions(),
                                     'pluginOptions' => [
                                         'width'                 => '100%',
                                         'allow_single_deselect' => true,
                                         'disable_search'        => true
                                     ],
                                 ]) ?>
                        <?= $form->field($model, 'language')
                                 ->widget(ChosenSelect::className(), [
                                     'items'         => Config::getLanguageOptions(),
                                     'pluginOptions' => [
                                         'width'                 => '100%',
                                         'allow_single_deselect' => true,
                                         'disable_search'        => true
                                     ],
                                 ]) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <?= $form->field($model, 'description')->textarea(['rows' => 10]) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col col-md-7">
                        <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>
                    </div>
                    <div class="col col-md-5">
                        <?= $form->field($model, 'confirmation', ['labelOptions' => ['class' => 'invisible']])->passwordInput(['maxlength' => true])->label() ?>
                    </div>
                </div>
            </div>
            <div class="panel-footer text-right">
                <?= Html::submitButton(__('Save'), ['class' => 'btn btn-primary']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
