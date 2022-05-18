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
$limit = intval(Yii::$app->request->get('limit', 12));
$empty = Post::getEmptyCroppedImage(205, 165);
$this->addBodyClass('search-page');
?>

<!--- Block -->
<div class="news_block_search">

    <div class="latest_news">
        <div class="latest_title">
            <div class="icon"></div>
            <h4 class="title_con"><?= $this->title ?></h4>
        </div>

        <div class="mini_news">

            <?php Pjax::begin(['timeout' => 10000, 'enablePushState' => false, 'options' => ['class' => 'st_block_search']]) ?>
            <?= ListView::widget([
                'dataProvider' => PostProvider::getPostsByQuery($search, $limit),
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
                'layout' => "{items}<div class=\"infinite-scroll-action\">{pager}</div>",
                'itemView' => 'partials/_view',
                'emptyText' => __('Topilmadi'),
                'pager' => [
                    'perLoad' => $limit,
                    'class' => ScrollPager::class,
                    'options' => ['class' => 'infinite-scroll-button button']
                ],
            ]) ?>
            <?php Pjax::end() ?>

        </div>

        <div class="mini_news_nd">
        </div>

    </div>

    <?= $this->render('//site/partials/_index_right_side') ?>
</div>
