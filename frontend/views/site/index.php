<?php

use common\models\Category;
use common\models\Post;
use frontend\components\View;
use frontend\models\PostProvider;
use yii\widgets\Pjax;

/**
 * @var $this       View
 * @var $basicPosts PostProvider[]
 * @var $limit int
 */
$this->_canonical = linkTo(['/'], true);
$mainList         = PostProvider::getTopPost(1);

$mainTop = array_shift($mainList);
$exclude = [$mainTop->_id];

$lastNewsCategory = Category::findOne(['slug' => 'yangiliklar']);
$lastNews         = PostProvider::getPostsByCategory($lastNewsCategory, 12, $exclude);


$choyxona      = Category::findOne(['slug' => 'choyxonabop']);
$choyxonaPosts = PostProvider::getPostsByCategory($choyxona, 3)->getModels();

foreach ($choyxonaPosts as $post) {
    $exclude[] = $post->_id;
}
$empty = $this->getImageUrl('img-placeholder.png');
$limit = 8;
$this->addBodyClass('home single');
?>

<div class="home-widgets">
    <section id="contentberg-block-blog-1" class="widget ts-block-widget contentberg-widget-blog">
        <div class="ts-row cf">

            <aside class="col-4 sidebar sidebar-left large-title" data-sticky=1>
                <div class="inner theiaStickySidebar">
                    <section class="cf block blog d-940 mb-45">
                        <div class="block-content">
                            <div class="posts-container posts-large cf">
                                <div class="posts-wrap">
                                    <article class="post-main large-post post type-post ">
                                        <header class="post-header cf">
                                            <div class="featured">
                                                <a href="<?= $url = $mainTop->getViewUrl() ?>"
                                                   class="image-link">
                                                    <img src="<?= $mainTop->getImage(770, 420) ?>"
                                                         class="attachment-contentberg-main size-contentberg-main"
                                                         title="<?= $mainTop->title ?>">
                                                </a>
                                            </div>
                                            <div class="post-meta post-meta-b">
                                                <h2 class="post-title-alt">
                                                    <?php if ($mainTop->is_bbc): ?>
                                                        <span class="bbc-tag">BBC</span>
                                                    <?php endif; ?>
                                                    <a href="<?= $url ?>">
                                                        <?= $mainTop->title ?>
                                                    </a>
                                                </h2>

                                                <div class="below">
                                                    <time class="post-date">
                                                        <?= $mainTop->getShortFormattedDate() ?>
                                                    </time>
                                                    <span class="meta-sep"></span>
                                                    <span class="meta-item read-time"><?= $mainTop->getReadMinLabel() ?></span>
                                                    <span class="meta-sep"></span>

                                                    <span class="post-cat">
                                                        <a href=" <?= $mainTop->category->getViewUrl() ?>"
                                                           class="category">
                                                            <?= $mainTop->category->name ?>
                                                        </a>
                                                    </span>
                                                </div>

                                            </div>
                                        </header>
                                        <div class="post-content description cf post-excerpt">
                                            <p>
                                                <?= $mainTop->info ?>
                                            </p>
                                        </div>
                                    </article>
                                </div>
                            </div>
                        </div>
                    </section>

                    <ul>
                        <li class="widget widget-posts posts-important">
                            <h5 class="widget-title">
                                <span><?= __('So\'nggi xabarlar') ?></span>
                                <a href="<?= $lastNewsCategory->getViewUrl() ?>" class="view-all">
                                    <?= __('Barchasi') ?>
                                    <span class="ui-right-open"></span>
                                </a>
                            </h5>
                            <ul class="posts cf large full">
                                <?php foreach ($lastNews->getModels() as $main): $exclude[] = $main->_id; ?>
                                    <li class="post cf">

                                        <div class="content">
                                            <?php if ($main->is_bbc): ?>
                                                <span class="bbc-tag">BBC</span>
                                            <?php endif; ?>
                                            <a class="post-title" href="<?= $main->getViewUrl() ?>">
                                                <?= $main->title ?>
                                            </a>

                                            <div class="post-meta post-meta-a">
                                                <a href="<?= $main->getViewUrl() ?>"
                                                   class="meta-item date-link">
                                                    <time class="post-date">
                                                        <?= $main->getShortFormattedDate() ?>
                                                    </time>
                                                </a>
                                                <span class="meta-sep"></span>
                                                <span class="meta-item read-time">
                                                    <?= $main->getReadMinLabel() ?>
                                                </span>
                                            </div>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </li>
                        <?php if ($middle = \frontend\widgets\Banner::widget(['place' => 'sidebar_middle'])): ?>
                            <li class="widget widget-a-wrap no-940">
                                <?= $middle ?>
                            </li>
                        <?php endif ?>
                        <?php
                        $posts = PostProvider::getPopularPosts(6);
                        $empty = Post::getEmptyCroppedImage(124, 100);
                        ?>
                        <?php if (count($posts)): ?>
                            <li class="widget widget-posts no-940">
                                <h5 class="widget-title">
                                    <span><?= __('Ommabop') ?></span>
                                    <a href="<?= linkTo(['/ommabop']) ?>" class="view-all">
                                        <?= __('Barchasi') ?>
                                        <span class="ui-right-open"></span>
                                    </a>
                                </h5>
                                <ul class="posts cf large">
                                    <?php foreach ($posts as $item): ?>
                                        <?php $img = $item->getImage(124, 100) ?>
                                        <li class="post cf">
                                            <a href="<?= $url = $item->getViewUrl() ?>" class="image-link">
                                                <img data-src="<?= $img ?>"
                                                     src="<?= $empty ?>"
                                                     class="size-contentberg-thumb-alt lazyload"
                                                     title="<?= $item->title ?>">
                                            </a>
                                            <div class="content">
                                                <a href="<?= $url ?>" class="post-title" title="<?= $item->title ?>">
                                                    <?= $item->title ?>
                                                </a>
                                                <div class="post-meta post-meta-a">
                                                    <time class="post-date">
                                                        <?= $item->getShortFormattedDate() ?>
                                                    </time>
                                                </div>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </li>
                        <?php endif; ?>

                    </ul>
                </div>
            </aside>

            <div class="col-8">
                <div class="blocks">
                    <section class="cf block blog no-940">
                        <div class="block-content">
                            <div class="posts-container posts-large cf">
                                <div class="posts-wrap">
                                    <article class="post-main large-post post type-post ">
                                        <header class="post-header cf">
                                            <div class="featured">
                                                <a href="<?= $url = $mainTop->getViewUrl() ?>"
                                                   class="image-link">
                                                    <img src="<?= $mainTop->getImage(770, 380) ?>"
                                                         class="attachment-contentberg-main size-contentberg-main"
                                                         title="<?= $mainTop->title ?>">
                                                </a>
                                            </div>
                                            <div class="post-meta post-meta-b">
                                                <h2 class="post-title-alt">

                                                    <a href="<?= $url ?>">
                                                        <?= $mainTop->title ?>
                                                    </a>
                                                </h2>

                                                <div class="below">
                                                    <time class="post-date">
                                                        <?= $mainTop->getShortFormattedDate() ?>
                                                    </time>
                                                    <span class="meta-sep"></span>
                                                    <span class="meta-item read-time"><?= $mainTop->getReadMinLabel() ?></span>

                                                    <?php if ($mainTop->is_bbc): ?>
                                                        <span class="meta-sep"></span>
                                                        <span class="bbc-tag">BBC</span>
                                                    <?php endif; ?>
                                                    <span class="meta-sep"></span>
                                                    <span class="post-cat">
                                                        <a href=" <?= $mainTop->category->getViewUrl() ?>"
                                                           class="category">
                                                            <?= $mainTop->category->name ?>
                                                        </a>
                                                    </span>
                                                </div>
                                            </div>
                                        </header>
                                        <div class="post-content description cf post-excerpt">
                                            <p>
                                                <?= $mainTop->info ?>
                                            </p>
                                        </div>
                                    </article>
                                </div>
                            </div>
                        </div>
                    </section>
                    <?= \frontend\widgets\Banner::widget(['place' => 'page_middle']) ?>

                    <?php
                    $limit = 10;
                    ?>
                    <section class="cf block loop-grid " data-id="2">
                        <h5 class="widget-title">
                            <span><?= __('Minbarda') ?></span>
                            <a href="<?= linkTo(['/minbarda']) ?>" class="view-all" data-pjax="0">
                                <?= __('Barchasi') ?>
                                <span class="ui-right-open"></span>
                            </a>
                        </h5>
                        <div class="block-content">
                            <?php Pjax::begin(['timeout' => 10000]) ?>
                            <?= yii\widgets\ListView::widget([
                                                                 'dataProvider' => PostProvider::getMinbarPosts(intval(\Yii::$app->request->get('ml', $limit)), $exclude),
                                                                 'options'      => [
                                                                     'tag' => false,
                                                                 ],
                                                                 'itemOptions'  => [
                                                                     'tag' => false,
                                                                 ],
                                                                 'viewParams'   => [
                                                                     'empty' => Post::getEmptyCroppedImage(263, 190),
                                                                 ],
                                                                 'layout'       => $this->render('partials/_layout'),
                                                                 'itemView'     => 'partials/_item',
                                                                 'emptyText'    => '',
                                                                 'pager'        => [
                                                                     'perLoad'   => $limit,
                                                                     'loadParam' => 'ml',
                                                                     'class'     => frontend\components\ScrollPager::class,
                                                                 ],
                                                             ]) ?>
                            <?php Pjax::end() ?>
                        </div>
                    </section>
                </div>
            </div>

        </div>
    </section>

    <?php
    $empty = Post::getEmptyCroppedImage(370, 230);
    ?>
    <div class="the-post-modern">
        <section class="related-posts grid-3 no-margins">
            <h4 class="section-head">
                <span class="title"><?= $choyxona->name ?></span>
            </h4>
            <div class="ts-row posts cf">
                <?php foreach ($choyxonaPosts as $i => $post): ?>
                    <article class="post col-4 ?>" data-pos="<?= $i ?>"
                             data-id="<?= $post->id ?>">
                        <?php if ($i < 3): ?>
                            <a href="<?= $post->getViewUrl() ?>"
                               title="<?= $post->title ?>" class="image-link">
                                <img class="image lazyload"
                                     data-src="<?= $post->getImage(370, 230) ?>"
                                     src="<?= $empty ?>"
                                     title="<?= $post->title ?>">
                            </a>
                        <?php endif; ?>
                        <div class="content">
                            <h3 class="post-title">
                                <a href="<?= $post->getViewUrl() ?>"
                                   class="post-link">
                                    <?= $post->title ?>
                                </a>
                            </h3>
                            <div class="post-meta">
                                <time class="post-date">
                                    <?= $post->getShortFormattedDate() ?>
                                </time>
                                <span class="meta-sep"></span>
                                <span class="meta-item read-time">
                                                <?= $post->getReadMinLabel() ?>
                                            </span>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>
    </div>
</div>


