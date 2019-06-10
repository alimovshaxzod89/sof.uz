<?php

use common\components\Config;
use frontend\components\View;
use frontend\models\CategoryProvider;
use frontend\models\PostProvider;

/**
 * @var $this View
 * @var $mainList PostProvider[]
 * @var $posts PostProvider[]
 * @var $limit int
 */
$this->_canonical = linkTo(['/'], true);
$this->addBodyClass('page-template page-template-page-modular page-template-page-modular-php page page-id-487 navbar-sticky sidebar-none pagination-infinite_button modular-title-1');
$mainList          = PostProvider::getTopPost();
$latestPosts       = PostProvider::getLastPosts();
$footerTopCategory = CategoryProvider::getFooterTopCategory();
?>
    <div class="section post_big_list">
        <div class="container">
            <div class="row">
                <div class="content-column col-lg-9">
                    <div class="content-area">
                        <main class="site-main">
                            <?php if (count($mainList)): ?>
                                <div class="main-block module big list u-module-margin">
                                    <div class="row">
                                        <div class="col-lg-6 col-one">
                                            <?php $one = array_shift($mainList) ?>
                                            <?php if ($one instanceof PostProvider): ?>
                                                <article
                                                        class="post post-large post-142 type-post status-publish format-video has-post-thumbnail hentry category-food post_format-post-format-video">
                                                    <div class="entry-media">
                                                        <div class="placeholder" style="padding-bottom: 70.25%;">
                                                            <a href="<?= $one->getViewUrl() ?>">
                                                                <img class="lazyload"
                                                                     data-srcset="
                                                                 <?= $one->getCroppedImage(800, 562) ?> 800w,
                                                                 <?= $one->getCroppedImage(300, 211) ?> 300w,
                                                                 <?= $one->getCroppedImage(768, 539) ?> 768w,
                                                                 <?= $one->getCroppedImage(1024, 719) ?> 1024w,
                                                                 <?= $one->getCroppedImage(30, 20) ?> 30w,
                                                                 <?= $one->getCroppedImage(400, 281) ?> 400w,
                                                                 <?= $one->getCroppedImage(1160, 814) ?> 1160w"
                                                                     data-sizes="auto"
                                                                     src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=="
                                                                     alt="">
                                                            </a>
                                                        </div>
                                                        <?php if (0): ?>
                                                            <div class="entry-format">
                                                                <i class="mdi mdi-youtube-play"></i>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                    <header class="entry-header">
                                                        <h2 class="entry-title">
                                                            <a href="<?= $one->getViewUrl() ?>" rel="bookmark">
                                                                <?= $one->title ?>
                                                            </a>
                                                        </h2>
                                                    </header>
                                                    <div class="entry-excerpt u-text-format">
                                                        <?= $one->info ?>
                                                    </div>
                                                    <div class="entry-footer">
                                                        <a href="<?= $one->getViewUrl() ?>">
                                                            <time datetime="<?= $one->getPublishedTimeIso() ?>">
                                                                <?= $one->getShortFormattedDate() ?>
                                                            </time>
                                                        </a>
                                                    </div>
                                                </article>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-lg-6 col-two">
                                            <?php foreach ($mainList as $post): ?>
                                                <article
                                                        class="post post-list post-51 type-post status-publish format-standard has-post-thumbnail hentry category-food">
                                                    <div class="entry-wrapper">
                                                        <header class="entry-header">
                                                            <h2 class="entry-title">
                                                                <a href="<?= $post->getViewUrl() ?>"
                                                                   rel="bookmark"><?= $post->title ?></a>
                                                            </h2>
                                                        </header>
                                                        <div class="entry-excerpt u-text-format">
                                                            <?= $post->info ?>
                                                        </div>
                                                        <div class="entry-footer">
                                                            <a href="<?= $post->getViewUrl() ?>">
                                                                <time datetime="<?= $post->getPublishedTimeIso() ?>">
                                                                    <?= $post->getShortFormattedDate() ?>
                                                                </time>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </article>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if (count($latestPosts)): ?>
                                <h1 class="latest-title"><?= __('Latest Posts') ?></h1>
                                <div class="row posts-wrapper last-news-list">
                                    <?php foreach ($latestPosts as $post): ?>
                                        <div class="col-12">
                                            <article
                                                    class="post post-list post-88 type-post status-publish format-standard has-post-thumbnail hentry category-design">
                                                <div class="entry-media">
                                                    <div class="placeholder" style="padding-bottom: 66.666666666667%;">
                                                        <a href="<?= $post->getViewUrl() ?>">
                                                            <img class="lazyload"
                                                                 data-srcset="
                                                             <?= $post->getCroppedImage(300, 200) ?> 300w,
                                                             <?= $post->getCroppedImage(768, 512) ?> 768w,
                                                             <?= $post->getCroppedImage(1024, 683) ?> 1024w,
                                                             <?= $post->getCroppedImage(30, 20) ?> 30w,
                                                             <?= $post->getCroppedImage(400, 267) ?> 400w,
                                                             <?= $post->getCroppedImage(800, 533) ?> 800w,
                                                             <?= $post->getCroppedImage(1160, 773) ?> 1160w"
                                                                 data-sizes="auto"
                                                                 src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=="
                                                                 alt="">
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="entry-wrapper">

                                                    <header class="entry-header">
                                                        <?php if ($post->hasCategory()): ?>
                                                            <div class="entry-meta">
                                                            <span class="meta-category">
                                                                <a href="<?= $post->category->getViewUrl() ?>"
                                                                   rel="category">
                                                                    <i class="dot"
                                                                       style="background-color: #ff7473;"></i>
                                                                    <?= $post->category->name ?></a>
                                                            </span>
                                                            </div>
                                                        <?php endif; ?>

                                                        <h2 class="entry-title">
                                                            <a href="<?= $post->getViewUrl() ?>" rel="bookmark">
                                                                <?= $post->title ?>
                                                            </a>
                                                        </h2>
                                                    </header>
                                                    <div class="entry-excerpt u-text-format">
                                                        <p><?= $post->info ?></p>
                                                    </div>
                                                    <div class="entry-footer">
                                                        <a href="<?= $post->getViewUrl() ?>">
                                                            <time datetime="<?= $post->getPublishedTimeIso() ?>">
                                                                <?= $post->getPublishedOnSeconds() ?>
                                                            </time>
                                                        </a>
                                                    </div>
                                                </div>
                                            </article>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <nav class="navigation posts-navigation" role="navigation">
                                    <h2 class="screen-reader-text">Posts navigation</h2>
                                    <div class="nav-links">
                                        <div class="nav-previous"><a href="page/2/index.html%3Fsb=right.html">Older
                                                posts</a></div>
                                    </div>
                                </nav>
                                <div class="infinite-scroll-status">
                                    <div class="infinite-scroll-request"></div>
                                </div>
                                <div class="infinite-scroll-action">
                                    <div class="infinite-scroll-button button">Load more</div>
                                </div>
                            <?php endif; ?>
                        </main>
                    </div>
                </div>
                <div class="sidebar-column col-lg-3">
                    <aside class="widget-area">
                        <?php
                        /* @var $trendingNews PostProvider[] */
                        $trendingNews = \frontend\models\PostProvider::getTrendingNews() ?>
                        <?php if (count($trendingNews)): ?>
                            <div id="magsy_posts_widget-2" class="widget-posts widget widget_magsy_posts_widget"><h5
                                        class="widget-title"><?= __('Recent Posts') ?></h5>
                                <div class="posts">
                                    <?php foreach ($trendingNews as $news): ?>
                                        <div>
                                            <div class="entry-thumbnail">
                                                <a class="u-permalink" href="<?= $news->getViewUrl() ?>"></a>
                                                <img class="lazyload"
                                                     data-src="<?= $news->getCroppedImage(150, 150) ?>">
                                            </div>
                                            <header class="entry-header">
                                                <div class="entry-meta">
                                                <span class="meta-date">
                                                    <a href="<?= $news->getViewUrl() ?>">
                                                        <time datetime="<?= $news->getPublishedTimeIso() ?>">
                                                            <?= $news->getPublishedOnSeconds() ?>
                                                        </time>
                                                    </a>
                                                </span>
                                                </div>

                                                <h6 class="entry-title">
                                                    <a href="<?= $news->getViewUrl() ?>" rel="bookmark">
                                                        <?= $news->title ?>
                                                    </a>
                                                </h6>
                                            </header>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php $cats = \frontend\models\CategoryProvider::getHomeCategories() ?>
                        <?php if (count($cats)): ?>
                            <div id="magsy_category_widget-2" class="widget widget_magsy_category_widget">
                                <h5 class="widget-title">Categories</h5>
                                <ul>
                                    <?php foreach ($cats as $cat): ?>
                                        <li class="category-item">
                                            <a href="<?= $cat->getViewUrl() ?>" title="<?= $cat->name ?>">
                                            <span class="category-name">
                                                <i class="dot" style="background-color: #ff7473;"></i>
                                                <?= $cat->name ?>
                                            </span>
                                                <span class="category-count"><?= $cat->count_posts ?></span>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                        <div id="magsy_picks_widget-2" class="widget widget_magsy_picks_widget">
                            <h5 class="widget-title">Hand Picked Articles</h5>
                            <div class="picks-wrapper">
                                <div class="icon" data-icon="&#xf238"></div>
                                <div class="picked-posts owl">
                                    <article class="post">
                                        <div class="entry-thumbnail">
                                            <img class="lazyload"
                                                 data-src="2018/08/priscilla-du-preez-228220-unsplash-150x150.jpg">
                                        </div>

                                        <header class="entry-header">
                                            <h6 class="entry-title">Designing a Closet with a Budget in
                                                Mind</h6>
                                        </header>
                                        <a class="u-permalink" href="../index.html%3Fp=172.html"></a>
                                    </article>
                                    <article class="post">
                                        <div class="entry-thumbnail">
                                            <img class="lazyload"
                                                 data-src="2018/08/andrew-neel-369701-unsplash-150x150.jpg">
                                        </div>

                                        <header class="entry-header">
                                            <h6 class="entry-title">Men&#8217;s Suits Are Back in Trend</h6>
                                        </header>
                                        <a class="u-permalink" href="../index.html%3Fp=162.html"></a>
                                    </article>
                                    <article class="post">
                                        <div class="entry-thumbnail">
                                            <img class="lazyload"
                                                 data-src="2018/07/alex-378877-unsplash-150x150.jpg">
                                        </div>

                                        <header class="entry-header">
                                            <h6 class="entry-title">A Piece of Art in Utrecht, Netherlands</h6>
                                        </header>
                                        <a class="u-permalink" href="../justified_gallery_post.html"></a>
                                    </article>
                                </div>
                            </div>
                        </div>
                        <div id="magsy_social_widget-2" class="widget widget_magsy_social_widget"><h5
                                    class="widget-title">Social Links</h5>
                            <div class="links">
                                <a style="background-color: #3b5998;" href="http://facebook.com"
                                   target="_blank">
                                    <i class="mdi mdi-facebook"></i>
                                    <span>Facebook</span>
                                </a>
                                <a style="background-color: #1da1f2;" href="http://twitter.com" target="_blank">
                                    <i class="mdi mdi-twitter"></i>
                                    <span>Twitter</span>
                                </a>
                                <a style="background-color: #e1306c;" href="http://instagram.com"
                                   target="_blank">
                                    <i class="mdi mdi-instagram"></i>
                                    <span>Instagram</span>
                                </a>
                                <a style="background-color: #1ab7ea;" href="http://vimeo.com" target="_blank">
                                    <i class="mdi mdi-vimeo"></i>
                                    <span>Vimeo</span>
                                </a>
                            </div>
                        </div>
                    </aside>
                </div>
            </div>
        </div>
    </div>

