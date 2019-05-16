<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2017. ActiveMedia Solutions LLC
 * @author    Rustam Mamadaminov <rmamdaminov@gmail.com>
 */

use common\models\Category;
use frontend\components\View;

/**
 * @var $this    View
 * @var $content string
 */
$globalVars = ['min' => minVersion(), 'l' => Yii::$app->language, 'a' => Yii::getAlias('@apiUrl'), 'd' => YII_DEBUG, 'typo' => linkTo(['site/typo']), 'm' => ['typo' => __('Iltimos xatoni belgilang!')], 'u' => !Yii::$app->user->getIsGuest() ? $this->getUser()->getId() : $this->context->getUserId()];
if (isset($this->params['post'])) $globalVars['p'] = $this->params['post']->getId();
if (isset($this->params['category'])) $globalVars['c'] = $this->params['category']->getId();
$this->registerJs('var globalVars=' . json_encode($globalVars) . ';', View::POS_HEAD);
$this->beginContent('@app/views/layouts/main.php');
?>

<div class="main-wrap">
    <?= $this->renderFile('@app/views/layouts/header.php') ?>
    <div class="main wrap">
        <?= \frontend\widgets\Banner::widget(['place' => 'header_top']) ?>
        <?= $content ?>
    </div>
    <?= $this->renderFile('@app/views/layouts/footer.php') ?>
</div>

<?php if (!minVersion()): ?>
    <div class="mobile-menu-container off-canvas" id="mobile-menu">
        <a href="#" class="close"><i class="ui-close"></i></a>
        <div class="logo"></div>
        <ul class="mobile-menu"></ul>
    </div>
<?php else: ?>
    <?php
    $categories = Category::findOne(['slug' => 'categories']);
    $menu       = Category::getCategoryTree(['is_menu' => true], $categories instanceof Category ? $categories->id : false);

    ?>
    <div class="search-modal-wrap" id="search-modal-wrap-menu">
        <ul class="mobile-menu text-center">
            <?php foreach ($menu as $item): ?>
                <li class="menu-item menu-item-type-custom menu-item-object-custom menu-<?= $item->slug ?>                                                                ">
                    <a class="np-right" href="<?= $item->getViewUrl() ?>"><span><?= $item->name ?></span></a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

<?php endif; ?>

<div class="search-modal-wrap" id="search-modal-wrap">
    <div class="search-modal-box" role="dialog" aria-modal="true">
        <form method="get" class="search-form" action="<?= linkTo(['/search']) ?>">
            <input type="search"
                   class="search-field"
                   name="q"
                   placeholder="<?= __('Qidiruv...') ?>"
                   value=""
                   required>
            <button type="submit" class="search-submit visuallyhidden">Submit</button>
            <p class="message">
                <?= __('Type above and press {em}Enter{/em} to search. Press {em}Esc{/em} to cancel.') ?>
            </p>
        </form>
    </div>
</div>
<?php $this->endContent() ?>
