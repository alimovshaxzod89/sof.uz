<?php

use backend\widgets\GridView;
use common\models\Post;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use yii2mod\chosen\ChosenSelect;

/* @var $this \backend\components\View */
/* @var $searchModel Login */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title                   = __('View Trash');
$this->params['breadcrumbs'][] = ['url' => ['system/index'], 'label' => __('System')];
$this->params['breadcrumbs'][] = $this->title;

?>

<?php Pjax::begin(['id' => 'post-grid', 'options' => ['data-pjax' => false], 'timeout' => false, 'enablePushState' => false]) ?>
<div class="panel panel-default data-grid">
    <div class="panel-heading">
        <div class="row" id="data-grid-filters">
            <?php $form = ActiveForm::begin(); ?>

            <div class="col col-md-3 col-md-offset-3">
                <?= $form->field($searchModel, 'language', ['labelOptions' => ['class' => 'invisible']])->widget(ChosenSelect::className(), [
                    'items'         => ['' => '', 'uzbek' => __('Uzbek'), 'russian' => __('Russian')],
                    'options'       => [
                        'data-placeholder' => __('Language'),
                    ],
                    'pluginOptions' => ['width' => '100%', 'allow_single_deselect' => true, 'disable_search' => true],
                ])->label(false) ?>
            </div>
            <div class="col col-md-6 ">
                <?= $form->field($searchModel, 'search', ['labelOptions' => ['class' => 'invisible']])->textInput(['placeholder' => __('Search by Title / Slug')])->label(false) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
    <?= GridView::widget([
                             'id'           => 'data-grid',
                             'dataProvider' => $dataProvider,
                             'rowOptions'   => function ($model, $key, $index, $grid) use ($searchModel) {
                                 $class = $model->status == Post::STATUS_DRAFT && $searchModel->status != Post::STATUS_DRAFT ? 'text-muted ' : ' ';
                                 $class .= $model->label == Post::LABEL_IMPORTANT ? 'text-bold ' : ' ';
                                 return [
                                     'class' => $class,
                                 ];
                             },
                             'columns'      => [
                                 [
                                     'attribute' => 'type',
                                     'format'    => 'raw',
                                     'value'     => function ($data) {
                                         $label = "";

                                         if ($data->has_video)
                                             $label .= "<i class='fa fa-film'></i> ";

                                         if ($data->has_gallery)
                                             $label .= "<i class='fa fa-image'></i> ";

                                         if ($data->label == Post::LABEL_EDITOR_CHOICE)
                                             $label .= "<i class='fa fa-check'></i> ";

                                         return $label;
                                     },
                                 ],
                                 [
                                     'attribute' => 'title',
                                     'format'    => 'raw',
                                     'value'     => function ($data) {
                                         return Html::a($data->getTitleView(), [
                                             'post/edit',
                                             'id' => $data->getId()
                                         ], ['data-pjax' => 0]);
                                     },
                                 ],
                                 [
                                     'attribute' => '_categories',
                                     'format'    => 'raw',
                                     'value'     => function ($data) {
                                         return implode(", ", ArrayHelper::map($data->categories, 'id', 'name'));
                                     },
                                 ],

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

