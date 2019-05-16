<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2017. ActiveMedia Solutions LLC
 * @author    Rustam Mamadaminov <rmamdaminov@gmail.com>
 */

use common\models\Blogger;
use frontend\components\ScrollPager;
use frontend\components\View;
use frontend\models\PostProvider;
use frontend\widgets\SidebarAudioPlay;
use frontend\widgets\SidebarPopular;
use frontend\widgets\SidebarShare;
use yii\widgets\Breadcrumbs;
use yii\widgets\ListView;
use yii\widgets\Pjax;

/**
 * Created by PhpStorm.
 * Date: 12/13/17
 * Time: 12:48 AM
 */

/**
 * @var $this  View
 * @var $model Blogger
 */
$this->title      = $model->getFullname();
$this->_canonical = linkTo(['mualliflar/' . $model->login], true);
$this->_image     = Blogger::getCropImage($model->image, 320, 320);
$this->addDescription([$model->intro]);
$this->params['breadcrumbs'][] = ['url' => ['/mualliflar'], 'label' => __('Mualliflar')];
$this->params['breadcrumbs'][] = ['label' => $model->getFullname()];

$limit = 10;
?>
<div class="main__content">
    <?= \frontend\widgets\Alert::widget() ?>
    <div class="author__desc">
        <div class="author__desc-personal">
            <a href="mailto:<?= $model->email ?>" class="mail-author"><i class="icon mail-author-icon"></i></a>

            <div class="media-info">
                <div class="media shrink is_left">
                    <a href="#">
                        <img src="<?= Blogger::getCropImage($model->image, 90, 90) ?>" width="90" height="90"
                             alt="<?= $model->getFullname() ?>">
                    </a>
                </div><!-- End of media-->

                <div class="info auto">
                    <p class="title"><strong><?= $model->getFullname() ?></strong></p>

                    <p><?= $model->intro ?></p>
                </div><!-- End of info-->
            </div><!-- End of media-info-->
        </div><!-- End of author__desc-personal-->

        <div class="sidebar__social-message is_facebook clickable-block">
            <div class="sidebar__social-message-title">
                <a href="<?= $model->facebook ?>" target="_blank"><i
                        class="icon facebook-icon"></i>@<?= trim($model->fullname) ?></a>
            </div><!-- End of sidebar__social-message-title-->

            <p><?= __('Muallifning Facebook sahifasi') ?></p>

            <p class="meta"><?= __('{minute} daqiqa oldin', ['minute' => Yii::$app->cache->getOrSet('time_' . $model->login, function () {
                    return rand(10, 59);
                }, 600)]) ?></p>
        </div><!-- End of sidebar__social-message-->
    </div><!-- End of author__desc-->

    <div class="author__biography">
        <?= $model->description ?>
    </div><!-- End of author__biography-->
    <?php Pjax::begin(['enablePushState' => false, 'timeout' => 10000]) ?>
    <?= ListView::widget([
                             'dataProvider' => PostProvider::dataProvider($model->articles, [], $limit),
                             'itemView'     => 'partials/post_item',
                             'emptyText'    => __('Ushbu muallifda maqolalar qo\'shilmagan'),
                             'options'      => [
                                 'class' => '',
                             ],
                             'layout'       => "<div class='latest__news-list'>{items}</div>{pager}",
                             'itemOptions'  => [
                                 'tag' => false,
                             ],

                             'pager' => [
                                 'perLoad'    => $limit,
                                 'class'      => ScrollPager::class,
                                 'buttonText' => __('Яна'),
                             ],
                         ]) ?>
    <?php Pjax::end() ?>
</div>
<div class="main__sidebar">
    <?= SidebarPopular::widget([]) ?>
    <?= SidebarAudioPlay::widget([]) ?>
</div>
