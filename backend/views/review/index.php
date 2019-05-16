<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title                   = __('Manage Reviews');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="button-panel">
    <a href="<?= Url::to(['review/edit']) ?>" class='btn btn-fab btn-raised btn-primary' data-pjax='0'>
        <i class="fa fa-plus"></i>
    </a>
</div>
<?php Pjax::begin(['id' => 'review-grid', 'timeout' => false, 'options' => ['data-pjax' => false], 'enablePushState' => false]) ?>

<div class="panel panel-default">
    <div class="panel-heading">
        <div class="row" id="data-grid-filters">
            <?php $form = ActiveForm::begin(); ?>
            <div class="col col-md-6 col-md-6">
            </div>
            <div class="col col-md-6 col-md-6">
                <?= $form->field($searchModel, 'search', ['labelOptions' => ['class' => 'invisible']])->textInput(['placeholder' => $searchModel->getAttributeLabel('search')])->label(false) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
    <?= GridView::widget([
                             'dataProvider' => $dataProvider,
                             'id'           => 'data-grid',
                             'layout'       => "{items}\n<div class='panel-footer'>{pager}<div class='clearfix'></div></div>",
                             'tableOptions' => ['class' => 'table table-striped table-hover '],
                             'columns'      => [
                                 [
                                     'attribute' => 'title',
                                     'value'     => function ($model) {
                                         return Html::a($model->title, Url::to(['review/edit', 'id' => $model->id]), ['data-pjax' => 0]);
                                     },
                                     'format'    => 'raw',
                                 ],
                                 [
                                     'attribute' => 'product_name',
                                 ],
                                 [
                                     'attribute' => 'status',
                                     'value'     => function ($model) {
                                         return $model->getStatusLabel();
                                     },
                                 ],
                                 '_rate',
                                 [
                                     'attribute' => 'created_at',
                                     'value'     => function ($model) {
                                         return Yii::$app->formatter->asDatetime($model->created_at);
                                     },
                                 ],
                             ],
                         ]); ?>
</div>
<?php Pjax::end() ?>
