<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2017. ActiveMedia Solutions LLC
 * @author    Rustam Mamadaminov <rmamdaminov@gmail.com>
 */

use common\components\Config;
use common\models\Category;
use frontend\components\View;

/**
 * Created by PhpStorm.
 * Date: 12/10/17
 * Time: 9:02 PM
 * @var $category Category
 * @var $item     Category
 * @var $this     View
 */
$thisType     = isset($this->params['post_type']) ? $this->params['post_type'] : '';
$thisCategory = isset($this->params['category']) ? $this->params['category'] : new Category();
$isGuest      = Yii::$app->user->getIsGuest();
$categories   = Category::findOne(['slug' => 'categories']);
$menu         = Category::getCategoryTree(['is_menu' => true], $categories instanceof Category ? $categories->id : false);
?>
<!-- Navigation -->
<div id="header" class="headroom header--fixed">
    <?php if (rand(1, 2) == 1): ?>
        <div class="header-messages" data-cookie="_mtg" data-count="2" style="display: none">
            <?= __('Xabarlar tezkor {b}Telegram{bc} kanalimizda', ['b' => "<strong>", 'bc' => "</strong>"]) ?>

            <a href="<?= getenv('TG_CHANNEL') ?>" class="action-btn" target="_blank"><?= __('Subscribe') ?></a>

            <span class="close-btn">×</span>
        </div>
    <?php else: ?>
        <div class="header-messages" data-cookie="_man" data-count="2" style="display: none">
            <?= __('Android qurilmalar uchun {b}mobil{bc} ilovamiz', ['b' => "<strong>", 'bc' => "</strong>"]) ?>

            <a href="https://play.google.com/store/apps/details?id=uz.minbar.app" class="action-btn"
               target="_blank"><?= __('Download') ?></a>

            <span class="close-btn">×</span>
        </div>
    <?php endif; ?>
    <header id="main-head"
            class="main-head head-nav-below has-search-modal simple simple-boxed"
            style="min-height: 106px;">

        <div class="inner inner-head" data-sticky-bar="">
            <div class="wrap cf wrap-head">
                <div class="left-contain">
                    <span class="mobile-nav"><i class="ui-menu"></i></span>
                    <div class="title">
                        <a href="<?= linkTo(['/']) ?>" title="Test" rel="home">
                            <?php $id = Yii::$app->request->get('lg', 0) ?>
                            <img src="<?= $this->getImageUrl("logo-v1.svg") ?>"
                                 class="logo-image" alt="Minbar.uz">
                        </a>
                    </div>
                </div>

                <div class="navigation-wrap inline">
                    <nav class="navigation inline simple light">
                        <div class="menu-main-menu-container">
                            <ul id="menu-main-menu" class="menu">
                                <?php foreach ($menu as $item): ?>
                                    <li class="menu-item menu-item-type-custom menu-item-object-custom menu-<?= $item->slug ?>
                                <?= $item->hasChild() ? ' menu-item-has-children ' : '' ?>
                                <?= isset($this->params['category']) && $this->params['category']->id == $item->id ? 'active' : '' ?>
">
                                        <a href="<?= $item->getViewUrl() ?>"><span><?= $item->name ?></span></a>
                                        <?php if ($item->hasChild()): ?>
                                            <ul class="sub-menu">
                                                <?php foreach ($item->child as $item): ?>
                                                    <li class="menu-item menu-item-type-custom menu-item-object-custom <?= isset($this->params['category']) && $this->params['category']->id == $item->id ? 'active' : '' ?>">
                                                        <a class="item-active"
                                                           href="<?= $item->getViewUrl() ?>"><span><?= $item->name ?></span></a>
                                                    </li>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php endif ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </nav>
                </div>

                <div class="actions">
                    <ul class="social-icons cf languages">
                        <?php if (Yii::$app->language == Config::LANGUAGE_CYRILLIC): ?>
                            <li>
                                <a href="<?= \yii\helpers\Url::current(['language' => 'uz-UZ']) ?>"><?= __('O‘zbek') ?></a>
                            </li>
                        <?php else: ?>
                            <li>
                                <a href="<?= \yii\helpers\Url::current(['language' => 'cy-UZ']) ?>"><?= __('Ўзбек') ?></a>
                            </li>
                        <?php endif; ?>

                    </ul>


                    <a href="#" title="Search" class="search-link">
                        <i class="ui-search"></i>
                    </a>
                </div>
            </div>
        </div>
    </header>

</div>

