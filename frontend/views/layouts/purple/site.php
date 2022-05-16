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

    <?= $this->renderFile('@frontend/views/layouts/purple/partials/before_header.php') ?>

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
