<?php

use frontend\components\View;

/* @var $this View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\Page */

$this->title = $model->title;
$this->_canonical = $model->getViewUrl();
$this->params['breadcrumbs'][] = $this->title;
$this->addDescription([$this->title]);
?>
<div class="container small">

    <h2 class="head"><?= $this->title ?></h2>

    <div class="head_add">
        <?= $model->content ?>
    </div>
</div>

