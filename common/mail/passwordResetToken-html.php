<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['account/reset-password', 'token' => $user->password_reset_token]);
?>
<p><?= __('Hello {user},', ['user' => $user->getFullname()]) ?></p>

<p><?= __('Follow the link below to reset your password:') ?></p>

<p><?= Html::a(Html::encode($resetLink), $resetLink) ?></p>
