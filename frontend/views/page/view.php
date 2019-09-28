<?php

use frontend\components\View;

/* @var $this View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\Page */

$this->title                   = $model->title;
$this->_canonical              = $model->getViewUrl();
$this->params['breadcrumbs'][] = $this->title;
$this->addDescription([$this->title]);
?>
<div class="container">
    <article id="post-241" class="post page type-page status-publish hentry">
        <div class="container small">
            <header class="entry-header">
                <h1 class="entry-title"><?= $this->title ?></h1>
            </header>
            <br>
        </div>

        <div class="container medium">
        </div>

        <div class="container small">
            <div class="entry-wrapper">
                <div class="entry-content u-text-format u-clearfix">
                    <?= $model->content ?>
                </div>
            </div>
        </div>
    </article>
</div>
