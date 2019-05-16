<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2017. ActiveMedia Solutions LLC
 * @author    Rustam Mamadaminov <rmamdaminov@gmail.com>
 */

use common\models\Comment;
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

$this->addBodyClass('post-template-default single single-post no-sidebar ');
$this->addBodyClass('post-' . $model->short_id);

$comments = false;

if (mb_strpos($model->content, 'twitter') !== false) {
    $this->registerJsFile('https://platform.twitter.com/widgets.js', ['async' => true, 'charset' => 'utf-8']);
}
?>

<div class="ts-row cf">
    <div class="col-8 main-content cf">
        <article class="the-post-modern the-post post">
            <header class="post-header the-post-header cf">
                <div class="post-meta post-meta-b the-post-meta <?= $model->canDisplayImage() ? '' : 'center-full' ?>">
                    <?php if ($model->is_bbc): ?>
                        <div class="bbc-wrap">
                            <div class="bbc-brand"><span><?= __("O'zbek") ?></span></div>
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
                <?php if ($model->canDisplayImage()): $full = $model->getImageWidth() > 1000; ?>
                    <div class="featured <?= $full ? '' : 'featured-small' ?>">
                        <img src="<?= $model->getImage($full ? 1170 : 720, $full ? 620 : 420) ?>"
                             class="attachment-contentberg-main-full size-contentberg-main-full"
                             title="<?= $model->title ?>"/>
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
            <div class="post-content description cf entry-content has-share-float content-spacious-full js-mediator-article">
                <?= $model->content ?>

                <?php if ($model->gallery): ?>

                    <?php
                    $gallery = $model->getGalleryItemsModel();
                    $count   = [4, 3, 2, 4, 3, 4, 4];
                    shuffle($count);
                    $start         = 1;
                    ?>
                    <?php foreach ($count as $limit): ?>
                        <?php
                        if ($start < count($gallery)):
                            $items = array_slice($gallery, $start - 1, $limit);
                            $start += $limit;
                            ?>
                            <ul class="wp-block-gallery columns-<?= count($items) ?> is-cropped">
                                <?php foreach ($items as $item): ?>
                                    <li class="blocks-gallery-item">
                                        <figure>
                                            <a
                                                    href="<?= $item->getImageCropped(1100, null) ?>"
                                                    class="lightbox-gallery-img">
                                                <img
                                                        alt="<?= $item->caption ?>"
                                                        data-image-title="<?= $item->caption ?>"
                                                        title="<?= $item->caption ?>"
                                                        src="<?= $item->getImageCropped(545, 390) ?>"
                                                />
                                            </a>
                                        </figure>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif ?>
                    <?php endforeach; ?>
                <?php endif ?>
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
                        <div class="ts-row posts cf">
                            <?php foreach ($posts as $i => $post): ?>
                                <article class="post col-<?= $count == 2 ? '6' : '4' ?>" data-pos="<?= $i ?>"
                                         data-id="<?= $post->id ?>">
                                    <?php if ($i < 3): ?>

                                        <a href="<?= $post->getViewUrl() ?>"
                                           title="<?= $post->title ?>" class="image-link">
                                            <img class="image"
                                                 src="<?= $post->getImage($count == 2 ? 570 : 370, $count == 2 ? 330 : 220) ?>"
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
                <?php if ($comments): ?>
                    <div class="comments">
                        <div id="comments" class="comments-area">
                            <div class="comments-wrap"><h4 class="section-head cf"><span class="title"> <span
                                                class="number">3</span> Comments </span></h4>
                                <ol class="comments-list add-separator">
                                    <li class="comment even thread-even depth-1" id="li-comment-13">
                                        <article id="comment-13" class="comment the-comment" itemscope
                                                 itemtype="http://schema.org/UserComments">
                                            <div class="comment-avatar"><img alt=''
                                                                             src='https://contentberg.theme-sphere.com/wp-content/uploads/2018/09/other.jpg'
                                                                             srcset='https://contentberg.theme-sphere.com/wp-content/uploads/2018/09/other.jpg 2x'
                                                                             class='avatar avatar-60 photo avatar-default'
                                                                             height='60' width='60'/></div>
                                            <div class="comment-content">
                                                <div class="comment-meta"><span class="comment-author"
                                                                                itemprop="creator" itemscope
                                                                                itemtype="http://schema.org/Person"> <span
                                                                itemprop="name"><a href='http://theme-sphere.com'
                                                                                   rel='external nofollow' class='url'>Groot Will</a></span> </span>
                                                    <a href="https://contentberg.theme-sphere.com/blog/2018/07/15/how-i-met-derpina/#comment-13"
                                                       class="comment-time">
                                                        <time itemprop="commentTime"
                                                              datetime="2018-09-17T07:31:48+00:00"> 5 months ago
                                                        </time>
                                                    </a> <span class="reply"> <a rel='nofollow'
                                                                                 class='comment-reply-link'
                                                                                 href='#comment-13'
                                                                                 onclick='return addComment.moveForm( "comment-13", "13", "respond", "1256" )'
                                                                                 aria-label='Reply to Groot Will'>Reply</a> </span>
                                                </div>
                                                <div class="text">
                                                    <div itemprop="commentText" class="comment-text"><p>That far ground
                                                            rat pure from newt far panther crane lorikeet overlay alas
                                                            cobra
                                                            across much gosh less goldfinch ruthlessly alas examined and
                                                            that more and the ouch jeez.</p></div>
                                                </div>
                                            </div>
                                        </article>
                                        <ul class="children">
                                            <li class="comment odd alt depth-2" id="li-comment-14">
                                                <article id="comment-14" class="comment the-comment" itemscope
                                                         itemtype="http://schema.org/UserComments">
                                                    <div class="comment-avatar"><img alt=''
                                                                                     src='https://cheerup.theme-sphere.com/wp-content/uploads/2016/05/jane-doe.jpg'
                                                                                     srcset='https://cheerup.theme-sphere.com/wp-content/uploads/2016/05/jane-doe.jpg 2x'
                                                                                     class='avatar avatar-60 photo avatar-default'
                                                                                     height='60' width='60'/></div>
                                                    <div class="comment-content">
                                                        <div class="comment-meta"><span class="comment-author"
                                                                                        itemprop="creator" itemscope
                                                                                        itemtype="http://schema.org/Person"> <span
                                                                        itemprop="name"><a
                                                                            href='http://theme-sphere.com'
                                                                            rel='external nofollow'
                                                                            class='url'>Jane Doe</a></span> </span>
                                                            <a href="https://contentberg.theme-sphere.com/blog/2018/07/15/how-i-met-derpina/#comment-14"
                                                               class="comment-time">
                                                                <time itemprop="commentTime"
                                                                      datetime="2018-09-18T07:31:49+00:00"> 5 months ago
                                                                </time>
                                                            </a> <span class="reply"> <a rel='nofollow'
                                                                                         class='comment-reply-link'
                                                                                         href='#comment-14'
                                                                                         onclick='return addComment.moveForm( "comment-14", "14", "respond", "1256" )'
                                                                                         aria-label='Reply to Jane Doe'>Reply</a> </span>
                                                        </div>
                                                        <div class="text">
                                                            <div itemprop="commentText" class="comment-text"><p>
                                                                    Coquettish darn pernicious foresaw therefore much
                                                                    amongst lingeringly shed much due antagonistically
                                                                    alongside so then more and about turgid.</p></div>
                                                        </div>
                                                    </div>
                                                </article>
                                            </li>
                                        </ul>
                                    </li>
                                    <li class="comment even thread-odd thread-alt depth-1" id="li-comment-15">
                                        <article id="comment-15" class="comment the-comment" itemscope
                                                 itemtype="http://schema.org/UserComments">
                                            <div class="comment-avatar"><img alt=''
                                                                             src='https://contentberg.theme-sphere.com/wp-content/uploads/2018/09/other.jpg'
                                                                             srcset='https://contentberg.theme-sphere.com/wp-content/uploads/2018/09/other.jpg 2x'
                                                                             class='avatar avatar-60 photo avatar-default'
                                                                             height='60' width='60'/></div>
                                            <div class="comment-content">
                                                <div class="comment-meta"><span class="comment-author"
                                                                                itemprop="creator" itemscope
                                                                                itemtype="http://schema.org/Person"> <span
                                                                itemprop="name"><a href='http://theme-sphere.com'
                                                                                   rel='external nofollow' class='url'>Groot Will</a></span> </span>
                                                    <a href="https://contentberg.theme-sphere.com/blog/2018/07/15/how-i-met-derpina/#comment-15"
                                                       class="comment-time">
                                                        <time itemprop="commentTime"
                                                              datetime="2018-09-18T07:40:09+00:00"> 5 months ago
                                                        </time>
                                                    </a> <span class="reply"> <a rel='nofollow'
                                                                                 class='comment-reply-link'
                                                                                 href='#comment-15'
                                                                                 onclick='return addComment.moveForm( "comment-15", "15", "respond", "1256" )'
                                                                                 aria-label='Reply to Groot Will'>Reply</a> </span>
                                                </div>
                                                <div class="text">
                                                    <div itemprop="commentText" class="comment-text"><p>Crud much
                                                            unstinting violently pessimistically far camel inanimately a
                                                            remade dove disagreed hellish one concisely before with this
                                                            erotic frivolous.</p></div>
                                                </div>
                                            </div>
                                        </article>
                                    </li>
                                </ol>
                            </div>
                            <div id="respond" class="comment-respond">
                                <h3 id="reply-title" class="comment-reply-title"><span class="section-head"><span
                                                class="title">Write A Comment</span></span>
                                    <small><a rel="nofollow" id="cancel-comment-reply-link"
                                              href="/blog/2018/07/15/how-i-met-derpina/#respond" style="display:none;">Cancel
                                            Reply</a></small>
                                </h3>
                                <form action="https://contentberg.theme-sphere.com/wp-comments-post.php" method="post"
                                      id="commentform" class="comment-form">
                                    <div class="inline-field"><input name="author" id="author" type="text" value=""
                                                                     aria-required="true" placeholder="Name" required/>
                                    </div>
                                    <div class="inline-field"><input name="email" id="email" type="text" value=""
                                                                     aria-required="true" placeholder="Email" required/>
                                    </div>
                                    <div class="inline-field"><input name="url" id="url" type="text" value=""
                                                                     placeholder="Website"/></div>
                                    <div class="reply-field cf"><textarea name="comment" id="comment" cols="45" rows="7"
                                                                          placeholder="Enter your comment here.."
                                                                          aria-required="true" required></textarea>
                                    </div>
                                    <p class="comment-form-cookies-consent"><input id="wp-comment-cookies-consent"
                                                                                   name="wp-comment-cookies-consent"
                                                                                   type="checkbox" value="yes"/> <label
                                                for="wp-comment-cookies-consent">Save my name, email, and website in
                                            this
                                            browser for the next time I comment. </label></p>
                                    <p class="form-submit"><input name="submit" type="submit" id="comment-submit"
                                                                  class="submit" value="Post Comment"/> <input
                                                type='hidden' name='comment_post_ID' value='1256' id='comment_post_ID'/>
                                        <input type='hidden' name='comment_parent' id='comment_parent' value='0'/></p>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
        </article>
    </div>
</div>