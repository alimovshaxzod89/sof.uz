<?php

use backend\assets\BackendAsset;
use backend\assets\UrbanAsset;
use yii\helpers\Html;


/* @var $this \yii\web\View */
/* @var $content string */
UrbanAsset::register($this);
BackendAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<?= $content ?>
<?php $this->endBody() ?>
<div id="message_box">
</div>
</body>
</html>
<?php $this->endPage() ?>
