<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2017. ActiveMedia Solutions LLC
 * @author    Rustam Mamadaminov <rmamdaminov@gmail.com>
 */

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
?>
<div class="ts-row cf">
    <div class="col-8 main-content cf">
        <h5 class="widget-title">
            <span><?= __('"{query}" bo\'yicha qidiruv', ['query' => \yii\helpers\Html::encode($search)]) ?></span>
        </h5>
        <?php Pjax::begin(['timeout' => 10000, 'options' => []]) ?>
        <?= ListView::widget([
            'dataProvider' => PostProvider::getPostsByQuery($search, $limit),
            'options'      => [
                'tag' => false,
            ],
            'itemOptions'  => [
                'tag' => false,
            ],
            'viewParams'   => [
                'empty'  => $empty,
                'search' => $search,
            ],
            'layout'       => $this->render('partials/_layout_search'),
            'itemView'     => 'partials/_search',
            'emptyText'    => __('Ushbu qidiruv bo\'yicha natija yo\'q'),
            'pager'        => [
                'perLoad' => $limit,
                'class'   => ScrollPager::class,
            ],
        ]) ?>
        <?php Pjax::end() ?>
    </div>

    <aside class="col-4 sidebar mb-45" data-sticky="1">
        <?= $this->render('/layouts/partials/sidebar.php', ['exclude' => []]) ?>
    </aside>

</div>
