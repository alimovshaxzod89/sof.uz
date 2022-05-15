<?php

use frontend\components\View;

use frontend\widgets\Banner;
use yii\helpers\Url;

/**
 * @var $this    View
 * @var $content string
 */
$globalVars = [
    'min' => minVersion(),
    'typo' => Url::to(['site/typo']),
    'l' => Yii::$app->language,
    'a' => Yii::getAlias('@apiUrl'),
    'd' => YII_DEBUG,
    'm' => ['typo' => __('Iltimos xatoni belgilang!')],
    'u' => !Yii::$app->user->getIsGuest() ? $this->getUser()->getId() : $this->context->getUserId()
];
if (isset($this->params['post'])) $globalVars['p'] = $this->params['post']->getId();
if (isset($this->params['post']) && strpos($this->params['post']->content, 'abt_test')) {
    \frontend\assets\TestAsset::register($this);
}
if (isset($this->params['category'])) $globalVars['c'] = $this->params['category']->getId();
$this->registerJs('var globalVars=' . \yii\helpers\Json::encode($globalVars) . ';', View::POS_HEAD);
$this->beginContent('@app/views/layouts/purple/main.php');
?>

<div class="main_body">

    <div class="cureency">
        <div class="head_currency">
            <span class="h5">Валюта курси:</span> <span class="bank">Ўз.Р.Марказий банки</span>
        </div>
        <div class="info">
            <div class="foot_currency">

                <div class="usd">
                    <div class="block"><span>USD</span> <br> <span>Lorem</span></div>
                    <div class="block"><span class="micro">АҚШ доллари</span> <span class="micro">lorem</span></div>
                </div>

                <div class="euro">
                    <div class="block"><span>EUR</span> <br> <span>Lorem</span></div>
                    <div class="block"><span class="micro">EВРО</span><span class="micro">lorem</span></div>
                </div>

                <div class="rub">
                    <div class="block"><span>RUB</span> <br> <span>Lorem</span></div>
                    <div class="block"><span class="micro">Россия рубли</span><span class="micro">lorem</span></div>
                </div>
            </div>
        </div>
    </div>

    <div class="running_info">
        <marquee behavior="scroll" direction="left">Бегущая строка</marquee>
    </div>

    <?= $this->renderFile('@frontend/views/layouts/purple/partials/header.php') ?>


    <!--    <div class="container">-->
    <!--        --><? //= \frontend\widgets\Banner::widget([
    //            'place' => 'before_main',
    //            'options' => ['class' => 'ads-wrapper']
    //        ]) ?>
    <!--    </div>-->

    <?= $content ?>

</div>

<?php $this->endContent() ?>
