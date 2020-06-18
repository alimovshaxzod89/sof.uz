<?php

use common\components\Config;
use common\models\Post;
use common\models\Category;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii2mod\chosen\ChosenSelect;
use wbraganca\fancytree\FancytreeWidget;
use yii\web\JsExpression;
use trntv\yii\datetime\DateTimeWidget;

use backend\widgets\checkbo\CheckBo;

/* @var $this yii\web\View */
/* @var $model common\models\AutoPost */


$this->title = __('Schedule Post');
$this->params['breadcrumbs'][] = ['url' => ['post/index'], 'label' => __('Manage Posts')];
$this->params['breadcrumbs'][] = ['url' => ['post/edit', 'id' => $model->post->id], 'label' => $model->post->getShortTitle()];
$this->params['breadcrumbs'][] = $this->title;
$user = $this->context->_user();

?>

<div class="user-create">
    <div class="user-form">
        <?php $form = ActiveForm::begin(['enableAjaxValidation' => false]); ?>
        <div class="row">
            <div class="col col-md-8">
                <div class="panel panel-primary">

                    <div class="panel-body">
                        <div class="row">
                            <div class="col col-md-12">
                                <div style="background-color:rgba(194,243,255,0.41);display: inline-block;border-radius: 5px">
                                    <div style="padding: 5px 10px">
                                        <p class="bold">Sof.uz | Соф хабарлар</p>
                                        <div style="margin: 0 -10px">
                                            <img
                                                    src="<?= \frontend\models\PostProvider::getCropImage($model->post->image, null, 320) ?>">
                                        </div>
                                        <h5>
                                            <strong><?= $model->post->title ?></strong>
                                        </h5>
                                        <p></p>
                                        <p>
                                            <a target="_blank" href="<?= $model->post->getShortViewUrl(true) ?>">
                                                <?= __('Batafsil') ?>: <?= $model->post->getShortViewUrl() ?>

                                            </a>
                                        </p>
                                        <p><?= __('Энг сўнгги хабарларга обуна бўлинг:') ?></p>
                                        <p><a target="_blank"
                                              href="<?= getenv('CHANNEL_LINK') ?>"><?= getenv('CHANNEL_LINK') ?></a></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col col-md-4 page_settings">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4><?= __('Settings') ?></h4>
                    </div>
                    <div class="panel-body">

                        <?= $form->field($model, 'tg')->widget(CheckBo::className(), ['type' => 'switch', 'options' => ['disabled' => $model->isLocked()]])->label('Telegram') ?>
                        <?= $form->field($model, 'tw')->widget(CheckBo::className(), ['type' => 'switch', 'options' => ['disabled' => $model->isLocked()]])->label('Twitter') ?>
                        <?= $form->field($model, 'fb')->widget(CheckBo::className(), ['type' => 'switch', 'options' => ['disabled' => $model->isLocked()]])->label('Facebook') ?>
                        <?= $form->field($model, 'an')->widget(CheckBo::className(), ['type' => 'switch', 'options' => ['disabled' => $model->isLocked()]])->label('Android') ?>

                        <hr>
                        <?= $form->field($model, 'status')
                            ->widget(ChosenSelect::className(), [
                                'items' => \common\models\AutoPost::getStatusOptions(),
                                'withPlaceHolder' => false,
                                'pluginOptions' => [
                                    'width' => '100%',
                                    'allow_single_deselect' => false,
                                    'disable_search' => true
                                ],
                                'options' => ['disabled' => true]
                            ]) ?>

                        <?php $time = $model->getDateFromSeconds() ?>

                        <div id="widget_published_on_wrapper" class="form-group">

                            <?= $form->field($model, 'date', [
                                'options' => [
                                    'value' => $time,
                                    'id' => 'hidden_published_on',
                                    'disabled' => ($model->status == \common\models\AutoPost::STATUS_POSTED),
                                ],
                            ])->hiddenInput(['id' => 'published_on_time', 'value' => $time]) ?>
                            <?= DateTimeWidget::widget([
                                'id' => 'widget_published_on',
                                'locale' => Yii::$app->language == Config::LANGUAGE_UZBEK ? 'uz-latn' : (Yii::$app->language == Config::LANGUAGE_CYRILLIC ? 'uz' : 'ru'),
                                'model' => $model,
                                'name' => 'date_published_on_time',
                                'value' => $time ? Yii::$app->formatter->asDatetime($time, 'dd.MM.yyyy, HH:mm') : null,
                                'containerOptions' => [],
                                'options' => ['disabled' => $model->isLocked()],
                                'clientEvents' => [
                                    'dp.change' => new JsExpression('function(d){
                                                                           time = d.date._d.getTime() / 1000;
                                                                           $("#published_on_time").val(Math.round(time))
                                                                        }'),
                                ],
                            ]) ?>
                        </div>
                        <?php if ($model->status == \common\models\AutoPost::STATUS_POSTED): ?>
                            <pre><?php print_r($model->getAttributes(['tg_status', 'tw_status', 'an_status'])) ?>

                        </pre>
                        <?php endif; ?>
                    </div>
                    <div class="panel-footer">
                        <div class="text-right">
                            <?php if ($model->getId()): ?>
                                <?= Html::a(__('Delete'), \yii\helpers\Url::current(['delete' => 1]), ['class' => 'btn btn-danger btn-delete', 'data-pjax' => 0]) ?>
                            <?php endif; ?>
                            <?= Html::submitButton(__('Save'), ['class' => 'btn btn-primary', 'disabled' => $model->isLocked()]) ?>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
