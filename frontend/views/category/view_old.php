<?php

use frontend\components\ScrollPager;
use frontend\components\View;
use frontend\models\CategoryProvider;
use frontend\models\PostProvider;
use frontend\models\TagProvider;
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
?>
<div class="container">
    <div class="row">
        <div class="col-md-9">
            <h5 class="u-border-title">
                <?= $this->title ?>
            </h5>
            <?php Pjax::begin(['timeout' => 10000, 'enablePushState' => false]) ?>
            <?= ListView::widget([
                'dataProvider' => $provider,
                'options' => [
                    'tag' => false,
                ],
                'itemOptions' => [
                    'tag' => 'div',
                    'class' => 'col-md-12',
                ],
                'viewParams' => [
                    'empty' => PostProvider::getEmptyCroppedImage(370, 220),
                    'limit' => $limit,
                    'load' => Yii::$app->request->get('load', $limit),
                ],
                'layout' => "<div class=\"row posts-wrapper\">{items}</div><div class=\"infinite-scroll-action\">{pager}</div>",
                'itemView' => 'partials/_view',
                'emptyText' => __('Ushbu bo\'limda yangiliklar yo\'q'),
                'pager' => [
                    'perLoad' => $limit,
                    'class' => ScrollPager::class,
                    'options' => ['class' => 'infinite-scroll-button button']
                ],
            ]) ?>
            <?php Pjax::end() ?>
        </div>
        <div class="col-md-3" id="sticky-sidebar">
            <?= $this->renderFile('@frontend/views/layouts/partials/sidebar.php', [
                'model' => null
            ]) ?>
        </div>
    </div>
</div>