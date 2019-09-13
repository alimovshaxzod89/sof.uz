<?php

use common\models\Comment;
use frontend\components\View;
use frontend\models\PostProvider;
use frontend\widgets\Banner;

/**
 * @var $this    View
 * @var $model   PostProvider
 * @var $post    PostProvider
 * @var $comment Comment
 */
$category         = isset($this->params['category']) ? $this->params['category'] : $model->category;
$this->_canonical = $model->hasAuthor() ? $model->getViewUrl() : $model->getViewUrl();
if (count($model->tags)) {
    $tags = [];
    foreach ($model->tags as $tag) {
        $tags[] = $tag->name;
    }
    $this->addKeywords($tags);
}

$this->title              = $model->title;
$this->params['category'] = $category;
$this->params['post']     = $model;

$this->addDescription([$model->info]);
$this->addBodyClass('post-template-default single single-post single-format-standard navbar-sticky sidebar-right pagination-infinite_button');
$js = <<<JS
    jQuery("#sticky-sidebar").theiaStickySidebar({
        additionalMarginTop: 90,
        additionalMarginBottom: 20
    });
JS;
$this->registerJs($js);
?>
<div class="site-content">
    <div class="container">
        <?= Banner::widget([
                               'place'   => 'before_main',
                               'options' => ['class' => 'ads-wrapper']
                           ]) ?>
    </div>

    <div class="container">
        <div class="row">
            <div class="content-column col-lg-9">
                <div class="content-area">
                    <main class="site-main">
                        <article
                                class="post type-post status-publish format-standard has-post-thumbnail hentry category-fashion">
                            <div class="container small">
                                <header class="entry-header">
                                    <h1 class="entry-title"><?= $model->title ?></h1>
                                    <div class="entry-meta">
                                        <?php if ($model->hasAuthor()): ?>
                                            <span class="meta-author">
                                                <a href="<?= $model->author->getViewUrl() ?>">
                                                    <img alt="<?= $model->author->full_name ?>"
                                                         src='<?= $model->author->getCroppedImage(40, 40) ?>'
                                                         class='avatar avatar-40 photo' height='40'
                                                         width='40'/><?= $model->author->full_name ?></a>
                                            </span>
                                        <?php endif; ?>
                                        <span class="meta-date">
                                            <time datetime="<?= $model->getPublishedTimeIso() ?>">
                                                <?= $model->getShortFormattedDate() ?></time>
                                        </span>
                                        <?php if ($model->hasCategory()): ?>
                                            <span class="meta-category">
                                                <a href="<?= $model->category->getViewUrl() ?>" rel="category">
                                                    <?= $model->category->name ?></a>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </header>
                            </div>

                            <div class="">
                                <div class="entry-media">
                                    <div class="placeholder">
                                        <a href="<?= $model->getViewUrl() ?>">
                                            <img src="<?= $model->getFileUrl('image') ?>"
                                                 alt="<?= $model->title ?>">
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="container small">
                                <div class="entry-wrapper">
                                    <div class="entry-content u-text-format u-clearfix">
                                        <?= $model->content ?>
                                    </div>

                                    <?php if (is_array($model->tags) && count($model->tags)): ?>
                                        <div class="entry-tags">
                                            <?php foreach ($model->tags as $tag): ?>
                                                <a href="<?= $tag->getViewUrl() ?>" rel="tag"><?= $tag->name ?></a>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>

                                    <div class="entry-action">
                                        <div class="action-count">
                                            <span class="view">
                                                <i class="mdi mdi-eye"></i>
                                                <span class="count">
                                                    <?= $model->views ?>
                                                </span>
                                            </span>
                                        </div>
                                        <?php
                                        $urlEnCode   = \yii\helpers\StringHelper::base64UrlEncode($model->getShortViewUrl());
                                        $titleEnCode = \yii\helpers\StringHelper::base64UrlEncode($model->title);
                                        ?>
                                        <div class="action-share">
                                            <a class="facebook" target="_blank"
                                               href="<?= 'https://www.facebook.com/sharer.php?u=' . $urlEnCode ?>">
                                                <i class="mdi mdi-facebook"></i>
                                            </a>
                                            <a class="twitter" target="_blank"
                                               href="https://twitter.com/intent/tweet?url=<?= $urlEnCode ?>&text=<?= $titleEnCode ?>">
                                                <i class="mdi mdi-twitter"></i>
                                            </a>
                                            <a class="vk" target="_blank"
                                               href="http://vk.com/share.php?url=<?= $urlEnCode ?>&title=<?= $titleEnCode ?>">
                                                <i class="mdi mdi-vk"></i>
                                            </a>
                                            <a class="telegram" target="_blank"
                                               href="https://t.me/share/url?url=<?= $urlEnCode ?>&text=<?= $titleEnCode ?>">
                                                <i class="mdi mdi-telegram"></i>
                                            </a>
                                        </div>
                                    </div>

                                    <?= Banner::widget([
                                                           'place'   => 'after_content',
                                                           'options' => ['class' => 'ads-wrapper']
                                                       ]) ?>

                                    <?php if ($model->hasAuthor()): ?>
                                        <div class="author-box">
                                            <div class="author-image">
                                                <img alt="<?= $model->author->full_name ?>" height="140" width="140"
                                                     src="<?= $model->author->getCroppedImage(140, 140) ?>"
                                                     class='avatar avatar-140 photo'/>
                                            </div>

                                            <div class="author-info">
                                                <h4 class="author-name">
                                                    <a href="<?= $model->author->getViewUrl() ?>"><?= $model->author->full_name ?></a>
                                                </h4>

                                                <div class="author-bio">
                                                    <?= $model->author->description ?>
                                                </div>

                                                <?php if (0): ?>
                                                    <div class="author-meta">
                                                        <a class="website" href="#" target="_blank">
                                                            <i class="mdi mdi-web"></i></a>
                                                        <a class="facebook" href="#" target="_blank">
                                                            <i class="mdi mdi-facebook-box"></i></a>
                                                        <a class="twitter" href="#" target="_blank">
                                                            <i class="mdi mdi-twitter-box"></i></a>
                                                        <a class="instagram" href="#" target="_blank">
                                                            <i class="mdi mdi-instagram"></i></a>
                                                        <a class="google" href="#" target="_blank">
                                                            <i class="mdi mdi-google-plus-box"></i></a>
                                                        <a class="linkedin" href="#" target="_blank">
                                                            <i class="mdi mdi-linkedin-box"></i></a>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </article>
                    </main>
                </div>
            </div>
            <div class="sidebar-column col-lg-3" id="sticky-sidebar">
                <?= $this->renderFile('@frontend/views/layouts/partials/sidebar.php', [
                    'model' => $model
                ]) ?>
            </div>
        </div>
    </div>

    <?= $this->renderFile('@frontend/views/post/like.php', [
        'similarPosts' => $model->getSimilarPosts(4)
    ]) ?>
</div>