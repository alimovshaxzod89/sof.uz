<?php

use frontend\components\ScrollPager;
use frontend\components\View;
use frontend\models\PostProvider;
use yii\data\ActiveDataProvider;
use yii\widgets\ListView;
use yii\widgets\Pjax;

/**
 * @var $this         View
 * @var $model        \common\models\Tag
 * @var $dataProvider ActiveDataProvider
 */
$this->_canonical = $model->getViewUrl(true);
$this->title      = $model->name;
$limit            = intval(Yii::$app->request->get('limit', 12));
?>
<div class="ts-row cf">
    <div class="col-8 main-content cf">
        <h5 class="widget-title">
            <span><?= __('"{query}"', ['query' => $model->name]) ?></span>
        </h5>
        <?php Pjax::begin(['timeout' => 10000, 'options' => []]) ?>
        <?= ListView::widget([
            'dataProvider' => PostProvider::getPostsByTag($model, $limit),
            'options'      => [
                'tag' => false,
            ],
            'itemOptions'  => [
                'tag' => false,
            ],
            'viewParams'   => [
                'search' => $model->name,
            ],
            'layout'       => $this->render('partials/_layout_search'),
            'itemView'     => 'partials/_search',
            'emptyText'    => __('Ushbu tegga aloqador maqolalar mavjud emas'),
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