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
$this->beginContent('@app/views/layouts/main.php');
?>
<div class="site">
    <?= $this->renderFile('@frontend/views/layouts/partials/header.php') ?>
    <div class="container">
        <?= \frontend\widgets\Banner::widget([
            'place' => 'before_main',
            'options' => ['class' => 'ads-wrapper']
        ]) ?>
    </div>
    <div class="site-content">
        <div class="content-area  pt40 pb40">
            <main class="site-main">
                <?= $content ?>
            </main>
        </div>
        <?php if (isset($this->params['post'])): ?>
            <?= $this->renderFile('@frontend/views/post/like.php', [
                'model' => $this->params['post']
            ]) ?>
        <?php endif; ?>
    </div>
    <?= $this->renderFile('@frontend/views/layouts/partials/footer.php') ?>
</div>

<?php $this->endContent() ?>
