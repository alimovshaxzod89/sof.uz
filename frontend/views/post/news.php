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
$this->addBodyClass('post-template-default single single-post single-format-gallery navbar-sticky sidebar-none pagination-infinite_button');
?>
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <article
                    class="post type-post status-publish format-gallery has-post-thumbnail hentry category-design post_format-post-format-gallery">
                <div class="container small">
                    <div class="small">
                        <header class="entry-header">
                            <h1 class="entry-title"><?= $model->title ?></h1>
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
                                <span class="meta-date">
                                            <?= $model->getShortFormattedDate() ?>
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
                    <div class="entry-wrapper">
                        <?php if ($model->checkImageFileExists()) : ?>
                            <div class="">
                                <div class="entry-media">
                                    <div class="placeholder">
                                        <img src="<?= $model->getCroppedImage(750, null, 1) ?>"
                                             alt="<?= $model->title ?>">
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        <div class="entry-content u-text-format u-clearfix">
                            <?= $model->content ?>
                        </div>

                        <?php if (is_array($model->gallery) && count($model->gallery)): ?>
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
                        <?php endif; ?>

                        <?php if (is_array($model->tags) && count($model->tags)): ?>
                            <div class="entry-tags">
                                <?php foreach ($model->tags as $tag): ?>
                                    <a href="<?= $tag->getViewUrl() ?>" rel="tag"><?= $tag->name ?></a>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>



                        <?= \frontend\widgets\Banner::widget([
                                                                 'place'   => 'after_content',
                                                                 'options' => ['class' => 'ads-wrapper']
                                                             ]) ?>

                        <?php if ($model->hasAuthor() && 0): ?>
                            <div class="author-box">
                                <div class="author-image">
                                    <img alt='<?= $model->author->full_name ?>'
                                         src='<?= $model->author->getCroppedImage(140, 140, 1) ?>'
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
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </article>
        </div>
    </div>
</div>