<?php

/* @var $this View */

/* @var $content string */

use common\components\Config;
use common\models\Category;
use frontend\assets\AppAsset;
use frontend\components\View;
use yii\helpers\Html;

AppAsset::register($this);

$url   = $this->getCanonical();
$title = Html::encode($this->title ? $this->title . ' — Minbar' : __('Xabarlar — Minbar'));

if (!$this->hasDescription())
    $this->addDescription([__('Хабар берамиз, муҳокама қиламиз ва кайфиятни кўтарамиз!')]);
if (!$this->hasKeywords())
    $this->addKeywords([__('Yangiliklar, xabarlar, voqealar, hodisalar, dunyo yangiliklari, mahalliy yangiliklar, kun xabarlari, tezkor xabarlar, tezkor yangiliklar, minbar.uz')]);

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
    <meta property="og:title" content="<?= Html::encode($this->title ? $this->title : __('Xabarlar — Minbar')) ?>">
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
    <link rel="apple-touch-icon" sizes="114x114" href="<?= $this->getImageUrl('/favicon/apple-icon-114x114.png') ?>">
    <link rel="apple-touch-icon" sizes="120x120" href="<?= $this->getImageUrl('/favicon/apple-icon-120x120.png') ?>">
    <link rel="apple-touch-icon" sizes="144x144" href="<?= $this->getImageUrl('/favicon/apple-icon-144x144.png') ?>">
    <link rel="apple-touch-icon" sizes="152x152" href="<?= $this->getImageUrl('/favicon/apple-icon-152x152.png') ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="<?= $this->getImageUrl('/favicon/apple-icon-180x180.png') ?>">
    <link rel="icon" type="image/png" sizes="192x192"
          href="<?= $this->getImageUrl('/favicon/android-icon-192x192.png') ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= $this->getImageUrl('/favicon/favicon-32x32.png') ?>">
    <link rel="icon" type="image/png" sizes="96x96" href="<?= $this->getImageUrl('/favicon/favicon-96x96.png') ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= $this->getImageUrl('/favicon/favicon-16x16.png') ?>">
    <link rel="manifest" href="<?= $this->getImageUrl('/favicon/manifest.json') ?>">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="<?= $this->getImageUrl('/favicon/ms-icon-144x144.png') ?>">
    <meta name="theme-color" content="#414042">

    <link rel="canonical" href="<?= $url ?>"/>

    <title><?= $title ?></title>

    <script src="<?= $this->getAssetManager()->getBundle('frontend\assets\AppAsset')->baseUrl ?>/js/lazysizes.min.js"></script>
    <?php $this->head() ?>
</head>
<body class="<?= $this->getBodyClass() ?> lazy-smart has-lb">
<?php $this->beginBody() ?>
<?= $content ?>
<?php $this->endBody() ?>

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-134145350-1"></script>
<script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }

    gtag('js', new Date());

    gtag('config', 'UA-134145350-1');
</script>
<!-- START WWW.UZ TOP-RATING -->
<SCRIPT language="javascript" type="text/javascript">
    top_js = "1.0";
    top_r = "id=32160&r=" + escape(document.referrer) + "&pg=" + escape(window.location.href);
    document.cookie = "smart_top=1; path=/";
    top_r += "&c=" + (document.cookie ? "Y" : "N")
</SCRIPT>
<SCRIPT language="javascript1.1" type="text/javascript">
    top_js = "1.1";
    top_r += "&j=" + (navigator.javaEnabled() ? "Y" : "N")
</SCRIPT>
<SCRIPT language="javascript1.2" type="text/javascript">
    top_js = "1.2";
    top_r += "&wh=" + screen.width + 'x' + screen.height + "&px=" +
        (((navigator.appName.substring(0, 3) == "Mic")) ? screen.colorDepth : screen.pixelDepth)
</SCRIPT>
<SCRIPT language="javascript1.3" type="text/javascript">
    top_js = "1.3";
</SCRIPT>
<SCRIPT language="JavaScript" type="text/javascript">
    top_rat = "&col=340F6E&t=ffffff&p=BD6F6F";
    top_r += "&js=" + top_js + "";
    (function () {
        document.write('<img asyn src="https://cnt0.www.uz/counter/collect?' + top_r + top_rat + '" width=0 height=0 border=0 style="display: none"/>')
    })();
</SCRIPT>
</body>
</html>
<?php $this->endPage() ?>
