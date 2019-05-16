<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2017. ActiveMedia Solutions LLC
 * @author    Rustam Mamadaminov <rmamdaminov@gmail.com>
 */

use backend\components\View;
use common\models\SystemDictionary;
use common\widgets\Alert;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/* @var $this View */
/* @var $searchModel SystemDictionary */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title                   = __('System Dictionary');
$this->params['breadcrumbs'][] = ['url' => ['system/index'], 'label' => __('System')];
$this->params['breadcrumbs'][] = $this->title;


?>
<?php Pjax::begin(['id' => 'dictionary-grid', 'timeout' => false, 'options' => ['data-pjax' => false], 'enablePushState' => false]) ?>
<?= Alert::widget() ?>
    <div class="post-form">
        <div class="row">
            <div class="col col-md-5" id="tag_settings">
                <?php $form = ActiveForm::begin(['enableAjaxValidation' => false, 'action' => Url::current(['id' => $model->getId(), 'save' => 1]), 'options' => ['data-pjax' => 1]]); ?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="pull-left">
                            <div class="h4 mb0"><?= __($model->isNewRecord ? 'Add new word' : 'Edit word') ?></div>
                        </div>
                        <div class="pull-right">
                            <a href="<?= Url::to(['system/dictionary']) ?>"
                               class="btn btn-success"><i class="fa fa-plus"></i> <?= __('Add') ?></a>
                        </div>
                    </div>
                    <div class="panel-body">
                        <?= $form->field($model, 'latin')->textInput(['maxlength' => true])->label(__('Uzbek')) ?>
                        <?= $form->field($model, 'cyrill')->textInput(['maxlength' => true])->label(__('Cyrillic')) ?>
                    </div>
                    <div class="panel-footer">
                        <div class="text-right">
                            <?php if ($model->getId()): ?>
                                <?= Html::a(__('Delete'), ['system/dictionary', 'id' => $model->getId(), 'delete' => 1], ['class' => 'btn btn-danger btn-delete']) ?>
                            <?php endif; ?>
                            <?= Html::submitButton(__('Save'), ['class' => 'btn btn-primary']) ?>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
            <div class="col col-md-7 dictionary_list">
                <div class="panel panel-default data-grid">
                    <div class="panel-heading">
                        <div class="row" id="data-grid-filters">
                            <?php $form = ActiveForm::begin(); ?>
                            <div class="col col-md-12">
                                <?= $form->field($searchModel, 'search', ['labelOptions' => ['class' => 'invisible']])->textInput(['autofocus' => true, 'placeholder' => __('Search by word')])->label(false) ?>
                            </div>
                            <?php ActiveForm::end(); ?>
                        </div>
                    </div>
                    <?= GridView::widget([
                                             'dataProvider' => $dataProvider,
                                             'id'           => 'data-grid',
                                             'layout'       => "{items}\n<div class='panel-footer'>{pager}<div class='clearfix'></div></div>",
                                             'tableOptions' => ['class' => 'table table-striped table-hover'],
                                             'columns'      => [
                                                 [
                                                     'attribute' => 'latin',
                                                     'format'    => 'raw',
                                                     'value'     => function ($data) {
                                                         return Html::a($data->latin, ['system/dictionary', 'id' => $data->getId()]);
                                                     },
                                                 ],

                                                 [
                                                     'attribute' => 'cyrill',
                                                 ],
                                             ],
                                         ]); ?>
                </div>
            </div>
        </div>
    </div>
<?php
$this->registerJs('
    $("#tag_settings").theiaStickySidebar({
        additionalMarginTop: 70,
        additionalMarginBottom: 20
    });
')
?>

<?php Pjax::end() ?>
