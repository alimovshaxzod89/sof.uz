<?php

use common\components\Config;
use common\models\Category;
use frontend\assets\PurpleThemeAsset;
use frontend\components\View;

/**
 * @var $this View
 * @var $content string
 */

PurpleThemeAsset::register($this);

$url = $this->getCanonical();
$title = $this->title ? $this->title . ' — Sof.uz' : __('Xabarlar — Sof.uz');

if (!$this->hasDescription())
    $this->addDescription([__('Хабар берамиз, муҳокама қиламиз ва кайфиятни кўтарамиз!')]);
if (!$this->hasKeywords())
    $this->addKeywords([__('Yangiliklar, xabarlar, voqealar, hodisalar, dunyo yangiliklari, mahalliy yangiliklar, kun xabarlari, tezkor xabarlar, tezkor yangiliklar, sof.uz')]);

$description = $this->getDescription();
$keywords = $this->getKeywords();
$this->registerCsrfMetaTags();
$main_menu = Category::getCategoryTree([], Config::getRootCatalog());

?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Config::getHtmlLangSpec(Yii::$app->language) ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta property="og:url" content="<?= $url ?>">
        <meta property="og:type" content="article"/>
        <meta property="og:title" content="<?= $this->title ? $this->title : __('Xabarlar — Sof.uz') ?>">
        <meta property="og:description" content="<?= $description ?>">
        <meta property="og:image:secure_url" content="<?= $this->getImage() ?>">
        <meta name="twitter:description" content="<?= $description ?>">
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:image" content="<?= $this->getImage() ?>" />

        <meta name="yandex-verification" content="90a7c91e07d99f26"/>
        <meta name="description" content="<?= $description ?>">
        <meta name="keywords" content="<?= $keywords ?>">

<!--        <link rel="apple-touch-icon" sizes="57x57" href="--><?php //echo $this->getImageUrl('favicon/apple-icon-57x57.png') ?><!--?v=1">-->
<!--        <link rel="apple-touch-icon" sizes="60x60" href="--><?php //echo $this->getImageUrl('favicon/apple-icon-60x60.png') ?><!--?v=1">-->
<!--        <link rel="apple-touch-icon" sizes="72x72" href="--><?php //echo $this->getImageUrl('favicon/apple-icon-72x72.png') ?><!--?v=1">-->
<!--        <link rel="apple-touch-icon" sizes="76x76" href="--><?php //echo $this->getImageUrl('favicon/apple-icon-76x76.png') ?><!--?v=1">-->
<!--        <link rel="apple-touch-icon" sizes="114x114"-->
<!--              href="--><?php //echo $this->getImageUrl('favicon/apple-icon-114x114.png') ?><!--?v=1">-->
<!--        <link rel="apple-touch-icon" sizes="120x120"-->
<!--              href="--><?php //echo $this->getImageUrl('favicon/apple-icon-120x120.png') ?><!--?v=1">-->
<!--        <link rel="apple-touch-icon" sizes="144x144"-->
<!--              href="--><?php //echo $this->getImageUrl('favicon/apple-icon-144x144.png') ?><!--?v=1">-->
<!--        <link rel="apple-touch-icon" sizes="152x152"-->
<!--              href="--><?php //echo $this->getImageUrl('favicon/apple-icon-152x152.png') ?><!--?v=1">-->
<!--        <link rel="apple-touch-icon" sizes="180x180"-->
<!--              href="--><?php //echo $this->getImageUrl('favicon/apple-icon-180x180.png') ?><!--?v=1">-->
<!--        <link rel="icon" type="image/png" sizes="192x192"-->
<!--              href="--><?php //echo $this->getImageUrl('favicon/android-icon-192x192.png') ?><!--?v=1">-->
        <link rel="icon" type="image/png" sizes="32x32"
              href="<?php echo Yii::getAlias('@web/favicon-32x32.png') ?>?v=2">
<!--        <link rel="icon" type="image/png" sizes="96x96"-->
<!--              href="--><?php //echo $this->getImageUrl('favicon/favicon-96x96.png') ?><!--?v=1">-->
<!--        <link rel="icon" type="image/png" sizes="16x16"-->
<!--              href="--><?php //echo $this->getImageUrl('favicon/favicon-16x16.png') ?><!--?v=1">-->
<!--        <link rel="manifest" href="--><?php //echo 'favicon/manifest.json') ?><!--?v=1">-->
        <meta name="msapplication-TileColor" content="#ffffff">
