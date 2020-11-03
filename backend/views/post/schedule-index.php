<?php

use backend\widgets\GridView;
use common\models\AutoPost;
use common\models\Category;
use common\models\Customer;
use common\models\product\model\_Model;
use common\models\QuestionComment;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $model \common\models\AutoPost */
/* @var $searchModel AutoPost */

$this->title = __('Manage Schedules');

$this->params['breadcrumbs'][] = ['url' => ['post/index'], 'label' => __('Manage Posts')];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="question-index">
    <?php Pjax::begin(['id' => 'question-grid', 'timeout' => false, 'options' => ['data-pjax' => false], 'enablePushState' => false]) ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="row" id="data-grid-filters">
                <?php $form = ActiveForm::begin(); ?>
                <div class="col col-md-6 col-md-6">
                </div>
                <div class="col col-md-6 col-md-6" id="w1-filters">
                    <?= $form->field($searchModel, 'search', ['labelOptions' => ['class' => 'invisible']])->textInput(['placeholder' => $searchModel->getAttributeLabel('search')])->label(false) ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
        <?= GridView::widget([
                                 'id'           => 'data-grid',
                                 'dataProvider' => $dataProvider,
                                 'layout'       => "{items}\n<div class='panel-footer'>{pager}<div class='clearfix'></div></div>",
                                 'tableOptions' => ['class' => 'table table-striped table-hover '],
                                 'columns'      => [
                                     [
                                         'attribute' => '_post',
                                         'format'    => 'raw',
                                         'value'     => function ($data) {
                                             return Html::a($data->post->getShortTitle(), ['post/schedule', 'id' => $data->id], ['data-pjax' => 0]);
                                         },
                                     ],
                                     [
                                         'attribute' => 'status',
                                         'value'     => function ($model) {
                                             return $model->getStatusLabel();
                                         },
                                     ],
                                     [
                                         'attribute' => 'date',
                                         'format'    => 'raw',
                                         'value'     => function ($data) {
                                             return Yii::$app->formatter->asDatetime($data->date->getTimestamp());
                                         },
                                     ],
                                     'tg_status',
                                     'fb_status',
                                     'tw_status',
                                     'an_status',
                                     [
                                         'attribute' => 'updated_at',
                                         'format'    => 'raw',
                                         'value'     => function ($data) {
                                             return Yii::$app->formatter->asDatetime($data->updated_at->getTimestamp());
                                         },
                                     ],
                                 ],
                             ]); ?>
    </div>
    <?php Pjax::end() ?>
</div>
