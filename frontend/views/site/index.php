<?php

use frontend\components\ScrollPager;
use frontend\components\View;
use frontend\models\PostProvider;
use yii\widgets\ListView;
use yii\widgets\Pjax;

/**
 * @var $this View
 * @var $mainPosts PostProvider[]
 * @var $photoPosts PostProvider[]
 */
$this->_canonical = Yii::$app->getHomeUrl();
$this->addBodyClass('home page-template page-template-page-modular page-template-page-modular-php page sidebar-none modular-title-1');
$limit      = 10;
$photoPosts = PostProvider::getTopPhotos(10);
$mainPosts  = PostProvider::getTopPost();
$mainPost   = false;
if (is_array($mainPosts) && count($mainPosts)) $mainPost = array_shift($mainPosts);

$js = <<<JS
    jQuery("#sticky-sidebar").theiaStickySidebar({
        additionalMarginTop: 90,
        additionalMarginBottom: 20
    });
JS;
$this->registerJs($js);
?>
<div class="site-content">
    <div class="content-area">
        <main class="site-main">
            <div class="advert-section">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <?= \frontend\widgets\Banner::widget([
                                                                     'place'   => 'before_main',
                                                                     'options' => ['class' => 'ads-wrapper']
                                                                 ]) ?>
                        </div>
                    </div>
                </div>
            </div>

            <?php if ($mainPost && count($mainPosts)): ?>
                <div id="magsy_module_post_big_list-2" class="section widget_magsy_module_post_big_list">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-9 col-md-6">
                                <div class="module big list u-module-margin">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <?php if ($mainPost instanceof PostProvider): ?>
                                                <article
                                                        class="post post-large type-post status-publish format-video has-post-thumbnail hentry category-food post_format-post-format-video">
                                                    <div class="entry-media">
                                                        <div class="placeholder">
                                                            <a href="<?= $mainPost->getViewUrl() ?>">
                                                                <img src="<?= $mainPost->getCroppedImage(416, 292, 1) ?>"
                                                                     alt="<?= $mainPost->title ?>">
                                                            </a>
                                                        </div>
                                                    </div>

                                                    <header class="entry-header">
                                                        <h2 class="entry-title">
                                                            <a href="<?= $mainPost->getViewUrl() ?>"
                                                               rel="bookmark"><?= $mainPost->title ?></a>
                                                        </h2>
                                                        <div class="entry-meta">
                                                            <span class="meta-category">
                                                                <time datetime="<?= $mainPost->getPublishedTimeIso() ?>">
                                                                    <?= $mainPost->getShortFormattedDate() ?>
                                                                </time>
                                                            </span>
                                                            <span class="meta-date">
                                                                <i class="mdi mdi-eye"></i> <?= $mainPost->views ?>
                                                            </span>
                                                            <?php if (is_array($mainPost->categories) && count($mainPost->categories)): ?>
                                                                <span class="meta-category">
                                                                    <?= $mainPost->metaCategoriesList() ?>
                                                                </span>
                                                            <?php endif; ?>
                                                        </div>
                                                    </header>
                                                    <div class="entry-excerpt u-text-format">
                                                        <?= $mainPost->info ?>
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
                                                            <div class="entry-meta">
                                                                <span class="meta-category">
                                                                    <time datetime="<?= $post->getPublishedTimeIso() ?>">
                                                                        <?= $post->getShortFormattedDate() ?>
                                                                    </time>
                                                                </span>
                                                                <span class="meta-date">
                                                                    <i class="mdi mdi-eye"></i> <?= $post->views ?>
                                                                </span>
                                                                <?php if (is_array($post->categories) && count($post->categories)): ?>
                                                                    <span class="meta-category">
                                                                        <?= $post->metaCategoriesList() ?>
                                                                    </span>
                                                                <?php endif; ?>
                                                            </div>
                                                        </header>
                                                        <div class="entry-excerpt u-text-format">
                                                            <?= $post->getInfoView() ?>
                                                        </div>
                                                    </div>
                                                </article>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <aside class="widget-area ">
                                    <?= $this->renderFile('@frontend/views/layouts/partials/top_posts.php', [
                                        'title' => __('Most read'),
                                        'posts' => PostProvider::getPopularPosts(5)
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
                            <?= \frontend\widgets\Banner::widget([
                                                                     'place'   => 'before_content',
                                                                     'options' => ['class' => 'ads-wrapper']
                                                                 ]) ?>

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

                        <div class="sidebar-column col-lg-3" id="sticky-sidebar">
                            <?= $this->renderFile('@frontend/views/layouts/partials/sidebar.php', [
                                'model' => null
                            ]) ?>
                        </div>
                    </div>
                </div>
            </div>

            <?php if (is_array($photoPosts) && count($photoPosts)): ?>
                <div class="section widget_magsy_module_post_carousel">
                    <div class="container">
                        <h5 class="u-border-title"><span><?= __('Photo news') ?></span></h5>
                        <div class="module carousel owl">
                            <?php foreach ($photoPosts as $post): ?>
                                <article
                                        class="post type-post status-publish format-standard has-post-thumbnail hentry category-design">
                                    <div class="entry-media">
                                        <div class="placeholder">
                                            <a href="<?= $post->getViewUrl() ?>">
                                                <img src="<?= $post->getCroppedImage(210, 140) ?>"
                                                     alt="<?= $post->title ?>">
                                            </a>
                                        </div>
                                    </div>
                                    <header class="entry-header">
                                        <div class="entry-meta">
                                            <span class="meta-category">
                                                <time datetime="<?= $post->getPublishedTimeIso() ?>">
                                                    <?= $post->getShortFormattedDate() ?>
                                                </time>
                                            </span>
                                            <span class="meta-date">
                                                <i class="mdi mdi-eye"></i> <?= $post->views ?>
                                            </span>
                                            <?php if (is_array($post->categories) && count($post->categories)): ?>
                                                <span class="meta-category">
                                                    <?= $post->metaCategoriesList() ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                        <h2 class="entry-title">
                                            <a href="<?= $post->getViewUrl() ?>" rel="bookmark">
                                                <?= $post->title ?>
                                            </a>
                                        </h2>
                                    </header>
                                </article>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </main>
    </div>
</div>