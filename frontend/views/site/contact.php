<?php

/* @var $this View */
/* @var $form yii\bootstrap\ActiveForm */

/* @var $model \frontend\models\ContactForm */

use frontend\components\View;
use himiklab\yii2\recaptcha\ReCaptcha;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title                   = __('Алоқа');
$this->_canonical              = Url::to(['/contact']);
$this->params['breadcrumbs'][] = $this->title;
$this->addDescription([__("Биз билан боғланинг")]);
?>
<style>
    .form-group {
        padding-bottom: 25px;
    }

    .form-group .wpcf7-form-control {
        margin-bottom: 0;
    }

    .form-group.has-error {
        padding-bottom: 0;
    }

    .form-group .help-block {
        margin-bottom: 12px;
        font-size: 12px;
        color: red;
    }
</style>
<div class="site-content">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="content-area">
                    <main class="site-main">
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
                                        <p><?= __('Сайт фаолияти юзасидан таклиф ёки танқидларингиз борми? «sof.uz»га мақола ёки хабар юбормоқчимисиз? Ёки ўзингиз гувоҳ бўлган қандайдир ҳодиса ҳақида маълум қилмоқчимисиз? Ҳамкорликка тайёрмиз, биз билан боғланинг.') ?></p>

                                        <div role="form" class="wpcf7">
                                            <div class="screen-reader-response"></div>
                                            <?php $form = ActiveForm::begin([
                                                                                'options' => [
                                                                                    'class'      => "wpcf7-form",
                                                                                    'novalidate' => "novalidate"
                                                                                ]
                                                                            ]) ?>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <?= $form->field($model, 'name')->textInput(['class' => 'wpcf7-form-control wpcf7-text', 'autofocus' => false, 'autocomplate' => false]) ?>
                                                </div>
                                                <div class="col-md-4">
                                                    <?= $form->field($model, 'email')->textInput(['class' => 'wpcf7-form-control wpcf7-text', 'autofocus' => false, 'autocomplate' => false]) ?>
                                                </div>
                                                <div class="col-md-4">
                                                    <?= $form->field($model, 'subject')->textInput(['class' => 'wpcf7-form-control wpcf7-text', 'autofocus' => false, 'autocomplate' => false]) ?>
                                                </div>
                                            </div>
                                            <?= $form->field($model, 'body')
                                                     ->textarea(['cols' => 40, 'rows' => 10, 'autofocus' => false, 'autocomplate' => false, 'class' => 'wpcf7-form-control wpcf7-textarea']) ?>

                                            <?= YII_DEBUG ? '' : $form->field($model, 'verifyCode')->widget(ReCaptcha::className(), [])->label(false) ?>
                                            <p>
                                                <?= \yii\helpers\Html::submitButton(__("Жўнатиш"), ['class' => 'btn btn-lg btn-color btn-button btn-wide']) ?>
                                            </p>
                                            <?php ActiveForm::end() ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </article>
                    </main>
                </div>
            </div>
        </div>
    </div>
</div>