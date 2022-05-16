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

    <div class="news_block">

        <?= $this->render('partials/_post_left_side') ?>

        <div class="latest_block_post">
            <div class="whole_post_page">

                <?= $content ?>

                <!--                <div class="latest_img_post">-->
                <!--                    <div class="first"></div>-->
                <!--                    <div class="second">-->
                <!--                        <a href="">-->
                <!--                            <div class="share"></div>-->
                <!--                        </a>-->
                <!--                        <div class="social">-->
                <!--                            <a href="">-->
                <!--                                <div class="tg"></div>-->
                <!--                            </a>-->
                <!--                            <a href="">-->
                <!--                                <div class="fc"></div>-->
                <!--                            </a>-->
                <!--                            <a href="">-->
                <!--                                <div class="insta"></div>-->
                <!--                            </a>-->
                <!--                        </div>-->
                <!--                    </div>-->
                <!--                </div>-->
                <!---->
                <!--                <div class="content">-->
                <!--                    <div class="title_post">-->
                <!--                        Ўзбекистон ва Туркия ҳарбий ҳамкорлик тўғрисида битим имзолади-->
                <!--                    </div>-->
                <!--                    <div class="date_post_whole">-->
                <!--                        <div class="calendar_icon"><p class="date_text">23:00</p></div>-->
                <!--                    </div>-->
                <!--                    <div class="paragraph_whole">-->
                <!--                        Ўзбекистон ва Туркия ҳарбий ҳамкорлик тўғрисида битим имзолади-->
                <!--                    </div>-->
                <!--                </div>-->
            </div>
        </div>

        <div class="full_post"></div>

        <?= $this->render('partials/_post_right_side') ?>

    </div>

    <?php
    if ($this->params['model'] ?? false)
        echo $this->render('partials/_posts_like', ['model' => $this->params['model']])
    ?>

</div>

<!--    <div class="site-content">-->
<?php //if (isset($this->params['post'])): ?>
<!--    --><? //= $this->renderFile('@frontend/views/post/like.php', [
//        'model' => $this->params['post']
//    ]) ?>
<?php //endif; ?>
<!--    </div>-->

<?php $this->endContent() ?>
