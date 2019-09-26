<?php

use common\components\Config;
use frontend\models\CategoryProvider;
use frontend\models\PostProvider;
use frontend\widgets\Banner;

/**
 * @var $this \frontend\components\View
 * @var $model PostProvider
 */
$exclude     = $model != null ? [$model->_id] : [];
$category    = CategoryProvider::findOne(Config::get(Config::CONFIG_SIDEBAR_CATEGORY));
$authorPosts = PostProvider::getTopAuthors();
?>
<aside class="widget-area theiaStickySidebar">
    <?php if (Yii::$app->controller->action->id != 'index'): ?>
        <?= $this->renderFile('@frontend/views/layouts/partials/top_posts.php', [
            'title' => __('Most read'),
            'posts' => PostProvider::getPopularPosts()
        ]) ?>
    <?php endif; ?>

    <?php if (!isset($hideAuthors) || !$hideAuthors): ?>
        <?php if (is_array($authorPosts) && count($authorPosts) >= 3): ?>
            <?= $this->renderFile('@frontend/views/layouts/partials/author_posts.php', [
                'title' => __('Authors'),
                'posts' => $authorPosts
            ]) ?>
        <?php endif; ?>
    <?php endif; ?>

    <?= Banner::widget([
                           'place'   => 'before_sidebar',
                           'options' => ['class' => 'ads-wrapper']
                       ]) ?>

    <?php if ($category instanceof CategoryProvider): ?>
        <?= $this->renderFile('@frontend/views/layouts/partials/slider_post.php', [
            'title' => $category->name,
            'posts' => PostProvider::getPostsByCategory($category, 5, false, $exclude)
        ]) ?>
    <?php endif; ?>

    <?= $this->renderFile('@frontend/views/layouts/partials/socials.php') ?>
</aside>