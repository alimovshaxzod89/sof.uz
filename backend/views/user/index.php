<?php

use backend\widgets\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $model \common\models\Admin */
/* @var $searchModel \common\models\Admin */

$this->title                   = __('Manage Users');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="button-panel">
    <a href="<?= Url::to(['user/create']) ?>" class='btn btn-fab btn-raised btn-primary' data-pjax='0'>
        <i class="fa fa-plus"></i>
    </a>
</div>
<div class="panel mb25">
    <?php Pjax::begin(['id' => 'admin-grid', 'timeout' => false, 'options' => ['data-pjax' => false], 'enablePushState' => false]) ?>

    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="row" id="data-grid-filters">
                <?php $form = ActiveForm::begin(); ?>
                <div class="col col-md-6 col-md-6">
                </div>
                <div class="col col-md-6 col-md-6">
                    <?= $form->field($searchModel, 'search', ['labelOptions' => ['class' => 'invisible']])->textInput(['placeholder' => __('Search by Fullname / Login / Email')])->label(false) ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
        <?= GridView::widget([
                                 'dataProvider' => $dataProvider,
                                 'id'           => 'data-grid',
                                 'layout'       => "{items}\n<div class='panel-footer'>{pager}<div class='clearfix'></div></div>",
                                 'columns'      => [
                                     [
                                         'attribute' => 'fullname',
                                         'format'    => 'raw',
                                         'value'     => function ($data) {
                                             return Html::a($data->fullname, ['user/update', 'id' => $data->id], ['data-pjax' => 0]);
                                         },
                                     ],
                                     [
                                         'attribute' => 'type',
                                         'format'    => 'raw',
                                         'value'     => function (\common\models\User $data) {
                                             return $data->authClient ? $data->authClient->source : __('Default');
                                         },
                                     ],
                                     'email',
                                     'status',
                                     [
                                         'attribute' => 'created_at',
                                         'format'    => 'raw',
                                         'value'     => function ($data) {
                                             return Yii::$app->formatter->asDatetime($data->created_at instanceof \MongoDB\BSON\Timestamp ? $data->created_at->getTimestamp() : $data->created_at);
                                         },
                                     ],
                                 ],
                             ]); ?>
    </div>
    <?php Pjax::end() ?>
</div>
