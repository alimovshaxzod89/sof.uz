<?php

use backend\components\View;
use backend\models\FormUploadRating;
use backend\widgets\GridView;
use common\models\Rating;
use dosamigos\tinymce\TinyMce;
use trntv\filekit\widget\Upload;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;
use yii2mod\chosen\ChosenSelect;

/* @var $this View */
/* @var $model common\models\Rating */
/* @var $uploadModel FormUploadRating */
/* @var $dataProvider \yii\data\ArrayDataProvider */

$this->registerJs('
$(\'#rating-title\').blur(function () {
    if ($(\'#rating-url\').val().length < 2)$(\'#rating-url\').val(convertToSlug($(this).val()));
});
');

$this->title                   = $model->isNewRecord ? __('Create Rating') : $model->title;
$this->params['breadcrumbs'][] = ['url' => ['rating/index'], 'label' => __('Manage Rating')];
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
                            <h4><?= __('Rating Information') ?></h4>
                        </div>
                        <div class="panel-body">
                            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
                            <?= $form->field($model, 'url')->textInput(['maxlength' => true, 'placeholder' => __('Page Link')])->label(false) ?>


                            <?= $form->field($model, 'content')->widget(TinyMce::className(), [
                                'clientOptions' => [
                                    'plugins'           => [
                                        "advlist autolink lists link imagetools image charmap print hr anchor pagebreak",
                                        "searchreplace wordcount visualblocks visualchars code fullscreen",
                                        "insertdatetime media nonbreaking save table contextmenu directionality",
                                        "template paste textcolor colorpicker textpattern",
                                    ],
                                    'image_title'       => true,
                                    'image_class_list'  => 'img-responsive',
                                    'image_dimensions'  => false,
                                    'automatic_uploads' => true,
                                    'image_caption'     => true,
                                    'content_style'     => 'body {max-width: 768px; margin: 5px auto;}.mce-content-body img{width:98%; height:98%}figure.image{margin:0px;width:100%}',
                                    'images_upload_url' => Url::to(['file-storage/upload', 'type' => 'content-image', 'fileparam' => 'file']),
                                    'preview_url'       => Url::to('@frontendUrl/site/p/' . $model->getId()),
                                    'toolbar1'          => "undo redo | styleselect blockquote | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist | link image table code fullscreen",
                                ],
                                'options'       => ['rows' => 15],
                            ]) ?>

                        </div>
                        <div class="panel-footer">
                            <div class="row">
                                <div class="col-md-6">
                                    <h4>
                                        <?= __('Rating table') ?>
                                    </h4>
                                </div>
                                <div class="col-md-6">
                                    <?= $form->field($uploadModel, 'file', ['template' => '{input}', 'options' => ['class' => '']])->fileInput(['class' => 'hidden', 'id' => 'upload'])->label(false) ?>
                                    <div class="text-right">
                                        <button class="btn btn-primary" type="button" onclick="$('#upload').click();"><i
                                                    class="fa fa-upload"></i> <?= __('Import') ?></button>
                                        <?php if (count($model->rows) && $model->columns): ?>
                                            <?= Html::a('<i class="fa fa-download"></i> ' . __('Export'), ['rating/edit', 'id' => $model->getId(), 'export' => 1], ['class' => 'btn btn-info']) ?>
                                        <?php endif; ?>
                                    </div>
                                    <div class="clear-fix"></div>
                                </div>
                            </div>
                        </div>
                        <?php if (count($model->rows) && $model->columns): ?>
                            <?= GridView::widget([
                                                     'dataProvider' => $dataProvider,
                                                     'id'           => 'data-grid',
                                                     'layout'       => "{items}\n<div class='panel-footer'>{pager}<div class='clearfix'></div></div>",
                                                     'tableOptions' => ['class' => 'table table-striped table-hover'],
                                                     'columns'      => $model->getGridColumns()/*[
                                                     [
                                                         'attribute' => 'orin',
                                                         'format'    => 'raw',
                                                         'value'     => function ($data) {
                                                             return $data['orni'];
                                                         },
                                                     ],
                                                     [
                                                         'attribute' => 'nomi',
                                                         'format'    => 'raw',
                                                         'value'     => function ($data) {
                                                             return $data['nomi'];
                                                         },
                                                     ],
                                                     [
                                                         'attribute' => 'reytingi',
                                                         'format'    => 'raw',
                                                         'value'     => function ($data) {
                                                             return $data['reytingi'];
                                                         },
                                                     ],
                                                 ],*/
                                                 ]); ?>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col col-md-3 page_settings" id="panel_settings">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h4><?= __('Settings') ?></h4>
                        </div>
                        <div class="panel-body">
                            <?php if ($model->getId()): ?>
                                <?= $this->renderFile('@backend/views/layouts/_convert.php', ['link' => Url::to(['rating/edit', 'id' => $model->getId(), 'convert' => 1])]) ?>
                            <?php endif; ?>

                            <?= $form->field($model, 'status')->widget(ChosenSelect::className(), [
                                'items'         => Rating::getStatusArray(),
                                'pluginOptions' => ['width' => '100%', 'allow_single_deselect' => true, 'disable_search' => true],
                            ]) ?>
                            <?= $form->field($model, 'countries')->textInput([]) ?>
                            <?= $form->field($model, 'selected')->textInput([]) ?>
                            <?= $form->field($model, 'year')->textInput([]) ?>
                            <?= $form->field($model, 'image')->widget(Upload::className(), [
                                'url'              => ['file-storage/upload', 'type' => 'post-image'],
                                'acceptFileTypes'  => new JsExpression('/(\.|\/)(jpe?g|png)$/i'),
                                'maxFileSize'      => 10 * 1024 * 1024, // 10 MiB
                                'maxNumberOfFiles' => 1,
                                'clientOptions'    => [],
                            ])->label(false) ?>
                        </div>
                        <div class="panel-footer">
                            <div class="text-right">
                                <?php if ($model->getId()): ?>
                                    <?= Html::a(__('Delete'), ['rating/delete', 'id' => $model->getId()], ['class' => 'btn btn-danger btn-delete', 'data-confirm' => __('Are you sure to delete?')]) ?>
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
<?php
$this->registerJs('
    $("#panel_settings").theiaStickySidebar({
        additionalMarginTop: 70,
        additionalMarginBottom: 20
    });
')
?>