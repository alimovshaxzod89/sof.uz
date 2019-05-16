<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2017. ActiveMedia Solutions LLC
 * @author    Rustam Mamadaminov <rmamdaminov@gmail.com>
 */

use backend\widgets\GridView;
use common\models\Comment;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $model Comment */
/* @var $searchModel Comment */

$this->title                   = __('Manage Comment');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">
    <?php Pjax::begin(['id' => 'comment-grid', 'timeout' => false, 'enablePushState' => false]) ?>
    <?php echo \common\widgets\Alert::widget() ?>
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
                                 'id'           => 'data-grid',
                                 'dataProvider' => $dataProvider,
                                 'columns'      => [
                                     [
                                         'attribute'     => 'created_at',
                                         'format'        => 'raw',
                                         'headerOptions' => [
                                             'width' => '600px',
                                         ],
                                         'value'         => function (Comment $data) {
                                             if ($data->status == Comment::STATUS_APPROVED) {
                                                 $update = Html::a(__('Reject'), ['comment/index', 'reject' => $data->id], ['class' => ' btn btn-warning']);
                                             } else {
                                                 $update = Html::a(__('Approve'), ['comment/index', 'approve' => $data->id], ['class' => ' btn btn-info']);
                                             }

                                             $url    = Url::to(['comment/index', 'edit' => $data->id]);
                                             $edit   = Html::a(__('Edit'), '#', ['class' => ' btn btn-success', 'onclick' => "editComment('{$url}')"]);
                                             $delete = Html::a(__('Delete'), ['comment/delete', 'id' => $data->id], ['class' => 'btn btn-danger btn-delete']);

                                             $user = '';
                                             if ($data->user)
                                                 $user = Html::a($data->user->fullname, ['user/update', 'id' => $data->user->getId()], ['target' => '_blank', 'data-pjax' => 0]);
                                             $date    = Yii::$app->formatter->asDatetime($data->created_at->getTimestamp());
                                             $post    = Html::a($data->post->getShortTitle(), Yii::getAlias("@frontendUrl/") . $data->post->short_id, ['target' => '_blank', 'data-pjax' => 0]);
                                             $upvotes = __('{count} upvotes', ['count' => $data->upvote_count]);
                                             $replies = __('{count} replies', ['count' => count($data->replies)]);
                                             $content = "<div class='row' ><div class='col-md-12 cmnt-info'>{$date} / {$user} / {$upvotes} / {$replies} / {$post}</div><div class='col-md-12 cmnt'>{$data->text}</div><div class='col-md-12' style='text-align: right'><br>{$update} {$edit} {$delete}</div></div>";
                                             return $content;
                                         },
                                     ],
                                 ],
                             ]); ?>
    </div>
    <?php Pjax::end() ?>
</div>

<script>
    function editComment(url) {
        $('#comment_edit').load(url, null, function () {
            $('#modal_comment').modal('show');
        })

    }

    function statusComment(url) {
        $.get(url, {}, function (response) {
            $.pjax.reload({container: '#comment-grid'});
        })

    }

    function saveComment() {
        var $form = $('#comment_form');
        $.post($form.attr('action'), $form.serialize(), function (response) {
            $('#modal_comment').modal('hide');
            $.pjax.reload({container: '#comment-grid'});
        })
    }
</script>
<div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" id="modal_comment">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"
                        aria-hidden="true">×
                </button>
                <h4 class="modal-title" id="myLargeModalLabel"><?= __('Edit Comment') ?></h4>
            </div>
            <div class="modal-body" id="comment_edit">
            </div>
            <div class="modal-footer text-right">
                <button typeof="button" onclick="saveComment()"
                        class="btn btn-primary"><?= __('Save') ?></button>
            </div>
        </div>
    </div>
</div>
