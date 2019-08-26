<?php

use backend\widgets\checkbo\CheckBo;
use dosamigos\tinymce\TinyMce;
use trntv\filekit\widget\Upload;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;
use yii2mod\chosen\ChosenSelect;

/* @var $this yii\web\View */
/* @var $model common\models\Blogger */

$this->title                   = $model->isNewRecord ? __('Create Author') : $model->full_name;
$this->params['breadcrumbs'][] = ['url' => ['blogger/index'], 'label' => __('Manage Authors')];
$this->params['breadcrumbs'][] = $this->title;

$this->registerJs("
$('#blogger-full_name').blur(function () {
    if ($('#blogger-slug').val().length < 2) $('#blogger-slug').val(convertToSlug($(this).val()));
});
");
?>
<?php $form = ActiveForm::begin(['enableAjaxValidation' => true,]); ?>
<div class="row">
    <div class="col col-md-9">
        <div class="panel panel-primary">
            <div class="panel-heading ">
                <h4><?= __('Blogger Information') ?></h4>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col col-md-8">
                        <?= $form->field($model, 'full_name')->textInput(['maxlength' => true]) ?>

                    </div>
                    <div class="col col-md-4">
                        <?= $form->field($model, 'slug')->textInput() ?>
                    </div>
                </div>

                <?= $form->field($model, 'intro')->textarea(['maxlength' => true, 'rows' => 3])->label(__('Author Quote')) ?>

                <?= $form->field($model, 'description')->widget(TinyMce::className(), [
                    'clientOptions' => [
                        'plugins'           => [
                            "advlist autolink lists link imagetools image charmap print hr anchor pagebreak",
                            "searchreplace wordcount visualblocks visualchars code fullscreen",
                            "insertdatetime media nonbreaking save table contextmenu directionality",
                            "emoticons template paste textcolor colorpicker textpattern",
                        ],
                        'image_title'       => true,
                        'image_class_list'  => 'img-responsive',
                        'image_dimensions'  => false,
                        'automatic_uploads' => true,
                        'image_caption'     => true,
                        'content_style'     => 'body {max-width: 768px; margin: 5px auto;}.mce-content-body img{width:98%}figure.image{margin:0px;width:100%}',
                        'images_upload_url' => Url::to(['file-storage/upload', 'type' => 'content-image', 'fileparam' => 'file']),
                        'preview_url'       => Url::to('@frontendUrl/site/p/' . $model->getId()),
                        'toolbar1'          => "undo redo | styleselect blockquote |  bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist | link code fullscreen",
                    ],
                    'options'       => ['rows' => 20],
                ]) ?>
                <div class="row">
                    <div class="col-md-3">
                        <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
                    </div>

                    <div class="col-md-3">
                        <?= $form->field($model, 'facebook')->textInput(['maxlength' => true, 'class' => 'form-control']) ?>
                    </div>
                    <div class="col-md-3">
                        <?= $form->field($model, 'twitter')->textInput(['maxlength' => true, 'class' => 'form-control']) ?>
                    </div>
                    <div class="col-md-3">
                        <?= $form->field($model, 'telegram')->textInput(['maxlength' => true, 'class' => 'form-control']) ?>
                    </div>
                </div>

            </div>

        </div>

    </div>
    <div class="col col-md-3">
        <div class="panel panel-primary">
            <div class="panel-heading ">
                <h4><?= __('Profile') ?></h4>
            </div>
            <div class="panel-body">
                <?php if ($model->getId()): ?>
                    <?= $this->renderFile('@backend/views/layouts/_convert.php', ['link' => Url::to(['blogger/update', 'id' => $model->getId(), 'convert' => 1])]) ?>
                <?php endif; ?>

                <?= $form->field($model, 'image')->widget(Upload::className(), [
                    'url'              => ['file-storage/upload', 'type' => 'blogger-image'],
                    'acceptFileTypes'  => new JsExpression('/(\.|\/)(jpe?g|png)$/i'),
                    'maxFileSize'      => 10 * 1024 * 1024, // 10 MiB
                    'maxNumberOfFiles' => 1,
                    'multiple'         => false,
                    'clientOptions'    => [],
                ]) ?>

                <?= $form->field($model, 'redaction')->widget(CheckBo::className(), ['type' => 'switch'])->label(__('Jamoa')) ?>
                <?= $form->field($model, 'position')->textInput(['maxlength' => true]) ?>
                <?= $form->field($model, 'job')->textInput(['maxlength' => true]) ?>


                <?= $form->field($model, 'status')->widget(ChosenSelect::className(), [
                    'items'         => \common\models\Blogger::getStatusOptions(),
                    'pluginOptions' => ['width' => '100%', 'allow_single_deselect' => true, 'disable_search' => true],
                ]) ?>


            </div>
            <div class="panel-footer text-right">
                <?php if ($model->getId() && $this->_user()->canAccessToResource('blogger/delete')): ?>
                    <?= Html::a(__('Delete'), [
                            'blogger/delete',
                            'id' => $model->getId()
                    ], ['class' => 'btn btn-danger', 'data-confirm' => __('Are you sure to delete?')]) ?>
                <?php endif; ?>
                <?= Html::submitButton(__('Save'), ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