<!--        <meta name="msapplication-TileImage" content="--><?php //echo $this->getImageUrl('favicon/ms-icon-144x144.png') ?><!--?v=1">-->
        <meta name="theme-color" content="#ffffff">

        <link rel="shortcut icon" href="<?= Yii::getAlias('@web/favicon.ico') ?>?v=2" type="image/x-icon">
        <link rel="icon" href="<?= Yii::getAlias('@web/favicon.ico') ?>?v=2" type="image/x-icon">

        <link rel="canonical" href="<?= $url ?>"/>

        <title><?= $title ?></title>

        <?php $this->head() ?>

        <link rel="stylesheet"
              href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    </head>
    <body>
    <?php $this->beginBody() ?>


    <div class="main_body">

        <?= $content ?>

        <?= $this->renderFile('@frontend/views/layouts/purple/partials/poster.php') ?>

        <?= $this->renderFile('@frontend/views/layouts/purple/partials/footer.php') ?>

    </div>

    <script>
        (function (i, s, o, g, r, a, m) {
            i['GoogleAnalyticsObject'] = r;
            i[r] = i[r] || function () {
                (i[r].q = i[r].q || []).push(arguments)
            }, i[r].l = 1 * new Date();
            a = s.createElement(o),
                m = s.getElementsByTagName(o)[0];
            a.async = 1;
            a.src = g;
            m.parentNode.insertBefore(a, m)
        })(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');
        ga('create', 'UA-73222527-2', 'auto');
        ga('send', 'pageview');
    </script>
    <!-- START WWW.UZ TOP-RATING -->
    <SCRIPT language="javascript" type="text/javascript">
        <!--
        top_js = "1.0";
        top_r = "id=1645&r=" + escape(document.referrer) + "&pg=" + escape(window.location.href);
        document.cookie = "smart_top=1; path=/";
        top_r += "&c=" + (document.cookie ? "Y" : "N")
        //-->
    </SCRIPT>
    <SCRIPT language="javascript1.1" type="text/javascript">
        <!--
        top_js = "1.1";
        top_r += "&j=" + (navigator.javaEnabled() ? "Y" : "N")
        //-->
    </SCRIPT>
    <SCRIPT language="javascript1.2" type="text/javascript">
        <!--
        top_js = "1.2";
        top_r += "&wh=" + screen.width + 'x' + screen.height + "&px=" +
            (((navigator.appName.substring(0, 3) == "Mic")) ? screen.colorDepth : screen.pixelDepth)
        //-->
    </SCRIPT>
    <SCRIPT language="javascript1.3" type="text/javascript">
        <!--
        top_js = "1.3";
        //-->
    </SCRIPT>
    <SCRIPT language="JavaScript" type="text/javascript">
        <!--
        top_rat = "&col=7DC53B&t=ffffff&p=DD444E";
        top_r += "&js=" + top_js + "";
        document.write('<a href="https://www.uz/uz/res/visitor/index?id=1645" target=_top><img style="display: none" src="https://cnt0.www.uz/counter/collect?' + top_r + top_rat + '" width=88 height=31 border=0 alt="Топ рейтинг www.uz"></a>')//-->
    </SCRIPT>
    <NOSCRIPT>
        <A href="https://www.uz/uz/res/visitor/index?id=1645" target=_top>
            <IMG height=31 style="display: none"
                 src="https://cnt0.www.uz/counter/collect?id=1645&pg=http%3A//uzinfocom.uz&&col=7DC53B&amp;t=ffffff&amp;p=DD444E"
                 width=88 border=0
                 alt="Топ рейтинг www.uz"></A>
    </NOSCRIPT><!-- FINISH WWW.UZ TOP-RATING -->

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
    <div id="banner-yandex"
         style="height:50px;display: none; box-shadow: 0 0 1px 1px rgba(0,0,0,0.2); position: fixed;z-index: 9999;left: 0;bottom: 0;max-height: 50px;background-color: #fff;width: 100%">
        <!-- Yandex.RTB R-A-476227-1 -->
        <span id="close-banner"
              style="border-radius: 50%;background: #00aa51;width: 28px;line-height: 29px;color: #fff;border-bottom-right-radius: 0;display: inline-block;text-align: center;position: absolute;top: -13px;right: 0px; cursor: pointer;z-index: 999999;box-shadow: 0 0 1px 1px rgba(0,0,0,0.2);">×</span>

        <div id="yandex_rtb_R-A-476227-1"></div>
        <script type="application/javascript">
            !function (a) {
                a(document).ready(function () {
                    if (a(window).width() < 769) {
                        a("#banner-yandex").show();
                        a('#close-banner').click(function (e) {
                            e.preventDefault();
                            a('#banner-yandex').slideUp();
                        });
                        (function (w, d, n, s, t) {
                            w[n] = w[n] || [];
                            w[n].push(function () {
                                Ya.Context.AdvManager.render({
                                        blockId: "R-A-476227-1",
                                        renderTo: "yandex_rtb_R-A-476227-1",
                                        async: true
                                    },
                                    function () {
                                        jQuery('#banner-yandex').initBanner({
                                            "place": "yandex-callback",
                                            "language": globalVars.l
                                        });
                                    });
                            });
                            t = d.getElementsByTagName("script")[0];
                            s = d.createElement("script");
                            s.type = "text/javascript";
                            s.src = "//an.yandex.ru/system/context.js";
                            s.async = true;
                            t.parentNode.insertBefore(s, t);
                        })(window, document, "yandexContextAsyncCallbacks");
                    }
                })
            }(jQuery);
        </script>
    </div>

    <!-- Start Native Network Ads -->
<!--    <script>window.yaContextCb = window.yaContextCb || []</script>-->
<!--    <script src="https://yandex.ru/ads/system/context.js" async></script>-->
<!---->
<!--    <script>-->
<!--        var cscr = document.createElement("script");-->
<!--        cscr.src = "https://ads.nativenetwork.uz/ads.min.js" + "?ts=" + new Date().getTime();-->
<!--        document.getElementsByTagName("body")[0].appendChild(cscr);-->
<!--    </script>-->
    <!-- End Native Network Ads -->

    </body>
    </html>
<?php $this->endPage() ?>