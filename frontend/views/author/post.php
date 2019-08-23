<?php

use common\models\Blogger;
use frontend\components\View;
use frontend\models\PostProvider;
use frontend\widgets\SidebarPopular;
use frontend\widgets\SidebarPost;
use frontend\widgets\SidebarShare;
use frontend\widgets\SocialSharer;
use yii\helpers\StringHelper;
use yii\widgets\Breadcrumbs;

/**
 * Created by PhpStorm.
 * Date: 12/13/17
 * Time: 12:48 AM
 */

/**
 * @var $this   View
 * @var $model  PostProvider
 * @var $author Blogger
 */
$category                 = isset($this->params['category']) ? $this->params['category'] : $model->category;
$this->params['category'] = $category;
$this->params['post']     = $model;
$this->title              = $model->title;
$this->addDescription([StringHelper::truncateWords($model->info, 15)]);

$this->_canonical              = $model->getAuthorPostUrl();
$this->params['breadcrumbs'][] = ['url' => ['/mualliflar'], 'label' => __('Mualliflar')];
$this->params['breadcrumbs'][] = ['url' => $author->getViewUrl(), 'label' => $author->getFullname()];
$this->params['breadcrumbs'][] = ['label' => $model->getShortTitle()];

?>
<div class="main__content article <?= $model->is_instant ? 'instant_view' : '' ?>">
    <?= Breadcrumbs::widget([
                                'homeLink'           => ['url' => linkTo(['/']), 'label' => __('Asosiy')],
                                'options'            => ['class' => 'breadcrumbs'],
                                'activeItemTemplate' => "<li>{link}</li>\n",
                                'links'              => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                            ]) ?>

    <div class="article__category">
        <a href="<?= $category->getViewUrl() ?>"><?= $category->name ?></a>

        <div class="article__category-controls">
            <a href="#">-A</a>
            <a href="#">+A</a>
            <a href="#">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="17"
                     height="16" viewBox="0 0 17 16" fill="#3b3d40">
                    <defs>
                        <path id="hqi8a"
                              d="M858.88 185.07a1.37 1.37 0 1 1 0-2.74 1.37 1.37 0 0 1 0 2.74zm-2.5 3.16h1v-2.38c0-.38.3-.69.69-.69h10.2c.38 0 .69.31.69.7v2.37h1v-5.44c0-.37-.3-.68-.67-.68h-12.24c-.38 0-.68.3-.68.68zm-.7 1.37a.69.69 0 0 1-.68-.69v-6.12c0-1.13.92-2.05 2.05-2.05h.33v-3.05c0-.38.3-.69.69-.69h10.2c.38 0 .69.3.69.69v3.05h.33c1.13 0 2.05.92 2.05 2.05v6.12c0 .38-.3.69-.69.69h-1.7v2.71c0 .38-.3.69-.68.69h-10.2a.69.69 0 0 1-.69-.69v-2.71zm3.07 2.03h8.84v-5.1h-8.84zm0-13.26v2.37h8.84v-2.37z"></path>
                    </defs>
                    <g>
                        <g transform="translate(-855 -177)">
                            <use xlink:href="#hqi8a"></use>
                        </g>
                    </g>
                </svg>
            </a>
        </div><!-- End of article__category-controls-->
    </div><!-- End of article__category -->

    <div class="author__desc-personal">
        <div class="media-info">
            <div class="media shrink is_left">
                <a href="<?=$author->getViewUrl()?>">
                    <img src="<?= Blogger::getCropImage($author->image, 90, 90) ?>" width="90" height="90"
                         alt="<?= $author->getFullname() ?>">
                </a>
            </div><!-- End of media-->

            <div class="info auto">
                <p class="title"><strong class="post__author"><?= $author->getFullname() ?></strong></p>

                <p><?= $author->intro ?></p>
            </div><!-- End of info-->
        </div><!-- End of media-info-->
    </div>

    <div class="article__content">
        <h2 class="post__title"><?= $model->title ?></h2>

        <div class="article__content-meta">
            <span class="date-time">
                <i class="icon clock-icon is_smaller"></i>
                <?= Yii::$app->formatter->asDatetime($model->published_on->sec, "php:d.m.Y H:i") ?>
            </span>
            <span class="h-space"></span>
            <span class="update-time"></span>
            <span class="h-space"></span>

            <span class="counters">
                <i class="icon comments-icon"></i><?= $model->comment_count ?>
                <span class="h-space"></span>
                <i class="icon eye-icon"></i><?= $model->views ?>
                <span class="h-space"></span>
                <!--<i class="icon thumbs-up-icon"></i>230
                <span class="h-space"></span>
                <i class="icon thumbs-down-icon"></i>15-->
            </span><!-- End of counters-->
        </div><!-- End of article__content-meta-->
        <div class="media">
            <img src="<?= $model->getImage(720, 460) ?>" alt="<?= $model->title ?>">
            <?php if ($model->image_caption): ?>
                <p class="caption"><?= $model->image_caption ?></p>
            <?php endif; ?>
            <?php if ($model->image_source): ?>
                <p class="author"><?= $model->image_source ?></p>
            <?php endif; ?>
        </div>
        <div class="post__body">
            <?= $model->content ?>
        </div>

        <?php if ($model->has_gallery): ?>
            <div class="article__content-list">
                <?= $this->renderFile('@frontend/views/post/type/_gallery.php', ['model' => $model]) ?>
            </div>
        <?php endif; ?>
        <div class="telegram__link">
            <i class="icon telegram-icon"></i><a target="_blank" href="https://t.me/xabaruzofficial">
                <?= __('Yangiliklarni {sp}telegram{spc} kanalimizda kuzatib boring', ['sp' => '<span>', 'spc' => '</span>']) ?>
            </a>
        </div>
    </div>

    <div class="article__social__likes">
        <?= SocialSharer::widget([
                                     'configuratorId' => 'socialShare',
                                     'wrapperTag'     => 'div',
                                     'linkWrapperTag' => false,
                                     'wrapperOptions' => ['class' => 'sidebar__share-links'],
                                     'url'            => $model->getAuthorPostUrl(),
                                     'title'          => $model->title,
                                     'description'    => StringHelper::truncateWords($model->info, 15),
                                     'imageUrl'       => $model->getImage(736, 736),
                                 ]); ?>
        <div class="article__tags-list short_link">
            <input class="link_share" value="<?= $model->getShortViewUrl() ?>" readonly/>
        </div>
    </div>
    <?php if (count($model->tags)): ?>
        <div class="article__tags">

            <div class="article__tags-list">
                <?php foreach ($model->tags as $tag): ?>
                    <a href="<?= $tag->getViewUrl() ?>"><?= $tag->name ?></a>
                <?php endforeach; ?>
            </div><!-- End of article__tags-list-->
        </div><!-- End of article__tags-->
    <?php endif; ?>
</div><!-- End of main__content-->

<div class="main__sidebar">
    <?= SidebarPost::widget() ?>
    <div class="banner banner-300x334">
        <img src="<?= $this->getImageUrl('banners/ann-300x334-2.png') ?>" width="300" height="334" alt="banner 300x334">
    </div>
    <?= SidebarPopular::widget() ?>
</div><!-- End of main__sidebar-->
