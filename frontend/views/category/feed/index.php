<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2017. ActiveMedia Solutions LLC
 * @author    Rustam Mamadaminov <rmamdaminov@gmail.com>
 */

use frontend\components\ScrollPager;
use frontend\components\View;
use frontend\models\PostProvider;
use yii\helpers\Url;
use yii\widgets\ListView;
use yii\widgets\Pjax;

/**
 * Created by PhpStorm.
 * Date: 12/17/17
 * Time: 2:24 PM
 * @var $this View
 */

$limit = 20;
if ($type == 'batafsil') {
    $this->title                   = __('Batafsil');
    $this->_canonical              = linkTo(['/yangiliklar/batafsil'], true);
    $this->params['breadcrumbs'][] = ['url' => ['/yangiliklar/qisqacha'], 'label' => __('Xabarlar lentasi')];
    $this->params['breadcrumbs'][] = ['label' => $this->title];
} else {
    $this->title                   = __('Qisqacha');
    $this->_canonical              = linkTo(['/yangiliklar/qisqacha'], true);
    $this->params['breadcrumbs'][] = ['url' => ['/yangiliklar/qisqacha'], 'label' => __('Xabarlar lentasi')];
    $this->params['breadcrumbs'][] = ['label' => $this->title];
}
$empty = $this->getImageUrl('img-placeholder.png');
?>

<div class="content-box">
    <?php if ($type == 'batafsil') : ?>
        <div class="catalog__nav">
            <a href="<?= Url::to(['/yangiliklar/qisqacha']) ?>" class=""><?= __('Qisqacha') ?></a>
            <a href="<?= Url::to(['/yangiliklar/batafsil']) ?>" class="active"><?= __('Batafsil') ?></a>
        </div>

        <div class="blog__content small-img big_list">
            <section class="section mb-0">
                <?php Pjax::begin(['enablePushState' => true, 'timeout' => 10000]) ?>
                <?= ListView::widget([
                    'dataProvider' => PostProvider::getFeed($limit),
                    'itemView'     => 'partials/more_item',
                    'emptyText'    => __('Ushbu b\'limda yangiliklar mavjud emas'),

                    'options'     => [
                        'class' => ' ',
                    ],
                    'layout'      => "<div class='catalog__items latest__news-list mb-32'>{items}</div>\n{pager}",
                    'itemOptions' => [
                        'tag' => false,
                    ],
                    'viewParams'  => [
                        'empty' => $empty,
                    ],

                    'pager' => [
                        'class'   => ScrollPager::class,
                        'perLoad' => $limit,
                    ],
                ]) ?>
                <?php Pjax::end() ?>
            </section>
        </div>
    <?php else: ?>
        <div class="catalog__nav">
            <a href="<?= Url::to(['/yangiliklar/qisqacha']) ?>" class="active"><?= __('Qisqacha') ?></a>
            <a href="<?= Url::to(['/yangiliklar/batafsil']) ?>" class=""><?= __('Batafsil') ?></a>
        </div>
        <section class="section mb-0">
            <div class="row card-row">
                <?php Pjax::begin(['timeout' => 10000, 'options' => ['class' => 'col-12']]) ?>
                <?= ListView::widget([
                    'dataProvider' => PostProvider::getFeed($limit),
                    'options'      => [
                        'tag' => false,
                    ],
                    'itemOptions'  => [
                        'tag' => false,
                    ],
                    'viewParams'  => [
                        'empty' => $empty,
                    ],
                    'layout'       => '
                    <ul class="post-list-small post-list-small--2 mb-32">
                        {items}
                    </ul>
                    <div class="paging text-center">
                        {pager}
                    </div>
                ',
                    'itemView'     => 'partials/short_item',
                    'pager'        => [
                        'perLoad' => $limit,
                        'class'   => ScrollPager::class,
                    ],
                ]) ?>
                <?php Pjax::end() ?>
            </div>
        </section>
    <?php endif ?>
</div>

