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
<div class="term-bar lazyload visible"
     data-bg="<?= $this->getImageUrl('images/002.jpg') ?>">
    <h1 class="term-title">
        <?= __('Author: {author}', [
            'author' => '<span class="vcard">' . $this->title . '</span>'
        ]) ?>
    </h1>
</div>
<div class="site-content">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="content-area">
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