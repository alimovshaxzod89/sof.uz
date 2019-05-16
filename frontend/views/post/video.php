<?php

use frontend\components\View;
use frontend\models\PostProvider;
use frontend\widgets\SidebarPopular;
use frontend\widgets\SidebarImportant;
use frontend\widgets\SidebarPost;
use frontend\widgets\SocialSharer;
use yii\helpers\StringHelper;

/**
 * @var $this  View
 * @var $model PostProvider
 */

$this->_canonical              = $model->getViewUrl($model->category);
$category                      = isset($this->params['category']) ? $this->params['category'] : $model->category;
$this->title                   = $model->title;
$this->params['category']      = $category;
$this->params['post']          = $model;
$this->params['breadcrumbs'][] = ['url' => $category->getViewUrl(), 'label' => $category->name];
$this->params['breadcrumbs'][] = ['label' => $model->getShortTitle()];
$this->addDescription([StringHelper::truncateWords($model->info, 15)]);
?>
<div class="main-container container" id="main-container">

    <!-- Content -->
    <div class="row">

        <!-- post content -->
        <div class="col-lg-8 blog__content mb-72">
            <div class="content-box">

                <!-- standard post -->
                <article class="entry mb-0">

                    <div class="single-post__entry-header entry__header">
                        <?php if ($category): ?>
                            <a href="<?= $category->getViewUrl() ?>"
                               class="entry__meta-category entry__meta-category--label entry__meta-category--green">
                                <?= $category->name ?>
                            </a>
                        <?php endif; ?>
                        <h1 class="single-post__entry-title">
                            <?= $model->title ?>
                        </h1>

                        <div class="entry__meta-holder">
                            <ul class="entry__meta">
                                <?php if ($author): ?>
                                    <li class="entry__meta-author">
                                        <span>by</span>
                                        <a href="#"><?= $author->getFullname() ?></a>
                                    </li>
                                <?php endif; ?>
                                <li class="entry__meta-date">
                                    <?= ($model->published_on) ? Yii::$app->formatter->asDatetime($model->published_on->getTimestamp(), "php:d.m.Y H:i") : "" ?>
                                </li>
                            </ul>

                            <ul class="entry__meta">
                                <li class="entry__meta-views">
                                    <i class="ui-eye"></i>
                                    <span><?= $model->views ?></span>
                                </li>
                                <li class="entry__meta-comments">
                                    <a href="#">
                                        <i class="ui-chat-empty"></i><?= $model->comment_count ?>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div> <!-- end entry header -->

                    <div class="entry__img-holder">
                        <iframe id="video_player" class="embed-responsive-item"
                                src="<?= $model->getYoutubeEmbedUrl() ?>" width="100%" height="420"
                                frameborder="0" allowfullscreen></iframe>
                    </div>

                    <div class="entry__article-wrap">

                        <!-- Share -->
                        <div class="entry__share" style="position: relative;">
                            <div class="sticky-col" style="">
                                    <?= SocialSharer::widget([
                                                                 'configuratorId' => 'socialShare',
                                                                 'wrapperTag'     => 'div',
                                                                 'linkWrapperTag' => false,
                                                                 'wrapperOptions' => ['class' => 'socials socials--rounded socials--large'],
                                                                 'url'            => $model->getShortViewUrl(),
                                                                 'title'          => $model->title,
                                                                 'description'    => StringHelper::truncateWords($model->info, 15),
                                                                 'imageUrl'       => $model->getImage(736, 736),
                                                             ]); ?>

                            </div>
                        </div> <!-- share -->

                        <div class="entry__article">
                            <?= $model->content ?>
                            <!-- tags -->
                            <?php if (\count($model->tags)): ?>
                                <div class="entry__tags">
                                    <i class="ui-tags"></i>
                                    <span class="entry__tags-label"><?= __('Tags') ?>:</span>
                                    <?php foreach ($model->tags as $tag): ?>
                                        <a href="<?= $tag->getViewUrl() ?>" rel="tag"><?= $tag->name ?></a>
                                    <?php endforeach; ?>
                                </div><!-- Tags end -->
                            <?php endif; ?>
                        </div> <!-- end entry article -->
                    </div> <!-- end entry article wrap -->
                    <div class="row text-center">
                        <a href="https://t.me/" class="btn btn-lg btn-color py-4 w-100">
                            <?= __('Yangiliklarni telegram kanalimizda kuzatib boring') ?>
                        </a>
                    </div>
                    <?= \frontend\widgets\Banner::widget(['place' => 'content_bottom']) ?>
                    <!-- Related Posts -->
                    <section class="section related-posts mt-40 mb-0">
                        <?php if ($posts = $model->getSimilarPosts(5)): ?>
                            <div class="title-wrap title-wrap--line title-wrap--pr">
                                <h3 class="section-title"><?= __('Related Posts') ?></h3>
                            </div>

                            <!-- Slider -->
                            <div id="owl-posts-3-items" class="owl-carousel owl-theme owl-carousel--arrows-outside">
                                <?php foreach ($posts as $post): ?>
                                    <article class="entry thumb thumb--size-1">
                                        <div class="entry__img-holder thumb__img-holder"
                                             style="background-image: url(<?= $model->getImage(230, 200) ?>);">
                                            <div class="bottom-gradient"></div>
                                            <div class="thumb-text-holder">
                                                <h2 class="thumb-entry-title">
                                                    <a href="<?= $model->getViewUrl() ?>"><?= $post->title ?></a>
                                                </h2>
                                            </div>
                                            <a href="<?= $model->getViewUrl() ?>" class="thumb-url"></a>
                                        </div>
                                    </article>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </section> <!-- end related posts -->
                </article> <!-- end standard post -->


            </div> <!-- end content box -->
        </div> <!-- end post content -->

        <!-- Sidebar -->
        <aside class="col-lg-4 sidebar sidebar--right">

            <!-- Widget Popular Posts -->
            <aside class="widget widget-popular-posts">
                <h4 class="widget-title"><?= __('Ommabop') ?></h4>
                <ul class="post-list-small">
                    <?php foreach (PostProvider::getPopularPosts(6) as $item): ?>
                        <?php $img = $item->getImage(90, 90) ?>
                        <li class="post-list-small__item">
                            <article class="post-list-small__entry clearfix">

                                <div class="post-list-small__body">
                                    <h3 class="post-list-small__entry-title">
                                        <a href="<?= $item->getViewUrl() ?>">
                                            <?= $item->title ?>
                                        </a>
                                    </h3>
                                    <ul class="entry__meta">

                                        <li class="entry__meta-date">
                                            <?= $item->getShortFormattedDate() ?>
                                        </li>
                                        <li class="entry__meta-author">
                                            <span class="ui-eye"></span> <?= $item->views_l7d ?: rand(200, 100) ?>
                                        </li>
                                    </ul>
                                </div>
                            </article>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </aside> <!-- end widget popular posts -->

            <!-- Widget Popular Posts -->
            <aside class="widget widget-popular-posts">
                <h4 class="widget-title"><?= __('Latest') ?></h4>
                <ul class="post-list-small">
                    <?php foreach (PostProvider::getPopularPosts(6) as $item): ?>
                        <?php $img = $item->getImage(90, 90) ?>
                        <li class="post-list-small__item">
                            <article class="post-list-small__entry clearfix">
                                <div class="post-list-small__img-holder">
                                    <div class="thumb-container thumb-100">
                                        <a href="<?= $item->getViewUrl() ?>">
                                            <img data-src="<?= $img ?>" src="<?= $img ?>" alt=""
                                                 class="post-list-small__img--rounded lazyloaded">
                                        </a>
                                    </div>
                                </div>
                                <div class="post-list-small__body">
                                    <h3 class="post-list-small__entry-title">
                                        <a href="<?= $item->getViewUrl() ?>">
                                            <?= $item->title ?>
                                        </a>
                                    </h3>
                                    <ul class="entry__meta">

                                        <li class="entry__meta-date">
                                            <?= $item->getShortFormattedDate() ?>
                                        </li>
                                        <li class="entry__meta-author">
                                            <span class="ui-eye"></span> <?= $item->views_l7d ?: rand(200, 100) ?>
                                        </li>
                                    </ul>
                                </div>
                            </article>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </aside> <!-- end widget popular posts -->

        </aside> <!-- end sidebar -->

    </div> <!-- end content -->
</div>