<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2017. ActiveMedia Solutions LLC
 * @author    Rustam Mamadaminov <rmamdaminov@gmail.com>
 */

use common\models\Blogger;
use frontend\models\AuthorProvider;
use Imagine\Image\ManipulatorInterface;
use yii\helpers\Url;

/**
 * Created by PhpStorm.
 * Date: 12/13/17
 * Time: 12:38 AM
 * @var $models \frontend\models\PostProvider[]
 * @var $post \frontend\models\PostProvider
 * @var $this \frontend\widgets\SidebarPopular
 */
?>
    <h3 class="block-title">
        <span><?= __('Popular News') ?></span>
    </h3>
    <div class="post-overaly-style clearfix">
        <?php if ($post): ?>
            <div class="post-thumb">
                <a href="<?= $post->getViewUrl() ?>">
                    <img class="img-fluid"
                         src="<?= $post->getImage(330, 220) ?: $this->getImageUrl('news/lifestyle/health4.jpg') ?>"
                         alt=""/>
                </a>
            </div>

            <div class="post-content">
                <?php if ($post->category): ?>
                    <a class="post-cat" href="<?= $post->category->getViewUrl() ?>"><?= $post->category->name ?></a>
                <?php endif; ?>
                <h2 class="post-title">
                    <a href="<?= $post->getViewUrl() ?>"><?= $post->title ?></a>
                </h2>
                <div class="post-meta">
                    <span class="post-date"><?= $post->getShortFormattedDate() ?></span>
                </div>
            </div><!-- Post content end -->
        <?php else: ?>
            <code><?= $this->context->emptyText ?></code>
        <?php endif; ?>
    </div><!-- Post Overaly Article end -->
<?php if (count($models)): ?>
    <div class="list-post-block">
        <ul class="list-post">
            <?php foreach ($models as $model): ?>
                <li class="clearfix">
                    <div class="post-block-style post-float clearfix">
                        <div class="post-thumb">
                            <a href="<?= $model->getViewUrl() ?>">
                                <img class="img-fluid"
                                     src="<?= $model->getImage(100, 75) ?: $this->getImageUrl('news/tech/gadget3.jpg') ?>"
                                     alt=""/>
                            </a>
                            <?php if ($model->category): ?>
                                <a class="post-cat"
                                   href="<?= $model->category->getViewUrl() ?>"><?= $model->category->name ?></a>
                            <?php endif; ?>
                        </div><!-- Post thumb end -->
                        <div class="post-content">
                            <h2 class="post-title title-small">
                                <a href="<?= $model->getViewUrl() ?>">
                                    <?= $model->getShortTitle() ?>
                                </a>
                            </h2>
                            <div class="post-meta">
                                <span class="post-date"><?= $model->getShortFormattedDate() ?></span>
                            </div>
                        </div><!-- Post content end -->
                    </div><!-- Post block style end -->
                </li><!-- Li 1 end -->
            <?php endforeach; ?>
        </ul><!-- List post end -->
    </div><!-- List post block end -->
<?php endif; ?>