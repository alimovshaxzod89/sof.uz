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
$author           = $model->author;
$category         = isset($this->params['category']) ? $this->params['category'] : $model->category;
$this->_canonical = $author ? $model->getAuthorPostUrl() : $model->getViewUrl();
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
$this->addBodyClass('post-template-default single single-post single-format-standard navbar-sticky sidebar-none with-hero hero-wide hero-image pagination-infinite_button');
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
                        <img alt='<?= $model->author->fullname ?>'
                             src='<?= $model->author->getImageUrl(40, 40) ?>'
                             srcset='<?= $model->author->getImageUrl(80, 80) ?> 2x'
                             class='avatar avatar-40 photo' height='40' width='40'/><?= $model->author->fullname ?>
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
                                                <a class="like" data-id="88" href="">
                                                    <span class="icon">
                                                        <i class="mdi mdi-thumb-up"></i>
                                                    </span>
                                                    <span class="count">2</span><span>&nbsp;likes</span>
                                                </a>
                                                <a class="view">
                                                    <span class="icon">
                                                        <i class="mdi mdi-eye"></i>
                                                    </span>
                                                    <span class="count">231</span><span>&nbsp;views</span>
                                                </a>
                                                <?php if (0): ?>
                                                    <a class="comment" href="#comments">
                                                    <span class="icon">
                                                        <i class="mdi mdi-comment"></i>
                                                    </span>
                                                        <span class="count">3</span>
                                                        <span>&nbsp;comments</span>
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                            <div class="action-share">
                                                <a class="facebook"
                                                   href="https://www.facebook.com/sharer.php?u=https%3A%2F%2Fmagsy.mondotheme.com%2F2018%2F09%2F29%2Fthe-art-of-hiring-a-product-designer%2F"
                                                   target="_blank">
                                                    <i class="mdi mdi-facebook"></i>
                                                </a>
                                                <a class="twitter"
                                                   href="https://twitter.com/intent/tweet?url=https%3A%2F%2Fmagsy.mondotheme.com%2F2018%2F09%2F29%2Fthe-art-of-hiring-a-product-designer%2F&#038;text=The+Art+of+Hiring+a+Product+Designer"
                                                   target="_blank">
                                                    <i class="mdi mdi-twitter"></i>
                                                </a>
                                                <a class="google"
                                                   href="https://plus.google.com/share?url=https%3A%2F%2Fmagsy.mondotheme.com%2F2018%2F09%2F29%2Fthe-art-of-hiring-a-product-designer%2F&#038;text=The+Art+of+Hiring+a+Product+Designer"
                                                   target="_blank">
                                                    <i class="mdi mdi-google-plus"></i>
                                                </a>
                                                <a class="linkedin"
                                                   href="https://www.linkedin.com/shareArticle?mini=true&#038;url=https%3A%2F%2Fmagsy.mondotheme.com%2F2018%2F09%2F29%2Fthe-art-of-hiring-a-product-designer%2F&#038;title=The+Art+of+Hiring+a+Product+Designer"
                                                   target="_blank">
                                                    <i class="mdi mdi-linkedin"></i>
                                                </a>
                                                <a class="pinterest"
                                                   href="https://pinterest.com/pin/create/button/?url=https%3A%2F%2Fmagsy.mondotheme.com%2F2018%2F09%2F29%2Fthe-art-of-hiring-a-product-designer%2F&#038;media=https%3A%2F%2Fmagsy.mondotheme.com%2Fwp-content%2Fuploads%2F2018%2F07%2Ftoa-heftiba-526264-unsplash-1160x773.jpg&#038;description=The+Art+of+Hiring+a+Product+Designer"
                                                   target="_blank">
                                                    <i class="mdi mdi-pinterest"></i>
                                                </a>
                                                <a class="reddit"
                                                   href="https://reddit.com/submit?url=https%3A%2F%2Fmagsy.mondotheme.com%2F2018%2F09%2F29%2Fthe-art-of-hiring-a-product-designer%2F&#038;title=The+Art+of+Hiring+a+Product+Designer"
                                                   target="_blank">
                                                    <i class="mdi mdi-reddit"></i>
                                                </a>
                                                <a class="tumblr"
                                                   href="https://www.tumblr.com/widgets/share/tool?canonicalUrl=https%3A%2F%2Fmagsy.mondotheme.com%2F2018%2F09%2F29%2Fthe-art-of-hiring-a-product-designer%2F&#038;title=The+Art+of+Hiring+a+Product+Designer"
                                                   target="_blank">
                                                    <i class="mdi mdi-tumblr"></i>
                                                </a>
                                                <a class="vk"
                                                   href="http://vk.com/share.php?url=https%3A%2F%2Fmagsy.mondotheme.com%2F2018%2F09%2F29%2Fthe-art-of-hiring-a-product-designer%2F&#038;title=The+Art+of+Hiring+a+Product+Designer"
                                                   target="_blank">
                                                    <i class="mdi mdi-vk"></i>
                                                </a>
                                                <a class="pocket"
                                                   href="https://getpocket.com/edit?url=https%3A%2F%2Fmagsy.mondotheme.com%2F2018%2F09%2F29%2Fthe-art-of-hiring-a-product-designer%2F"
                                                   target="_blank">
                                                    <i class="mdi mdi-pocket"></i>
                                                </a>
                                                <a class="telegram"
                                                   href="https://t.me/share/url?url=https%3A%2F%2Fmagsy.mondotheme.com%2F2018%2F09%2F29%2Fthe-art-of-hiring-a-product-designer%2F&#038;text=The+Art+of+Hiring+a+Product+Designer"
                                                   target="_blank">
                                                    <i class="mdi mdi-telegram"></i>
                                                </a>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <?php if ($model->hasAuthor()): ?>
                                        <div class="author-box">
                                            <div class="author-image">
                                                <img alt='Nancy Welch' src='<?= $model->getCroppedImage(140, 140) ?>'
                                                     srcset='<?= $model->author->getFileUrl('image') ?> 2x'
                                                     class='avatar avatar-140 photo'
                                                     height='140' width='140'/>
                                            </div>

                                            <div class="author-info">
                                                <h4 class="author-name">
                                                    <a href="<?= $model->author->getViewUrl() ?>">
                                                        <?= $model->author->fullname ?>
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

    <div class="bottom-area">
        <div class="container medium">
            <?php if (count($similarPosts)): ?>
                <div class="related-posts">
                    <h3 class="u-border-title"><?= __('You might also like') ?></h3>
                    <div class="row">
                        <?php foreach ($similarPosts as $similarPost) : ?>
                            <div class="col-lg-6">
                                <article class="post">
                                    <div class="entry-media">
                                        <div class="placeholder" style="padding-bottom: 66.666666666667%;">
                                            <a href="<?= $similarPost->getViewUrl() ?>">
                                                <img class="lazyload"
                                                     data-srcset="<?= $model->getCroppedImage(300, 200) ?> 300w, <?= $model->getCroppedImage(768, 512) ?> 768w, <?= $model->getCroppedImage(1024, 683) ?> 1024w, <?= $model->getCroppedImage(30, 20) ?> 30w, <?= $model->getCroppedImage(400, 267) ?> 400w, <?= $model->getCroppedImage(800, 533) ?> 800w, <?= $model->getCroppedImage(1160, 773) ?> 1160w"
                                                     data-sizes="auto"
                                                     src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=="
                                                     alt="">
                                            </a>
                                        </div>
                                        <div class="entry-format">
                                            <i class="mdi mdi-image-multiple"></i>
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
            <?php endif; ?>
        </div>
    </div>

    <?php if (0): ?>
        <!--comment-->
        <div class="bottom-area">
            <div class="container small">
                <div id="comments" class="comments-area">
                    <h3 class="comments-title">3 comments </h3>

                    <ol class="comment-list">

                        <li id="comment-7"
                            class="comment byuser comment-author-kathryn even thread-even depth-1 parent">
                            <article id="div-comment-7" class="comment-wrapper u-clearfix" itemscope
                                     itemtype="https://schema.org/Comment">
                                <div class="comment-author-avatar vcard">
                                    <img class="lazyload" data-src="2018/08/kathryn-hughes-96x96.jpg">
                                </div>

                                <div class="comment-content">
                                    <div class="comment-author-name vcard" itemprop="author">
                                        <cite class="fn"><a href='https://themeforest.net/user/mondotheme/portfolio'
                                                            rel='external nofollow' class='url'>Kathryn
                                                Hughes</a></cite></div>

                                    <div class="comment-metadata">
                                        <time datetime="2018-09-01T14:44:17+00:00" itemprop="datePublished">
                                            September 1, 2018 at 2:44 pm
                                        </time>

                                        <span class="reply-link"><a rel='nofollow' class='comment-reply-link'
                                                                    href='2018/09/29/the-art-of-hiring-a-product-designer/index.html%3Freplytocom=7.html#respond'
                                                                    onclick='return addComment.moveForm( "div-comment-7", "7", "respond", "88" )'
                                                                    aria-label='Reply to Kathryn Hughes'>Reply</a></span>
                                    </div>

                                    <div class="comment-body" itemprop="comment">
                                        <p>Credibly reintermediate backend ideas for cross-platform models. Continually
                                            reintermediate integrated processes through technically sound intellectual
                                            capital.</p>
                                    </div>

                                </div>
                            </article>
                            <ol class="children">

                                <li id="comment-8" class="comment byuser comment-author-daniel odd alt depth-2">
                                    <article id="div-comment-8" class="comment-wrapper u-clearfix" itemscope
                                             itemtype="https://schema.org/Comment">
                                        <div class="comment-author-avatar vcard">
                                            <img class="lazyload" data-src="2018/08/daniel-wade-96x96.jpg">
                                        </div>

                                        <div class="comment-content">
                                            <div class="comment-author-name vcard" itemprop="author">
                                                <cite class="fn"><a
                                                            href='https://themeforest.net/user/mondotheme/portfolio'
                                                            rel='external nofollow' class='url'>Daniel Wade</a></cite>
                                            </div>

                                            <div class="comment-metadata">
                                                <time datetime="2018-09-01T14:44:17+00:00" itemprop="datePublished">
                                                    September 1, 2018 at 2:44 pm
                                                </time>

                                                <span class="reply-link"><a rel='nofollow' class='comment-reply-link'
                                                                            href='2018/09/29/the-art-of-hiring-a-product-designer/index.html%3Freplytocom=8.html#respond'
                                                                            onclick='return addComment.moveForm( "div-comment-8", "8", "respond", "88" )'
                                                                            aria-label='Reply to Daniel Wade'>Reply</a></span>
                                            </div>

                                            <div class="comment-body" itemprop="comment">
                                                <p>Globally incubate standards compliant channels before scalable
                                                    benefits 🙌🏼</p>
                                            </div>

                                        </div>
                                    </article>
                                </li><!-- #comment-## -->
                            </ol><!-- .children -->
                        </li><!-- #comment-## -->

                        <li id="comment-9"
                            class="comment byuser comment-author-nancy bypostauthor even thread-odd thread-alt depth-1">
                            <article id="div-comment-9" class="comment-wrapper u-clearfix" itemscope
                                     itemtype="https://schema.org/Comment">
                                <div class="comment-author-avatar vcard">
                                    <img class="lazyload" data-src="2018/08/nancy-welch-96x96.jpg">
                                </div>

                                <div class="comment-content">
                                    <div class="comment-author-name vcard" itemprop="author">
                                        <cite class="fn"><a href='https://themeforest.net/user/mondotheme/portfolio'
                                                            rel='external nofollow' class='url'>Nancy Welch</a></cite>
                                    </div>

                                    <div class="comment-metadata">
                                        <time datetime="2018-09-01T14:44:17+00:00" itemprop="datePublished">
                                            September 1, 2018 at 2:44 pm
                                        </time>

                                        <span class="reply-link"><a rel='nofollow' class='comment-reply-link'
                                                                    href='2018/09/29/the-art-of-hiring-a-product-designer/index.html%3Freplytocom=9.html#respond'
                                                                    onclick='return addComment.moveForm( "div-comment-9", "9", "respond", "88" )'
                                                                    aria-label='Reply to Nancy Welch'>Reply</a></span>
                                    </div>

                                    <div class="comment-body" itemprop="comment">
                                        <p>Interactively procrastinate high-payoff content without backward-compatible
                                            data. Quickly cultivate optimal processes and tactical architectures.
                                            Completely iterate covalent strategic theme areas via accurate e-markets
                                            👍</p>
                                    </div>

                                </div>
                            </article>
                        </li><!-- #comment-## -->
                    </ol>


                    <div id="respond" class="comment-respond">
                        <h3 id="reply-title" class="comment-reply-title">Leave a Reply
                            <small><a rel="nofollow" id="cancel-comment-reply-link" href="full_hero_post.html#respond"
                                      style="display:none;">Cancel reply</a></small>
                        </h3>
                        <form action="https://magsy.mondotheme.com/wp-comments-post.php" method="post" id="commentform"
                              class="comment-form" novalidate>
                            <p class="comment-form-comment"><textarea
                                        onfocus="if(!this._s==true){var _i=document.createElement('input');_i.setAttribute('type','hidden');_i.setAttribute('name','ssc_key_3cf27bfce5417982');_i.setAttribute('value','0536516bffb02642');var _p=this.parentNode;_p.insertBefore(_i,this);this._s=true;}"
                                        id="comment" name="comment" rows="8" aria-required="true"></textarea></p>
                            <div class="row comment-author-inputs">
                                <div class="col-md-4 input"><p class="comment-form-author"><label
                                                for="author">Name*</label><input id="author" name="author" type="text"
                                                                                 value=""
                                                                                 size="30" aria-required='true'></p>
                                </div>
                                <div class="col-md-4 input"><p class="comment-form-email"><label
                                                for="email">E-mail*</label><input id="email" name="email" type="text"
                                                                                  value=""
                                                                                  size="30" aria-required='true'></p>
                                </div>
                                <div class="col-md-4 input"><p class="comment-form-url"><label
                                                for="url">Website</label><input
                                                id="url" name="url" type="text" value="" size="30"></p></div>
                            </div>
                            <p class="comment-form-cookies-consent"><input id="wp-comment-cookies-consent"
                                                                           name="wp-comment-cookies-consent"
                                                                           type="checkbox" value="yes"> <label
                                        for="wp-comment-cookies-consent">Save my name, email, and website in this
                                    browser
                                    for the next time I comment.</label></p>
                            <p class="form-submit"><input name="submit" type="submit" id="submit" class="button"
                                                          value="Post Comment"/> <input type='hidden'
                                                                                        name='comment_post_ID'
                                                                                        value='88'
                                                                                        id='comment_post_ID'/>
                                <input type='hidden' name='comment_parent' id='comment_parent' value='0'/>
                            </p>
                            <p style="display: none;"><input type="hidden" id="akismet_comment_nonce"
                                                             name="akismet_comment_nonce" value="b902d19b2a"/></p>
                            <p style="display: none;"><input type="hidden" id="ak_js" name="ak_js" value="244"/></p>
                            <style>.ssc_notice_3cf27bfce5417982 strong {
                                    display: none;
                                }

                                .ssc_notice_3cf27bfce5417982:after {
                                    content: '\2018\0030\0035\0033\0036\0035\0031\0036\0062\0066\0066\0062\0030\0032\0036\0034\0032\0033\0063\0066\0032\0037\0062\0066\0063\0065\0035\0034\0031\0037\0039\0038\0032\2019';
                                    font-weight: bold;
                                }</style>
                            <noscript><p class="ssc_notice_3cf27bfce5417982">Notice: It seems you have Javascript
                                    disabled in your Browser. In order to submit a comment to this post, please write
                                    this
                                    code along with your comment: <strong aria-hidden="true">fbcc725680644e56121f7f9bb2f33250</strong>
                                </p></noscript>
                        </form>
                    </div><!-- #respond -->
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>