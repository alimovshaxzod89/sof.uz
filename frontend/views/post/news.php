<?php

use common\models\Comment;
use frontend\components\View;
use frontend\models\PostProvider;

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
$empty = $this->getImageUrl('img-placeholder.png');
if (mb_strpos($model->content, 'twitter') !== false) {
    $this->registerJsFile('https://platform.twitter.com/widgets.js', ['async' => true, 'charset' => 'utf-8']);
}

$similarPosts = $model->getSimilarPosts(4);
?>
<div class="hero lazyload visible" data-bg="<?= $model->getFileUrl('image') ?>">
    <div class="container small">
        <header class="entry-header white">
            <div class="entry-meta">
                <?php if ($model->hasAuthor()): ?>
                    <span class="meta-author">
                    <a href="<?= $model->author->getViewUrl() ?>">
                        <img alt='<?= $model->author->full_name ?>'
                             src='<?= $model->author->getImageUrl(40, 40) ?>'
                             class='avatar avatar-40 photo' height='40' width='40'/><?= $model->author->full_name ?>
                    </a>
                </span>
                <?php endif; ?>
                <?php if ($model->hasCategory()): ?>
                    <span class="meta-category">
                        <a href="<?= $model->category->getViewUrl() ?>" rel="category">
                            <i class="dot" style="background-color: #ff7473;"></i>
                            <?= $model->category->name ?>
                        </a>
                    </span>
                <?php endif; ?>
                <span class="meta-date">
                    <span>
                        <time datetime="<?= $model->getPublishedTimeIso() ?>">
                            <?= $model->getShortFormattedDate() ?>
                        </time>
                    </span>
                </span>
            </div>

            <h1 class="entry-title"><?= $model->title ?></h1>
        </header>
    </div>
</div>
<div class="site-content">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="content-area">
                    <main class="site-main">
                        <article
                                class="post type-post status-publish format-standard has-post-thumbnail hentry category-design">

                            <div class="container small">

                                <div class="entry-wrapper">
                                    <div class="entry-content u-text-format u-clearfix">
                                        <?= $model->content ?>
                                    </div>

                                    <?php if (1): ?>
                                        <div class="entry-action">
                                            <div class="action-count">
                                                <span class="view">
                                                    <span class="icon">
                                                        <i class="mdi mdi-eye"></i>
                                                    </span>
                                                    <span class="count">
                                                        <?= __('{view} {sp}views{spc}', [
                                                            'view' => $model->views
                                                        ]) ?>
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
                                    <?php endif; ?>

                                    <?php if ($model->hasAuthor()): ?>
                                        <div class="author-box">
                                            <div class="author-image">
                                                <img alt='<?= $model->author->getFullName() ?>'
                                                     src='<?= $model->getCroppedImage(140, 140, 1) ?>'
                                                     class='avatar avatar-140 photo'
                                                     height='140' width='140'/>
                                            </div>

                                            <div class="author-info">
                                                <h4 class="author-name">
                                                    <a href="<?= $model->author->getViewUrl() ?>">
                                                        <?= $model->author->full_name ?>
                                                    </a>
                                                </h4>

                                                <div class="author-bio">
                                                    <?= $model->author->description ?>
                                                </div>

                                                <div class="author-meta">
                                                    <a class="website"
                                                       href="https://themeforest.net/user/mondotheme/portfolio"
                                                       target="_blank">
                                                        <i class="mdi mdi-web"></i>
                                                    </a>
                                                    <a class="facebook" href="https://www.facebook.com" target="_blank">
                                                        <i class="mdi mdi-facebook-box"></i>
                                                    </a>
                                                    <a class="twitter" href="https://www.twitter.com" target="_blank">
                                                        <i class="mdi mdi-twitter-box"></i>
                                                    </a>
                                                    <a class="instagram" href="https://www.instagram.com"
                                                       target="_blank">
                                                        <i class="mdi mdi-instagram"></i>
                                                    </a>
                                                    <a class="google" href="https://plus.google.com" target="_blank">
                                                        <i class="mdi mdi-google-plus-box"></i>
                                                    </a>
                                                    <a class="linkedin" href="https://www.linkedin.com" target="_blank">
                                                        <i class="mdi mdi-linkedin-box"></i>
                                                    </a>
                                                    <a class="more" href="author/nancy/index.html">More</a>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </article>
                    </main>
                </div>
            </div>
        </div>
    </div>

    <?php if (count($similarPosts)): ?>
        <div class="bottom-area">
            <div class="container medium">
                <div class="related-posts">
                    <h3 class="u-border-title"><?= __('You might also like') ?></h3>
                    <div class="row">
                        <?php foreach ($similarPosts as $similarPost) : ?>
                            <div class="col-lg-6">
                                <article class="post">
                                    <div class="entry-media">
                                        <div class="placeholder" style="padding-bottom: 66.666666666667%;">
                                            <a href="<?= $similarPost->getViewUrl() ?>">
                                                <img alt="<?= $similarPost->title ?>"
                                                     src="<?= $similarPost->getCroppedImage(220, 146) ?>">
                                            </a>
                                        </div>
                                    </div>
                                    <div class="entry-wrapper">

                                        <header class="entry-header">
                                            <h4 class="entry-title">
                                                <a href="<?= $similarPost->getViewUrl() ?>" rel="bookmark">
                                                    <?= $similarPost->title ?>
                                                </a>
                                            </h4>
                                        </header>
                                        <div class="entry-excerpt u-text-format">
                                            <?= $similarPost->getInfoView() ?>
                                        </div>
                                    </div>
                                </article>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>