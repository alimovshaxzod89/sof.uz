<?php

/* @var $this View */
/* @var $form yii\bootstrap\ActiveForm */

/* @var $model \frontend\models\ContactForm */

use frontend\components\View;
use himiklab\yii2\recaptcha\ReCaptcha;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title                   = __('Aloqa');
$this->_canonical              = linkTo(['/contact']);
$this->params['breadcrumbs'][] = $this->title;
$this->addDescription([__('Biz bilan bog\'laning')]);
?>
<div class="main-container container  mt-32" id="main-container">
    <div class="row">
        <div class="col-lg-8 blog__content mb-40">
            <div class="content-box">
                <h4 class="widget-title"><?= __('Biz bilan bog\'laning') ?></h4>

                <p>
                    <?= __('Сайт фаолияти юзасидан таклиф ёки танқидларингиз борми? «Zarnews.uz»га мақола ёки хабар юбормоқчимисиз? Ёки ўзингиз гувоҳ бўлган қандайдир ҳодиса ҳақида маълум қилмоқчимисиз? Ҳамкорликка тайёрмиз, биз билан ўзингизга қулай шаклда боғланинг.') ?>
                </p>
                <hr>
                <div class="card-row">
                    <?= \frontend\widgets\Alert::widget() ?>

                    <?php $form = ActiveForm::begin(['options' => ['class' => 'contact-form mt-30 mb-30']]); ?>

                    <?= $form->field($model, 'name')->textInput(['autofocus' => false, 'autocomplate' => false, 'placeholder' => $model->getAttributeLabel('name')]) ?>

                    <?= $form->field($model, 'email')->textInput(['autofocus' => false, 'autocomplate' => false, 'placeholder' => $model->getAttributeLabel('email')]) ?>

                    <?= $form->field($model, 'subject')->textInput(['autofocus' => false, 'autocomplate' => false, 'placeholder' => $model->getAttributeLabel('subject')]) ?>

                    <?= $form->field($model, 'body')->textarea(['rows' => 6, 'autofocus' => false, 'autocomplate' => false, 'placeholder' => $model->getAttributeLabel('body')]) ?>

                    <div class="row mt-32">
                        <div class="col-md-8">
                            <?= $form->field($model, 'verifyCode')->widget(ReCaptcha::className(), [])->label(false) ?>

                        </div>
                        <div class="col-md-4 text-right">
                            <?= Html::submitButton(__('Jo\'natish'), ['class' => 'btn btn-lg btn-color btn-button btn-wide']) ?>

                        </div>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>

            </div>
        </div>
        <aside class="col-lg-4 sidebar sidebar--right ssbar">
            <aside class="widget widget-popular-posts">
                <?= \common\models\Page::getStaticBlock('sidebar-contact') ?>
            </aside>
        </aside>
    </div>
</div>