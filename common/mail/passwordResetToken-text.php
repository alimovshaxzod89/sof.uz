<?php

/* @var $this yii\web\View */
/* @var $user common\models\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['account/reset-password', 'token' => $user->password_reset_token]);
?>
<?= __('Hello {user},', ['user' => $user->getFullname()]) ?>

<?= __('Follow the link below to reset your password:') ?>

<?= $resetLink ?>
