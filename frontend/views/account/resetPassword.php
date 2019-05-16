<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2018. ActiveMedia Solutions LLC
 * @author    Rustam Mamadaminov <rmamdaminov@gmail.com>
 */

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

/* @var $model \frontend\models\ResetPasswordForm */

use yii\authclient\widgets\AuthChoice;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

$this->title                   = __('Parolni tiklash');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="main__container middle-content">

    <div class="popup__header">
        <h2><?= __('Yangi parol o\'rnatish') ?></h2>
    </div><!-- End of popup__header -->

    <div class="popup__body">
        <?= \frontend\widgets\Alert::widget() ?>

        <?php $form = ActiveForm::begin([
                                            'errorCssClass'   => 'has_error',
                                            'successCssClass' => 'has_success',

                                        ]); ?>

        <?= $form->field($model, 'password', [
            'template' => "<span class=\"before\"><i class=\"icon form-password-icon\"></i></span><span class=\"after\"><i class=\"icon form-error-icon\"></i><i class=\"icon form-success-icon\"></i></span>\n{input}",
            'options'  => ['class' => 'form-row validate has_icon'],
        ])->passwordInput(['autofocus' => false, 'autocomplate' => false, 'placeholder' => __('Password')])->label(false) ?>
        <?= $form->field($model, 'confirmation', [
            'template' => "<span class=\"after\"><i class=\"icon form-error-icon\"></i><i class=\"icon form-success-icon\"></i></span>\n{input}",
            'options'  => ['class' => 'form-row mb-none validate'],
        ])->passwordInput(['autofocus' => false, 'placeholder' => __('Confirmation')])->label(false) ?>
        <p class="form-hint"><?= __('Sizning parolingiz eng kamida 6 ta harfdan iborat bo’lishi kerak') ?></p>

        <p class="form-group">
            <?= Html::submitButton(__('Tiklash'), ['class' => 'btn rounded']) ?>
        </p>

        <?php ActiveForm::end(); ?>
    </div><!-- End of popup__body -->
</div><!-- End of popup__content -->
