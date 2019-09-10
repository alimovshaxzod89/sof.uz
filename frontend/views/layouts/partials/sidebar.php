<?php

use common\components\Config;
use frontend\models\CategoryProvider;
use frontend\models\PostProvider;
use frontend\widgets\Banner;

/**
 * @var $this \frontend\components\View
 * @var $model PostProvider
 */
$exclude  = $model != null ? [$model->_id] : [];
$category = CategoryProvider::findOne(Config::get(Config::CONFIG_SIDEBAR_CATEGORY));
?>
<aside class="widget-area theiaStickySidebar">
    <?= Banner::widget([
                           'place'   => 'before_sidebar',
                           'options' => ['class' => 'ads-wrapper']
                       ]) ?>
    <?php if (Yii::$app->controller->id == 'site'): ?>
        <?= $this->renderFile('@frontend/views/layouts/partials/author_posts.php', [
            'title' => __('Authors'),
            'posts' => PostProvider::getTopAuthors()
        ]) ?>
    <?php else: ?>
        <?= $this->renderFile('@frontend/views/layouts/partials/top_posts.php', [
            'title' => __('Most read'),
            'posts' => PostProvider::getTopPosts(6, $exclude)
        ]) ?>
    <?php endif; ?>
    <?php if ($category instanceof CategoryProvider): ?>
        <?= $this->renderFile('@frontend/views/layouts/partials/slider_post.php', [
            'title' => $category->name,
            'posts' => PostProvider::getPostsByCategory($category, 5, false, $exclude)
        ]) ?>
    <?php endif; ?>
    <?= $this->renderFile('@frontend/views/layouts/partials/socials.php') ?>
    <?= YII_DEBUG ? $this->renderFile('@frontend/views/layouts/partials/popular_categories.php') : '' ?>
    <?= Banner::widget([
                           'place'   => 'after_sidebar',
                           'options' => ['class' => 'ads-wrapper']
                       ]) ?>
</aside>