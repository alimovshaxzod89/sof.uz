<?php

use backend\widgets\checkbo\CheckBo;
use backend\widgets\GridView;
use common\models\Post;
use MongoDB\BSON\Timestamp;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/* @var $this backend\components\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $model \common\models\Post */
/* @var $searchModel \common\models\Post */

$this->title = __('Manage Posts');
$this->params['breadcrumbs'][] = $this->title;
$user = $this->_user();
$types = [Post::TYPE_NEWS => 'fa-file-text'];
?>
<div class="button-panel">
    <?php foreach ($types as $type => $icon): ?>
        <?= Html::a('<i class="fa ' . $icon . '"></i>', ['post/create', 'type' => $type], [
            'data-pjax' => false, 'title' => __('Create {type} Post', ['type' => $icon]), 'class' => 'btn btn-fab btn-raised btn-primary',
        ]) ?>
    <?php endforeach; ?>
</div>


<?php Pjax::begin(['id' => 'post-grid', 'options' => ['data-pjax' => false], 'timeout' => false, 'enablePushState' => false]) ?>
<div class="panel panel-default data-grid">
    <div class="panel-heading">
        <div class="row" id="data-grid-filters">
            <?php $form = ActiveForm::begin(); ?>

            <div class="col col-md-3  col-md-offset-3">

            </div>
            <div class="col col-md-6 col-md-6">
                <?= $form->field($searchModel, 'search', ['labelOptions' => ['class' => 'invisible']])->textInput(['placeholder' => __('Search by Title / Slug')])->label(false) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
    <?= GridView::widget([
        'id' => 'data-grid',
        'dataProvider' => $dataProvider,
        'rowOptions' => function ($model, $key, $index, $grid) use ($searchModel) {
            $class = $model->status == Post::STATUS_DRAFT && $searchModel->status != Post::STATUS_DRAFT ? 'text-muted ' : ' ';
            $class .= $model->label == Post::LABEL_IMPORTANT ? 'text-bold ' : ' ';
            return [
                'class' => $class,
            ];
        },
        'columns' => [
            [
                'format' => 'raw',
                'value' => function ($model, $key, $index, $column) use ($user) {
                    $pagination = $column->grid->dataProvider->getPagination();
                    if ($pagination !== false) {
                        return $pagination->totalCount - $pagination->getOffset() - $index;
                    }

                    return $index + 1;
                },
            ],
            [
                'attribute' => 'type',
                'format' => 'raw',
                'value' => function (Post $data) use ($user) {
                    $label = "";
                    /* if ($data->isLocked($user, '')) {
                         if ($user->canAccessToResource('post/release')) {
                             $label .= Html::a("<i class='fa fa-lock'></i>", ['post/edit', 'id' => $data->id, 'release' => 1, 'return' => Url::current()], ['data-pjax' => 0]);
                         } else {
                             $label .= "<i class='fa fa-lock'></i>";
                         }
                     }*/

                    return $label . '<a href="' . $data->getShortViewUrl() . '" data-pjax="0" target="_blank"><i class="fa fa-external-link"></i></a>';
                },
            ],
            [
                'attribute' => 'title',
                'format' => 'raw',
                'value' => function ($data) use ($user) {
                    return Html::a($data->getTitleView(), ['post/edit', 'id' => $data->id], ['data-pjax' => 0]);
                },
            ],
            [
                'attribute' => 'views',
            ],
            [
                'label' => __('Trending'),
                'attribute' => 'views_l3d',
                'format' => 'raw',
                'value' => function ($data) {
                    return $data->views_l3d ? $data->views_l3d : 0;
                },
            ],

            [
                'attribute' => '_creator',
                'format' => 'raw',
                'value' => function ($data) {
                    return $data->creator ? $data->creator->login : '';
                },
            ],
            [
                'attribute' => '_author',
                'format' => 'raw',
                'value' => function ($data) {
                    return $data->author ? $data->author->login : '';
                },
            ],
            $searchModel->status == Post::STATUS_DRAFT ?
                [
                    'attribute' => 'created_at',
                    'format' => 'raw',
                    'value' => function ($data) {
                        return ($data->created_at instanceof Timestamp) ? Yii::$app->formatter->asDatetime($data->created_at->getTimestamp()) : '';
                    },
                ] :
                [
                    'attribute' => 'published_on',
                    'format' => 'raw',
                    'value' => function ($data) {
                        return ($data->published_on instanceof Timestamp) ? Yii::$app->formatter->asDatetime($data->published_on->getTimestamp()) : '';
                    },
                ],
            [
                'attribute' => 'updated_at',
                'format' => 'raw',
                'value' => function ($data) {
                    return Yii::$app->formatter->asDatetime($data->updated_at->getTimestamp());
                },
            ],
            $searchModel->status == Post::STATUS_DRAFT && $this->_user()->canAccessToResource('post/trash') ?
                [
                    'format' => 'raw',
                    'value' => function ($data) {
                        if ($data->status == Post::STATUS_DRAFT) {
                            return Html::a(__('Delete'), ['post/trash', 'id' => $data->id], ['class' => 'btn-delete']);
                        }
                    },
                ] :
                [
                    'attribute' => 'is_main',
                    'format' => 'raw',
                    'value' => function (Post $data) {
                        return CheckBo::widget([
                            'type' => 'switch',
                            'options' => [
                                'onclick' => "changeAttribute('$data->id', 'is_main')",
                                'disabled' => $data->status != Post::STATUS_PUBLISHED ? true : false
                            ],
                            'name' => $data->id,
                            'value' => $data->is_main
                        ]);
                    },
                ],

            [
                'attribute' => 'id',
                'format' => 'raw',
                'value' => function ($data) {
                    return Html::a(__('Schedule'), ['post/schedule', 'p' => $data->id], ['data-pjax' => 0]);
                },
            ],
        ],
    ]); ?>
</div>
<script>
    function changeAttribute(id, att) {
        var data = {};
        data.id = id;
        data.attribute = att;
        $.get('<?= Url::to(['post/change'])?>', data)
    }
</script>
<?php Pjax::end() ?>
