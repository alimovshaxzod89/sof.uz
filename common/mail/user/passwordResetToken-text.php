<?php

/* @var $this yii\web\View */
/* @var $user common\models\Admin */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['account/reset-password', 'token' => $user->password_reset_token]);
?>
Hello <?= $user->fullname ?>,

Follow the link below to reset your password:

<?= $resetLink ?>
