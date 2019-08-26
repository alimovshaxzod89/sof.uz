<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2017. ActiveMedia Solutions LLC
 * @author    Toir Tuychiyev <toir427@gmail.com>
 */

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

/* @var $model \frontend\models\SignupForm */

use yii\authclient\widgets\AuthChoice;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

$this->title = __('Ro’yhatdan o’tish');
$this->registerJs("
$('#agreement').on('click', function (e) {
        if ($(e.target).is(':checked')) {
            window.open('".Url::to(['page/foydalanish-shartlari']) ."', 'agreement_page')
        }
    })
")
?>

<div class="main__container middle-content">

    <div class="popup__header">
        <h2><?= __('Ro’yhatdan o’tish') ?></h2>
    </div><!-- End of popup__header -->

    <div class="popup__body">
        <?= \frontend\widgets\Alert::widget() ?>
        <?php $form = ActiveForm::begin([
                                            'errorCssClass'   => 'has_error',
                                            'successCssClass' => 'has_success',

                                        ]); ?>

        <?= $form->field($model, 'full_name', [
            'template' => "<span class=\"after\"><i class=\"icon form-error-icon\"></i><i class=\"icon form-success-icon\"></i></span>\n{input}",
            'options'  => ['class' => 'form-row validate'],
        ])->textInput(['autofocus' => false, 'placeholder' => __('Full Name')])->label(false) ?>

        <?= $form->field($model, 'email', [
            'template' => "<span class=\"after\"><i class=\"icon form-error-icon\"></i><i class=\"icon form-success-icon\"></i></span>\n{input}",
            'options'  => ['class' => 'form-row validate'],
        ])->textInput(['autofocus' => false, 'placeholder' => __('Email')])->label(false) ?>

        <?= $form->field($model, 'password', [
            'template' => "<span class=\"after\"><i class=\"icon form-error-icon\"></i><i class=\"icon form-success-icon\"></i></span>\n{input}",
            'options'  => ['class' => 'form-row  validate'],
        ])->passwordInput(['autofocus' => false, 'placeholder' => __('Password')])->label(false) ?>

        <?= $form->field($model, 'confirmation', [
        'template' => "<span class=\"after\"><i class=\"icon form-error-icon\"></i><i class=\"icon form-success-icon\"></i></span>\n{input}",
        'options'  => ['class' => 'form-row mb-none validate'],
        ])->passwordInput(['autofocus' => false, 'placeholder' => __('Confirmation')])->label(false) ?>
        <p class="form-hint"><?= __('Sizning parolingiz eng kamida 6 ta harfdan iborat bo’lishi kerak') ?></p>



        <p class="form-group">
            <?= Html::submitButton(__('Akkount ochish'), ['class' => 'btn rounded']) ?>
        </p>
        <p class="enter-options"><span><?= __('Ijtimoiy tarmoqlar bilan kiring') ?></span></p>

        <?php $authChoice = AuthChoice::begin(['baseAuthUrl' => ['account/auth']]) ?>
        <p>
            <?php foreach ($authChoice->getClients() as $i => $client) {
                echo $authChoice->clientLink($client, $client->getTitle(), ['class' => 'btn rounded ' . $client->getName()]);
                if ($i + 1 < count($authChoice->getClients())) {
                    echo "<span class='h-space'></span>";
                }
            } ?>
        </p>
        <?php AuthChoice::end() ?>

        <div class="hr smaller"></div>

        <p><?= __('Menda akkount bor') ?><span class="h-space"></span>
            <a href="<?= Url::to(['account/login']) ?>" data-target="popup-enter"><?= __('Kirish') ?></a>
        </p>
        <?php ActiveForm::end(); ?>

    </div><!-- End of popup__body -->
</div><!-- End of popup__content -->
<script>

</script>