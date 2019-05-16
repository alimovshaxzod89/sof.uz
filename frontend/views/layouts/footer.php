<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2017. ActiveMedia Solutions LLC
 * @author    Rustam Mamadaminov <rmamdaminov@gmail.com>
 */

use common\models\Category;
use common\models\Tag;
use frontend\components\View;
use yii\helpers\Html;

/**
 * Created by PhpStorm.
 * Date: 12/10/17
 * Time: 9:04 PM
 * @var $tag  Tag
 * @var $this View
 */
$thisCategory = isset($this->params['category']) ? $this->params['category'] : new Category();
$categories   = Category::findOne(['slug' => 'categories']);
$menu         = Category::getCategoryTree(['is_menu' => true], $categories instanceof Category ? $categories->id : false);
?>
<footer class="main-footer dark bold">
    <section class="lower-footer cf">
        <div class="wrap">
            <ul class="social-icons">

                <li><a href="https://fb.com/rasmiyminbar" class="social-link" target="_blank" title="Facebook"> <i
                                class="ui-facebook"></i>
                        <span class="label">Minbar Facebook</span> </a></li>
                <li><a href="https://twitter.com/rasmiyminbar" class="social-link" target="_blank" title="Twitter"> <i
                                class="ui-twitter"></i> <span
                                class="label">Minbar Twitter</span> </a></li>
                <li><a href="https://t.me/joinchat/AAAAAEoY0v7yPda5u-bzQA" class="social-link" target="_blank"
                       title="Telegram"> <i
                                class="ui-paper-plane"></i>
                        <span class="label">Minbar Telegram</span> </a></li>
                <li><a href="https://www.instagram.com/rasmiyminbar/" class="social-link" target="_blank"
                       title="Instagram">
                        <i class="ui-instagram"></i>
                        <span class="label">Minbar Instagram</span> </a></li>
            </ul>
            <div class="links">
                <div class="menu-footer-links-container">
                    <ul id="menu-footer-links" class="menu">
                        <?php foreach ($menu as $item): ?>
                            <li class="menu-item menu-item-type-custom menu-item-object-custom">
                                <a href="<?= $item->getViewUrl() ?>"><?= $item->name ?></a>
                            </li>
                        <?php endforeach; ?>

                        <li class="menu-item menu-item-type-custom menu-item-object-custom">
                            <a href="<?= linkTo(['/tahririyat']) ?>"><?= __('Tahririyat') ?></a>
                        </li>
                        <li class="menu-item menu-item-type-custom menu-item-object-custom">
                            <a class="age-restriction" href="#">
                                +18
                            </a>
                        </li>

                    </ul>
                </div>
            </div>
            <p class="copyright">
                <?= __('"Minbar.uz" ахборот-таҳлилий cайти 2019 йилнинг 20 февралида электрон ОАВ сифатида давлат рўйхатидан ўтказилган. {br}Гувоҳнома рақами: {b}1274{bc}. Муассис: "Minbar News Media" МЧЖ. {br}Таҳририят манзили: 100031, Тошкент шаҳри, Миробод кўчаси, 14-уй. {br}Масъул муҳаррир: Сардор Салим. {br}Электрон манзил: {email}', ['email' => Html::a('info@minbar.uz', 'mailto:info@minbar.uz')]) ?>
            </p>
            <p>
                <a target="_blank" class="mobile-apps" title="<?= __('Minbar Android App') ?>"
                   href="https://play.google.com/store/apps/details?id=uz.minbar.app">
                    <img src="<?= $this->getImageUrl('google-android.png') ?>">
                </a>
                <a target="_blank" class="mobile-apps" title="<?= __('Minbar iOS App') ?>"
                   href="#">
                    <img src="<?= $this->getImageUrl('apple-ios.png') ?>">
                </a>
            </p>
            <div class="to-top">
                <a href="#" class="back-to-top"><i class="ui-angle-up"></i> <?= __('Yuqoriga') ?></a>
            </div>
        </div>
    </section>
</footer>