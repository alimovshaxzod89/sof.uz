<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2017. ActiveMedia Solutions LLC
 * @author    Rustam Mamadaminov <rmamdaminov@gmail.com>
 */

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
$limit                         = 15;
?>
<h4 class="widget-title"><?= $model->name ?></h4>

<?php Pjax::begin(['timeout' => 10000, 'options' => ['class' => '']]) ?>
<?= ListView::widget([
    'dataProvider' => PostProvider::getPostsByCategory($model, $limit),
    'options'      => [
        'tag' => false,
    ],
    'itemOptions'  => [
        'tag' => false,
    ],
    'layout'       => '<div class="row card-row">{items}<div class="col-lg-12">{pager}</div> </div>',
    'itemView'     => 'partials/_photo',
    'emptyText'    => __('Ushbu bo\'limda yangiliklar yo\'q'),
    'pager'        => [
        'perLoad' => $limit,
        'class'   => ScrollPager::class,
    ],
]) ?>
<?php Pjax::end() ?>
