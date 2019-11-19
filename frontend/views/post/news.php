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
                                                         src='<?= $model->author->getCroppedImage(40, 40, 1) ?>'
                                                         class='avatar avatar-40 photo' height='40'
                                                         width='40'/><?= $model->author->full_name ?></a>
                                            </span>
                                <?php endif; ?>
                                <span class="meta-date">
                                    <?= $model->getShortFormattedDate() ?>
                                </span>
                                <span class="view">
                                    <i class="mdi mdi-eye"></i>
                                    <span class="count">
                                         <?= $model->getViewLabel() ?>
                                    </span>
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
                                <div class="entry-media u-clearfix">
                                    <div class="placeholder">
                                        <img src="<?= $model->getCroppedImage(826, null) ?>"
                                             alt="<?= $model->title ?>">
                                    </div>
                                    <?php if ($c = $model->image_caption): ?>
                                        <p class="meta-author"><?= $c ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        <div class="entry-content u-text-format u-clearfix">
                            <?= $model->content; ?>
                            <?php if ($model->isColumnists()): ?>
                                <br>
                                <p>
                                    <i>
                                        <?= __('{b}Эслатма:{bc} Муаллиф фикри таҳририят нуқтаи назарини ифодаламаслиги мумкин') ?>
                                    </i>
                                </p>
                            <?php endif; ?>
                        </div>

                        <?= $this->renderFile('@frontend/views/post/_footer.php', ['model' => $model]) ?>

                    </div>
                </div>
            </article>
        </div>
    </div>
</div>