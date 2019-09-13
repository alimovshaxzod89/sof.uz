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
<div class="advert-section">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?= \frontend\widgets\Banner::widget([
                                                         'place'   => 'before_main',
                                                         'options' => ['class' => 'ads-wrapper']
                                                     ]) ?>
            </div>
        </div>
    </div>
</div>

<div class="site-content">
    <div class="container">
        <div class="row">
            <div class="content-column col-lg-9">
                <div class="content-area">
                    <main class="site-main">
                        <h5 class="u-border-title">
                            <?= $this->title ?>
                        </h5>
                        <?php Pjax::begin(['timeout' => 10000, 'enablePushState' => false]) ?>
                        <?= ListView::widget([
                                                 'dataProvider' => PostProvider::getPostsByQuery($search, $limit),
                                                 'options'      => [
                                                     'class' => 'row posts-wrapper',
                                                 ],
                                                 'itemOptions'  => [
                                                     'tag'   => 'div',
                                                     'class' => 'col-md-12',
                                                 ],
                                                 'viewParams'   => [
                                                     'empty' => $empty,
                                                     'limit' => $limit,
                                                     'load'  => Yii::$app->request->get('load', $limit),
                                                 ],
                                                 'layout'       => "{items}<div class=\"infinite-scroll-action\">{pager}</div>",
                                                 'itemView'     => 'partials/_view',
                                                 'emptyText'    => __("Ушбу қидирув бўйича натижа йўқ"),
                                                 'pager'        => [
                                                     'perLoad' => $limit,
                                                     'class'   => ScrollPager::class,
                                                     'options' => ['class' => 'infinite-scroll-button button']
                                                 ],
                                             ]) ?>
                        <?php Pjax::end() ?>
                    </main>
                </div>
            </div>

            <div class="sidebar-column col-lg-3" id="sticky-sidebar">
                <?= $this->renderFile('@frontend/views/layouts/partials/sidebar.php', [
                    'model' => null
                ]) ?>
            </div>
        </div>
    </div>
</div>