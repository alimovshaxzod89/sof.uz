<?php

use common\components\Config;
use frontend\models\CategoryProvider;
use yii\helpers\Url;

/**
 * @var $this frontend\components\View
 */

?>
<header class="site-header">
    <div class="header-messages" data-cookie="_man" data-count="1" style="">
        <?= __('Sof xabarlar {b}Telegram{bc} kanalimizda') ?>
        <a href="https://t.me/sofuzb" class="action-btn" target="_blank"><?= __('Obuna bo‘ling') ?></a>
        <span class="close-btn">×</span>
    </div>
    <div class="container">
        <div class="navbar">

            <div class="logo-wrapper">
                <a class="logo text" href="<?= Yii::$app->getHomeUrl() ?>">
                    <img src="<?= $this->getImageUrl('images/logo2.svg') ?>?v=1" alt=""></a>
            </div>

            <div class="sep"></div>

            <?php $links = CategoryProvider::getCategoryTree(['is_menu' => ['$eq' => true]], Config::get(Config::CONFIG_MENU_CATEGORY)) ?>
            <?php if (is_array($links) && count($links)): ?>
                <nav class="main-menu hidden-xs hidden-sm hidden-md">
                    <ul id="menu-primary" class="nav-list u-plain-list">
                        <?php foreach ($links as $link): ?>
                            <?php if ($link->hasChild()): ?>
                                <li class="menu-item menu-item-type-custom menu-item-object-custom current-menu-item current_page_item menu-item-home current-menu-ancestor current-menu-parent menu-item-has-children">
                                    <a href="<?= $link->getViewUrl() ?>"><?= $link->name ?></a>
                                    <ul class="sub-menu">
                                        <?php foreach ($link->child as $item): ?>
                                        <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-home current-menu-item page_item current_page_item">
                                            <a href="<?= $item->getViewUrl() ?>"><?= $item->name ?></a>
                                            <?php endforeach; ?>
                                    </ul>
                                </li>
                            <?php else: ?>
                                <li class="menu-item menu-item-type-custom menu-item-object-custom">
                                    <a href="<?= $link->getViewUrl() ?>"><?= $link->name ?></a></li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                </nav>
            <?php endif; ?>

            <div class="main-search">
                <form method="get" class="search-form inline"
                      action="<?= \yii\helpers\Url::to(['/search']) ?>">
                    <input type="search" class="search-field inline-field"
                           placeholder="<?= __('Enter keyword...') ?>"
                           autocomplete="off" name="q"
                           value="<?= Yii::$app->controller->action->id == 'search' ? Yii::$app->request->get('q', '') : ''; ?>">
                    <button type="submit" class="search-submit">
                        <i class="mdi mdi-magnify"></i>
                    </button>
                </form>
                <div class="search-close navbar-button">
                    <i class="mdi mdi-close"></i>
                </div>
            </div>

            <div class="actions">
                <ul class="language-block">
                    <li class="<?= Yii::$app->language == Config::LANGUAGE_CYRILLIC ? 'active' : '' ?>">
                        <a href="<?= Url::current(['language' => 'oz']) ?>"><?= __('Ўз') ?></a>
                    </li>
                    <li class="<?= Yii::$app->language == Config::LANGUAGE_UZBEK ? 'active' : '' ?>">
                        <a href="<?= Url::current(['language' => 'uz']) ?>"><?= __('O‘z') ?></a>
                    </li>
                    <li class="search-open">
                        <i class="mdi mdi-magnify"></i>
                    </li>
                </ul>
                <div class="burger"></div>
            </div>
        </div>
    </div>
</header>

<div class="header-gap"></div>