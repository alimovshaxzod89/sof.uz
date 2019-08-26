<?php

use frontend\components\ScrollPager;
use frontend\components\View;
use frontend\models\PostProvider;
use yii\widgets\ListView;
use yii\widgets\Pjax;

/**
 * @var $this View
 * @var $model \frontend\models\CategoryProvider
 */
$this->_canonical              = $model->getViewUrl();
$this->title                   = $model->name;
$exclude                       = [];
$this->params['breadcrumbs'][] = $this->title;
$limit                         = 12;
$this->addBodyClass('category-' . $model->slug)
?>
<div class="site-content">
    <div class="container">
        <div class="row">
            <div class="content-column col-lg-9">
                <div class="content-area">
                    <main class="site-main">
                        <h5 class="u-border-title"><?= $model->name ?></h5>
                        <?php Pjax::begin(['timeout' => 10000, 'enablePushState' => false]) ?>
                        <?= ListView::widget([
                                                 'dataProvider' => $model->getProvider($limit),
                                                 'options'      => [
                                                     'tag' => false,
                                                 ],
                                                 'itemOptions'  => [
                                                     'tag' => false,
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
                    <?= $this->renderFile('@frontend/views/layouts/partials/most_read.php') ?>
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