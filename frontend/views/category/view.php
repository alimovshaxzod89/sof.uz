<?php

use common\components\Config;
use frontend\components\ScrollPager;
use frontend\components\View;
use frontend\models\CategoryProvider;
use frontend\models\PostProvider;
use frontend\models\TagProvider;
use yii\widgets\ListView;
use yii\widgets\Pjax;

/**
 * @var $this View
 * @var $provider \yii\data\ActiveDataProvider
 * @var $model CategoryProvider|TagProvider
 */
$limit                         = 12;
$this->title                   = $model->name;
$this->params['breadcrumbs'][] = $this->title;
$this->_canonical              = $model->getViewUrl();
?>
<div class="site-content">
    <div class="container">
        <div class="row">
            <div class="content-column col-lg-9">
                <div class="content-area">
                    <main class="site-main">
                        <h5 class="u-border-title"><?= $this->title ?></h5>
                        <?php Pjax::begin(['timeout' => 10000, 'enablePushState' => false]) ?>
                        <?= ListView::widget([
                                                 'dataProvider' => $provider,
                                                 'options'      => [
                                                     'tag' => false,
                                                 ],
                                                 'itemOptions'  => [
                                                     'tag'   => 'div',
                                                     'class' => 'col-md-12',
                                                 ],
                                                 'viewParams'   => [
                                                     'empty' => PostProvider::getEmptyCroppedImage(370, 220),
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
                        'posts' => PostProvider::getTopPosts()
                    ]) ?>
                </aside>
            </div>
        </div>
    </div>
</div>