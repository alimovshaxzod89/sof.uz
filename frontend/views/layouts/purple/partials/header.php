<?php

use common\components\Config;
use frontend\models\CategoryProvider;
use yii\helpers\Url;

/**
 * @var $this frontend\components\View
 */

?>

<!-- Navbar is here -->
<header>
    <nav class="nav">

        <a href="<?= Yii::$app->getHomeUrl() ?>" class="logo"></a>

        <div class="hamburger">
            <span class="line"></span>
            <span class="line"></span>
            <span class="line"></span>
        </div>

        <?php
        $links = CategoryProvider::getCategoryTree(['is_menu' => ['$eq' => true]], Config::get(Config::CONFIG_MENU_CATEGORY));
        $moreLinks = [];
        if (count($links) > 4) {
            $moreLinks = array_splice($links, 4, count($links) - 4);
        }
        ?>
        <?php if (is_array($links) && count($links)): ?>
            <div class="nav__link hide">
                <?php foreach ($links as $link): ?>
                    <?php if ($link->hasChild()): ?>

                        <!--                        <a href="--><? //= $link->getViewUrl() ?><!--" class="nav_link"-->
                        <!--                           style="color: white;">--><? //= $link->name ?><!--</a> <span class="break">|</span>-->

                        <div class="dropdown">
                            <button class="dropbtn"><?= $link->name ?></button>
                            <div class="dropdown-content">
                                <?php foreach ($link->child as $item): ?>
                                    <a href="<?= $item->getViewUrl() ?>"><?= $item->name ?></a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <a href="<?= $link->getViewUrl() ?>" class="nav_link"
                           style="color: white;"><?= $link->name ?></a> <span class="break">|</span>
                    <?php endif; ?>
                <?php endforeach; ?>

                <?php if (!empty($moreLinks)): ?>
                    <div class="dropdown">
                        <button class="dropbtn">КЎПРОҚ</button>
                        <div class="dropdown-content">
                            <?php foreach ($moreLinks as $item): ?>
                                <a href="<?= $item->getViewUrl() ?>"><?= $item->name ?></a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>


        <div class="nav_second">

            <form method="get" action="<?= Url::to(['/search']) ?>">
                <div class="search-box">
                    <button type="button" class="btn-search"><i class="fa fa-search"></i></button>
                    <input type="text" class="input-search" placeholder="<?= __('Enter keyword...') ?>" name="q"
                           autocomplete="off">
                </div>
            </form>
            <div></div>
            <div>
                <a href="#" class="mode">
                    <label class="switch">
                        <input onclick="darkMode()" type="checkbox">
                        <span class="slider round"></span>
                    </label>
                </a>
            </div>
            <div>
                <div>
                    <?php
                    $langs = [
                        Config::LANGUAGE_CYRILLIC => [
                            'text' => __('Ўз'),
                            'link' => Url::current(['language' => 'oz']),
                        ],
                        Config::LANGUAGE_UZBEK => [
                            'text' => __('O‘z'),
                            'link' => Url::current(['language' => 'uz']),
                        ]
                    ];

                    //joriy tilni birinchiga chiqarish
                    $lang = $langs[Yii::$app->language];
                    unset($langs[Yii::$app->language]);
                    $langs = [$lang] + $langs;
                    ?>
                    <select class="select1" onchange="la(this.value)">

                        <?php foreach ($langs as $value => $lang): ?>
                            <option value="<?= $lang['link'] ?>"
                            <?= Yii::$app->language == $value ? 'selected' : '' ?>"
                            ><?= $lang['text'] ?></option>
                        <?php endforeach; ?>

                    </select>
                </div>
            </div>

        </div>

    </nav>
</header>

<script>

</script>