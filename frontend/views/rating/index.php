<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2018. ActiveMedia Solutions LLC
 * @author    Toir Tuychiyev <toir427@gmail.com>
 */

use frontend\components\ScrollPager;
use frontend\components\View;
use frontend\widgets\SidebarPopular;
use frontend\widgets\SidebarPost;
use frontend\widgets\SidebarTrending;
use yii\widgets\ListView;
use yii\widgets\Pjax;
use yii\helpers\Url;

/**
 * @var $this View
 */
$this->title      = __('Reytinglar');
$limit            = 10;
$this->_canonical = Url::to(['/reytinglar'], true);
?>
<div class="main__content article">
    <?= \frontend\widgets\Alert::widget() ?>
    <?php Pjax::begin(['enablePushState' => true, 'timeout' => 10000, 'options' => ['class' => 'main__news']]) ?>
    <?= ListView::widget([
                             'id'           => 'rating_list',
                             'dataProvider' => \common\models\Rating::dataProvider($limit),
                             'itemView'     => 'partials/item',
                             'emptyText'    => __('Ushbu b\'limda yangiliklar mavjud emas'),

                             'options'     => [
                                 'class' => '',
                             ],
                             'layout'      => "<div class='main__news-list'>{items}</div>\n{pager}",
                             'itemOptions' => [
                                 'tag' => false,
                             ],

                             'pager' => [
                                 'class'   => ScrollPager::class,
                                 'perLoad' => $limit,
                             ],
                         ]) ?>
    <?php Pjax::end() ?>
</div>
<div class="main__sidebar">
    <?= SidebarTrending::widget() ?>
    <?= SidebarPost::widget() ?>
    <?= SidebarPopular::widget() ?>
</div>
