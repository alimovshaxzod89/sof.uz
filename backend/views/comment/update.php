<?php

use backend\components\View;
use common\models\Comment;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model Comment */
?>

<div class="user-create">
    <div class="user-form">
        <?php $form = ActiveForm::begin(['id' => 'comment_form', 'action' => ['comment/index', 'save' => $model->id]]); ?>
        <div class="row">
            <div class="col col-md-12">
                <div class="panel panel-primary">
                    <div class="panel-body">
                        <?= $form->field($model, 'text', [])->textarea(['rows' => 6]) ?>
                    </div>
                </div>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
