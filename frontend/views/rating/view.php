<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2018. ActiveMedia Solutions LLC
 * @author    Toir Tuychiyev <toir427@gmail.com>
 */

use frontend\components\View;

/**
 * Created by PhpStorm.
 * Date: 12/13/17
 * Time: 12:48 AM
 */

/**
 * @var $this  View
 * @var $model \common\models\Rating
 */
$this->_canonical = $model->getViewUrl();

$this->title                   = $model->title;
$this->params['breadcrumbs'][] = ['url' => ['/reytinglar'], 'label' => __('Reytinglar')];
$this->params['breadcrumbs'][] = ['label' => $model->title];
?>
<div class="main__content article">
    <?= \frontend\widgets\Alert::widget() ?>

    <div class="row-flex">
        <div class="col col-2 rating-col">
            <div id="rating-index" class="rating__indexer" data-count="<?= $model->countries ?>"></div>
            <span class="article__content-meta rating_count" id="rating-count">
                <?= __('{count} davlat', ['count' => "<span>" . $model->countries . "</span>"]) ?>
            </span>
        </div>
        <div class="col col-10 rating__content article__content" id="rating">

            <?php if ($model->image && is_array($model->image)): ?>
                <img class="img-responsive mb-half"
                     src="<?= \common\models\MongoModel::getCropImage($model->image, 760, 260, \Imagine\Image\ManipulatorInterface::THUMBNAIL_OUTBOUND) ?>"
                     alt="">
            <?php endif; ?>

            <div class="article__content-meta">
                <?= __('{year} yil / {count} davlat / {date}', ['year' => $model->year, 'count' => $model->countries, 'date' => Yii::$app->formatter->asDate($model->updated_at->sec, 'php:d M, y')]) ?>
            </div>
            <h2 class="post__title"><?= $model->title ?></h2>

            <div class=" rating__content-info">
                <?= $model->content ?>
            </div>
            <table class="striped">
                <tbody>
                <tr>
                    <?php foreach ($model->columns as $column): ?>
                        <th><?= $column ?></th>
                    <?php endforeach; ?>
                </tr>
                <?php foreach ($model->rows as $i => $row): ?>
                    <?php $prCol = $model->columns[0] ?>
                    <tr class="item <?= ($i + 1 == $model->selected) ? 'index__select' : '' ?> "
                        data-pos="_idx_<?= $row[$prCol] ?> ">
                        <?php foreach ($model->columns as $j => $col) { ?>
                            <td><?= ($j == 1) ? __($row[$col]) : $row[$col] ?></td>
                        <?php } ?>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <div class="article__content-meta">
            <span class="date-time">
                <?= $model->year ?>
            </span>
            </div>
        </div>
    </div>
</div><!-- End of main__content-->

<div class="main__sidebar">
    <?= \frontend\widgets\SidebarRating::widget(['exclude' => $model->_id]) ?>
</div><!-- End of main__sidebar-->
