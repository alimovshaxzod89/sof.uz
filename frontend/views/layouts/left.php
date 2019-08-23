<?php

use common\models\MongoModel;
use frontend\components\View;
use frontend\models\AuthorProvider;
use frontend\models\PostProvider;

/**
 * @var $this    View
 * @var $content string
 */
$this->beginContent('@app/views/layouts/site.php');
?>
<div class="main-container container mt-32" id="main-container">
    <div class="row">
        <div class="col-lg-8 blog__content mb-40">
            <?= $content ?>
        </div>

        <aside class="col-lg-4 sidebar sidebar--right ssbar">
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
                                            <span class="ui-eye"></span> <?= $item->views_l3d ? $item->views_l3d : $item->views ?>
                                        </li>
                                    </ul>
                                </div>
                            </article>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <hr>
                <div class="widget-socials">
                    <?= \common\models\Page::getStaticBlock('social') ?>
                </div>
            </aside>
            <aside class="widget widget_media_image">
                <?= \frontend\widgets\Banner::widget(['place' => 'sidebar_middle']) ?>
            </aside>
            <?php if (count(AuthorProvider::getList())): ?>
                <aside class="widget widget-popular-posts">
                    <h4 class="widget-title"><?= __('Authors') ?></h4>
                    <ul class="post-list-small">
                        <?php foreach (AuthorProvider::getList() as $item): ?>
                            <?php $img = MongoModel::getCropImage($item->image, 90, 90) ?>
                            <li class="post-list-small__item">
                                <article class="post-list-small__entry clearfix">
                                    <div class="post-list-small__img-holder">
                                        <div class="thumb-container thumb-100">
                                            <a href="#">
                                                <img data-src="<?= $img ?>" src="<?= $img ?>" alt=""
                                                     class="post-list-small__img--rounded lazyloaded">
                                            </a>
                                        </div>
                                    </div>
                                    <div class="post-list-small__body">
                                        <h3 class="post-list-small__entry-title">
                                            <a href="#">
                                                <?= $item->fullname ?>
                                            </a>
                                        </h3>
                                        <ul class="entry__meta">
                                            <li class="entry__meta-date">
                                                <?= $item->job ?>
                                            </li>
                                        </ul>
                                    </div>
                                </article>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </aside> <!-- end widget popular posts -->
            <?php endif; ?>
        </aside>
    </div>
</div>
<?php $this->endContent() ?>
