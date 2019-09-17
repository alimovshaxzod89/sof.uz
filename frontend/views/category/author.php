<?php

use frontend\components\ScrollPager;
use frontend\components\View;
use frontend\models\PostProvider;
use yii\widgets\ListView;
use yii\widgets\Pjax;

/**
 * @var $this View
 * @var $model \common\models\Admin
 */
$this->_canonical              = $model->getViewUrl();
$this->title                   = $model->full_name;
$exclude                       = [];
$this->params['breadcrumbs'][] = $this->title;
$limit                         = 12;
?>
<div class="site-content">
    <div class="container">

        <div class="row">
            <div class="col-lg-12">
                <div class="content-area">
                    <?= \frontend\widgets\Banner::widget([
                                                             'place'   => 'before_main',
                                                             'options' => ['class' => 'ads-wrapper']
                                                         ]) ?>

                    <div class="author-inbox">
                        <div class="media-info">
                            <div class="media shrink is_left">
                                <a data-pjax="0" href="<?= $model->getViewUrl() ?>">
                                    <img src="<?= $model->getCroppedImage(90, 90, 1) ?>" width="90"
                                         height="90" alt="<?= $model->getFullName() ?>">
                                </a>
                            </div>
                            <div class="info auto">
                                <a data-pjax="0" href="<?= $model->getViewUrl() ?>">
                                    <p class="title"><strong><?= $model->getFullName() ?></strong></p>
                                </a>
                                <p><?= $model->description ?></p>
                            </div>
                        </div>
                    </div>

                    <?php Pjax::begin(['timeout' => 10000, 'enablePushState' => false]) ?>
                    <?= ListView::widget([
                                             'dataProvider' => PostProvider::getAuthorPosts($model, $limit),
                                             'options'      => [
                                                 'tag' => false,
                                             ],
                                             'itemOptions'  => [
                                                 'tag'   => 'div',
                                                 'class' => 'col-md-6',
                                             ],
                                             'viewParams'   => [
                                                 'empty' => PostProvider::getEmptyCroppedImage(370, 220),
                                                 'limit' => $limit,
                                                 'load'  => Yii::$app->request->get('load', $limit),
                                             ],
                                             'layout'       => "<main class=\"site-main\">
                                                                    <div class=\"row posts-wrapper\">{items}</div>
                                                                    <div class=\"infinite-scroll-action\">{pager}</div>
                                                                </main>",
                                             'itemView'     => 'partials/_view',
                                             'emptyText'    => __('Ushbu bo\'limda yangiliklar yo\'q'),
                                             'pager'        => [
                                                 'perLoad' => $limit,
                                                 'class'   => ScrollPager::class,
                                                 'options' => ['class' => 'infinite-scroll-button button']
                                             ],
                                         ]) ?>
                    <?php Pjax::end() ?>
                </div>
            </div>
        </div>
    </div>
</div>