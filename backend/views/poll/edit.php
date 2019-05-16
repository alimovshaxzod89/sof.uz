<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2017. ActiveMedia Solutions LLC
 * @author    Rustam Mamadaminov <rmamdaminov@gmail.com>
 */

use backend\components\View;
use common\components\Config;
use common\components\MultipleInputTableRenderer;
use common\models\Poll;
use dosamigos\tinymce\TinyMce;
use trntv\yii\datetime\DateTimeWidget;
use unclead\multipleinput\MultipleInput;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;
use yii2mod\chosen\ChosenSelect;

/* @var $this View */
/* @var $model common\models\Poll */

$this->title                   = $model->isNewRecord ? __('Create Poll') : __('Edit');
$this->params['breadcrumbs'][] = ['url' => ['poll/index'], 'label' => __('Manage Polls')];
if (!$model->isNewRecord) $this->params['breadcrumbs'][] = ['url' => ['poll/view', 'id' => $model->getId()], 'label' => $model->getShortTitle()];
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
                        <h4><?= __('Poll Information') ?></h4>
                    </div>
                    <div class="panel-body">
                        <?= $form->field($model, 'question')->textInput() ?>
                        <?= $form->field($model, 'content')->widget(TinyMce::className(), [
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
                                'content_style'     => 'body {max-width: 768px; margin: 5px auto;}.mce-content-body img{width:98%; height:98%}figure.image{margin:0px;width:100%}',
                                'images_upload_url' => Url::to(['file-storage/upload', 'type' => 'content-image', 'fileparam' => 'file']),
                                'preview_url'       => Url::to('@frontendUrl/preview/' . $model->getId()),
                                'toolbar1'          => "undo redo | styleselect blockquote |  bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist | link code fullscreen",
                            ],
                            'options'       => ['rows' => 10],
                        ]) ?>
                        <?= $form->field($model, 'answers')->widget(MultipleInput::className(), [
                            'max'               => 10,
                            'min'               => 2,
                            'rendererClass'     => MultipleInputTableRenderer::className(),
                            'allowEmptyList'    => true,
                            'sortable'          => false,
                            'addButtonPosition' => MultipleInput::POS_FOOTER // show add button in the header
                        ]) ?>

                    </div>

                </div>
            </div>
            <div class="col col-md-3 page_settings">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4><?= __('Settings') ?></h4>
                    </div>
                    <div class="panel-body">

                        <?= $this->renderFile('@backend/views/layouts/_convert.php', ['link' => Url::to(['poll/edit', 'id' => $model->getId(), 'convert' => 1])]) ?>

                        <?= $form->field($model, 'status')->widget(ChosenSelect::className(), [
                            'items'         => Poll::getStatusArray(),
                            'pluginOptions' => ['width' => '100%', 'allow_single_deselect' => true, 'disable_search' => true],
                        ]) ?>
                        <?= $form->field($model, 'expire_time', ['options' => ['class' => '']])->hiddenInput(['id' => 'expire_time']) ?>
                        <?= DateTimeWidget::widget([
                                                       'id'           => 'dpw',
                                                       'locale'       => Yii::$app->language == Config::LANGUAGE_UZBEK ? 'uz-latn' : (Yii::$app->language == Config::LANGUAGE_CYRILLIC ? 'uz' : 'ru'),
                                                       'model'        => $model,
                                                       'name'         => 'expire_time',
                                                       'value'        => $model->expire_time ? Yii::$app->formatter->asDatetime($model->expire_time, 'dd.MM.yyyy, HH:mm') : null,
                                                       'clientEvents' => [
                                                           'dp.change' => new JsExpression('function(d){
                                                               time = d.date._d.getTime() / 1000;
                                                               $("#expire_time").val(Math.round(time))
                                                               console.log(d.date._d.getTime());
                                                            }'),
                                                       ],
                                                   ]) ?>

                    </div>
                    <div class="panel-footer">
                        <div class="text-right">
                            <?php if ($this->_user()->canAccessToResource('poll/delete')): ?>
                                <?php if ($model->getId()): ?>
                                    <?= Html::a(__('Delete'), ['poll/delete', 'id' => $model->getId()], ['class' => 'btn btn-danger btn-delete', 'data-confirm' => __('Are you sure to delete?')]) ?>
                                <?php endif; ?>
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
