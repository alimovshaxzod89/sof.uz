<?php

use common\models\Post;
use frontend\components\View;

/**
 * @var $this View
 * @var $model \frontend\models\CategoryProvider
 */
$this->_canonical              = $model->getViewUrl();
$this->title                   = $model->name;
$exclude                       = [];
$this->params['breadcrumbs'][] = $this->title;
$limit                         = intval(Yii::$app->request->get('limit', 12));
$empty                         = Post::getEmptyCroppedImage(370, 220);
$this->addBodyClass('category-' . $model->slug)
?>
<div class="term-bar lazyload visible"
     data-bg="2018/07/andres-jasso-220776-unsplash.jpg">
    <h1 class="term-title">Author: <span class="vcard">Kathryn Hughes</span></h1>
</div>
<div class="site-content">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="content-area">
                    <main class="site-main">
                        <div class="row posts-wrapper">

                            <div class="col-lg-6">
                                <article id="post-96"
                                         class="post post-list post-96 type-post status-publish format-standard has-post-thumbnail hentry category-design">

                                    <div class="entry-media">
                                        <div class="placeholder" style="padding-bottom: 66.666666666667%;">
                                            <a href="index.html%3Fp=96.html">
                                                <img class="lazyload"
                                                     data-srcset="2018/07/andres-jasso-220776-unsplash-300x200.jpg 300w, 2018/07/andres-jasso-220776-unsplash-30x20.jpg 30w"
                                                     data-sizes="auto"
                                                     src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=="
                                                     alt="">
                                            </a>
                                        </div>
                                    </div>
                                    <div class="entry-wrapper">

                                        <header class="entry-header">
                                            <div class="entry-meta">
                  <span class="meta-category">
                          <a href="category/design/index.html" rel="category">
                                  <i class="dot" style="background-color: #ff7473;"></i>
                Design              </a>
                      </span>
                                            </div>

                                            <h2 class="entry-title"><a href="index.html%3Fp=96.html"
                                                                       rel="bookmark">Black Eames Style Chair</a>
                                            </h2></header>
                                        <div class="entry-excerpt u-text-format">
                                            <p>Leverage agile frameworks to provide a robust synopsis for high level
                                                overviews.<span class="excerpt-more"></span></p>
                                        </div>
                                        <div class="entry-footer">
                                            <a href="index.html%3Fp=96.html">
                                                <time datetime="2018-09-29T06:31:57+00:00">
                                                    4 months ago
                                                </time>
                                            </a>
                                        </div>
                                    </div>
                                </article>
                            </div>
                            <div class="col-lg-6">
                                <article id="post-90"
                                         class="post post-list post-90 type-post status-publish format-standard has-post-thumbnail hentry category-design">

                                    <div class="entry-media">
                                        <div class="placeholder" style="padding-bottom: 66.666666666667%;">
                                            <a href="index.html%3Fp=90.html">
                                                <img class="lazyload"
                                                     data-srcset="2018/07/marion-michele-330691-unsplash-300x200.jpg 300w, 2018/07/marion-michele-330691-unsplash-768x512.jpg 768w, 2018/07/marion-michele-330691-unsplash-1024x683.jpg 1024w, 2018/07/marion-michele-330691-unsplash-30x20.jpg 30w, 2018/07/marion-michele-330691-unsplash-400x267.jpg 400w, 2018/07/marion-michele-330691-unsplash-800x533.jpg 800w, 2018/07/marion-michele-330691-unsplash-1160x773.jpg 1160w"
                                                     data-sizes="auto"
                                                     src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=="
                                                     alt="">
                                            </a>
                                        </div>
                                    </div>
                                    <div class="entry-wrapper">

                                        <header class="entry-header">
                                            <div class="entry-meta">
                  <span class="meta-category">
                          <a href="category/design/index.html" rel="category">
                                  <i class="dot" style="background-color: #ff7473;"></i>
                Design              </a>
                      </span>
                                            </div>

                                            <h2 class="entry-title"><a href="index.html%3Fp=90.html"
                                                                       rel="bookmark">Café Buho Branding</a></h2>
                                        </header>
                                        <div class="entry-excerpt u-text-format">
                                            <p>Leverage agile frameworks to provide a robust synopsis for high level
                                                overviews.<span class="excerpt-more"></span></p>
                                        </div>
                                        <div class="entry-footer">
                                            <a href="index.html%3Fp=90.html">
                                                <time datetime="2018-09-28T21:31:57+00:00">
                                                    4 months ago
                                                </time>
                                            </a>
                                        </div>
                                    </div>
                                </article>
                            </div>
                            <div class="col-lg-6">
                                <article id="post-100"
                                         class="post post-list post-100 type-post status-publish format-gallery has-post-thumbnail hentry category-design post_format-post-format-gallery">

                                    <div class="entry-media">
                                        <div class="placeholder" style="padding-bottom: 66.666666666667%;">
                                            <a href="justified_gallery_post.html">
                                                <img class="lazyload"
                                                     data-srcset="2018/07/alex-378877-unsplash-300x200.jpg 300w, 2018/07/alex-378877-unsplash-768x512.jpg 768w, 2018/07/alex-378877-unsplash-1024x683.jpg 1024w, 2018/07/alex-378877-unsplash-30x20.jpg 30w, 2018/07/alex-378877-unsplash-400x267.jpg 400w, 2018/07/alex-378877-unsplash-800x533.jpg 800w, 2018/07/alex-378877-unsplash-1160x773.jpg 1160w"
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
                                            <div class="entry-meta">
                  <span class="meta-category">
                          <a href="category/design/index.html" rel="category">
                                  <i class="dot" style="background-color: #ff7473;"></i>
                Design              </a>
                      </span>
                                            </div>

                                            <h2 class="entry-title"><a href="justified_gallery_post.html"
                                                                       rel="bookmark">A Piece of Art in Utrecht,
                                                    Netherlands</a></h2></header>
                                        <div class="entry-excerpt u-text-format">
                                            <p>Leverage agile frameworks to provide a robust synopsis for high level
                                                overviews.<span class="excerpt-more"></span></p>
                                        </div>
                                        <div class="entry-footer">
                                            <a href="justified_gallery_post.html">
                                                <time datetime="2018-09-28T12:31:57+00:00">
                                                    4 months ago
                                                </time>
                                            </a>
                                        </div>
                                    </div>
                                </article>
                            </div>
                            <div class="col-lg-6">
                                <article id="post-172"
                                         class="post post-list post-172 type-post status-publish format-standard has-post-thumbnail hentry category-fashion">

                                    <div class="entry-media">
                                        <div class="placeholder" style="padding-bottom: 66.666666666667%;">
                                            <a href="index.html%3Fp=172.html">
                                                <img class="lazyload"
                                                     data-srcset="2018/08/priscilla-du-preez-228220-unsplash-300x200.jpg 300w, 2018/08/priscilla-du-preez-228220-unsplash-768x512.jpg 768w, 2018/08/priscilla-du-preez-228220-unsplash-1024x683.jpg 1024w, 2018/08/priscilla-du-preez-228220-unsplash-30x20.jpg 30w, 2018/08/priscilla-du-preez-228220-unsplash-400x267.jpg 400w, 2018/08/priscilla-du-preez-228220-unsplash-800x533.jpg 800w, 2018/08/priscilla-du-preez-228220-unsplash-1160x773.jpg 1160w"
                                                     data-sizes="auto"
                                                     src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=="
                                                     alt="">
                                            </a>
                                        </div>
                                    </div>
                                    <div class="entry-wrapper">

                                        <header class="entry-header">
                                            <div class="entry-meta">
                  <span class="meta-category">
                          <a href="category_fashion.html" rel="category">
                                  <i class="dot" style="background-color: #d1b6e1;"></i>
                Fashion              </a>
                      </span>
                                            </div>

                                            <h2 class="entry-title"><a href="index.html%3Fp=172.html"
                                                                       rel="bookmark">Designing a Closet with a
                                                    Budget in Mind</a></h2></header>
                                        <div class="entry-excerpt u-text-format">
                                            <p>Leverage agile frameworks to provide a robust synopsis for high level
                                                overviews.<span class="excerpt-more"></span></p>
                                        </div>
                                        <div class="entry-footer">
                                            <a href="index.html%3Fp=172.html">
                                                <time datetime="2018-09-28T03:31:57+00:00">
                                                    4 months ago
                                                </time>
                                            </a>
                                        </div>
                                    </div>
                                </article>
                            </div>
                            <div class="col-lg-6">
                                <article id="post-38"
                                         class="post post-list post-38 type-post status-publish format-standard has-post-thumbnail hentry category-interior">

                                    <div class="entry-media">
                                        <div class="placeholder" style="padding-bottom: 66.666666666667%;">
                                            <a href="index.html%3Fp=38.html">
                                                <img class="lazyload"
                                                     data-srcset="2018/07/jean-philippe-delberghe-383612-unsplash-300x200.jpg 300w, 2018/07/jean-philippe-delberghe-383612-unsplash-30x20.jpg 30w"
                                                     data-sizes="auto"
                                                     src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=="
                                                     alt="">
                                            </a>
                                        </div>
                                    </div>
                                    <div class="entry-wrapper">

                                        <header class="entry-header">
                                            <div class="entry-meta">
                  <span class="meta-category">
                          <a href="category_interior.html" rel="category">
                                  <i class="dot" style="background-color: #7cbef1;"></i>
                Interior              </a>
                      </span>
                                            </div>

                                            <h2 class="entry-title"><a href="index.html%3Fp=38.html"
                                                                       rel="bookmark">Modern Classic Interiors
                                                    Tour</a></h2></header>
                                        <div class="entry-excerpt u-text-format">
                                            <p>Leverage agile frameworks to provide a robust synopsis for high level
                                                overviews.<span class="excerpt-more"></span></p>
                                        </div>
                                        <div class="entry-footer">
                                            <a href="index.html%3Fp=38.html">
                                                <time datetime="2018-09-27T18:31:57+00:00">
                                                    4 months ago
                                                </time>
                                            </a>
                                        </div>
                                    </div>
                                </article>
                            </div>
                            <div class="col-lg-6">
                                <article id="post-51"
                                         class="post post-list post-51 type-post status-publish format-standard has-post-thumbnail hentry category-food">

                                    <div class="entry-media">
                                        <div class="placeholder" style="padding-bottom: 66.666666666667%;">
                                            <a href="vertical_thumbnail.html">
                                                <img class="lazyload"
                                                     data-srcset="2018/08/tyler-nix-466138-unsplash-300x200.jpg 300w, 2018/08/tyler-nix-466138-unsplash-30x20.jpg 30w"
                                                     data-sizes="auto"
                                                     src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=="
                                                     alt="">
                                            </a>
                                        </div>
                                    </div>
                                    <div class="entry-wrapper">

                                        <header class="entry-header">
                                            <div class="entry-meta">
                  <span class="meta-category">
                          <a href="category/food/index.html" rel="category">
                                  <i class="dot" style="background-color: #e7c291;"></i>
                Food              </a>
                      </span>
                                            </div>

                                            <h2 class="entry-title"><a href="vertical_thumbnail.html"
                                                                       rel="bookmark">Top Instagrammable Coffee
                                                    Shops</a></h2></header>
                                        <div class="entry-excerpt u-text-format">
                                            <p>Leverage agile frameworks to provide a robust synopsis for high level
                                                overviews.<span class="excerpt-more"></span></p>
                                        </div>
                                        <div class="entry-footer">
                                            <a href="vertical_thumbnail.html">
                                                <time datetime="2018-09-27T09:31:57+00:00">
                                                    4 months ago
                                                </time>
                                            </a>
                                        </div>
                                    </div>
                                </article>
                            </div>
                            <div class="col-lg-6">
                                <article id="post-162"
                                         class="post post-list post-162 type-post status-publish format-standard has-post-thumbnail hentry category-fashion">

                                    <div class="entry-media">
                                        <div class="placeholder" style="padding-bottom: 66.666666666667%;">
                                            <a href="index.html%3Fp=162.html">
                                                <img class="lazyload"
                                                     data-srcset="2018/08/andrew-neel-369701-unsplash-300x200.jpg 300w, 2018/08/andrew-neel-369701-unsplash-768x512.jpg 768w, 2018/08/andrew-neel-369701-unsplash-1024x683.jpg 1024w, 2018/08/andrew-neel-369701-unsplash-30x20.jpg 30w, 2018/08/andrew-neel-369701-unsplash-400x267.jpg 400w, 2018/08/andrew-neel-369701-unsplash-800x533.jpg 800w, 2018/08/andrew-neel-369701-unsplash-1160x773.jpg 1160w"
                                                     data-sizes="auto"
                                                     src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=="
                                                     alt="">
                                            </a>
                                        </div>
                                    </div>
                                    <div class="entry-wrapper">

                                        <header class="entry-header">
                                            <div class="entry-meta">
                  <span class="meta-category">
                          <a href="category_fashion.html" rel="category">
                                  <i class="dot" style="background-color: #d1b6e1;"></i>
                Fashion              </a>
                      </span>
                                            </div>

                                            <h2 class="entry-title"><a href="index.html%3Fp=162.html"
                                                                       rel="bookmark">Men&#8217;s Suits Are Back in
                                                    Trend</a></h2></header>
                                        <div class="entry-excerpt u-text-format">
                                            <p>Leverage agile frameworks to provide a robust synopsis for high level
                                                overviews.<span class="excerpt-more"></span></p>
                                        </div>
                                        <div class="entry-footer">
                                            <a href="index.html%3Fp=162.html">
                                                <time datetime="2018-09-27T00:31:57+00:00">
                                                    4 months ago
                                                </time>
                                            </a>
                                        </div>
                                    </div>
                                </article>
                            </div>
                            <div class="col-lg-6">
                                <article id="post-92"
                                         class="post post-list post-92 type-post status-publish format-video has-post-thumbnail hentry category-design tag-featured post_format-video">

                                    <div class="entry-media">
                                        <div class="placeholder" style="padding-bottom: 66.666666666667%;">
                                            <a href="video_post.html">
                                                <img class="lazyload"
                                                     data-srcset="2018/08/roman-kraft-73631-unsplash-300x200.jpg 300w, 2018/08/roman-kraft-73631-unsplash-30x20.jpg 30w"
                                                     data-sizes="auto"
                                                     src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=="
                                                     alt="">
                                            </a>
                                        </div>
                                        <div class="entry-format">
                                            <i class="mdi mdi-youtube-play"></i>
                                        </div>
                                    </div>
                                    <div class="entry-wrapper">

                                        <header class="entry-header">
                                            <div class="entry-meta">
                  <span class="meta-category">
                          <a href="category/design/index.html" rel="category">
                                  <i class="dot" style="background-color: #ff7473;"></i>
                Design              </a>
                      </span>
                                            </div>

                                            <h2 class="entry-title"><a href="video_post.html" rel="bookmark">Street
                                                    Art in Camden Town</a></h2></header>
                                        <div class="entry-excerpt u-text-format">
                                            <p>Leverage agile frameworks to provide a robust synopsis for high level
                                                overviews.<span class="excerpt-more"></span></p>
                                        </div>
                                        <div class="entry-footer">
                                            <a href="video_post.html">
                                                <time datetime="2018-09-26T15:31:57+00:00">
                                                    4 months ago
                                                </time>
                                            </a>
                                        </div>
                                    </div>
                                </article>
                            </div>
                            <div class="col-lg-6">
                                <article id="post-20"
                                         class="post post-list post-20 type-post status-publish format-standard has-post-thumbnail hentry category-interior">

                                    <div class="entry-media">
                                        <div class="placeholder" style="padding-bottom: 66.666666666667%;">
                                            <a href="index.html%3Fp=20.html">
                                                <img class="lazyload"
                                                     data-srcset="2018/07/jonny-caspari-483355-unsplash-300x200.jpg 300w, 2018/07/jonny-caspari-483355-unsplash-768x512.jpg 768w, 2018/07/jonny-caspari-483355-unsplash-1024x683.jpg 1024w, 2018/07/jonny-caspari-483355-unsplash-30x20.jpg 30w, 2018/07/jonny-caspari-483355-unsplash-400x267.jpg 400w, 2018/07/jonny-caspari-483355-unsplash-800x533.jpg 800w, 2018/07/jonny-caspari-483355-unsplash-1160x773.jpg 1160w"
                                                     data-sizes="auto"
                                                     src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=="
                                                     alt="">
                                            </a>
                                        </div>
                                    </div>
                                    <div class="entry-wrapper">

                                        <header class="entry-header">
                                            <div class="entry-meta">
                  <span class="meta-category">
                          <a href="category_interior.html" rel="category">
                                  <i class="dot" style="background-color: #7cbef1;"></i>
                Interior              </a>
                      </span>
                                            </div>

                                            <h2 class="entry-title"><a href="index.html%3Fp=20.html"
                                                                       rel="bookmark">Everything You Need to Know
                                                    About Buying Art in Your 20s</a></h2></header>
                                        <div class="entry-excerpt u-text-format">
                                            <p>Leverage agile frameworks to provide a robust synopsis for high level
                                                overviews.<span class="excerpt-more"></span></p>
                                        </div>
                                        <div class="entry-footer">
                                            <a href="index.html%3Fp=20.html">
                                                <time datetime="2018-09-26T06:31:57+00:00">
                                                    4 months ago
                                                </time>
                                            </a>
                                        </div>
                                    </div>
                                </article>
                            </div>
                            <div class="col-lg-6">
                                <article id="post-53"
                                         class="post post-list post-53 type-post status-publish format-standard has-post-thumbnail hentry category-food">

                                    <div class="entry-media">
                                        <div class="placeholder" style="padding-bottom: 66.666666666667%;">
                                            <a href="index.html%3Fp=53.html">
                                                <img class="lazyload"
                                                     data-srcset="2018/07/brooke-lark-331977-unsplash-300x200.jpg 300w, 2018/07/brooke-lark-331977-unsplash-30x20.jpg 30w"
                                                     data-sizes="auto"
                                                     src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=="
                                                     alt="">
                                            </a>
                                        </div>
                                    </div>
                                    <div class="entry-wrapper">

                                        <header class="entry-header">
                                            <div class="entry-meta">
                  <span class="meta-category">
                          <a href="category/food/index.html" rel="category">
                                  <i class="dot" style="background-color: #e7c291;"></i>
                Food              </a>
                      </span>
                                            </div>

                                            <h2 class="entry-title"><a href="index.html%3Fp=53.html"
                                                                       rel="bookmark">Healthy Breakfast Recipes That
                                                    Help You Lose Weight</a></h2></header>
                                        <div class="entry-excerpt u-text-format">
                                            <p>Leverage agile frameworks to provide a robust synopsis for high level
                                                overviews.<span class="excerpt-more"></span></p>
                                        </div>
                                        <div class="entry-footer">
                                            <a href="index.html%3Fp=53.html">
                                                <time datetime="2018-09-25T21:31:57+00:00">
                                                    4 months ago
                                                </time>
                                            </a>
                                        </div>
                                    </div>
                                </article>
                            </div>
                            <div class="col-lg-6">
                                <article id="post-9"
                                         class="post post-list post-9 type-post status-publish format-standard has-post-thumbnail hentry category-interior">

                                    <div class="entry-media">
                                        <div class="placeholder" style="padding-bottom: 66.666666666667%;">
                                            <a href="index.html%3Fp=9.html">
                                                <img class="lazyload"
                                                     data-srcset="2018/07/alexandra-gorn-485551-unsplash-300x200.jpg 300w, 2018/07/alexandra-gorn-485551-unsplash-768x513.jpg 768w, 2018/07/alexandra-gorn-485551-unsplash-1024x684.jpg 1024w, 2018/07/alexandra-gorn-485551-unsplash-30x20.jpg 30w, 2018/07/alexandra-gorn-485551-unsplash-400x267.jpg 400w, 2018/07/alexandra-gorn-485551-unsplash-800x534.jpg 800w, 2018/07/alexandra-gorn-485551-unsplash-1160x775.jpg 1160w"
                                                     data-sizes="auto"
                                                     src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw=="
                                                     alt="">
                                            </a>
                                        </div>
                                    </div>
                                    <div class="entry-wrapper">

                                        <header class="entry-header">
                                            <div class="entry-meta">
                  <span class="meta-category">
                          <a href="category_interior.html" rel="category">
                                  <i class="dot" style="background-color: #7cbef1;"></i>
                Interior              </a>
                      </span>
                                            </div>

                                            <h2 class="entry-title"><a href="index.html%3Fp=9.html"
                                                                       rel="bookmark">Make Your Living Room
                                                    Breathing</a></h2></header>
                                        <div class="entry-excerpt u-text-format">
                                            <p>Leverage agile frameworks to provide a robust synopsis for high level
                                                overviews.<span class="excerpt-more"></span></p>
                                        </div>
                                        <div class="entry-footer">
                                            <a href="index.html%3Fp=9.html">
                                                <time datetime="2018-09-25T12:31:57+00:00">
                                                    4 months ago
                                                </time>
                                            </a>
                                        </div>
                                    </div>
                                </article>
                            </div>
                        </div>

                        <div class="infinite-scroll-status">
                            <div class="infinite-scroll-request"></div>
                        </div>
                        <div class="infinite-scroll-action">
                            <div class="infinite-scroll-button button">Load more</div>
                        </div>
                    </main>
                </div>
            </div>
        </div>
    </div>

</div>