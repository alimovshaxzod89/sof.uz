<?php

use frontend\components\View;

/**
 * @var $this    View
 * @var $content string
 */
$globalVars = [
    'min'  => minVersion(),
    'typo' => linkTo(['site/typo']),
    'l'    => Yii::$app->language,
    'a'    => Yii::getAlias('@apiUrl'),
    'd'    => YII_DEBUG,
    'm'    => ['typo' => __('Iltimos xatoni belgilang!')],
    'u'    => !Yii::$app->user->getIsGuest() ? $this->getUser()->getId() : $this->context->getUserId()
];
if (isset($this->params['post'])) $globalVars['p'] = $this->params['post']->getId();
if (isset($this->params['category'])) $globalVars['c'] = $this->params['category']->getId();
$this->registerJs('var globalVars=' . \yii\helpers\Json::encode($globalVars) . ';', View::POS_HEAD);
$this->beginContent('@app/views/layouts/main.php');
?>
<div class="site">
<?= $this->renderFile('@app/views/layouts/header.php') ?>

    <?= \frontend\widgets\Banner::widget(['place' => 'header_top']) ?>
    <?= $content ?>

<?= $this->renderFile('@app/views/layouts/footer.php') ?>
</div>

<?php $this->endContent() ?>
