<?php

use backend\components\View;
use backend\widgets\GridView;
use yii\helpers\Html;
use yii\helpers\StringHelper;
use yii\widgets\ActiveForm;
use yii2mod\chosen\ChosenSelect;

/* @var $this View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $model \common\models\Error */
/* @var $searchModel \common\models\Error */

?>
<div class="panel panel-default data-grid">
    <div class="panel-heading" id="data-grid-filters">
        <?php $form = ActiveForm::begin(); ?>
        <div class="row">
            <div class="col col-md-4">
                <?= $form->field($searchModel, 'status')->widget(ChosenSelect::className(), [
                    'items'         => \common\models\Error::getStatusOptions(true),
                    'pluginOptions' => ['width' => '100%', 'allow_single_deselect' => true, 'disable_search' => true],
                ])->label(false) ?>
            </div>
            <div class="col col-md-8">
                <?= $form->field($searchModel, 'search', ['options' => ['class' => false], 'labelOptions' => ['class' => 'invisible']])->textInput(['placeholder' => __('Search by Text'), ['class' => 'tag-search']])->label(false) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
    <?= GridView::widget([
                             'id'           => 'data-grid',
                             'dataProvider' => $dataProvider,
                             'columns'      => [
                                 [
                                     'attribute' => 'text',
                                     'format'    => 'raw',
                                     'value'     => function ($data) {
                                         return Html::a(StringHelper::truncateWords($data->text, 3), ['error/index', 'id' => $data->getId()]);
                                     },
                                 ],
                                 [
                                     'attribute' => 'created_at',
                                     'format'    => 'raw',
                                     'value'     => function ($data) {
                                         return Html::a(Yii::$app->formatter->asDatetime($data->created_at->getTimestamp()), ['error/index', 'id' => $data->getId()]);
                                     },
                                 ],

                             ],
                         ]); ?>
</div>
