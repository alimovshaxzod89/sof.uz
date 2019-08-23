<?php

use frontend\components\ScrollPager;
use frontend\components\View;
use frontend\models\AuthorProvider;
use frontend\widgets\SidebarImportant;
use frontend\widgets\SidebarPost;
use frontend\widgets\SidebarTrending;
use yii\data\ActiveDataProvider;
use yii\widgets\ListView;
use yii\widgets\Pjax;

/**
 * Created by PhpStorm.
 * Date: 12/13/17
 * Time: 12:48 AM
 */

/**
 * @var $this         View
 * @var $dataProvider ActiveDataProvider
 * @var $topProvider  ActiveDataProvider
 */

$this->title      = __('Mualliflar');
$this->_canonical = linkTo(['/mualliflar'], true);
$limit            = 18;
?>
<div class="main__content">
    <?= \frontend\widgets\Alert::widget() ?>
    <?php Pjax::begin(['enablePushState' => false, 'timeout' => 10000]) ?>
    <?= ListView::widget([
                             'dataProvider' => AuthorProvider::getBySort('views_l3d', Yii::$app->request->get('load', $limit)),
                             'itemView'     => 'partials/_item',
                             'options'      => ['class' => 'authors__list'],
                             'itemOptions'  => ['class' => 'authors__list-item'],
                             'layout'       => "<div class='latest__news-list'>{items}</div>{pager}",
                             'pager'        => [
                                 'perLoad'    => $limit,
                                 'class'      => ScrollPager::class,
                                 'buttonText' => __('Яна'),
                             ],
                         ]) ?>
    <?php Pjax::end() ?>
</div><!-- End of main__content-->

<div class="main__sidebar">
    <?= SidebarTrending::widget() ?>
    <?= SidebarImportant::widget() ?>
    <?= SidebarPost::widget([]) ?>
</div><!-- End of main__sidebar-->
