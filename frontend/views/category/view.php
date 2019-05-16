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
 * @var $model        \frontend\models\CategoryProvider
 * @var $news         PostProvider[]
 * @var $dataProvider ActiveDataProvider
 */
$this->_canonical              = $model->getViewUrl();
$this->title                   = $model->name;
$exclude                       = [];
$this->params['breadcrumbs'][] = $this->title;
$limit                         = intval(Yii::$app->request->get('limit', 12));
$empty                         = Post::getEmptyCroppedImage(370, 220);
$this->addBodyClass('category-' . $model->slug)
?>
<div class="ts-row cf">
    <div class="col-8 main-content cf">
        <?php Pjax::begin(['timeout' => 10000, 'options' => []]) ?>
        <?= ListView::widget([
            'dataProvider' => PostProvider::getPostsByCategory($model, $limit),
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
            'layout'       => $this->render('partials/_layout'),
            'itemView'     => 'partials/_view',
            'emptyText'    => __('Ushbu bo\'limda yangiliklar yo\'q'),
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