<?php if ($footerTopCategory instanceof CategoryProvider && count($footerTopCategory->getPosts(4))): ?>
    <div class="section">
        <div class="footer-block">
            <div class="container">
                <h3 class="section-title">
                    <?php
                    $cat = Config::get(Config::CONFIG_FOOTER_TOP_POSTS);
                    $cat = CategoryProvider::findOne($cat); ?>
                    <span><?= $cat->name ?></span>
                </h3>
                <div class="module grid u-module-margin">
                    <div class="row">
                        <?php foreach ($footerTopCategory->getPosts(4) as $post): ?>
                            <div class="col-sm-6 col-md-4 col-lg-3">
                                <article
                                        class="post post-88 type-post status-publish format-standard has-post-thumbnail hentry category-design">
                                    <div class="entry-media">
                                        <div class="placeholder" style="padding-bottom: 66.75%;">
                                            <a href="<?= $post->getViewUrl() ?>">
                                                <img class="lazyload"
                                                     data-srcset="
                                             <?= $post->getCroppedImage(400, 267) ?> 400w,
                                             <?= $post->getCroppedImage(300, 200) ?> 300w,
                                             <?= $post->getCroppedImage(768, 512) ?> 768w,
                                             <?= $post->getCroppedImage(1024, 683) ?> 1024w,
                                             <?= $post->getCroppedImage(30, 20) ?> 30w,
                                             <?= $post->getCroppedImage(800, 533) ?> 800w,
                                             <?= $post->getCroppedImage(1160, 773) ?> 1160w"
                                                     data-sizes="auto"
                                                     src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=="
                                                     alt="">
                                            </a>
                                        </div>
                                    </div>
                                    <header class="entry-header">
                                        <h2 class="entry-title">
                                            <a href="<?= $post->getViewUrl() ?>" rel="bookmark"><?= $post->title ?></a>
                                        </h2>
                                    </header>
                                    <div class="entry-excerpt u-text-format">
                                        <?= $post->info ?>
                                    </div>
                                    <div class="entry-footer">
                                        <a href="<?= $post->getViewUrl() ?>">
                                            <time datetime="<?= $post->getPublishedTimeIso() ?>">
                                                <?= $post->getPublishedOnSeconds() ?>
                                            </time>
                                        </a>
                                    </div>
                                </article>
                            </div>
                        <?php endforeach; ?>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>