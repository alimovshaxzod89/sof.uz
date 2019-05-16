<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2017. ActiveMedia Solutions LLC
 * @author    Rustam Mamadaminov <rmamdaminov@gmail.com>
 */

use common\models\Comment;
use common\models\PostBBC;
use frontend\components\View;
use frontend\models\PostProvider;
use frontend\widgets\SocialSharer;
use ymaker\social\share\drivers\Telegram;

/**
 * @var $this    View
 * @var $model   PostProvider
 * @var $post    PostProvider
 * @var $comment Comment
 */
$author   = $model->author;
$category = isset($this->params['category']) ? $this->params['category'] : $model->category;

if ($author) {
    $this->_canonical = $model->getAuthorPostUrl();
} else {
    $this->_canonical = $model->getViewUrl();
}

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

$this->addBodyClass('post-template-default single single-post single-format-standard right-sidebar  ');
$this->addBodyClass('post-' . $model->short_id);

$comments = false;

if (mb_strpos($model->content, 'twitter') !== false) {
    $this->registerJsFile('https://platform.twitter.com/widgets.js', ['async' => true, 'charset' => 'utf-8']);
}
?>
<div class="ts-row cf">
    <div class="col-8 main-content cf mb-45">
        <article class="the-post post">
            <header class="post-header the-post-header cf">
                <div class="post-meta post-meta-b the-post-meta <?= $model->canDisplayImage() ? '' : 'center-full' ?>">
                    <?php if ($model->is_bbc): ?>
                        <div class="bbc-wrap">
                            <div class="bbc-brand"></div>
                        </div>
                    <?php endif; ?>
                    <h1 class="post-title-alt">
                        <?= $model->title ?>
                    </h1>
                    <div class="below">
                        <time class="post-date"><?= $model->getShortFormattedDate() ?></time>
                        <span class="meta-sep"></span>
                        <span class="meta-item read-time"><?= $model->getReadMinLabel() ?></span>
                        <?php if ($model->category): ?>
                            <span class="meta-sep"></span>
                            <span class="post-cat">
                            <a href="<?= $model->category->getViewUrl() ?>" class="category">
                                <?= $model->category->name ?>
                            </a>
                        </span>
                        <?php endif; ?>
                    </div>
                </div>
                <?php if ($model->canDisplayImage()): ?>
                    <div class="featured">
                        <?php if (($full = $model->getImageWidth()) > 1000): ?>
                            <a href="<?= $model->getImage(1170, null) ?>" class="image-link">
                                <img src="<?= $model->getImage(770, 420) ?>"
                                     class="attachment-contentberg-main-full size-contentberg-main-full wp-post-image"
                                     title="<?= $model->title ?>"/>
                            </a>
                        <?php else: ?>
                            <img src="<?= $model->getImage(770, 420) ?>"
                                 class="attachment-contentberg-main-full size-contentberg-main-full"
                                 title="<?= $model->title ?>"/>
                        <?php endif; ?>
                        <?php if ($model->image_caption): ?>
                            <div class="img-caption"><?= nl2br($model->image_caption) ?></div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </header>
            <div>
                <div class="post-share-float share-float-a is-hidden cf">
                    <span class="share-text"><?= __('Ulashish') ?></span>
                    <div class="services">
                        <?= $shares = SocialSharer::widget([
                                                               'configurator'         => 'socialShare',
                                                               'containerOptions'     => [
                                                                   'tag' => false,
                                                               ],
                                                               'linkContainerOptions' => ['tag' => false],
                                                               'url'                  => $model->getShortViewUrl(),
                                                               'title'                => $model->title,
                                                               'imageUrl'             => $model->getCroppedImage(736, 736),
                                                               'driverProperties'     => [
                                                                   Telegram::class => [
                                                                   ],
                                                               ],
                                                           ]); ?>
                    </div>
                </div>
            </div>

            <div class="post-content description cf entry-content has-share-float content-spacious js-mediator-article">
                <?= $model->content ?>
            </div>

            <?= $this->render('partials/_mediator.php') ?>
            <div class="the-post-foot cf">
                <div class="tag-share cf">
                    <?php if (count($model->tags)): ?>
                        <div class="post-tags">
                            <?php foreach ($model->tags as $tag): ?>
                                <a href="<?= $tag->getViewUrl() ?>"
                                   rel="tag"><?= $tag->name ?></a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    <div class="post-share">

                        <div class="post-share-icons cf">
                            <!--<span class="counters">
                                <a href="#" class="likes-count ui-heart">
                                    <span class="number">47</span>
                                </a>

                            </span>-->
                            <?= $shares ?>
                            <a href="#" class="service social copy" title="<?= $model->getShortViewUrl() ?>"
                               onclick="copyToClipboard('<?= $model->getShortViewUrl() ?>');return false">
                                <i class="ui-doc"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <?php if ($model->is_bbc): ?>
                    <div class="bbc-wrap">
                        <div class="related-posts">
                            <?php foreach (PostBBC::getLast() as $link): ?>
                                <h3 class="post-title"><a href="<?= $link->url ?>" class="post-link" target="_blank">
                                        <?= $link->title ?>
                                    </a>
                                </h3>
                            <?php endforeach; ?>
                        </div>
                        <div class="bbc-brand"></div>
                    </div>
                <?php endif; ?>
                <?php if ($model->author): ?>
                    <div class="author-box">
                        <div class="image">
                            <img alt='<?= $model->author->fullname ?>'
                                 src='<?= $model->author->getImageUrl(82, 82) ?>'
                                 class='avatar avatar-82 photo'/>
                        </div>
                        <div class="content">
                        <span class="author"> <span><?= __('Muallif') ?></span>
                            <a href="<?= $model->author->getViewUrl() ?>"
                               title="<?= __('{author}ning maqolalari', ['author' => $model->author->fullname]) ?>"
                               rel="author">
                                <?= $model->author->fullname ?>
                            </a>
                        </span>
                            <p class="text author-bio">
                                <?= $model->author->intro ?>
                            </p>
                            <ul class="social-icons">
                                <?php if ($model->author->email): ?>
                                    <li>
                                        <a href="mailto:<?= $model->author->email ?>" class="ui-mail" title="Email">
                                            <span class="visuallyhidden">Email</span>
                                        </a>
                                    </li>
                                <?php endif; ?>
                                <?php if ($model->author->facebook): ?>
                                    <li>
                                        <a href="<?= $model->author->facebook ?>" class="ui-facebook" title="Facebook">
                                            <span class="visuallyhidden">Facebook</span>
                                        </a>
                                    </li>
                                <?php endif; ?>
                                <?php if ($model->author->twitter): ?>
                                    <li>
                                        <a href="<?= $model->author->twitter ?>" class="ui-twitter" title="Twitter">
                                            <span class="visuallyhidden">Twitter</span>
                                        </a>
                                    </li>
                                <?php endif; ?>
                                <?php if ($model->author->telegram): ?>
                                    <li>
                                        <a href="<?= $model->author->telegram ?>" class="ui-paper-plane"
                                           title="Telegram">
                                            <span class="visuallyhidden">Telegram</span>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                <?php endif; ?>

                <?php
                $title = __('Aloqador maqolalar');
                $posts = $model->getSimilarPosts(6);
                $count = count($posts);
                if ($count < 2) {
                    $posts = PostProvider::getLastPosts(6, false, [$model->_id]);
                    $title = __('So\'nggi yangiliklar');

                    $count = count($posts);
                }
                ?>
                <?php if ($count > 1): ?>
                    <section class="related-posts grid-3 <?= $comments ? '' : 'no-margins' ?>">
                        <h4 class="section-head">
                            <span class="title"><?= $title ?></span>
                        </h4>
                        <div class="ts-row posts ">
                            <?php foreach ($posts as $i => $post): ?>
                                <article class="post col-6" data-pos="<?= $i ?>" data-id="<?= $post->id ?>">
                                    <?php if ($i < 2): ?>
                                        <a href="<?= $post->getViewUrl() ?>"
                                           title="<?= $post->title ?>" class="image-link">
                                            <img class="image"
                                                 src="<?= $post->getImage(370, 220) ?>"
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
                <?php endif; ?>
        </article>
    </div>

    <aside class="col-4 sidebar mb-45" data-sticky="1">
        <?= $this->render('/layouts/partials/sidebar.php', ['exclude' => [$model->_id]]) ?>
    </aside>
</div>