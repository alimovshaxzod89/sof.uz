<?php

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \backend\models\AdminLoginForm */

$this->title                   = __('Reset Password');
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $form = ActiveForm::begin(['id' => 'reset-form', 'enableAjaxValidation' => true,]); ?>
<div class="panel panel-primary panel-lg">
    <div class="panel-heading">
        <?= __('Reset Password') ?>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col col-lg-12">
                <?= $form->field($model, 'password')->passwordInput(['placeholder' => __('Enter Strong Password')])->label(false) ?>
                <?= $form->field($model, 'confirmation')->passwordInput(['placeholder' => __('Confirm Password')])->label(false) ?>
            </div>
        </div>
    </div>
    <div class="panel-footer">
        <div class="pull-right">
            <?= Html::submitButton('Change Password', ['class' => 'btn btn-primary pull-right']) ?>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
<?php ActiveForm::end(); ?>
<?php
$this->registerJs('
    $(document).ready(function () {
        var input = $("#admin-email");
        input.focus().val(input.val());
    })
')
?>
