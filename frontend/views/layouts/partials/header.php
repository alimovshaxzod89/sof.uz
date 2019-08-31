<?php

use common\components\Config;
use frontend\models\CategoryProvider;

/**
 * @var $this frontend\components\View
 */

?>
<header class="site-header">
    <div class="container">
        <div class="navbar">

            <div class="logo-wrapper">
                <a class="logo text" href="<?= Yii::$app->getHomeUrl() ?>">
                    <img src="<?= $this->getImageUrl('images/logo.svg') ?>" alt=""></a>
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
                           autocomplete="off" value="" name="q" required="required">
                    <button type="submit" class="search-submit">
                        <i class="mdi mdi-magnify"></i>
                    </button>
                </form>
                <div class="search-close navbar-button">
                    <i class="mdi mdi-close"></i>
                </div>
            </div>

            <div class="actions">
                <div style="margin-top: 2px;margin-right: 5px;">
                    <?php
                    $urlManager = Yii::$app->urlManager;
                    $uzUrl      = array_merge(
                        [Yii::$app->controller->route],
                        Yii::$app->request->get(),
                        ['language' => 'uz']
                    );
                    $ozUrl      = array_merge(
                        [Yii::$app->controller->route],
                        Yii::$app->request->get(),
                        ['language' => 'oz']
                    ); ?>
                    <?php if (Yii::$app->language === Config::LANGUAGE_CYRILLIC): ?>
                        <a href="<?= $urlManager->createAbsoluteUrl($uzUrl, true) ?>"><strong><?= __('O‘zbek') ?></strong></a>
                    <?php endif; ?>
                    <?php if (Yii::$app->language === Config::LANGUAGE_UZBEK): ?>
                        <a href="<?= $urlManager->createAbsoluteUrl($ozUrl, true) ?>"><strong><?= __('Ўзбек') ?></strong></a>
                    <?php endif; ?>
                </div>
                <div class="search-open navbar-button">
                    <i class="mdi mdi-magnify"></i>
                </div>
                <div class="burger"></div>
            </div>
        </div>
    </div>
</header>

<div class="header-gap"></div>