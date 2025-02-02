<?php

use backend\widgets\GridView;
use common\models\Place;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/**
 * @var $this yii\web\View
 * @var $model Place
 * @var $dataProvider yii\data\ActiveDataProvider
 */

$this->title                   = __('Manage Places');
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="button-panel">
        <a href="<?= Url::to(['edit']) ?>" class='btn btn-fab btn-raised btn-primary'>
            <i class="fa fa-plus"></i>
        </a>
    </div>

<?php Pjax::begin(['id' => 'places-grid', 'enablePushState' => false, 'options' => ['data-pjax' => true]]) ?>
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="row" id="data-grid-filters">
                <?php $form = ActiveForm::begin(); ?>
                <div class="col col-md-6 col-md-6">
                </div>
                <div class="col col-md-6 col-md-6">
                    <?= $form->field($model, 'search', [
                        'labelOptions' => ['class' => 'invisible']
                    ])->textInput(['placeholder' => __('Search by Title')])->label(false) ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
        <?= GridView::widget([
                                 'dataProvider' => $dataProvider,
                                 'id'           => 'data-grid',
                                 'columns'      => [
                                     [
                                         'attribute' => 'title',
                                         'format'    => 'raw',
                                         'value'     => function (Place $model) {
                                             return Html::a($model->title, [
                                                 'place/edit',
                                                 'id' => $model->getId()
                                             ], ['data-pjax' => 0]);
                                         },
                                     ],
                                     'slug',
                                     [
                                         'attribute' => '_ads',
                                         'format'    => 'raw',
                                         'value'     => function (Place $model) {
                                             return count($model->_ads);
                                         },
                                     ],
                                     [
                                         'attribute' => '_type',
                                         'format'    => 'raw',
                                         'value'     => function (Place $model) {
                                             return $model->getMode();
                                         },
                                     ],
                                     [
                                         'attribute' => 'status',
                                         'format'    => 'raw',
                                         'value'     => function (Place $model) {
                                             return $model->getStatusLabel();
                                         },
                                     ],
                                     [
                                         'attribute' => 'created_at',
                                         'format'    => 'raw',
                                         'value'     => function (Place $model) {
                                             return Yii::$app->formatter->asDatetime($model->created_at->getTimestamp());
                                         },
                                     ],
                                     [
                                         'attribute' => 'updated_at',
                                         'format'    => 'raw',
                                         'value'     => function (Place $model) {
                                             return Yii::$app->formatter->asDatetime($model->updated_at->getTimestamp());
                                         },
                                     ],
                                 ],
                             ]); ?>
    </div>
<?php Pjax::end() ?>