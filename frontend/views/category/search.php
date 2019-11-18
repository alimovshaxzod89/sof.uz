<?php

use common\models\Post;
use common\models\Tag;
use frontend\components\ScrollPager;
use frontend\components\View;
use frontend\models\PostProvider;
use yii\data\ActiveDataProvider;
use yii\widgets\ListView;
use yii\widgets\Pjax;

/**
 * @var        $this         View
 * @var        $model        Tag
 * @var        $news         PostProvider[]
 * @var        $dataProvider ActiveDataProvider
 * @var string $search
 */
$this->title = __('"{query}" bo\'yicha qidiruv', ['query' => $search]);
$limit       = intval(Yii::$app->request->get('limit', 12));
$empty       = Post::getEmptyCroppedImage(205, 165);
$this->addBodyClass('search-page');
?>
<div class="container">
    <div class="row">
        <div class="col-md-9">
            <h5 class="u-border-title">
                <?= $this->title ?>
            </h5>
            <?php Pjax::begin(['timeout' => 10000, 'enablePushState' => false]) ?>
            <?= ListView::widget([
                                     'dataProvider' => PostProvider::getPostsByQuery($search, $limit),
                                     'options'      => [
                                         'tag' => false,
                                     ],
                                     'itemOptions'  => [
                                         'tag'   => 'div',
                                         'class' => 'col-md-12',
                                     ],
                                     'viewParams'   => [
                                         'empty' => PostProvider::getEmptyCroppedImage(370, 220),
                                         'limit' => $limit,
                                         'load'  => Yii::$app->request->get('load', $limit),
                                     ],
                                     'layout'       => "<div class=\"row posts-wrapper\">{items}</div><div class=\"infinite-scroll-action\">{pager}</div>",
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
        <div class="col-md-3" id="sticky-sidebar">
            <?= $this->renderFile('@frontend/views/layouts/partials/sidebar.php', [
                'model' => null
            ]) ?>
        </div>
    </div>
</div>