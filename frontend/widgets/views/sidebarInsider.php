<?php
/**
 * @link      http://www.activemedia.uz/
 * @copyright Copyright (c) 2017. ActiveMedia Solutions LLC
 * @author    Rustam Mamadaminov <rmamdaminov@gmail.com>
 */

use frontend\models\PostProvider;
use yii\helpers\Url;
use yii\widgets\Pjax;

/**
 * @var $post PostProvider
 */

$js = "$('#refresh__topic').on('click', function (e) {
    e.preventDefault();
    $.pjax.reload({container: '#wt'});
    return false;
});"
?>
<?php Pjax::begin(['enablePushState' => true, 'timeout' => 10000,
                   'options'         => ['class' => 'sidebar__day ']]) ?>
    <div class="sidebar__day-title">
        <h2><?= __('Aytishlaricha') ?></h2>
        <a href="<?= Url::current(['rand'=>time()]) ?>" class="sidebar__day-refresh">
            <i class="icon refresh-icon"></i>
        </a>
    </div>
    <div class="sidebar__day-quote">
        <p class="quote-sm">
            <a href="<?= $post->getViewUrl($post->category) ?>" data-pjax="0">
                <?= $post->title ?>
            </a>
        </p>
        <a class="btn-all" href="<?= Url::to(['/insayder']) ?>" data-pjax="0"><?= __('Barchasi') ?></a>
    </div>
<?php Pjax::end() ?>