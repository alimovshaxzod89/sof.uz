<?php

use yii\grid\GridView;
use yii\widgets\Pjax;

$this->title                   = __('Review Rates');
$this->params['breadcrumbs'][] = $this->title;
?>

<?php Pjax::begin(['id' => 'review-rates-grid', 'timeout' => false, 'options' => ['data-pjax' => false], 'enablePushState' => false]) ?>
<div class="panel panel-default">
    <?= GridView::widget([
                             'dataProvider' => $dataProvider,
                             'id'           => 'data-grid',
                             'layout'       => "{items}\n<div class='panel-footer'>{pager}<div class='clearfix'></div></div>",
                             'tableOptions' => ['class' => 'table table-striped table-hover '],
                             'columns'      => [
                                 [
                                     'attribute' => '_vendor',
                                     'value'     => function ($model) {
                                         return $model->review->title;
                                     },
                                 ],
                                 [
                                     'attribute' => '_user',
                                     'value'     => function ($model) {
                                         return $model->user->getFullname();
                                     },
                                 ],
                                 'rate',
                                 [
                                     'attribute' => 'created_at',
                                     'value'     => function ($model) {
                                         return Yii::$app->formatter->asDatetime($model->created_at->getTimestamp());
                                     },
                                 ],
                             ],
                         ]); ?>
</div>
<?php Pjax::end() ?>
