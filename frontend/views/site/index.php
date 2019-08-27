<?php

use frontend\components\ScrollPager;
use frontend\components\View;
use frontend\models\PostProvider;
use yii\helpers\Url;
use yii\widgets\ListView;
use yii\widgets\Pjax;

/**
 * @var $this View
 * @var $mainPosts PostProvider[]
 * @var $photoPosts PostProvider[]
 */
$this->_canonical = Url::to(['/'], true);
$this->addBodyClass('home page-template page-template-page-modular page-template-page-modular-php page sidebar-none modular-title-1');
$limit      = 10;
$photoPosts = PostProvider::getTopPhotos();
$mainPosts  = PostProvider::getTopPosts();
$mainPost   = false;
if (is_array($mainPosts) && count($mainPosts)) $mainPost = array_shift($mainPosts);
?>
<div class="site-content">
    <div class="content-area">
        <main class="site-main">
            <?php if ($mainPost && count($mainPosts)): ?>
                <div id="magsy_module_post_big_list-2" class="section widget_magsy_module_post_big_list">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-9">
                                <div class="module big list u-module-margin">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <?php if ($mainPost instanceof PostProvider): ?>
                                                <article
                                                        class="post post-large type-post status-publish format-video has-post-thumbnail hentry category-food post_format-post-format-video">
                                                    <div class="entry-media">
                                                        <div class="placeholder" style="padding-bottom: 70.25%;">
                                                            <a href="<?= $mainPost->getViewUrl() ?>">
                                                                <img src="<?= $mainPost->getCroppedImage(416, 292) ?>"
                                                                     alt="<?= $mainPost->title ?>">
                                                            </a>
                                                        </div>
                                                    </div>

                                                    <header class="entry-header">
                                                        <h2 class="entry-title">
                                                            <a href="<?= $mainPost->getViewUrl() ?>"
                                                               rel="bookmark"><?= $mainPost->title ?></a>
                                                        </h2>
                                                    </header>
                                                    <div class="entry-excerpt u-text-format">
                                                        <?= $mainPost->info ?>
                                                    </div>
                                                    <div class="entry-footer">
                                                        <time datetime="<?= $mainPost->getPublishedTimeIso() ?>">
                                                            <?= $mainPost->getShortFormattedDate() ?>
                                                        </time>
                                                    </div>
                                                </article>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-lg-6">
                                            <?php foreach ($mainPosts as $post): ?>
                                                <article
                                                        class="post post-list type-post status-publish format-standard has-post-thumbnail hentry category-food">
                                                    <div class="entry-wrapper">
                                                        <header class="entry-header">
                                                            <h2 class="entry-title">
                                                                <a href="<?= $post->getViewUrl() ?>"
                                                                   rel="bookmark"><?= $post->title ?></a>
                                                            </h2>
                                                        </header>
                                                        <div class="entry-excerpt u-text-format">
                                                            <?= $post->info ?>
                                                        </div>
                                                    </div>
                                                </article>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <aside class="widget-area">
                                    <?= $this->renderFile('@frontend/views/layouts/partials/top_posts.php', [
                                        'title' => __('Most read'),
                                        'posts' => PostProvider::getTopPosts()
                                    ]) ?>
                                </aside>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <div id="magsy_module_post_grid-6" class="section widget_magsy_module_post_grid">
                <div class="container">
                    <div class="row">
                        <div class="content-column col-lg-9">
                            <div class="content-area">
                                <main class="site-main">
                                    <h5 class="u-border-title"><?= __('Latest News') ?></h5>
                                    <?php Pjax::begin(['timeout' => 10000, 'enablePushState' => false]) ?>
                                    <?= ListView::widget([
                                                             'dataProvider' => PostProvider::getLastPosts($limit, true),
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
                                <?= $this->renderFile('@frontend/views/layouts/partials/author_posts.php', [
                                    'title' => __('Most read'),
                                    'posts' => PostProvider::getTopPosts()
                                ]) ?>
                            </aside>
                        </div>
                    </div>
                </div>
            </div>

            <?php if (is_array($photoPosts) && count($photoPosts)): ?>
                <div id="magsy_module_post_grid-6" class="section widget_magsy_module_post_grid">
                    <div class="container">
                        <h5 class="u-border-title"><?= __('Photo news') ?></h5>
                        <div class="module grid u-module-margin">
                            <div class="row">
                                <?php foreach ($photoPosts as $post): ?>
                                    <div class="col-sm-6 col-md-4 col-lg-3">
                                        <article
                                                class="post type-post status-publish format-standard has-post-thumbnail hentry category-design">
                                            <div class="entry-media">
                                                <div class="placeholder" style="padding-bottom: 66.75%;">
                                                    <a href="<?= $post->getViewUrl() ?>">
                                                        <img src="<?= $post->getCroppedImage(267, 178) ?>"
                                                             alt="<?= $post->title ?>">
                                                    </a>
                                                </div>
                                            </div>

                                            <header class="entry-header">
                                                <h2 class="entry-title">
                                                    <a href="<?= $post->getViewUrl() ?>"
                                                       rel="bookmark"><?= $post->title ?></a>
                                                </h2>
                                            </header>
                                            <div class="entry-excerpt u-text-format">
                                                <?= $post->info ?>
                                            </div>
                                            <div class="entry-footer">
                                                <time datetime="<?= $post->getPublishedTimeIso() ?>">
                                                    <?= $post->getShortFormattedDate() ?>
                                                </time>
                                            </div>
                                        </article>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </main>
    </div>
</div>