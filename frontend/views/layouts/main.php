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

        <title><?= Html::encode($title) ?></title>
        <?php $this->head() ?>
    </head>
    <body class="<?= $this->getBodyClass() ?>">
    <?php $this->beginBody() ?>

    <?= $content ?>

    <div class="dimmer"></div>
    <div class="off-canvas">
        <div class="canvas-close"><i class="mdi mdi-close"></i></div>

        <div class="logo-wrapper">
            <a class="logo text" href="index.html">Magsy</a>
        </div>

        <div class="mobile-menu hidden-lg hidden-xl"></div>
        <aside class="widget-area">
            <div id="magsy_about_widget-3" class="widget widget_magsy_about_widget">
                <img class="profile-image lazyload"
                     data-src="2018/08/brooke-cagle-609873-unsplash-e1533736730760.jpg"
                     src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=="
                     alt="Magsy Magazine">

                <div class="bio">
                    Schlitz semiotics bespoke flannel small batch, irony raw denim cred chambray bushwick.
                </div>

                <div class="profile-autograph">
                    <img data-src="2018/08/autograph.png"
                         class="profile-autograph lazyload"
                         src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=="
                         alt="Magsy Magazine">
                </div>

            </div>
            <div id="magsy_category_widget-4" class="widget widget_magsy_category_widget">
                <h5 class="widget-title">Categories</h5>
                <ul>
                    <li class="category-item">
                        <a href="category/design/index.html" title="View all posts in Design">
                            <span class="category-name"><i class="dot"
                                                           style="background-color: #ff7473;"></i>Design</span>
                            <span class="category-count">8</span>
                        </a>
                    </li>
                    <li class="category-item">
                        <a href="category_fashion.html" title="View all posts in Fashion">
                            <span class="category-name"><i class="dot"
                                                           style="background-color: #d1b6e1;"></i>Fashion</span>
                            <span class="category-count">10</span>
                        </a>
                    </li>
                    <li class="category-item">
                        <a href="category/food/index.html" title="View all posts in Food">
                            <span class="category-name"><i class="dot"
                                                           style="background-color: #e7c291;"></i>Food</span>
                            <span class="category-count">5</span>
                        </a>
                    </li>
                    <li class="category-item">
                        <a href="category_interior.html" title="View all posts in Interior">
                        <span class="category-name"><i class="dot"
                                                       style="background-color: #7cbef1;"></i>Interior</span>
                            <span class="category-count">10</span>
                        </a>
                    </li>
                </ul>
            </div>
            <div id="magsy_promo_widget-2" class="widget widget_magsy_promo_widget">
                <div class="promo lazyload"
                     data-bg="2018/09/tim-bennett-538189-unsplash.jpg">
                    <a class="u-permalink" href="https://themeforest.net/user/mondotheme/portfolio"></a>
                    <h6 class="promo-title">Follow on Facebook</h6>
                </div>
            </div>
            <div id="magsy_promo_widget-3" class="widget widget_magsy_promo_widget">
                <div class="promo lazyload"
                     data-bg="2018/09/ben-kolde-430913-unsplash.jpg">
                    <a class="u-permalink" href="https://themeforest.net/user/mondotheme/portfolio"></a>
                    <h6 class="promo-title">Follow on Instagram</h6>
                </div>
            </div>
            <div id="magsy_picks_widget-3" class="widget widget_magsy_picks_widget">
                <h5 class="widget-title">Picked Posts</h5>
                <div class="picks-wrapper">
                    <div class="icon" data-icon="&#xf238" style="border-top-color: #EC7357; color: #FFF;"></div>
                    <div class="picked-posts owl">
                        <article class="post">
                            <div class="entry-thumbnail">
                                <img class="lazyload"
                                     data-src="2018/07/andres-jasso-220776-unsplash-150x150.jpg">
                            </div>

                            <header class="entry-header">
                                <h6 class="entry-title">Black Eames Style Chair</h6>
                            </header>
                            <a class="u-permalink" href="index.html%3Fp=96.html"></a>
                        </article>
                        <article class="post">
                            <div class="entry-thumbnail">
                                <img class="lazyload"
                                     data-src="2018/07/marion-michele-330691-unsplash-150x150.jpg">
                            </div>

                            <header class="entry-header">
                                <h6 class="entry-title">Café Buho Branding</h6></header>
                            <a class="u-permalink" href="index.html%3Fp=90.html"></a>
                        </article>
                        <article class="post">
                            <div class="entry-thumbnail">
                                <img class="lazyload"
                                     data-src="2018/07/alex-378877-unsplash-150x150.jpg">
                            </div>

                            <header class="entry-header">
                                <h6 class="entry-title">A Piece of Art in Utrecht, Netherlands</h6>
                            </header>
                            <a class="u-permalink" href="justified_gallery_post.html"></a>
                        </article>
                    </div>
                </div>
            </div>
            <div id="magsy_facebook_widget-3" class="widget widget_magsy_facebook_widget">
                <h5 class="widget-title">Follow Us on Facebook</h5>
                <div id="fb-root"></div>
                <script>(function (d, s, id) {
                        var js, fjs = d.getElementsByTagName(s)[0];
                        if (d.getElementById(id)) return;
                        js = d.createElement(s);
                        js.id = id;
                        js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.4";
                        fjs.parentNode.insertBefore(js, fjs);
                    }(document, 'script', 'facebook-jssdk'));</script>
                <div class="fb-page" data-href="https://www.facebook.com/UltraLinx/" data-width="340" data-height="500"
                     data-hide-cover="0" data-show-facepile="1" data-show-posts="0"></div>
            </div>
            <div id="null-instagram-feed-4" class="widget null-instagram-feed">
                <h5 class="widget-title">Our Latest Shots</h5>
                <ul class="instagram-pics instagram-size-thumbnail">
                    <li class="">
                        <a href="https://instagram.com/p/Bu7I8wKl-XV/" target="_blank" class="">
                            <img src="2018/ig/52980601_2242734735986092_4312319860660460905_n.jpg"
                                 alt="| Reminiscing." title="| Reminiscing." class=""/></a></li>
                    <li class="">
                        <a href="https://instagram.com/p/BuxMj4llv2n/" target="_blank" class="">
                            <img src="2018/ig/53109515_405884846641278_7118663302228178745_n.jpg"
                                 alt="| We have one mini cinematic light box from @additionstudios in grey left! Now -50% Lucky last 🥰"
                                 title="| We have one mini cinematic light box from @additionstudios in grey left! Now -50% Lucky last 🥰"
                                 class=""/></a></li>
                    <li class="">
                        <a href="https://instagram.com/p/BukFkAalHop/" target="_blank" class="">
                            <img src="2018/ig/51750056_158103141851518_1826402198746777161_n.jpg"
                                 alt="| Final clearance is happening now. Tap the link in profile to shop."
                                 title="| Final clearance is happening now. Tap the link in profile to shop." class=""/></a>
                    </li>
                    <li class="">
                        <a href="https://instagram.com/p/BuKdsOKl3oW/" target="_blank" class="">
                            <img src="2018/ig/51616868_318851222314882_5709174565510525746_n.jpg"
                                 alt="| Black lava rocks lean on each other and their own reflection in this beautiful photographic print."
                                 title="| Black lava rocks lean on each other and their own reflection in this beautiful photographic print."
                                 class=""/></a></li>
                    <li class="">
                        <a href="https://instagram.com/p/BuAFpIqllgN/" target="_blank" class="">
                            <img src="2018/ig/51279182_402065780596457_6150181447095511220_n.jpg"
                                 alt="| Decor dreams." title="| Decor dreams." class=""/></a></li>
                    <li class="">
                        <a href="https://instagram.com/p/Bt91TDXlo-K/" target="_blank" class="">
                            <img src="2018/ig/51902630_437741943432491_762288282046794941_n.jpg"
                                 alt="Probably our biggest ever sale. The Minimalist is minimising, help us out. Tap the link in profile to shop."
                                 title="Probably our biggest ever sale. The Minimalist is minimising, help us out. Tap the link in profile to shop."
                                 class=""/></a></li>
                </ul>
            </div>
        </aside>
    </div>

    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>