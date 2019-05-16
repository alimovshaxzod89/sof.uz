<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

/* @var $model \frontend\models\LoginForm */

use frontend\models\ErrorForm;
use frontend\models\LoginForm;
use frontend\models\SignupForm;
use yii\authclient\widgets\AuthChoice;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title                   = __('Kirish');
$this->params['breadcrumbs'][] = $this->title;

$loginModel = new LoginForm();
$regModel   = new SignupForm();
$errorModel = new ErrorForm();
?>

<div class="main__container middle-content">

    <div class="popup__header">
        <h2><?= __('Kirish') ?></h2>
    </div><!-- End of popup__header -->

    <div class="popup__body">
        <?= \frontend\widgets\Alert::widget() ?>

        <?php $form = ActiveForm::begin([
                                            'errorCssClass'   => 'has_error',
                                            'successCssClass' => 'has_success',

                                        ]); ?>

        <?= $form->field($model, 'email', [
            'template' => "<span class=\"before\"><i class=\"icon form-email-icon\"></i></span><span class=\"after\"><i class=\"icon form-error-icon\"></i><i class=\"icon form-success-icon\"></i></span>\n{input}",
            'options'  => ['class' => 'form-row validate has_icon'],
        ])->textInput(['autofocus' => false, 'autocomplate' => false, 'placeholder' => __('Email')])->label(false) ?>

        <?= $form->field($model, 'password', [
            'template' => "<span class=\"before\"><i class=\"icon form-password-icon\"></i></span><span class=\"after\"><i class=\"icon form-error-icon\"></i><i class=\"icon form-success-icon\"></i></span>\n{input}",
            'options'  => ['class' => 'form-row validate has_icon'],
        ])->passwordInput(['autofocus' => false, 'autocomplate' => false, 'placeholder' => __('Password')])->label(false) ?>

        <p class="form-group">
            <?= Html::submitButton(__('Kirish'), ['class' => 'btn rounded']) ?>
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
        <p class="mb-none"><a
                    href="<?= Url::to(['account/request-password-reset']) ?>"><?= __('Parolni unutdingizmi?') ?></a>
        </p>

        <div class="hr smaller"></div>
        <p><?= __('Akkauntingiz yo\'qmi?') ?><span class="h-space"></span>
            <a href="<?= Url::to(['account/signup']) ?>" data-target="popup-registration">
                <?= __('Ro\'yhatdan o\'tish') ?>
            </a>
        </p>
        <?php ActiveForm::end(); ?>
    </div><!-- End of popup__body -->
</div><!-- End of popup__content -->
