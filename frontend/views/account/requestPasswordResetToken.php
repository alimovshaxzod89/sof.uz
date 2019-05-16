<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2018. ActiveMedia Solutions LLC
 * @author    Rustam Mamadaminov <rmamdaminov@gmail.com>
 */

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

/* @var $model \frontend\models\PasswordResetRequestForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title                   = __('Parolni tiklash so\'rovi');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="main__container middle-content">

    <div class="popup__header">
        <h2><?= __('Parolni tiklash so\'rovini pochta manziliga yuborish') ?></h2>
    </div><!-- End of popup__header -->

    <div class="popup__body">
        <?= \frontend\widgets\Alert::widget() ?>

        <?php $form = ActiveForm::begin([
                                            'errorCssClass'   => 'has_error',
                                            'successCssClass' => 'has_success',

                                        ]); ?>

        <?= $form->field($model, 'email', [
            'template' => "<span class=\"before\"><i class=\"icon form-password-icon\"></i></span><span class=\"after\"><i class=\"icon form-error-icon\"></i><i class=\"icon form-success-icon\"></i></span>\n{input}",
            'options'  => ['class' => 'form-row validate has_icon'],
        ])->textInput(['autofocus' => false, 'autocomplate' => false, 'placeholder' => __('Email')])->label(false) ?>

        <p class="form-group">
            <?= Html::submitButton(__('Yuborish'), ['class' => 'btn rounded']) ?>
        </p>

        <?php ActiveForm::end(); ?>
    </div><!-- End of popup__body -->
</div><!-- End of popup__content -->
