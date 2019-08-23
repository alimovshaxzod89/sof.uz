<?php

use backend\components\View;
use common\widgets\Alert;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/* @var $this View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $model \common\models\Error */
/* @var $searchModel \common\models\Error */

$this->title                   = __('Manage Errors');
$this->params['breadcrumbs'][] = $this->title;
$user                          = $this->context->_user();

?>

<?php Pjax::begin(['id' => 'tag-grid', 'timeout' => false, 'options' => ['data-pjax' => false], 'enablePushState' => false]) ?>
<?= Alert::widget() ?>
<div class="post-form">
    <div class="row">
        <div class="col col-md-5" id="tag_settings">
            <?php $form = ActiveForm::begin(['enableAjaxValidation' => false, 'action' => Url::current(['id' => $model->getId(), 'save' => 1]), 'options' => ['data-pjax' => 1]]); ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="pull-left">
                        <div class="h4 mb5"><?= __('Xatolik xabari') ?></div>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col col-md-12">
                            <label class="control-label bold"><?= __('Belgilangan matn') ?></label>

                            <p>
                                <?= $model->text ?>
                            </p>
                            <hr>
                        </div>
                        <div class="col col-md-12">
                            <label class="control-label bold"><?= __('Xabar') ?></label>

                            <p>
                                <?= $model->message ?>
                            </p>
                            <hr>
                        </div>
                        <div class="col col-md-12">
                            <label class="control-label bold"><?= __('Sahifa') ?></label>

                            <p>
                                <a href="<?= $model->url ?>" target="_blank">
                                    <?= $model->url ?>
                                </a>
                            </p>
                            <hr>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col col-md-6">

                        </div>
                    </div>

                </div>
                <div class="panel-footer">
                    <div class="text-right">
                        <?php if ($model->getId()): ?>
                            <?= Html::a(__('Delete'), ['error/delete', 'id' => $model->getId()], ['class' => 'btn btn-danger', 'data-confirm' => __('Are you sure to delete?')]) ?>
                            <?php if ($model->status != \common\models\Error::STATUS_FIXED): ?>
                                <?= Html::a('<i class="fa fa-check"></i> ' . __('Fixed'), ['error/index', 'id' => $model->getId(), 'fix' => 1], ['class' => 'btn btn-success']) ?>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
        <div class="col col-md-7 tags_list">
            <?= $this->renderFile('@backend/views/error/_list.php', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider]) ?>
        </div>
    </div>
</div>

<?php
$this->registerJs('
$(document).ready(function () {
    jQuery(\'#tag_settings\').theiaStickySidebar({
        additionalMarginTop: 70,
        additionalMarginBottom: 20
    });
})
');
?>


<?php Pjax::end() ?>

