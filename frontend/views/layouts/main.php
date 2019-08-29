<?php

use common\components\Config;
use common\models\Category;
use frontend\assets\AppAsset;
use frontend\components\View;
use yii\helpers\Html;

/**
 * @var $this View
 * @var $content string
 */

AppAsset::register($this);

$url   = $this->getCanonical();
$title = Html::encode($this->title ? $this->title . ' — Sof.uz' : __('Xabarlar — Sof.uz'));

if (!$this->hasDescription())
    $this->addDescription([__('Хабар берамиз, муҳокама қиламиз ва кайфиятни кўтарамиз!')]);
if (!$this->hasKeywords())
    $this->addKeywords([__('Yangiliklar, xabarlar, voqealar, hodisalar, dunyo yangiliklari, mahalliy yangiliklar, kun xabarlari, tezkor xabarlar, tezkor yangiliklar, sof.uz')]);

$description = $this->getDescription();
$keywords    = $this->getKeywords();
$this->registerCsrfMetaTags();
$main_menu = Category::getCategoryTree([], Config::getRootCatalog());

?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Config::getHtmlLangSpec(Yii::$app->language) ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

        <meta property="og:url" content="<?= $url ?>">
        <meta property="og:title" content="<?= Html::encode($this->title ? $this->title : __('Xabarlar — Sof.uz')) ?>">
        <meta property="og:description" content="<?= $description ?>">
        <meta property="og:image" content="<?= $this->getImage() ?>">
        <meta property="og:type" content="article"/>

        <meta name="description" content="<?= $description ?>">
        <meta name="keywords" content="<?= $keywords ?>">
        <?php if ($post = $this->getPost()): ?>
            <?php if ($post->isPublished()): ?>
                <?php if ($category = $this->getCategory()): ?>
                    <meta name="mediator_theme"
                          content="<?= $category->getTranslation('name', Config::LANGUAGE_DEFAULT) ?>"/>
                <?php endif; ?>
                <meta name="mediator" content="<?= $post->short_id ?>"/>
                <meta name="mediator_published_time" content="<?= $post->getPublishedTimeIso() ?>"/>
            <?php endif; ?>
        <?php endif; ?>
        <link rel="apple-touch-icon" sizes="57x57" href="<?= $this->getImageUrl('/favicon/apple-icon-57x57.png') ?>">
        <link rel="apple-touch-icon" sizes="60x60" href="<?= $this->getImageUrl('/favicon/apple-icon-60x60.png') ?>">
        <link rel="apple-touch-icon" sizes="72x72" href="<?= $this->getImageUrl('/favicon/apple-icon-72x72.png') ?>">
        <link rel="apple-touch-icon" sizes="76x76" href="<?= $this->getImageUrl('/favicon/apple-icon-76x76.png') ?>">
        <link rel="apple-touch-icon" sizes="114x114"
              href="<?= $this->getImageUrl('/favicon/apple-icon-114x114.png') ?>">
        <link rel="apple-touch-icon" sizes="120x120"
              href="<?= $this->getImageUrl('/favicon/apple-icon-120x120.png') ?>">
        <link rel="apple-touch-icon" sizes="144x144"
              href="<?= $this->getImageUrl('/favicon/apple-icon-144x144.png') ?>">
        <link rel="apple-touch-icon" sizes="152x152"
              href="<?= $this->getImageUrl('/favicon/apple-icon-152x152.png') ?>">
        <link rel="apple-touch-icon" sizes="180x180"
              href="<?= $this->getImageUrl('/favicon/apple-icon-180x180.png') ?>">
        <!--<link rel="icon" type="image/png" sizes="192x192"
              href="<?= $this->getImageUrl('/favicon/android-icon-192x192.png') ?>">
        <link rel="icon" type="image/png" sizes="32x32" href="<? /*= $this->getImageUrl('/favicon/favicon-32x32.png') */ ?>">
        <link rel="icon" type="image/png" sizes="96x96" href="<? /*= $this->getImageUrl('/favicon/favicon-96x96.png') */ ?>">
        <link rel="icon" type="image/png" sizes="16x16" href="<? /*= $this->getImageUrl('/favicon/favicon-16x16.png') */ ?>">-->
        <link rel="manifest" href="<?= $this->getImageUrl('/favicon/manifest.json') ?>">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="<?= $this->getImageUrl('/favicon/ms-icon-144x144.png') ?>">
        <meta name="theme-color" content="#414042">

        <link rel="canonical" href="<?= $url ?>"/>

        <title><?= $title ?></title>
        <?php $this->head() ?>
    </head>
    <body class="<?= $this->getBodyClass() ?>">
    <?php $this->beginBody() ?>

    <?= $content ?>

    <div class="dimmer"></div>
    <div class="off-canvas">
        <div class="canvas-close"><i class="mdi mdi-close"></i></div>

        <div class="logo-wrapper">
            <a class="logo text" href="<?= Yii::$app->getHomeUrl() ?>">SOF</a>
        </div>
        <div class="mobile-menu hidden-lg hidden-xl"></div>
    </div>
    <div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="pswp__bg"></div>

        <div class="pswp__scroll-wrap">
            <div class="pswp__container">
                <div class="pswp__item"></div>
                <div class="pswp__item"></div>
                <div class="pswp__item"></div>
            </div>

            <div class="pswp__ui pswp__ui--hidden">
                <div class="pswp__top-bar">
                    <div class="pswp__counter"></div>

                    <button class="pswp__button pswp__button--close" title="Close (Esc)"></button>
                    <button class="pswp__button pswp__button--share" title="Share"></button>
                    <button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>
                    <button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>

                    <div class="pswp__preloader">
                        <div class="pswp__preloader__icn">
                            <div class="pswp__preloader__cut">
                                <div class="pswp__preloader__donut"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
                    <div class="pswp__share-tooltip"></div>
                </div>

                <button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)"></button>

                <button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)"></button>

                <div class="pswp__caption">
                    <div class="pswp__caption__center"></div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        var magsyParams = {
            "home_url": "https:\/\/magsy.mondotheme.com",
            "admin_url": "https:\/\/magsy.mondotheme.com\/wp-admin\/admin-ajax.php",
            "logo_regular": "",
            "logo_contrary": "",
            "like_nonce": "f8730706c2",
            "unlike_nonce": "1068f1b44e",
            "like_title": "Click to like this post.",
            "unlike_title": "You have already liked this post. Click again to unlike it.",
            "infinite_load": "Load more",
            "infinite_loading": "Loading..."
        };
    </script>
    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>