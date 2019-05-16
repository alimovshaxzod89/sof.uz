<?php

use backend\widgets\GridView;
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
/* @var $model Customer */
/* @var $searchModel Customer */

$this->title = __('Manage Questions');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="button-panel">
    <a href="<?= Url::to(['faq/edit']) ?>" class='btn btn-fab btn-raised btn-primary' data-pjax='0'>
        <i class="fa fa-plus"></i>
    </a>
</div>

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
            'id' => 'data-grid',
            'dataProvider' => $dataProvider,
            'layout' => "{items}\n<div class='panel-footer'>{pager}<div class='clearfix'></div></div>",
            'tableOptions' => ['class' => 'table table-striped table-hover '],
            'columns' => [
                [
                    'attribute' => 'question',
                    'format' => 'raw',
                    'value' => function ($data) {
                        return Html::a($data->question, ['faq/edit', 'id' => $data->id], ['data-pjax' => 0]);
                    },
                ],
                [
                    'attribute' => '_category',
                    'format' => 'raw',
                    'value' => function($model) {
                        if($p = Category::findOne(['_id' => $model->_category])){
                            return $p->name;
                        }
                        return null;
                    }
                ],
                [
                    'attribute' => 'name',
                    'format' => 'raw',
                    'value' => function ($data) {
                        if(($data->_user)):
                            return Html::a($data->name, ['customer/update', 'id' => $data->_user], ['data-pjax' => 0]);
                        else: return $data->name;
                        endif;
                    },
                ],
                [
                    'attribute' => 'email',
                    'format' => 'raw',
                    'value' => function ($data) {
                        return $data->email;
                    },
                ],
                [
                    'attribute' => 'phone',
                    'format' => 'raw',
                    'value' => function ($data) {
                        return $data->phone;
                    },
                ],
                [
                    'attribute' => 'status',
                    'value' => function ($model) {
                        return $model->getStatusLabel();
                    },
                ],
                [
                    'attribute' => 'created_at',
                    'format' => 'raw',
                    'value' => function ($data) {
                        return Yii::$app->formatter->asDatetime($data->updated_at);
                    },
                ],
            ],
        ]); ?>
    </div>
    <?php Pjax::end() ?>
</div>
