<?php

use frontend\components\ScrollPager;
use frontend\components\View;
use frontend\models\CategoryProvider;
use frontend\models\PostProvider;
use frontend\models\TagProvider;
use yii\helpers\ArrayHelper;
use yii\widgets\LinkPager;
use yii\widgets\ListView;
use yii\widgets\Pjax;

/**
 * @var $this View
 * @var $provider \yii\data\ActiveDataProvider
 * @var $model CategoryProvider|TagProvider
 */
$limit = 12;
$this->title = $model->name;
$this->params['breadcrumbs'][] = $this->title;
$this->_canonical = $model->getViewUrl();
$this->addBodyClass('category-page');
$posts = $provider->getModels();
$post = array_shift($posts);

?>

<div class="news_block">

    <?php Pjax::begin(['timeout' => 10000, 'enablePushState' => false, 'options' => ['class' => 'latest_news']]) ?>

        <?php if ($post instanceof PostProvider): ?>

            <div class="latest_block">

                <div class="latest_title">
                    <div class="icon"></div>
                    <h4 class="title_con"><?= $post->category->name ?></h4>
                </div>

                <div class="whole_post">

                    <div class="latest_img" style='background-image: url("<?= $post->getCroppedImage(500, 350, 1) ?>")'>
                        <div class="first"></div>
                        <div class="second">
                            <a href="">
                                <div class="share"></div>
                            </a>
                            <div class="social">
                                <a href="">
                                    <div class="tg"></div>
                                </a>
                                <a href="">
                                    <div class="fc"></div>
                                </a>
                                <a href="">
                                    <div class="insta"></div>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="content">
                        <a href="<?= $post->getViewUrl() ?>">
                            <div class="title">
                                <?= $post->title ?>
                            </div>
                        </a>
                        <div class="date_post">
                            <div class="calendar_icon"></div>
                            <div class="date_text"><?= $post->getShortFormattedDate() ?></div>
                        </div>
                        <div class="paragraph">
                            <?= $post->info ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if (count($posts) > 0): ?>

            <div class="mini_news">

                <div class="st_block">

                    <?php for ($i = 0; $i < (min((count($posts)-1), 4)); $i++): ?>
                    <?php $post = $posts[$i] ?>
                    <div class="mini_post">
                        <div class="img_mini"
                             style='background-image: url("<?= $post->getCroppedImage(190, 100, 1) ?>")'>
                            <div></div>
                        </div>
                        <div class="text_mini">
                            <div class="date_post">
                                <div class="calendar_icon"></div>
                                <div class="date_text"><?= $post->getShortFormattedDate() ?></div>
                            </div>
                            <a href="<?= $post->getViewUrl() ?>"><p class="title_mini"><?= $post->title ?></p></a>
                        </div>
                    </div>
                    <?php if ($i == 1): ?>
                </div>
                <div class="st_block">
                    <?php endif; ?>

                    <?php endfor; ?>

                </div>
            </div>

        <?php endif; ?>

        <?php if (count($posts) > 4): ?>

        <div class="mini_news_nd">

            <div class="nd_block">

                <?php for ($i = 4; $i < count($posts); $i++): ?>
                    <?php $post = $posts[$i] ?>
                    <div class="<?= $i == 0 ? 'block_news_cecond' : ($i == 1 ? 'block_news_second' : 'block_news_third') ?>">
                        <div class="block_image"
                             style='background-image: url("<?= $post->getCroppedImage(500, 350, 1) ?>")'>
                            <div></div>
                        </div>
                        <div class="date_post_bold">
                            <div class="calendar_icon"></div>
                            <div class="date_text"><?= $post->getShortFormattedDate() ?></div>
                        </div>

                        <div class="paragraph_bold">
                            <a href="<?= $post->getViewUrl() ?>">
                                <?= $post->title ?>
                            </a>
                        </div>
                    </div>

                <?php if($i%3 == 0):?>
            </div>
            <div class="nd_block">
                <?php endif; ?>

                <?php endfor; ?>
            </div>
        </div>

        <?php endif; ?>

    <br/>
    <br/>
    <br/>

    <?php
//    $pagination = $this->dataProvider->getPagination();
//    if ($pagination === false || $this->dataProvider->getCount() <= 0) {
//        return '';
//    }
//    /* @var $class LinkPager */
//    $pager = $this->pager;
//    $class = ArrayHelper::remove($pager, 'class', LinkPager::className());
//    $pager['pagination'] = $pagination;
//    $pager['view'] = $this->getView();

    echo ScrollPager::widget([
        'pagination' => $provider->getPagination(),
        'options' => ['class' => 'infinite-scroll-button button', '1_pjax' => '#latest_news'],
        'perLoad' => 12,
    ]);
    ?>

    <?php Pjax::end() ?>

    <?= $this->render('//site/partials/_index_right_side') ?>

</div>