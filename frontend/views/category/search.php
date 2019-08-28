<?php

use common\components\Config;
use common\models\Post;
use common\models\Tag;
use frontend\components\ScrollPager;
use frontend\components\View;
use frontend\models\CategoryProvider;
use frontend\models\PostProvider;
use yii\data\ActiveDataProvider;
use yii\widgets\ListView;
use yii\widgets\Pjax;

/**
 * @var        $this         View
 * @var        $model        Tag
 * @var        $news         PostProvider[]
 * @var        $dataProvider ActiveDataProvider
 * @var string $search
 */
$this->title = __('"{query}" bo\'yicha qidiruv', ['query' => $search]);
$limit       = intval(Yii::$app->request->get('limit', 12));
$empty       = Post::getEmptyCroppedImage(205, 165);
?>
<div class="term-bar lazyload visible" data-bg="<?= $this->getImageUrl('images/002.jpg') ?>">
    <h1 class="term-title"><?= $this->title ?></h1>
</div>

<div class="site-content">
    <div class="container">
        <div class="row">
            <div class="content-column col-lg-9">
                <div class="content-area">
                    <main class="site-main">
                        <?php Pjax::begin(['timeout' => 10000, 'enablePushState' => false]) ?>
                        <?= ListView::widget([
                                                 'dataProvider' => PostProvider::getPostsByQuery($search, $limit),
                                                 'options'      => [
                                                     'tag' => false,
                                                 ],
                                                 'itemOptions'  => [
                                                     'tag'   => 'div',
                                                     'class' => 'col-md-12',
                                                 ],
                                                 'viewParams'   => [
                                                     'empty' => $empty,
                                                     'limit' => $limit,
                                                     'load'  => Yii::$app->request->get('load', $limit),
                                                 ],
                                                 'layout'       => "<div class=\"row posts-wrapper\">{items}</div><div class=\"infinite-scroll-action\">{pager}</div>",
                                                 'itemView'     => 'partials/_view',
                                                 'emptyText'    => __('Ushbu bo\'limda yangiliklar yo\'q'),
                                                 'pager'        => [
                                                     'perLoad' => $limit,
                                                     'class'   => ScrollPager::class,
                                                     'options' => ['class' => 'infinite-scroll-button button']
                                                 ],
                                             ]) ?>
                        <?php Pjax::end() ?>
                    </main>
                </div>
            </div>

            <div class="sidebar-column col-lg-3">
                <aside class="widget-area">
                    <?= 1 ? '' : $this->renderFile('@frontend/views/layouts/partials/popular_categories.php') ?>
                    <?php
                    $cat = CategoryProvider::findOne(Config::get(Config::CONFIG_SIDEBAR_CATEGORY));
                    if ($cat instanceof CategoryProvider): ?>
                        <?= $this->renderFile('@frontend/views/layouts/partials/slider_post.php', [
                            'title' => $cat->name,
                            'posts' => PostProvider::getPostsByCategory($cat, 5, false)
                        ]) ?>
                    <?php endif; ?>

                    <?= $this->renderFile('@frontend/views/layouts/partials/socials.php') ?>
                    <?= $this->renderFile('@frontend/views/layouts/partials/top_posts.php', [
                        'title' => __('Most read'),
                        'posts' => \frontend\models\PostProvider::getTopPosts()
                    ]) ?>
                </aside>
            </div>
        </div>
    </div>
</div>