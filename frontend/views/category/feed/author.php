<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2017. ActiveMedia Solutions LLC
 * @author    Rustam Mamadaminov <rmamdaminov@gmail.com>
 */

use common\models\Post;
use frontend\components\ScrollPager;
use frontend\components\View;
use frontend\models\PostProvider;
use yii\data\ActiveDataProvider;
use yii\widgets\ListView;
use yii\widgets\Pjax;

/**
 * @var $this         View
 * @var $model        \common\models\Blogger
 * @var $news         PostProvider[]
 * @var $dataProvider ActiveDataProvider
 */
$this->_canonical              = $model->getViewUrl();
$this->title                   = __('{author}ning maqolalari', ['author' => $model->fullname]);
$exclude                       = [];
$this->params['breadcrumbs'][] = $this->title;
$limit                         = intval(Yii::$app->request->get('limit', 12));
$empty                         = Post::getEmptyCroppedImage(170, 100);
?>

<div class="ts-row cf">
    <aside class="col-4 sidebar sidebar-left mb-45" data-sticky="1">
        <div class="inner">
            <ul>
                <li class="widget widget-author widget-card no-margins-md">
                    <div class="author-box">
                        <div class="image">
                            <img alt='<?= $model->fullname ?>'
                                 src='<?= $model->getImageUrl(82, 82) ?>'
                                 class='avatar avatar-82 photo'/>
                        </div>
                        <div class="content">
                            <h3 >
                                <span><?= $model->fullname ?></span>
                            </h3>

                            <p class="text author-bio">
                                <?= $model->intro ?>
                            </p>
                            <ul class="social-icons">
                                <?php if ($model->email): ?>
                                    <li>
                                        <a href="mailto:<?= $model->email ?>" class="ui-mail" title="Email">
                                            <span class="visuallyhidden">Email</span>
                                        </a>
                                    </li>
                                <?php endif; ?>
                                <?php if ($model->facebook): ?>
                                    <li>
                                        <a href="<?= $model->facebook ?>" class="ui-facebook" title="Facebook">
                                            <span class="visuallyhidden">Facebook</span>
                                        </a>
                                    </li>
                                <?php endif; ?>
                                <?php if ($model->twitter): ?>
                                    <li>
                                        <a href="<?= $model->twitter ?>" class="ui-twitter" title="Twitter">
                                            <span class="visuallyhidden">Twitter</span>
                                        </a>
                                    </li>
                                <?php endif; ?>
                                <?php if ($model->telegram): ?>
                                    <li>
                                        <a href="<?= $model->telegram ?>" class="ui-paper-plane"
                                           title="Telegram">
                                            <span class="visuallyhidden">Telegram</span>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                </li>
                <?php if ($middle = \frontend\widgets\Banner::widget(['place' => 'sidebar_middle'])): ?>
                    <li class="widget widget-a-wrap no-940">
                        <?= $middle ?>
                    </li>
                <?php endif ?>
            </ul>
        </div>
    </aside>

    <div class="col-8 main-content cf">
        <?php Pjax::begin(['timeout' => 10000, 'options' => []]) ?>
        <?= ListView::widget([
            'dataProvider' => PostProvider::getAuthorPosts($model, $limit),
            'options'      => [
                'tag' => false,
            ],
            'itemOptions'  => [
                'tag' => false,
            ],
            'viewParams'   => [
                'empty' => $empty,
                'limit' => $limit,
                'load'  => Yii::$app->getRequest()->get('load', $limit),
            ],
            'layout'       => $this->render('partials/_layout_minbarda'),
            'itemView'     => 'partials/_author',
            'emptyText'    => __('Ushbu bo\'limda yangiliklar yo\'q'),
            'pager'        => [
                'perLoad' => $limit,
                'class'   => ScrollPager::class,
            ],
        ]) ?>
        <?php Pjax::end() ?>
    </div>
</div>