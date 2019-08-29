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
$this->addBodyClass('post-template-default single single-post single-format-gallery navbar-sticky sidebar-none pagination-infinite_button');
?>
<div class="site-content">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="content-area">
                    <main class="site-main">
                        <article
                                class="post type-post status-publish format-gallery has-post-thumbnail hentry category-design post_format-post-format-gallery">
                            <div class="container small">
                                <header class="entry-header">
                                    <div class="entry-meta">
                                        <?php if ($model->hasAuthor()): ?>
                                            <span class="meta-author">
                                                <a href="<?= $model->author->getViewUrl() ?>">
                                                    <img alt="<?= $model->author->full_name ?>"
                                                         src="<?= $model->author->getCroppedImage(40, 40) ?>"
                                                         class='avatar avatar-40 photo' height='40'
                                                         width='40'/><?= $model->author->full_name ?></a>
                                            </span>
                                        <?php endif; ?>
                                        <?php if ($model->hasCategory()): ?>
                                            <span class="meta-category">
                                                <a href="<?= $model->category->getViewUrl() ?>" rel="category">
                                                    <i class="dot" style="background-color: #ff7473;"></i>
                                                    <?= $model->category->name ?></a>
                                            </span>
                                        <?php endif; ?>
                                        <span class="meta-date">
                                            <time datetime="<?= $model->getPublishedTimeIso() ?>">
                                                <?= $model->getShortFormattedDate() ?>
                                            </time>
                                        </span>
                                    </div>

                                    <h1 class="entry-title"><?= $model->title ?></h1></header>
                            </div>

                            <?php if (is_array($model->gallery) && count($model->gallery)): ?>
                                <div class="container medium">
                                    <div class="entry-media">
                                        <div class="entry-gallery justified-gallery">
                                            <?php foreach ($model->gallery as $item):
                                                if (!isset($item['path'])) continue;
                                                $imagePath  = Yii::getAlias('@static') . DS . 'uploads' . DS . $item['path'];
                                                $imageUrl   = Yii::getAlias('@staticUrl') . '/uploads/' . $item['path'];
                                                if (file_exists($imagePath)):
                                                    $size = getimagesize($imagePath);
                                                    $width  = isset($size[0]) ? $size[0] : 800;
                                                    $height = isset($size[1]) ? $size[1] : 533; ?>
                                                    <div class="gallery-item">
                                                        <a href="<?= $imageUrl ?>"
                                                           data-width="<?= $width ?>" data-height="<?= $height ?>">
                                                            <img src="<?= PostProvider::getCropImage($item, $width, $height) ?>"
                                                                 alt="">
                                                        </a>
                                                    </div>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <div class="container small">
                                <div class="entry-wrapper">
                                    <div class="entry-content u-text-format u-clearfix">
                                        <?= $model->content ?>
                                    </div>

                                    <?php if (0 && is_array($model->tags) && count($model->tags)): ?>
                                        <div class="entry-tags">
                                            <?php foreach ($model->tags as $tag): ?>
                                                <a href="<?= $tag->getViewUrl() ?>" rel="tag"><?= $tag->name ?></a>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>

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
                                                <img alt='<?= $model->author->full_name ?>'
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
        </div>
    </div>

    <?= $this->renderFile('@frontend/views/post/like.php', [
        'similarPosts' => $model->getSimilarPosts(4)
    ]) ?>
</div>